<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Project;
use App\Models\Bidang;
use App\Models\Document;
use App\Models\Detail;
use App\Models\RequestDetail;
use App\Models\Request as RequestModel;
use App\Services\BoqValidationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
    /**
     * Check if current admin can access the project
     */
    private function checkProjectAccess(Project $project)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if admin can access this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->admin_id) {
            abort(403, 'You do not have access to this project.');
        }
    }

    /**
     * Display tools for a specific project
     */
    public function index(Request $request, Project $project)
    {
        $this->checkProjectAccess($project);

        $query = Tool::where('no_IO', $project->no_IO)
                    ->with(['bidang', 'document.tahapans', 'request']);

        // Search functionality
        if ($request->filled('search')) 
        {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) 
            {
                $q->where('Description', 'LIKE', "%{$search}%")
                  ->orWhere('unit', 'LIKE', "%{$search}%")
                  ->orWhere('remarks', 'LIKE', "%{$search}%");
            });
        }

        // Filter by document
        if ($request->filled('document')) 
        {
            $query->where('no_document', $request->get('document'));
        }

        $tools = $query->orderBy('created_at', 'desc')->get();
        
        // Get available documents for filter dropdown
        $documents = Document::orderBy('date_issue', 'desc')->get();
        
        return view('projects.tools.index', compact('tools', 'project', 'documents'));
    }

    /**
     * Show the form for creating a new tool
     */
    public function create(Project $project)
    {
        $this->checkProjectAccess($project);
        
        // Additional safety check: Store current project in session and clear BOQ if different
        $lastAccessedProject = session('last_accessed_project');
        if ($lastAccessedProject && $lastAccessedProject !== $project->no_IO) 
        {
            session()->forget('selected_boq_items_for_tool');
            \Log::info('BOQ session cleared due to project change via route', 
            [
                'previous_project' => $lastAccessedProject,
                'current_project' => $project->no_IO
            ]);
        }
        session(['last_accessed_project' => $project->no_IO]);
        
        // Clear any existing BOQ selection from previous projects
        if (session('selected_boq_items_for_tool')) 
        {
            $selectedItemIds = session('selected_boq_items_for_tool');
            $selectedBoqItems = Detail::whereIn('no', $selectedItemIds)
                ->with(['section.boq'])
                ->get();
            
            // Check if selected BOQ items belong to current project
            $belongsToCurrentProject = $selectedBoqItems->every(function($item) use ($project) 
            {
                return $item->section && $item->section->boq && 
                       $item->section->boq->project_no_io == $project->no_IO;
            });
            
            if (!$belongsToCurrentProject) 
            {
                // Clear session if BOQ items don't belong to current project
                session()->forget('selected_boq_items_for_tool');
            }
        }
        
        // Check if project has BOQ with details
        if ($project->boq && $project->boq->sections()->whereHas('details')->exists()) 
        {
            // If no pre-selected BOQ items, redirect to BOQ selection
            if (!session('selected_boq_items_for_tool')) 
            {
                return redirect()->route('projects.tools.select-boq-items', $project);
            }
        }
        
        // Initialize variables
        $selectedBoqItems = null;
        $totalQuantity = 0;
        $commonUnit = '';
        $unitMismatch = false;
        $generatedDescription = '';
        $boqUsageData = [];
        
        if (session('selected_boq_items_for_tool')) 
        {
            $selectedItemIds = session('selected_boq_items_for_tool');
            $selectedBoqItems = Detail::whereIn('no', $selectedItemIds)
                ->with(['section.boq', 'requestDetails.request']) // Eager load for better performance
                ->get();
            
            if ($selectedBoqItems->count() > 0) 
            {
                // Check unit consistency
                $units = $selectedBoqItems->pluck('unit')->unique();
                if ($units->count() > 1) 
                {
                    $unitMismatch = true;
                } else {
                    $commonUnit = $units->first();
                    
                    // Calculate total available quantity and build usage data
                    $boqUsageData = [];
                    $totalQuantity = $selectedBoqItems->sum(function($item) use (&$boqUsageData) 
                    {
                        $availableQuantity = $item->getAvailableQuantity();
                        $usedQuantity = $item->getActualUsedQuantity(); // Use proper method from model
                        
                        // Debug logging (can be removed in production)
                        \Log::debug('BOQ Usage Data Debug', [
                            'detail_id' => $item->no,
                            'detail_name' => $item->nama_detail,
                            'total_quantity' => $item->quantity,
                            'used_quantity' => $usedQuantity,
                            'available_quantity' => $availableQuantity,
                            'calculation_check' => ($item->quantity - $usedQuantity) === $availableQuantity ? 'CORRECT' : 'INCORRECT'
                        ]);
                        
                        // Build usage data for this item
                        $boqUsageData[$item->no] = [
                            'total_quantity' => $item->quantity,
                            'used_quantity' => $usedQuantity,
                            'available_quantity' => $availableQuantity,
                            'unit' => $item->unit,
                            'unit_price' => $item->harga_satuan,
                            'usage_percentage' => $item->quantity > 0 ? ($usedQuantity / $item->quantity) * 100 : 0
                        ];
                        
                        return $availableQuantity;
                    });
                    
                    // Generate description from BOQ items
                    $descriptions = $selectedBoqItems->pluck('nama_detail')->take(2);
                    if ($selectedBoqItems->count() > 2) 
                    {
                        $generatedDescription = $descriptions->implode(', ') . ' & ' . ($selectedBoqItems->count() - 2) . ' lainnya';
                    } else 
                    {
                        $generatedDescription = $descriptions->implode(' & ');
                    }
                    
                    // Limit description to 45 characters
                    if (strlen($generatedDescription) > 45) 
                    {
                        $generatedDescription = substr($generatedDescription, 0, 42) . '...';
                    }
                }
            }
            
            // Don't clear session here - we need it for the store method
        }
        
        $bidangs = Bidang::all();
        $documents = Document::orderBy('date_issue', 'desc')->get();
        
        return view('projects.tools.create', compact(
            'project', 
            'bidangs', 
            'documents', 
            'selectedBoqItems', 
            'totalQuantity',
            'commonUnit',
            'unitMismatch',
            'generatedDescription',
            'boqUsageData'
        ));
    }

    /**
     * Show BOQ items selection page for creating tools
     */
    public function selectBoqItems(Project $project)
    {
        $this->checkProjectAccess($project);
        
        // Clear any previous BOQ selection from other projects
        session()->forget('selected_boq_items_for_tool');
        
        // Check if project has BOQ with details
        if (!$project->boq) 
        {
            return redirect()->route('projects.tools.index', $project)
                ->withErrors(['error' => 'This Project does not have a BOQ yet. Please create a BOQ first before making a tool request.']);
        }
        
        if ($project->boq->sections()->count() === 0) 
        {
            return redirect()->route('projects.tools.index', $project)
                ->withErrors(['error' => 'BOQ already exists but does not have any sections. Please add sections and details to the BOQ first.']);
        }

        $hasDetails = $project->boq->sections()->whereHas('details')->exists();
        if (!$hasDetails) 
        {
            return redirect()->route('projects.tools.index', $project)
                ->withErrors(['error' => 'BOQ and section already exist but do not have any details. Please add details to the BOQ section first.']);
        }

        // Load BOQ with sections and details
        $boq = $project->boq->load(['sections.details']);
        
        return view('projects.tools.select-boq-items', compact('project', 'boq'));
    }

    /**
     * Process BOQ items selection for tools and redirect to tool creation form
     */
    public function processBoqSelection(Request $request, Project $project)
    {
        $this->checkProjectAccess($project);
        
        $request->validate([
            'selected_details' => 'required|array|min:1',
            'selected_details.*' => 'exists:details,no'
        ], [
            'selected_details.required' => 'Choose at least one item from the BOQ.',
            'selected_details.min' => 'Choose at least one item from the BOQ.',
        ]);

        // Validate that selected details belong to current project's BOQ
        $selectedDetails = Detail::whereIn('no', $request->selected_details)
            ->with(['section.boq'])
            ->get();
            
        $invalidDetails = $selectedDetails->filter(function($detail) use ($project) 
        {
            return !$detail->section || 
                   !$detail->section->boq || 
                   $detail->section->boq->project_no_io != $project->no_IO;
        });
        
        if ($invalidDetails->count() > 0) 
        {
            return redirect()->route('projects.tools.select-boq-items', $project)
                ->withErrors(['error' => 'Several selected BOQ items are not valid for this project.']);
        }

        // Store selected BOQ items in session for use in tool creation form
        session(['selected_boq_items_for_tool' => $request->selected_details]);
        
        // Redirect to tool creation form with pre-selected items
        return redirect()->route('projects.tools.create', $project);
    }

    /**
     * Store a newly created tool
     */
    public function store(Request $request, Project $project, BoqValidationService $boqValidator)
    {
        $this->checkProjectAccess($project);
        
        $request->validate([
            'Description' => 'required|string|max:45',
            'quantity' => 'required|integer|min:1', // Ubah ke integer dengan min 1
            'unit' => 'required|string|max:20',
            'delivery_date' => 'nullable|date',
            'kode_GL' => 'required|exists:bidangs,kode_GL',
            'remarks' => 'nullable|string|max:45',
            'no_document' => 'nullable|exists:documents,no_request',
            'boq_max_quantity' => 'nullable|integer', // Ubah ke integer
            'boq_unit' => 'nullable|string'
        ]);

        // Get selected BOQ items from session
        $selectedBoqItems = null;
        $boqMaxQuantity = 0;
        $toolStatus = 'On Process'; // Default tool status
        $requestStatus = 'Pending'; // Default request status
        
        if (session('selected_boq_items_for_tool')) 
        {
            $selectedItemIds = session('selected_boq_items_for_tool');
            $selectedBoqItems = Detail::whereIn('no', $selectedItemIds)->get();
            
            // Calculate total available quantity from BOQ using helper method
            $boqMaxQuantity = $selectedBoqItems->sum(function($item) 
            {
                return $item->getAvailableQuantity();
            });
        } else 
        {
            // Fallback to hidden field values
            $boqMaxQuantity = intval($request->boq_max_quantity ?? 0);
        }

        // Check if requested quantity exceeds BOQ availability or if available quantity is 0
        $requestedQuantity = intval($request->quantity); // Convert to integer
        if ($boqMaxQuantity <= 0) 
        {
            // If available quantity is 0 or negative, set tool status to hold
            $toolStatus = 'Hold';
        } elseif ($requestedQuantity > $boqMaxQuantity) {
            // If requested quantity exceeds available BOQ quantity, set tool status to hold
            $toolStatus = 'Hold';
        }

        // Create the tool
        $tool = Tool::create([
            'Description' => $request->Description,
            'quantity' => $requestedQuantity,
            'unit' => $request->unit,
            'delivery_date' => $request->delivery_date,
            'remarks' => $request->remarks,
            'no_IO' => $project->no_IO,
            'kode_GL' => $request->kode_GL,
            'no_document' => $request->no_document,
            'status_tools' => $toolStatus
        ]);

        // Create Request and RequestDetail records for BOQ-based tools
        if ($selectedBoqItems && $selectedBoqItems->count() > 0) 
        {
            $toolRequest = RequestModel::create([
                'type_surat' => 'SPS',
                'jenis_req' => 'PO',
                'no_surat' => 'REQ-' . date('YmdHis'),
                'date_req' => now(),
                'status_req' => $requestStatus // Document request status
            ]);

            // Associate tool with the request (new relationship)
            $tool->update(['request_id' => $toolRequest->id_req]);

            // Distribusi quantity yang diminta user ke BOQ items yang dipilih
            $userRequestedQuantity = intval($requestedQuantity); // Quantity yang diinput user (13)
            $selectedItemsCount = $selectedBoqItems->count();
            
            // Jika hanya ada 1 BOQ item yang dipilih, berikan semua quantity ke item tersebut
            // Distribusi quantity yang diminta user ke BOQ items yang dipilih
            $userRequestedQuantity = intval($requestedQuantity);


            $sortedItems = $selectedBoqItems->sortByDesc(function($item) 
            {
                return $item->getAvailableQuantity();
            });

            $remainingRequest = $userRequestedQuantity;
            $allocations = []; // Format: [ 'detail_id' => ['quantity' => QTY, 'price' => HARGA] ]

            if ($toolStatus === 'On Process') 
            {
                foreach ($sortedItems as $boqItem) 
                {
                    if ($remainingRequest <= 0) break; // Request sudah terpenuhi
                    $availableQuantity = $boqItem->getAvailableQuantity();

                    $finalQuantity = min($remainingRequest, $availableQuantity);
                    if ($finalQuantity > 0)
                    {
                        $allocations[$boqItem->no] = [
                            'quantity' => $finalQuantity,
                            'price' => $boqItem->harga_satuan
                        ];
                        $remainingRequest -= $finalQuantity;
                    }
                }
            
            } else {
                $itemCount = $sortedItems->count();
                if ($itemCount > 0) {
                    $baseQuantityPerItem = intval($userRequestedQuantity / $itemCount);
                    $extraQuantity = $userRequestedQuantity % $itemCount;
                    $itemIndex = 0;
                    foreach ($sortedItems as $boqItem) {
                        // Hitung alokasi merata
                        $itemQuantity = $baseQuantityPerItem;
                        if ($itemIndex < $extraQuantity) {
                            $itemQuantity += 1;
                        }
                        if ($itemQuantity > 0) {
                            $allocations[$boqItem->no] = [
                                'quantity' => $itemQuantity, // Kuantitas ini BISA jadi > stok
                                'price' => $boqItem->harga_satuan
                            ];
                        }
                        $itemIndex++;
                    }
                }
            }

            // 3. Buat record RequestDetail berdasarkan hasil alokasi
            foreach ($allocations as $detailId => $data) {
                $finalQuantity = $data['quantity'];
                $unitPrice = $data['price'];

                if ($finalQuantity > 0) {
                    RequestDetail::create([
                        'request_id' => $toolRequest->id_req,
                        'detail_id' => $detailId,
                        'requested_quantity' => $finalQuantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $finalQuantity * $unitPrice,
                        'status' => 'pending' // Status selalu pending, akan di-approve/reject nanti
                    ]);
                }
            }

            // If tool status is hold, send notification to admins
            if ($toolStatus === 'Hold') 
            {
                $this->sendHoldNotificationToAdmins($tool, $project, $requestedQuantity, $boqMaxQuantity);
            }
    }

        // Clear session data
        session()->forget(['selected_boq_items_for_tool', 'assigned_document', 'created_document_project_id', 'project_id']);

        // Prepare success message
        $successMessage = 'Request Tool Successfully Created!';
        
        if ($toolStatus === 'Hold') 
        {
            if ($boqMaxQuantity <= 0) 
            {
                $successMessage = 'Request Tool is created with HOLD status because BoQ stock is not available (0).';
                $successMessage .= " Request: {$requestedQuantity} {$request->unit}, Available: 0 {$request->unit}.";
                $successMessage .= " Admin has been notified for review and stock addition.";
            } else 
            {
                $successMessage = 'Request Tool is created with HOLD status because quantity exceeds BoQ stock.';
                $successMessage .= " Request: {$requestedQuantity} {$request->unit}, Available: {$boqMaxQuantity} {$request->unit}.";
                $successMessage .= " Admin has been notified for review.";
            }
            
            return redirect()->route('projects.tools.index', $project)
                            ->with('warning', $successMessage);
        } else {
            if ($selectedBoqItems && $selectedBoqItems->count() > 0) {
                $remaining = $boqMaxQuantity - $requestedQuantity;
                $successMessage .= "Remaining BOQ: {$remaining} {$request->unit}";
            }
            
            return redirect()->route('projects.tools.index', $project)
                            ->with('success', $successMessage);
        }
    }

    /**
     * Show hold requests for admin review
     */
    public function holdRequests()
    {
        $admin = Auth::guard('admin')->user();
        
        // Only admins and super admins can access hold requests
        if (!in_array($admin->role, ['Super Admin', 'Admin', 'Project Manager'])) {
            abort(403, 'You do not have permission to access hold requests.');
        }

        $holdTools = Tool::where('status_tools', 'Hold')
            ->with(['project', 'request.requestDetails.detail'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.hold-requests.index', compact('holdTools'));
    }

    /**
     * Approve or reject a hold tool request
     */
    public function updateHoldRequest(Request $request, Tool $Tool)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!in_array($admin->role, ['Super Admin', 'Admin', 'Project Manager'])) {
            abort(403, 'You do not have permission to update hold requests.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:255'
        ]);

        $action = $request->action;
        $notes = $request->notes;

        if ($action === 'approve') {
            // Approve the tool
            $Tool->approve($admin->admin_id, $notes);
            
            // Update request status to On Process if exists
            if ($Tool->request) {
                $Tool->request->update(['status_req' => 'On Process']);
                
                // Update all request details to approved
                $Tool->request->requestDetails()->update([
                    'status' => 'approved'
                ]);
            }

            $message = 'Request Tool successfully approved!';
            $type = 'success';
        } else {
            // Reject the tool
            $Tool->reject($admin->admin_id, $notes);
            
            // Update request status to Closed if exists
            if ($Tool->request) {
                $Tool->request->update(['status_req' => 'Closed']);
                
                // Update all request details to rejected and restore BOQ quantities
                $Tool->request->requestDetails()->update([
                    'status' => 'rejected'
                ]);
                
                // Restore BOQ quantities for rejected request
                foreach ($Tool->request->requestDetails as $detail) {
                    // This will be handled by BOQ quantity restoration logic
                    // You may need to implement this based on your BOQ system
                }
            }

            $message = 'Request Tool successfully rejected!';
            $type = 'success';
        }

        return redirect()->route('hold-requests.index')
                        ->with($type, $message);
    }

    /**
     * Send notification to admins when request is on hold
     */
    private function sendHoldNotificationToAdmins($tool, $project, $requestedQuantity, $availableQuantity)
    {
        try {
            // Get all admins or admins with specific roles
            $admins = \App\Models\Admin::whereIn('role', [
                'Super Admin', 
                'Admin', 
                'Project Manager'
            ])->get();

            $notificationData = [
                'type' => 'tool_request_hold',
                'title' => 'Request Tool Need Requires Approval',
                'message' => "Request Tool '{$tool->Description}' in project '{$project->title_project}' requires approval because quantity exceeds BoQ stock.",
                'details' => [
                    'tool_id' => $tool->idTools,
                    'project_no_io' => $project->no_IO,
                    'requested_quantity' => $requestedQuantity,
                    'available_quantity' => $availableQuantity,
                    'unit' => $tool->unit,
                    'created_by' => Auth::guard('admin')->user()->name ?? 'System',
                    'created_at' => now()->format('d/m/Y H:i')
                ]
            ];

            foreach ($admins as $admin) {
                // You can implement your notification system here
                // For example: send email, create in-app notification, etc.
                
                \Log::info("Hold notification sent to admin: {$admin->name}", $notificationData);
                
                // Example: If you have an in-app notification system
                // Notification::create([
                //     'admin_id' => $admin->admin_id,
                //     'type' => $notificationData['type'],
                //     'title' => $notificationData['title'],
                //     'message' => $notificationData['message'],
                //     'data' => json_encode($notificationData['details']),
                //     'read_at' => null
                // ]);
            }

        } catch (\Exception $e) {
            \Log::error('Failed to send hold notification to admins: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint untuk validasi BOQ real-time
     */
    public function validateBoq(Request $request, Project $project, BoqValidationService $boqValidator)
    {
        $this->checkProjectAccess($project);
        
        $request->validate([
            'description' => 'required|string',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string'
        ]);

        $toolData = [
            'Description' => $request->description,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'harga_satuan' => $request->harga_satuan ?? 0
        ];

        $validationResult = $boqValidator->validateToolRequest($project, $toolData);
        $messages = $boqValidator->formatValidationMessage($validationResult);

        return response()->json([
            'is_valid' => $validationResult['is_valid'],
            'messages' => $messages,
            'matched_details' => $validationResult['matched_details'],
            'suggestions' => $validationResult['suggestions'] ?? [],
            'debug_info' => $validationResult['debug_info'] ?? [],
            'search_input' => [
                'description' => $request->description,
                'quantity' => $request->quantity,
                'unit' => $request->unit
            ]
        ]);
    }

    /**
     * Display the specified tool
     */
    public function show(Project $project, Tool $tool)
    {
        $this->checkProjectAccess($project);
        
        return view('projects.tools.show', compact('tool', 'project'));
    }

    /**
     * Show the form for editing the specified tool
     */
    public function edit(Project $project, Tool $tool)
    {
        $this->checkProjectAccess($project);
        
        $bidangs = Bidang::all();
        $documents = Document::orderBy('date_issue', 'desc')->get();
        return view('projects.tools.edit', compact('tool', 'project', 'bidangs', 'documents'));
    }

    /**
     * Update the specified tool
     */
    public function update(Request $request, Project $project, Tool $tool)
    {
        $this->checkProjectAccess($project);
        
        $request->validate([
            'Description' => 'required|string|max:45',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'delivery_date' => 'nullable|date',
            'kode_GL' => 'required|exists:bidangs,kode_GL',
            'remarks' => 'nullable|string|max:45',
            'no_document' => 'nullable|exists:documents,no_request'
        ]);

        $tool->update([
            'Description' => $request->Description,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'delivery_date' => $request->delivery_date,
            'remarks' => $request->remarks,
            'kode_GL' => $request->kode_GL,
            'no_document' => $request->no_document
        ]);

        return redirect()->route('projects.tools.index', $project)
                        ->with('success', 'Request Tools Successfully Updated!');
    }

    /**
     * Remove the specified tool
     */
    public function destroy(Project $project, Tool $tool)
    {
        $this->checkProjectAccess($project);
        
        if ($tool->request) {
            $tool->request->requestDetails()->delete();
            $tool->request->delete();
        }

        $tool->delete();

        return redirect()->route('projects.tools.index', $project)
                        ->with('success', 'Request Tools Successfully Deleted!');
    }

    /**
     * Show form for bulk assign document to multiple tools
     */
    public function bulkAssignDocument(Request $request, Project $project)
    {
        $selectedTools = $request->get('selected_tools', []);
        
        if (empty($selectedTools)) {
            return redirect()->route('projects.tools.index', $project)
                           ->with('error', 'Choose at least one tool to assign the document');
        }

        $tools = Tool::whereIn('idTools', $selectedTools)->get();
        $documents = Document::orderBy('date_issue', 'desc')->get();
        
        return view('projects.tools.bulk-assign', compact('tools', 'project', 'documents'));
    }

    /**
     * Process bulk assign document to multiple tools
     */
    public function processBulkAssign(Request $request, Project $project)
    {
        \Log::info('=== BULK ASSIGN REQUEST START ===', [
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'project_no_IO' => $project->no_IO,
            'project_object' => $project->toArray(),
            'headers' => $request->headers->all()
        ]);

        // Cek apakah request benar-benar sampai
        if (!$request->has('tool_ids')) {
            \Log::error('tool_ids parameter missing');
            return redirect()->route('projects.tools.index', $project)
                            ->with('error', 'The tool_ids parameter is missing. Please ensure that tools are selected.');
        }

        if (!$request->has('no_document')) {
            \Log::error('no_document parameter missing');
            return redirect()->route('projects.tools.index', $project)
                            ->with('error', 'The no_document parameter is missing. Please ensure that a document is selected.');
        }

        try {
            $request->validate([
                'tool_ids' => 'required|array|min:1',
                'tool_ids.*' => 'exists:tools,idTools',
                'no_document' => 'required|exists:documents,no_request'
            ]);

            \Log::info('Validation passed, updating tools', [
                'tool_ids' => $request->tool_ids,
                'document' => $request->no_document,
                'tool_count' => count($request->tool_ids)
            ]);

            // Cek apakah tools ada di database
            $existingTools = Tool::whereIn('idTools', $request->tool_ids)->get();
            \Log::info('Tools found in database', [
                'requested_count' => count($request->tool_ids),
                'found_count' => $existingTools->count(),
                'found_tools' => $existingTools->pluck('idTools')->toArray()
            ]);

            $updatedCount = Tool::whereIn('idTools', $request->tool_ids)
                ->update(['no_document' => $request->no_document]);

            \Log::info('Assignment completed', [
                'tools_updated' => $updatedCount,
                'document' => $request->no_document
            ]);

            if ($updatedCount > 0) {
                return redirect()->route('projects.tools.index', $project)
                                ->with('success', "Successfully assigned {$updatedCount} tools to document {$request->no_document}!");
            } else {
                return redirect()->route('projects.tools.index', $project)
                                ->with('error', "No tools were updated. Tools may have already been assigned to the same document.");
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                $errorMessages[] = "{$field}: " . implode(', ', $messages);
            }
            
            return redirect()->route('projects.tools.index', $project)
                            ->with('error', 'Validation error: ' . implode(' | ', $errorMessages));

        } catch (\Exception $e) {
            \Log::error('Bulk assign error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('projects.tools.index', $project)
                            ->with('error', 'An error occurred during bulk assignment: ' . $e->getMessage());
        }
    }

    /**
     * Quick assign document to single tool
     */
    public function quickAssignDocument(Request $request, Project $project, Tool $tool)
    {
        try {
            $request->validate([
                'document_no' => 'nullable|exists:documents,no_request'
            ]);

            $tool->update(['no_document' => $request->document_no]);

            return redirect()->route('projects.tools.index', $project)
                            ->with('success', 'Document successfully assigned to tool!');
        } catch (\Exception $e) {
            \Log::error('Quick assign error: ' . $e->getMessage());
            
            return redirect()->route('projects.tools.index', $project)
                            ->with('error', 'An error occurred while assigning document: ' . $e->getMessage());
        }
    }

    /**
     * Assign document request to tool
     */
    public function assignDocumentRequest(Request $request, Project $project)
    {
        Log::info('Assign document request called with data: ' . json_encode($request->all()));
        
        try {
            $request->validate([
                'document_no' => 'required|string',
                'request_option' => 'required|string|in:existing,new',
                'existing_request_id' => 'required_if:request_option,existing|exists:requests,id_req',
                'no_surat' => 'required_if:request_option,new|string|max:255',
                'type_surat' => 'required_if:request_option,new|string|in:SPS,SPMP,PCM',
                'jenis_req' => 'required_if:request_option,new|string|in:PO,Kontrak,PCM',
                'date_req' => 'required_if:request_option,new|date'
            ]);
            
            Log::info('Assign document request validation passed');

            if ($request->request_option === 'existing') {
                // Assign existing request to tool
                $requestModel = RequestModel::find($request->existing_request_id);
                
                // Find tool by document_no
                $tool = Tool::where('no_document', $request->document_no)
                           ->where('no_IO', $project->no_IO)
                           ->first();
                
                if ($tool) {
                    // Check if tool is currently on 'Hold' due to stock validation
                    // If so, don't change the status - keep it on 'Hold'
                    if ($tool->status_tools === 'Hold') {
                        // Only update request_id, keep status_tools as 'Hold'
                        $tool->update([
                            'request_id' => $requestModel->id_req
                        ]);
                        
                        Log::info("Tool {$tool->no_document} assigned to request {$requestModel->no_surat} but status_tools remains 'Hold' due to stock validation");
                    } else {
                        // Determine appropriate status_tools based on request status
                        $statusTools = 'On Process'; // Default status
                        
                        switch ($requestModel->status_req) {
                            case 'Pending':
                                $statusTools = 'On Process';
                                break;
                            case 'On Process':
                                $statusTools = 'On Process';
                                break;
                            case 'Closed':
                                $statusTools = 'Closed';
                                break;
                            default:
                                $statusTools = 'On Process';
                                break;
                        }
                        
                        $tool->update([
                            'request_id' => $requestModel->id_req,
                            'status_tools' => $statusTools
                        ]);
                        
                        Log::info("Tool {$tool->no_document} assigned to request {$requestModel->no_surat} with status_tools: {$statusTools}");
                    }
                }

                $message = 'Document Successfully assigned to existing request!';
            } else {
                // Create new request
                $requestModel = RequestModel::create([
                    'no_surat' => $request->no_surat,
                    'type_surat' => $request->type_surat,
                    'jenis_req' => $request->jenis_req,
                    'date_req' => $request->date_req,
                    'status_req' => 'Pending'
                ]);
                
                // Find tool by document_no and assign new request
                $tool = Tool::where('no_document', $request->document_no)
                           ->where('no_IO', $project->no_IO)
                           ->first();
                
                if ($tool) {
                    // Check if tool is currently on 'Hold' due to stock validation
                    // If so, don't change the status - keep it on 'Hold'
                    if ($tool->status_tools === 'Hold') {
                        // Only update request_id, keep status_tools as 'Hold'
                        $tool->update([
                            'request_id' => $requestModel->id_req
                        ]);
                        
                        Log::info("Tool {$tool->no_document} assigned to new request {$requestModel->no_surat} but status_tools remains 'Hold' due to stock validation");
                    } else {
                        // New request always starts with 'Pending', so set status_tools to 'On Process'
                        $tool->update([
                            'request_id' => $requestModel->id_req,
                            'status_tools' => 'On Process'
                        ]);
                        
                        Log::info("Tool {$tool->no_document} assigned to new request {$requestModel->no_surat} with status_tools: On Process");
                    }
                }

                $message = 'New request Successfully created and assigned to tool!';
            }

            return response()->json(['success' => $message]);
        } catch (\Exception $e) {
            \Log::error('Assign document request error: ' . $e->getMessage());
            
            return response()->json(['error' => 'Terjadi error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Assign existing request to tool
     */
    public function assignRequest(Request $request, Project $project)
    {
        Log::info('Assign request called with data: ' . json_encode($request->all()));
        
        try {
            $request->validate([
                'tool_id' => 'required|exists:tools,idTools',
                'request_id' => 'required|exists:requests,id_req'
            ]);
            
            Log::info('Assign request validation passed');

            // Find the tool
            $tool = Tool::where('idTools', $request->tool_id)
                       ->where('no_IO', $project->no_IO)
                       ->firstOrFail();
                       
            // Find the request
            $requestModel = RequestModel::findOrFail($request->request_id);
            
            // Check if tool is currently on 'Hold' due to stock validation
            // If so, don't change the status - keep it on 'Hold'
            if ($tool->status_tools === 'Hold') {
                // Only update request_id, keep status_tools as 'Hold'
                $tool->update([
                    'request_id' => $requestModel->id_req
                ]);
                
                Log::info("Tool {$tool->Description} assigned to request {$requestModel->no_surat} but status_tools remains 'Hold' due to stock validation");
            } else {
                // Determine appropriate status_tools based on request status
                $statusTools = 'On Process'; // Default status
                
                switch ($requestModel->status_req) {
                    case 'Pending':
                        $statusTools = 'On Process';
                        break;
                    case 'On Process':
                        $statusTools = 'On Process';
                        break;
                    case 'Closed':
                        $statusTools = 'Closed';
                        break;
                    default:
                        $statusTools = 'On Process';
                        break;
                }
                
                $tool->update([
                    'request_id' => $requestModel->id_req,
                    'status_tools' => $statusTools
                ]);
                
                Log::info("Tool {$tool->Description} assigned to request {$requestModel->no_surat} with status_tools: {$statusTools}");
            }

            return response()->json([
                'success' => 'Request Successfully assigned to tool!',
                'tool' => [
                    'id' => $tool->idTools,
                    'description' => $tool->Description,
                    'status_tools' => $tool->status_tools
                ],
                'request' => [
                    'id_req' => $requestModel->id_req,
                    'no_surat' => $requestModel->no_surat,
                    'type_surat' => $requestModel->type_surat,
                    'jenis_req' => $requestModel->jenis_req,
                    'status_req' => $requestModel->status_req
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Assign request error: ' . $e->getMessage());
            
            return response()->json(['error' => 'Terjadi error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create new request and assign to tool
     */
    public function createAndAssignRequest(Request $request, Project $project)
    {
        Log::info('Create and assign request called with data: ' . json_encode($request->all()));
        
        try {
            $request->validate([
                'no_surat' => 'required|string|max:255|unique:requests,no_surat',
                'type_surat' => 'required|string|in:SPS,SPMP,PCM',
                'jenis_req' => 'required|string|in:PO,Kontrak,PCM',
                'date_req' => 'required|date',
                'tool_id' => 'required|exists:tools,idTools'
            ]);
            
            Log::info('Create and assign request validation passed');

            // 1. Create new request in requests table
            $newRequest = RequestModel::create([
                'no_surat' => $request->no_surat,
                'type_surat' => $request->type_surat,
                'jenis_req' => $request->jenis_req,
                'date_req' => $request->date_req,
                'status_req' => 'Pending' // Default status for new request
            ]);
            
            Log::info("New request created with ID: {$newRequest->id_req}, no_surat: {$newRequest->no_surat}");

            // 2. Find the tool
            $tool = Tool::where('idTools', $request->tool_id)
                       ->where('no_IO', $project->no_IO)
                       ->firstOrFail();
                       
            Log::info("Tool found: {$tool->Description} (ID: {$tool->idTools})");

            // 3. Update tool with new request_id
            // Check if tool is currently on 'Hold' due to stock validation
            if ($tool->status_tools === 'Hold') {
                // Only update request_id, keep status_tools as 'Hold'
                $tool->update([
                    'request_id' => $newRequest->id_req
                ]);
                
                Log::info("Tool {$tool->Description} assigned to new request {$newRequest->no_surat} but status_tools remains 'Hold' due to stock validation");

                $message = "New Request Successfully created and assigned to tool, however tool remains in HOLD status due to BOQ stock validation.";
            } else {
                // Set status_tools to 'On Process' for new request
                $tool->update([
                    'request_id' => $newRequest->id_req,
                    'status_tools' => 'On Process'
                ]);
                
                Log::info("Tool {$tool->Description} assigned to new request {$newRequest->no_surat} with status_tools: On Process");

                $message = "New Request Successfully created and assigned to tool!";
            }

            return response()->json([
                'success' => $message,
                'request_id' => $newRequest->id_req,
                'tool' => [
                    'id' => $tool->idTools,
                    'description' => $tool->Description,
                    'status_tools' => $tool->status_tools
                ],
                'request' => [
                    'id_req' => $newRequest->id_req,
                    'no_surat' => $newRequest->no_surat,
                    'type_surat' => $newRequest->type_surat,
                    'jenis_req' => $newRequest->jenis_req,
                    'date_req' => $newRequest->date_req,
                    'status_req' => $newRequest->status_req
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Create and assign request validation failed: ' . json_encode($e->errors()));
            
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Create and assign request error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json(['error' => 'Terjadi error: ' . $e->getMessage()], 500);
        }
    }
}

