<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Tahapan;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            $query = Document::query()->with(['tools', 'tahapans' => function($query) {
                $query->orderBy('Date_Tahapan', 'desc');
            }]);

            // If user is Project Manager, only show documents from assigned projects
            if ($admin->role === 'Project Manager') {
                $assignedProjectNos = \App\Models\Project::where('assigned_to', $admin->admin_id)->pluck('no_IO');
                $documentsFromAssignedProjects = Tool::whereIn('no_IO', $assignedProjectNos)
                    ->whereNotNull('no_document')
                    ->pluck('no_document')
                    ->unique();
                
                $query->whereIn('no_request', $documentsFromAssignedProjects);
            }

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('no_request', 'LIKE', "%{$search}%")
                      ->orWhere('jenis_request', 'LIKE', "%{$search}%");
                });
            }

            // Filter by jenis_request
            if ($request->filled('jenis_request')) {
                $query->where('jenis_request', $request->get('jenis_request'));
            }

            // Sorting functionality
            $sort = $request->get('sort', 'date_issue_desc');
            switch ($sort) {
                case 'date_issue_asc':
                    $query->orderBy('date_issue', 'asc');
                    break;
                case 'no_request_asc':
                    $query->orderBy('no_request', 'asc');
                    break;
                case 'no_request_desc':
                    $query->orderBy('no_request', 'desc');
                    break;
                case 'jenis_asc':
                    $query->orderBy('jenis_request', 'asc');
                    break;
                case 'jenis_desc':
                    $query->orderBy('jenis_request', 'desc');
                    break;
                default: // date_issue_desc
                    $query->orderBy('date_issue', 'desc');
                    break;
            }

            $documents = $query->paginate(15);

            return view('documents.index', compact('documents'));
        } catch (\Exception $e) {
            // For debugging - show error details
            \Log::error('Document index error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error loading documents: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Check if coming from Add Request modal
        $fromAddRequest = $request->query('from_add_request', false);
        $projectId = $request->query('project_id', null);
        
        // Store in session for use after document creation
        if ($fromAddRequest && $projectId) {
            session([
                'from_add_request' => true,
                'project_id' => $projectId
            ]);
        }
        
        // Pass context to view
        $context = [
            'from_add_request' => $fromAddRequest,
            'project_id' => $projectId
        ];
        
        return view('documents.create', $context);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            \Log::info('Document store started', [
                'from_add_request' => $request->input('from_add_request'),
                'project_id' => $request->input('project_id'),
                'session_from_add_request' => session('from_add_request'),
                'session_project_id' => session('project_id')
            ]);

            $validated = $request->validate([
                'no_request' => 'required|string|max:255|unique:documents,no_request',
                'jenis_request' => 'required|in:Material Request,Service Request,Facility Request,Aset',
                'date_issue' => 'required|date',
                'description' => 'nullable|string'
            ]);

            $document = Document::create($validated);
            \Log::info('Document created', ['document_no' => $document->no_request]);

            // Create initial tahapan (BELUM DI PROSES)
            Tahapan::create([
                'no_request' => $document->no_request,
                'namaTahapan' => Tahapan::TAHAPAN_BELUM_DIPROSES,
                'Date_Tahapan' => now() // Set current date when document is created
            ]);

            // Check if coming from Add Request workflow (from session or form data)
            $fromAddRequest = session('from_add_request') || $request->filled('from_add_request');
            $projectId = session('project_id') ?? $request->input('project_id');
            
            \Log::info('Checking workflow', [
                'fromAddRequest' => $fromAddRequest,
                'projectId' => $projectId
            ]);
            
            if ($fromAddRequest && $projectId) {
                \Log::info('Showing success modal for Add Request workflow', ['project' => $projectId]);
                
                // TODO: Create a Tool to link the document with the project (temporarily disabled)
                // We'll focus on the main issue first - selectBoqItems method not being called
                \Log::info('Skipping Tool creation for now - focusing on selectBoqItems method', [
                    'project_id' => $projectId,
                    'document_no' => $document->no_request
                ]);
                
                // Store project_id in session for BOQ selection workflow (don't clear it yet)
                session([
                    'created_document_project_id' => $projectId,
                    'project_id' => $projectId  // Keep this for BOQ selection
                ]);
                
                // Clear only the add request flag, keep project_id for BOQ workflow
                session()->forget(['from_add_request']);
                
                // Return back to create page with success modal data
                return back()->with([
                    'document_created_success' => true,
                    'document_no' => $document->no_request,
                    'project_id' => $projectId,
                    'from_add_request' => true,
                    'success' => 'Document berhasil dibuat dengan nomor: ' . $validated['no_request']
                ]);
            }

            \Log::info('Redirecting to documents.index for regular workflow');
            return redirect()->route('documents.index')
                ->with('success', 'Document berhasil dibuat dengan nomor: ' . $validated['no_request']);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in document store', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error in document store', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error creating document: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show next step options after document creation
     */
    public function afterCreate($project)
    {
        $projectModel = \App\Models\Project::where('no_IO', $project)->firstOrFail();
        $documentNo = session('new_document_no');
        
        return view('documents.after-create', compact('projectModel', 'documentNo'));
    }

    /**
     * Display the specified resource.
     */
    public function show($no_request)
    {
        try {
            $document = Document::where('no_request', base64_decode($no_request))->firstOrFail();
            $document->load(['tools.project', 'tools.bidang', 'tahapans' => function($query) {
                $query->orderBy('created_at', 'asc');
            }]);
            return view('documents.show', compact('document'));
        } catch (\Exception $e) {
            \Log::error('Document show error: ' . $e->getMessage());
            return redirect()->route('documents.index')
                ->with('error', 'Error loading document: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($no_request)
    {
        try {
            $document = Document::where('no_request', base64_decode($no_request))->firstOrFail();
            $document->load(['tools.project', 'tools.bidang', 'tahapans' => function($query) {
                $query->orderBy('created_at', 'asc');
            }]);
            
            $tahapanOptions = Tahapan::getTahapanOptions();
            return view('documents.edit', compact('document', 'tahapanOptions'));
        } catch (\Exception $e) {
            \Log::error('Document edit error: ' . $e->getMessage());
            return redirect()->route('documents.index')
                ->with('error', 'Error loading document for edit: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_request)
    {
        try {
            $document = Document::where('no_request', base64_decode($no_request))->firstOrFail();
            
            $validated = $request->validate([
                'no_request' => 'required|string|max:255|unique:documents,no_request,' . $document->no_request . ',no_request',
                'jenis_request' => 'required|in:Material Request,Service Request,Facility Request,Aset',
                'date_issue' => 'required|date',
                'description' => 'nullable|string',
                'tahapan' => 'nullable|in:' . implode(',', Tahapan::getTahapanOptions()),
                'tahapan_date' => 'nullable|date|required_with:tahapan',
                'new_tahapan' => 'nullable|in:' . implode(',', Tahapan::getTahapanOptions()),
                'complete_tahapan' => 'nullable|exists:tahapans,idTahapan'
            ]);

            // If no_request is being changed, we need to update related tools and tahapans
            if ($document->no_request !== $validated['no_request']) {
                // Update related tools to use the new document number
                $document->tools()->update(['no_document' => $validated['no_request']]);
                // Update related tahapans to use the new document number
                $document->tahapans()->update(['no_request' => $validated['no_request']]);
            }

            $document->update([
                'no_request' => $validated['no_request'],
                'jenis_request' => $validated['jenis_request'],
                'date_issue' => $validated['date_issue'],
                'description' => $validated['description']
            ]);

            $hasChanges = false;

            // Handle tahapan update if provided
            if (isset($validated['tahapan']) && isset($validated['tahapan_date'])) {
                // Check if this tahapan already exists
                $existingTahapan = $document->tahapans()
                    ->where('namaTahapan', $validated['tahapan'])
                    ->first();

                if ($existingTahapan) {
                    // Update existing tahapan date
                    $existingTahapan->update(['Date_Tahapan' => $validated['tahapan_date']]);
                    $hasChanges = true;
                } else {
                    // Create new tahapan
                    Tahapan::create([
                        'no_request' => $validated['no_request'],
                        'namaTahapan' => $validated['tahapan'],
                        'Date_Tahapan' => $validated['tahapan_date']
                    ]);
                    $hasChanges = true;
                }
            }

            // Handle alternative new_tahapan field (add without completion date)
            if (isset($validated['new_tahapan']) && !empty($validated['new_tahapan'])) {
                $existingTahapan = $document->tahapans()
                    ->where('namaTahapan', $validated['new_tahapan'])
                    ->first();

                if (!$existingTahapan) {
                    Tahapan::create([
                        'no_request' => $validated['no_request'],
                        'namaTahapan' => $validated['new_tahapan'],
                        'Date_Tahapan' => null // New tahapan starts incomplete
                    ]);
                    $hasChanges = true;
                }
            }

            // Handle complete_tahapan field (mark existing tahapan as completed)
            if (isset($validated['complete_tahapan']) && !empty($validated['complete_tahapan'])) {
                $tahapanToComplete = Tahapan::find($validated['complete_tahapan']);
                if ($tahapanToComplete && is_null($tahapanToComplete->Date_Tahapan)) {
                    $tahapanToComplete->update(['Date_Tahapan' => now()]);
                    $hasChanges = true;
                }
            }

            return redirect()->route('documents.index')
                ->with('success', 'Document berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Document update error: ' . $e->getMessage());
            return redirect()->route('documents.index')
                ->with('error', 'Error updating document: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_request)
    {
        $document = Document::where('no_request', base64_decode($no_request))->firstOrFail();
        
        // Check if document has related tools
        if ($document->tools()->count() > 0) {
            return redirect()->route('documents.index')
                ->with('error', 'Document tidak dapat dihapus karena masih memiliki tools terkait');
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document berhasil dihapus!');
    }

    /**
     * API endpoint to get documents for tool assignment
     */
    public function getForAssignment(Request $request)
    {
        $jenisRequest = $request->get('jenis_request');
        
        $query = Document::query();
        
        if ($jenisRequest) {
            $query->where('jenis_request', $jenisRequest);
        }
        
        $documents = $query->orderBy('date_issue', 'desc')->get(['no_request', 'jenis_request', 'date_issue']);
        
        return response()->json($documents);
    }
}
