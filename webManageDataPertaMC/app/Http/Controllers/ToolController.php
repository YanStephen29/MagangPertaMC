<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Project;
use App\Models\Bidang;
use App\Models\Document;
use App\Services\BoqValidationService;
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
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('Description', 'LIKE', "%{$search}%")
                  ->orWhere('unit', 'LIKE', "%{$search}%")
                  ->orWhere('remarks', 'LIKE', "%{$search}%");
            });
        }

        // Filter by document
        if ($request->filled('document')) {
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
        
        $bidangs = Bidang::all();
        $documents = Document::orderBy('date_issue', 'desc')->get();
        return view('projects.tools.create', compact('project', 'bidangs', 'documents'));
    }

    /**
     * Store a newly created tool
     */
    public function store(Request $request, Project $project, BoqValidationService $boqValidator)
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

        // Prepare tool data for BOQ validation
        $toolData = [
            'Description' => $request->Description,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'harga_satuan' => $request->harga_satuan ?? 0
        ];

        // Validate against BOQ
        $validationResult = $boqValidator->validateToolRequest($project, $toolData);
        
        // Check if validation passed or if user wants to force create
        if (!$validationResult['is_valid'] && !$request->has('force_create')) {
            // Format validation messages
            $messages = $boqValidator->formatValidationMessage($validationResult);
            
            // Store validation result in session for display
            session()->flash('boq_validation_error', true);
            session()->flash('validation_messages', $messages);
            session()->flash('validation_result', $validationResult);
            session()->flash('tool_data', $request->all());
            
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Validasi BOQ gagal. Silakan periksa detail di bawah.');
        }

        // If validation passed or forced, create the tool
        $tool = Tool::create([
            'Description' => $request->Description,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'delivery_date' => $request->delivery_date,
            'remarks' => $request->remarks,
            'no_IO' => $project->no_IO,
            'kode_GL' => $request->kode_GL,
            'no_document' => $request->no_document
        ]);

        $successMessage = 'Tool berhasil ditambahkan!';
        
        // Add BOQ info if validation found matches
        if (!empty($validationResult['matched_details'])) {
            $matchedDetail = $validationResult['matched_details'][0];
            if (isset($matchedDetail['availability'])) {
                $remaining = $matchedDetail['availability']['remaining_quantity'] - $request->quantity;
                $successMessage .= " Sisa BOQ: {$remaining} {$request->unit}";
            }
        }

        return redirect()->route('projects.tools.index', $project)
                        ->with('success', $successMessage);
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
        
        return view('tools.show', compact('tool', 'project'));
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
                        ->with('success', 'Tool berhasil diupdate!');
    }

    /**
     * Remove the specified tool
     */
    public function destroy(Project $project, Tool $tool)
    {
        $this->checkProjectAccess($project);
        
        $tool->delete();

        return redirect()->route('projects.tools.index', $project)
                        ->with('success', 'Tool berhasil dihapus!');
    }

    /**
     * Show form for bulk assign document to multiple tools
     */
    public function bulkAssignDocument(Request $request, Project $project)
    {
        $selectedTools = $request->get('selected_tools', []);
        
        if (empty($selectedTools)) {
            return redirect()->route('projects.tools.index', $project)
                           ->with('error', 'Pilih minimal satu tool untuk di-assign document');
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
                            ->with('error', 'Parameter tool_ids tidak ditemukan. Pastikan tools sudah dipilih.');
        }

        if (!$request->has('no_document')) {
            \Log::error('no_document parameter missing');
            return redirect()->route('projects.tools.index', $project)
                            ->with('error', 'Parameter no_document tidak ditemukan. Pastikan document sudah dipilih.');
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
                                ->with('success', "Berhasil assign {$updatedCount} tools ke document {$request->no_document}!");
            } else {
                return redirect()->route('projects.tools.index', $project)
                                ->with('error', "Tidak ada tools yang diupdate. Tools mungkin sudah ter-assign ke document yang sama.");
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
                            ->with('error', 'Terjadi error saat bulk assign: ' . $e->getMessage());
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
                            ->with('success', 'Document berhasil di-assign ke tool!');
        } catch (\Exception $e) {
            \Log::error('Quick assign error: ' . $e->getMessage());
            
            return redirect()->route('projects.tools.index', $project)
                            ->with('error', 'Terjadi error saat assign document: ' . $e->getMessage());
        }
    }
}
