<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Document;
use App\Models\Project;
use App\Models\Boq;
use App\Models\Detail;
use App\Models\RequestDetail;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($documentNo)
    {
        try {
            // Handle URL decoding properly
            $decodedDocumentNo = urldecode($documentNo);
            
            // Try to find document with decoded version first, then original
            $document = Document::with(['request', 'tahapans'])
                           ->where('no_request', $decodedDocumentNo)
                           ->orWhere('no_request', $documentNo)
                           ->first();
            
            if (!$document) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'error' => 'Document tidak ditemukan',
                        'message' => 'Document dengan nomor "' . $decodedDocumentNo . '" tidak ditemukan dalam sistem.'
                    ], 404);
                } else {
                    return redirect()->back()->withErrors(['error' => 'Document tidak ditemukan.']);
                }
            }
            
            // Check if document tahapan is completed
            if ($document->getProgressPercentage() < 100) {
                // Check if request is JSON (AJAX) or regular request
                if (request()->expectsJson()) {
                    return response()->json([
                        'error' => 'Document Stage not finished yet',
                        'message' => 'Document stage must reach 100% (EPC TO PROCUREMENT) before accessing the request.'
                    ], 422);
                } else {
                    return redirect()->back()->withErrors(['error' => 'Document stage not finished yet. Document stage must reach 100% (EPC TO PROCUREMENT) before accessing the request.']);
                }
            }

            // Get project and check BOQ
            $firstTool = $document->tools()->first();
            if ($firstTool && $firstTool->project) {
                $project = $firstTool->project;
                
                // If no pre-selected BOQ items, redirect to selection page
                if (!session('selected_boq_items')) {
                    // Check if BOQ exists
                    if (!$project->boq) {
                        if (request()->expectsJson()) {
                            return response()->json([
                                'error' => 'BOQ has not been created',
                                'message' => 'This project does not have a BOQ. Please create a BOQ before making a request.',
                                'action' => 'create_boq',
                                'project_id' => $project->no_IO
                            ], 422);
                        } else {
                            return redirect()->back()->withErrors(['error' => 'This project does not have a BOQ. Please create a BOQ before making a request.']);
                        }
                    }
                    
                    // Check if BOQ has sections with details
                    if ($project->boq->sections()->count() === 0) {
                        if (request()->expectsJson()) {
                            return response()->json([
                                'error' => 'BOQ is still empty',
                                'message' => 'BOQ already exists but has no sections. Please add sections and details to the BOQ first.',
                                'action' => 'manage_boq',
                                'project_id' => $project->no_IO
                            ], 422);
                        } else {
                            return redirect()->back()->withErrors(['error' => 'BOQ already exists but has no sections. Please add sections and details to the BOQ first.']);
                        }
                    }

                    // Check if BOQ sections have details
                    $hasDetails = false;
                    foreach ($project->boq->sections as $section) {
                        if ($section->details()->count() > 0) {
                            $hasDetails = true;
                            break;
                        }
                    }
                    
                    if (!$hasDetails) {
                        if (request()->expectsJson()) {
                            return response()->json([
                                'error' => 'BOQ is incomplete',
                                'message' => 'BOQ and sections already exist but have no details. Please add details to the BOQ sections first.',
                                'action' => 'manage_boq',
                                'project_id' => $project->no_IO
                            ], 422);
                        } else {
                            return redirect()->back()->withErrors(['error' => 'BOQ and sections already exist but have no details. Please add details to the BOQ sections first.']);
                        }
                    }

                    // Redirect to BOQ item selection
                    if (!request()->expectsJson()) {
                        return redirect()->route('requests.select-boq-items', $documentNo);
                    }
                }
            }

            // Handle pre-selected BOQ items - No availability checking needed
            // BOQ items yang dipilih akan langsung terhubung dengan request
            $selectedBoqItems = null;
            $boqUsageData = [];
            
            if (session('selected_boq_items')) {
                $selectedItemIds = session('selected_boq_items');
                $selectedBoqItems = Detail::whereIn('id', $selectedItemIds)
                    ->with(['section'])
                    ->get();
                
                // No need to check availability - user can request any quantity they need
                // The selected BOQ items will be directly linked to the request
                
                // Clear session after use
                session()->forget('selected_boq_items');
            }

            // If tool doesn't have a request, create one and link the tool to it
            if (!$firstTool->request) {
                $request = Request::create([
                    'type_surat' => 'SPS',
                    'jenis_req' => 'PO',
                    'no_surat' => '',
                    'date_req' => now(),
                    'status_req' => 'pending', // Default to pending for new requests
                ]);
                
                // Link the tool to the new request
                $firstTool->request_id = $request->id_req;
                $firstTool->save();
                
                $document->load('tools'); // Reload relationship
            }

            // Return appropriate response
            if (request()->expectsJson()) {
                return response()->json([
                    'document' => $document,
                    'request' => $firstTool?->request,
                    'selected_boq_items' => $selectedBoqItems,
                    'boq_usage_data' => $boqUsageData
                ]);
            } else {
                // Return view with BOQ data (no usage data needed since we don't check availability)
                return view('requests.form', compact('document', 'selectedBoqItems'));
            }
        } catch (\Exception $e) {
            Log::error('Request show error: ' . $e->getMessage());
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Error loading request data',
                    'message' => $e->getMessage()
                ], 500);
            } else {
                return redirect()->back()->withErrors(['error' => 'An error occurred while loading the request data..']);
            }
        }
    }



    /**
     * Show BOQ items selection page for creating request
     */
    public function selectBoqItems($documentNo)
    {
        Log::info('SelectBoqItems called for document: ' . $documentNo);
        
        try {
            // Handle URL decoding properly
            $decodedDocumentNo = urldecode($documentNo);
            
            Log::info('=== SelectBoqItems METHOD CALLED ===');
            Log::info('Original documentNo: ' . $documentNo);
            Log::info('Decoded documentNo: ' . $decodedDocumentNo);
            Log::info('Request URL: ' . request()->fullUrl());
            Log::info('Request method: ' . request()->method());
            Log::info('Available sessions: ', [
                'project_id' => session('project_id'),
                'created_document_project_id' => session('created_document_project_id'),
                'from_add_request' => session('from_add_request'),
                'all_sessions' => session()->all()
            ]);
            
            // Try to find document with decoded version first, then original
            $document = Document::with(['request', 'tahapans'])
                           ->where('no_request', $decodedDocumentNo)
                           ->orWhere('no_request', $documentNo)
                           ->first();
                           
            if (!$document) {
                Log::error('Document not found with number: ' . $decodedDocumentNo . ' or ' . $documentNo);
                
                // Debug: Check what documents exist in database
                $existingDocs = Document::select('no_request')->get();
                Log::info('Existing documents in database: ' . $existingDocs->pluck('no_request')->implode(', '));
                
                // Check if this is a retry request (to prevent infinite loops)
                $retryCount = request()->get('retry', 0);
                if ($retryCount < 3) {
                    Log::info('Document not found, attempting retry #' . ($retryCount + 1));
                    
                    // Wait a moment and retry
                    sleep(1);
                    
                    // Try again
                    $document = Document::with(['request', 'tahapans'])
                               ->where('no_request', $decodedDocumentNo)
                               ->orWhere('no_request', $documentNo)
                               ->first();
                               
                    if (!$document) {
                        // Redirect with retry parameter
                        $currentUrl = request()->fullUrl();
                        $separator = strpos($currentUrl, '?') !== false ? '&' : '?';
                        return redirect($currentUrl . $separator . 'retry=' . ($retryCount + 1));
                    }
                } else {
                    return redirect()->back()->withErrors(['error' => 'Document not found with number: ' . $decodedDocumentNo . '. Please ensure the document has been created and saved in the database. (Tried ' . $retryCount . ' times)']);
                }
            }
            
            // Check if document tahapan is completed (skip for Add Request workflow)
            $fromAddRequestWorkflow = request()->get('project_id') !== null; // If project_id is passed, it's from Add Request workflow
            
            if (!$fromAddRequestWorkflow && $document->getProgressPercentage() < 100) {
                return redirect()->back()->withErrors(['error' => 'Document stages are not complete. Document stages must reach 100% (EPC TO PROCUREMENT) before accessing the request.']);
            }
            
            if ($fromAddRequestWorkflow) {
                Log::info('Skipping tahapan check for Add Request workflow', [
                    'document' => $decodedDocumentNo,
                    'progress' => $document->getProgressPercentage() . '%'
                ]);
            }

            // Get project through multiple methods
            $project = null;
            
            // Method 1: Through existing tool relationship (for normal workflow)
            $firstTool = $document->tools()->first();
            if ($firstTool && $firstTool->project) {
                $project = $firstTool->project;
                Log::info('Project found through tool relationship', ['project_id' => $project->no_IO]);
            }
            
            // Method 2: Through session or URL parameter (for Add Request workflow)
            if (!$project) {
                $projectId = session('project_id') ?? request()->get('project_id');
                if ($projectId) {
                    $project = \App\Models\Project::where('no_IO', $projectId)->first();
                    if ($project) {
                        Log::info('Project found through session/parameter', ['project_id' => $projectId]);
                    }
                }
            }
            
            // Method 3: Try to find project through document pattern or other documents with same pattern
            if (!$project) {
                // For Add Request workflow, document number might contain project info
                // Try to find other documents with similar pattern that have tools
                $documentPattern = explode('-', $decodedDocumentNo)[0] ?? $decodedDocumentNo;
                
                $relatedDocument = Document::where('no_request', 'LIKE', $documentPattern . '%')
                    ->whereHas('tools.project')
                    ->with('tools.project')
                    ->first();
                    
                if ($relatedDocument && $relatedDocument->tools->first()) {
                    $project = $relatedDocument->tools->first()->project;
                    Log::info('Project found through related document pattern', [
                        'pattern' => $documentPattern,
                        'related_doc' => $relatedDocument->no_request,
                        'project_id' => $project->no_IO
                    ]);
                }
            }
            
            // Method 4: Try to get from success modal session (from document creation workflow)
            if (!$project) {
                $createdProjectId = session('created_document_project_id');
                if ($createdProjectId) {
                    $project = \App\Models\Project::where('no_IO', $createdProjectId)->first();
                    if ($project) {
                        Log::info('Project found through created document session', ['project_id' => $createdProjectId]);
                    }
                }
            }
            
            if (!$project) {
                Log::error('No project found for document after all methods', [
                    'document' => $decodedDocumentNo,
                    'has_tools' => $document->tools()->count(),
                    'session_project_id' => session('project_id'),
                    'request_project_id' => request()->get('project_id'),
                    'created_document_project_id' => session('created_document_project_id')
                ]);
                return redirect()->back()->withErrors(['error' => 'No project was found for this document. Please ensure the document was created using the correct workflow.']);
            }
            
            // Check if BOQ exists and has content
            if (!$project->boq) {
                return redirect()->back()->withErrors(['error' => 'This project does not have a BOQ. Please create a BOQ first.']);
            }
            
            if ($project->boq->sections()->count() === 0) {
                return redirect()->back()->withErrors(['error' => 'BOQ already exists but has no sections. Please add sections and details to the BOQ first.']);
            }

            // Load BOQ with sections and details
            $boq = $project->boq->load(['sections.details']);
            
            return view('requests.select-boq-items', compact('document', 'project', 'boq'));
        } catch (\Exception $e) {
            Log::error('Error in selectBoqItems: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while loading the BOQ items selection page.']);
        }
    }

    /**
     * Process BOQ items selection and redirect to request form
     */
    public function processBoqSelection(HttpRequest $request, $documentNo)
    {
        try {
            // Handle URL decoding properly
            $decodedDocumentNo = urldecode($documentNo);
            
            Log::info('ProcessBoqSelection called with documentNo: ' . $documentNo);
            Log::info('Decoded documentNo: ' . $decodedDocumentNo);
            
            $request->validate([
                'selected_details' => 'required|array|min:1',
                'selected_details.*' => 'exists:details,no'
            ], [
                'selected_details.required' => 'Choose at least one item from the BOQ.',
                'selected_details.min' => 'Choose at least one item from the BOQ.',
            ]);

            // Get document and project for validation
            $document = Document::where('no_request', $decodedDocumentNo)
                           ->orWhere('no_request', $documentNo)
                           ->first();
                           
            if (!$document) {
                return redirect()->back()->withErrors(['error' => 'Document not found.']);
            }

            // Get project through multiple methods (same logic as selectBoqItems)
            $project = null;
            
            // Method 1: Through existing tool relationship (for normal workflow)
            $firstTool = $document->tools()->first();
            if ($firstTool && $firstTool->project) {
                $project = $firstTool->project;
                Log::info('Project found through tool relationship in processBoqSelection', ['project_id' => $project->no_IO]);
            }
            
            // Method 2: Through session or URL parameter (for Add Request workflow)
            if (!$project) {
                $projectId = session('project_id') ?? request()->get('project_id');
                if ($projectId) {
                    $project = \App\Models\Project::where('no_IO', $projectId)->first();
                    if ($project) {
                        Log::info('Project found through session/parameter in processBoqSelection', ['project_id' => $projectId]);
                    }
                }
            }
            
            // Method 3: Try to get from success modal session (from document creation workflow)
            if (!$project) {
                $createdProjectId = session('created_document_project_id');
                if ($createdProjectId) {
                    $project = \App\Models\Project::where('no_IO', $createdProjectId)->first();
                    if ($project) {
                        Log::info('Project found through created document session in processBoqSelection', ['project_id' => $createdProjectId]);
                    }
                }
            }
            
            if (!$project) {
                Log::error('No project found for document in processBoqSelection', [
                    'document' => $decodedDocumentNo,
                    'session_project_id' => session('project_id'),
                    'request_project_id' => request()->get('project_id'),
                    'created_document_project_id' => session('created_document_project_id')
                ]);
                return redirect()->back()->withErrors(['error' => 'No project was found for this document.']);
            }

            // Validate that selected details belong to current project's BOQ (same as ToolController)
            $selectedDetails = Detail::whereIn('no', $request->selected_details)
                ->with(['section.boq'])
                ->get();
                
            $invalidDetails = $selectedDetails->filter(function($detail) use ($project) {
                return !$detail->section || 
                       !$detail->section->boq || 
                       $detail->section->boq->project_no_io != $project->no_IO;
            });
            
            if ($invalidDetails->count() > 0) {
                return redirect()->back()->withErrors(['error' => 'Some selected BOQ items are not valid for this project.']);
            }

            // Store selected BOQ items in session for use in tool creation (same as ToolController)
            session(['selected_boq_items_for_tool' => $request->selected_details]);
            
            // Redirect to tool creation form with pre-selected items and document assigned
            return redirect()->route('projects.tools.create', $project)
                ->with('assigned_document', $decodedDocumentNo);
                
        } catch (\Exception $e) {
            Log::error('ProcessBoqSelection error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while processing the BOQ items selection.']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HttpRequest $httpRequest, $requestId)
    {
        try {
            Log::info('Request update called with requestId: ' . $requestId);
            Log::info('Request data: ' . json_encode($httpRequest->all()));
            
            $request = Request::findOrFail($requestId);
            
            // Check if can be accessed through tool's document
            $document = $request->tools->first()?->document;
            if (!$document || $document->getProgressPercentage() < 100) {
                return response()->json([
                    'error' => 'Document stage is not complete',
                    'message' => 'Document stage must reach 100% (EPC TO PROCUREMENT) before accessing the request.'
                ], 422);
            }

            // Validate basic request data
            $validated = $httpRequest->validate([
                'type_surat' => 'required|in:SPS,SPMP,PCM',
                'jenis_req' => 'required|in:PO,Kontrak,PCM',
                'no_surat' => 'required|string|max:45',
                'date_req' => 'required|date',
                'status_req' => 'required|in:Pending,On Process,Closed',
                'selected_boq_items' => 'nullable|string',
                'has_request_details' => 'nullable|boolean',
                'request_details' => 'nullable|array'
            ]);
            
            Log::info('Validation passed');

            // Update basic request data
            $request->update([
                'type_surat' => $validated['type_surat'],
                'jenis_req' => $validated['jenis_req'],
                'no_surat' => $validated['no_surat'],
                'date_req' => $validated['date_req'],
                'status_req' => $validated['status_req']
            ]);

            // Return success response
            if (!$httpRequest->expectsJson()) {
                return redirect()->back()->with('success', 'Request Successfully updated!');
            }

            return response()->json([
                'success' => 'Request Successfully updated!',
                'request' => $request->load('requestDetails.detail')
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (!$httpRequest->expectsJson()) {
                return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
            }

            return response()->json([
                'error' => 'Error updating request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update only the status of the specified request.
     */
    public function updateStatus(HttpRequest $httpRequest, $requestId)
    {
        try {
            Log::info('Request status update called with requestId: ' . $requestId);
            Log::info('Status data: ' . json_encode($httpRequest->all()));
            
            $request = Request::findOrFail($requestId);
            
            // Validate only the status field
            $validated = $httpRequest->validate([
                'status_req' => 'required|in:Pending,On Process,Closed'
            ]);
            
            Log::info('Status validation passed');

            // Update only the status
            $request->update([
                'status_req' => $validated['status_req']
            ]);

            // Return success response
            if (!$httpRequest->expectsJson()) {
                return redirect()->back()->with('success', 'Status request successfully updated!');
            }

            return response()->json([
                'success' => 'Status request successfully updated!',
                'request' => $request
            ]);

            // Check if request was submitted via web form (non-JSON)
            if (!$httpRequest->expectsJson()) {
                // If has request details, redirect to details page
                if ($httpRequest->has('has_request_details') && !empty($validated['request_details'])) {
                    return redirect()->route('requests.details', $request->id_req)
                        ->with('success', 'Request successfully updated with BOQ item details!');
                } else {
                    return redirect()->back()->with('success', 'Request successfully updated!');
                }
            }

            return response()->json([
                'success' => 'Request successfully updated with BOQ item details!',
                'request' => $request->load('requestDetails.detail')
            ]);
        } catch (\Exception $e) {
            Log::error('Request update error: ' . $e->getMessage());
            
            if (!$httpRequest->expectsJson()) {
                return redirect()->back()
                    ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])
                    ->withInput();
            }
            
            return response()->json([
                'error' => 'Error updating request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the request details with BOQ items
     */
    public function showDetails($requestId)
    {
        try {
            Log::info('RequestController showDetails called', [
                'requestId' => $requestId,
                'expectsJson' => request()->expectsJson(),
                'headers' => request()->headers->all()
            ]);
            
            $request = Request::with([
                'requestDetails.detail.section', 
                'tools.document'
            ])->findOrFail($requestId);
            
            Log::info('Request found', [
                'request_id' => $request->id_req,
                'no_surat' => $request->no_surat,
                'tools_count' => $request->tools->count()
            ]);
            
            // Check access permissions
            $document = $request->tools->first()?->document;
            
            Log::info('Document check', [
                'document_found' => $document ? true : false,
                'document_no' => $document?->no_request ?? 'null'
            ]);
            
            if (!$document) {
                Log::warning('Document not found for request', ['requestId' => $requestId]);
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'error' => 'Document not found for this request.'
                    ], 404);
                }
                return redirect()->back()->withErrors(['error' => 'Document not found for this request.']);
            }
            
            // Return JSON for AJAX requests
            if (request()->expectsJson()) {
                return response()->json([
                    'request' => [
                        'id_req' => $request->id_req,
                        'no_surat' => $request->no_surat,
                        'type_surat' => $request->type_surat,
                        'jenis_req' => $request->jenis_req,
                        'date_req' => $request->date_req->format('Y-m-d'),
                        'status_req' => $request->status_req
                    ],
                    'document' => [
                        'no_request' => $document->no_request,
                        'jenis_request' => $document->jenis_request
                    ]
                ]);
            }
            
            return view('requests.show', compact('request', 'document'));
        } catch (\Exception $e) {
            Log::error('Request show details error: ' . $e->getMessage());
            
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'An error occurred while loading request details.'
                ], 500);
            }

            return redirect()->back()->withErrors(['error' => 'An error occurred while loading request details.']);
        }
    }

    /**
     * Check if document can access request based on tahapan
     */
    public function checkAccess($documentNo)
    {
        try {
            $document = Document::with(['tahapans'])->where('no_request', $documentNo)->firstOrFail();
            
            $canAccess = $document->getProgressPercentage() >= 100;
            
            return response()->json([
                'can_access' => $canAccess,
                'progress' => $document->getProgressPercentage(),
                'current_tahapan' => $document->getCurrentTahapan()?->namaTahapan
            ]);
        } catch (\Exception $e) {
            Log::error('Request access check error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error checking access',
                'can_access' => false
            ], 500);
        }
    }

    /**
     * Store a new request with auto-generated number
     */
    public function store(\Illuminate\Http\Request $request)
    {
        Log::info('Store request called with data: ' . json_encode($request->all()));
        Log::info('Request method: ' . $request->method());
        Log::info('Request headers: ' . json_encode($request->headers->all()));
        
        try {
            // Validate the request
            $validatedData = $request->validate([
                'jenis_req' => 'required|string',
                'type_surat' => 'required|string',
                'date_req' => 'required|date',
                'document_no' => 'required|string'
            ]);
            
            Log::info('Validation passed with data: ' . json_encode($validatedData));
            
            // Generate the next number for no_surat using the same format as modal: {code}-{year}-{number}
            $jenisReq = $validatedData['jenis_req'];
            
            // Map jenis_req to code (same as in modal)
            $requestCodes = [
                'PO' => 'PO',
                'Kontrak' => 'KT', 
                'PCM' => 'PCM'
            ];
            
            $code = $requestCodes[$jenisReq] ?? $jenisReq;
            $currentYear = date('Y');
            $prefix = $code . '-' . $currentYear . '-';
            
            // Find latest request with same prefix
            $latestRequest = \App\Models\Request::where('no_surat', 'LIKE', $prefix . '%')
                                              ->orderBy('no_surat', 'desc')
                                              ->first();
            
            $nextNumber = 1;
            if ($latestRequest) {
                $parts = explode('-', $latestRequest->no_surat);
                if (count($parts) >= 3 && is_numeric($parts[2])) {
                    $nextNumber = intval($parts[2]) + 1;
                }
            }
            
            $no_surat = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            
            Log::info('Generated no_surat: ' . $no_surat);
            
            // Create the new request
            $newRequest = \App\Models\Request::create([
                'no_surat' => $no_surat,
                'jenis_req' => $validatedData['jenis_req'],
                'type_surat' => $validatedData['type_surat'],
                'date_req' => $validatedData['date_req'],
                'status_req' => 'Pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            Log::info('Request created with ID: ' . $newRequest->id_req);
            
            // Find the tool by document_no and assign it to this new request
            $tool = \App\Models\Tool::where('no_document', $validatedData['document_no'])->first();
            
            if ($tool) {
                $tool->update([
                    'request_id' => $newRequest->id_req,
                    'updated_at' => now()
                ]);
                Log::info('Tool assigned to new request. Tool ID: ' . $tool->idTools . ', Request ID: ' . $newRequest->id_req);
            } else {
                Log::warning('Tool not found with document_no: ' . $validatedData['document_no']);
                return response()->json([
                    'error' => 'Tool tidak ditemukan dengan document number: ' . $validatedData['document_no']
                ], 404);
            }
            
            return response()->json([
                'success' => 'New request berhasil dibuat dan di-assign ke tool!',
                'request' => [
                    'id_req' => $newRequest->id_req,
                    'no_surat' => $newRequest->no_surat,
                    'type_surat' => $newRequest->type_surat,
                    'jenis_req' => $newRequest->jenis_req,
                    'status_req' => $newRequest->status_req,
                    'date_req' => $newRequest->date_req
                ]
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json([
                'error' => 'Validation failed: ' . implode(', ', array_flatten($e->errors()))
            ], 422);
        } catch (\Exception $e) {
            Log::error('Store request error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Terjadi error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get requests for API endpoint
     */
    public function getRequestsForApi()
    {
        Log::info('getRequestsForApi called');
        
        try {
            $requests = Request::select('id_req', 'no_surat', 'type_surat', 'jenis_req', 'date_req', 'status_req')
                              ->orderBy('created_at', 'desc')
                              ->get();
            
            Log::info('Found ' . $requests->count() . ' requests');
            Log::info('Requests data: ' . json_encode($requests->toArray()));
            
            return response()->json([
                'success' => true,
                'requests' => $requests
            ]);
        } catch (\Exception $e) {
            Log::error('Get requests for API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error loading requests'
            ], 500);
        }
    }
}
