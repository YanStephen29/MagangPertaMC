<?php

namespace App\Http\Controllers;

use App\Models\Boq;
use App\Models\Admin;
use App\Models\Project;
use App\Exports\BoqExport;
use App\Exports\BoqOnlyExport;
use App\Exports\BoqWithActualExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class BoqController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    /**
     * Display a listing of the resource for a specific project.
     */
    public function index(Project $project)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if admin can access BOQ for this project
        if (!$project->canAccessBOQ($admin)) {
            return redirect()->route('projects.index')->with('error', 'This project is not assigned to you. You can only access BOQ for projects that are assigned to you.');
        }
        
        // Get BOQ for this project with sections and details
        $boq = $project->boq()->with(['sections.details.children'])->first();
        
        // Auto-update status if needed
        if ($boq) {
            $boq->autoUpdateStatus();
        }
        
        return view('boqs.index', compact('boq', 'project'));
    }
    
    /**
     * Generate BOQ number automatically
     */
    private function generateBoqNumber()
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "BOQ{$year}{$month}";
        
        // Get the latest BOQ with this prefix
        $latestBoq = Boq::where('nomorBoq', 'like', $prefix . '%')
                        ->orderBy('nomorBoq', 'desc')
                        ->first();
        
        if ($latestBoq) {
            // Extract the sequential number and increment
            $lastNumber = (int)substr($latestBoq->nomorBoq, -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }
        
        return $prefix . $nextNumber;
    }

    /**
     * Show the form for creating a new resource for a specific project.
     */
    public function create(Project $project)
    {
        $admin = Auth::guard('admin')->user();
        
        // Debug: Log access check
        \Log::info('BOQ Create Debug', [
            'admin_id' => $admin->admin_id,
            'admin_role' => $admin->role,
            'project_no_io' => $project->no_IO,
            'project_assigned_to' => $project->assigned_to,
            'canAccessBOQ' => $project->canAccessBOQ($admin),
            'canManageBoq_global' => $admin->canManageBoq(),
            'canManageBoq_project' => $admin->canManageBoq($project),
            'hasLockedAnyBoq' => $admin->hasLockedAnyBoq(),
            'project_has_boq' => $project->boq ? true : false,
            'project_boq_status' => $project->boq ? $project->boq->status : 'N/A'
        ]);
        
        // Check if admin can access BOQ for this project
        if (!$project->canAccessBOQ($admin)) {
            \Log::info('BOQ Create Failed: canAccessBOQ returned false');
            return redirect()->route('projects.index')->with('error', 'This project is not assigned to you. You can only access BOQ for projects that are assigned to you.');
        }
        
        // Check if PM can manage BOQ for this specific project
        if (!$admin->canManageBoq($project)) {
            \Log::info('BOQ Create Failed: canManageBoq for this project returned false', [
                'admin_role' => $admin->role,
                'project_no_io' => $project->no_IO,
                'project_has_boq' => $project->boq ? true : false,
                'boq_status' => $project->boq ? $project->boq->status : 'N/A'
            ]);
            
            return redirect()->route('projects.index')->with('error', 'Cannot manage BOQ for this project. BOQ is already locked.');
        }
        
        // Check if project already has BOQ
        if ($project->boq) {
            \Log::info('BOQ Create Failed: Project already has BOQ');
            return redirect()->route('projects.boq.index', $project)->with('error', 'This project already has a BOQ.');
        }
        
        // Generate BOQ number automatically
        $nomorBoq = $this->generateBoqNumber();
        
        return view('boqs.create', compact('project', 'nomorBoq'));
    }

    /**
     * Store a newly created resource in storage for a specific project.
     */
    public function store(Request $request, Project $project)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if admin can access BOQ for this project
        if (!$project->canAccessBOQ($admin)) {
            return redirect()->route('projects.index')->with('error', 'This project is not assigned to you. You can only access BOQ for projects that are assigned to you.');
        }
        
        // Check if PM can manage BOQ for this project
        if (!$admin->canManageBoq($project)) {
            return redirect()->route('projects.index')->with('error', 'Cannot manage BOQ for this project. BOQ is already locked.');
        }
        
        // Check if project already has BOQ
        if ($project->boq) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'This project already has a BOQ.');
        }
        
        $validated = $request->validate([
            'nomorBoq' => 'required|string|max:15|unique:boqs',
            'date' => 'required|date'
        ]);
        
        // Add project reference and initial total_harga
        $validated['project_no_io'] = $project->no_IO;
        $validated['total_harga'] = 0;

        Boq::create($validated);
        return redirect()->route('projects.boq.index', $project)->with('success', 'BOQ created successfully.');
    }

    /**
     * Display the specified resource for a specific project.
     */
    public function show(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if (!$project->canAccessBOQ($admin)) {
            return redirect()->route('projects.index')->with('error', 'This project is not assigned to you. You can only access BOQ for projects that are assigned to you.');
        }
        
        // Ensure BOQ belongs to this project
        if ($boq->project_no_io !== $project->no_IO) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'BOQ not found for this project.');
        }
        
        $boq->load(['project', 'sections']);
        
        return view('boqs.show', compact('boq', 'project'));
    }

    /**
     * Show the form for editing the specified resource for a specific project.
     */
    public function edit(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if admin can access BOQ for this project
        if (!$project->canAccessBOQ($admin)) {
            return redirect()->route('projects.index')->with('error', 'This project is not assigned to you. You can only access BOQ for projects that are assigned to you.');
        }
        
        // Check if BOQ can be edited (considering lock status)
        if (!$boq->canBeEdited($admin)) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'This BOQ is already locked or you do not have access to edit it.');
        }
        
        // Ensure BOQ belongs to this project
        if ($boq->project_no_io !== $project->no_IO) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'BOQ not found for this project.');
        }
        
        return view('boqs.edit', compact('boq', 'project'));
    }

    /**
     * Update the specified resource in storage for a specific project.
     */
    public function update(Request $request, Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if (!$project->canAccessBOQ($admin)) {
            return redirect()->route('projects.index')->with('error', 'This project is not assigned to you. You can only access BOQ for projects that are assigned to you.');
        }
        
        // Check if PM can manage BOQ for this project
        if (!$admin->canManageBoq($project)) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'Cannot edit BOQ. This BOQ is already locked.');
        }
        
        // Ensure BOQ belongs to this project
        if ($boq->project_no_io !== $project->no_IO) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'BOQ not found for this project.');
        }
        
        $validated = $request->validate([
            'nomorBoq' => 'required|string|max:15|unique:boqs,nomorBoq,' . $boq->nomorBoq . ',nomorBoq',
            'total_harga' => 'required|integer|min:0',
            'date' => 'required|date'
        ]);

        $boq->update($validated);
        return redirect()->route('projects.boq.index', $project)->with('success', 'BOQ updated successfully.');
    }

    /**
     * Remove the specified resource from storage for a specific project.
     */
    public function destroy(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if admin can access BOQ for this project
        if (!$project->canAccessBOQ($admin)) {
            return redirect()->route('projects.index')->with('error', 'This project is not assigned to you. You can only access BOQ for projects that are assigned to you.');
        }
        
        // Check if BOQ can be deleted (considering lock status)
        if (!$boq->canBeDeleted($admin)) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'BOQ sudah di-lock atau Anda tidak memiliki akses untuk menghapus BOQ ini.');
        }
        
        // Ensure BOQ belongs to this project
        if ($boq->project_no_io !== $project->no_IO) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'BOQ not found for this project.');
        }
        
        $boq->delete();
        return redirect()->route('projects.boq.index', $project)->with('success', 'BOQ deleted successfully.');
    }

    /**
     * Show form for uploading Excel file
     */
    public function showUploadForm(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if (!$project->canAccessBOQ($admin)) {
            return redirect()->route('projects.index')->with('error', 'This project is not assigned to you. You can only access BOQ for projects that are assigned to you.');
        }

        // Ensure BOQ belongs to this project
        if ($boq->project_no_io !== $project->no_IO) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'BOQ not found for this project.');
        }

        return view('boq.upload', compact('project', 'boq'));
    }

    /**
     * Handle Excel file upload and import
     */
    public function uploadExcel(Request $request, Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if (!$project->canAccessBOQ($admin)) {
            return redirect()->route('projects.index')->with('error', 'This project is not assigned to you. You can only access BOQ for projects that are assigned to you.');
        }

        // Ensure BOQ belongs to this project
        if ($boq->project_no_io !== $project->no_IO) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'BOQ not found for this project.');
        }

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('excel_file');
            
            // Import the Excel file
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\BoqExcelImport($boq), 
                $file
            );

            return redirect()->route('projects.boq.index', $project)
                           ->with('success', 'Excel file uploaded and processed successfully! Sections and details have been created.');
                           
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error processing Excel file: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display comprehensive view of all sections and details
     */
    public function manageDetails(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if (!$project->canAccessBOQ($admin)) 
        {
            return redirect()->route('projects.index')->with('error', 'This project is not assigned to you. You can only access BOQ for projects that are assigned to you.');
        }

        // Get all sections with all details (including nested children) for comprehensive view
        $sections = $boq->sections()->with([
            'details' => function($query) 
            { 
                $query->whereNull('parent_no')->orderBy('no', 'asc');
            },  
            'details.requestDetails.request',
            'details.children' => function($query) 
            {
                $query->orderBy('no', 'asc');
            },  
            'details.children.requestDetails.request',
            'details.children.children' => function($query) 
            {
                $query->orderBy('no', 'asc');
            },  
            'details.children.children.requestDetails.request',
            'details.children.children.children' => function($query) 
            {
                $query->orderBy('no', 'asc');
            }
        ])->orderBy('id', 'asc')->get();

        return view('boqs.manage-details', compact('project', 'boq', 'sections'));
    }

    /**
     * Download BOQ details as Excel file
     */
    public function downloadExcel(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if (!$project->canAccessBOQ($admin)) {
            abort(403, 'Project ini tidak di-assign kepada Anda. Hanya bisa mengakses BOQ project yang di-assign.');
        }

        // Get all sections with their details in hierarchical order
        $sections = $boq->sections()->with([
            'details' => function($query) {
                $query->whereNull('parent_no')->orderBy('no', 'asc');
            },
            'details.children' => function($query) {
                $query->orderBy('no', 'asc');
            },
            'details.children.children' => function($query) {
                $query->orderBy('no', 'asc');
            },
            'details.children.children.children' => function($query) {
                $query->orderBy('no', 'asc');
            }
        ])->orderBy('id', 'asc')->get();

        // Clean filename from invalid characters
        $cleanProjectTitle = preg_replace('/[^\w\-_\.]/', '_', $project->title_project);
        $cleanBoqNumber = preg_replace('/[^\w\-_\.]/', '_', $boq->nomorBoq);
        $filename = 'BOQ_' . $cleanProjectTitle . '_' . $cleanBoqNumber . '.xlsx';
        
        return Excel::download(new BoqExport($project, $boq, $sections), $filename);
    }

    /**
     * Download BOQ Only (using template) as Excel file
     */
    public function downloadBoqOnly(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if (!$project->canAccessBOQ($admin)) {
            abort(403, 'Project ini tidak di-assign kepada Anda. Hanya bisa mengakses BOQ project yang di-assign.');
        }

        // Get all sections with their details in hierarchical order
        $sections = $boq->sections()->with([
            'details' => function($query) {
                $query->whereNull('parent_no')->orderBy('no', 'asc');
            },
            'details.children' => function($query) {
                $query->orderBy('no', 'asc');
            },
            'details.children.children' => function($query) {
                $query->orderBy('no', 'asc');
            },
            'details.children.children.children' => function($query) {
                $query->orderBy('no', 'asc');
            }
        ])->orderBy('id', 'asc')->get();

        $export = new BoqOnlyExport($project, $boq, $sections);
        return $export->download();
    }

    /**
     * Download BOQ with actual usage data as Excel
     */
    public function downloadBoqWithActual(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if (!$project->canAccessBOQ($admin)) {
            abort(403, 'Project ini tidak di-assign kepada Anda. Hanya bisa mengakses BOQ project yang di-assign.');
        }

        // Get all sections with their details in hierarchical order including request details for usage calculation
        $sections = $boq->sections()->with([
            'details' => function($query) {
                $query->whereNull('parent_no')->orderBy('no', 'asc');
            },
            'details.children' => function($query) {
                $query->orderBy('no', 'asc');
            },
            'details.children.children' => function($query) {
                $query->orderBy('no', 'asc');
            },
            'details.children.children.children' => function($query) {
                $query->orderBy('no', 'asc');
            },
            'details.requestDetails.request', // For usage calculation
            'details.children.requestDetails.request',
            'details.children.children.requestDetails.request',
            'details.children.children.children.requestDetails.request'
        ])->orderBy('id', 'asc')->get();

        $export = new BoqWithActualExport($project, $boq, $sections);
        return $export->download();
    }

    /**
     * Download BOQ details as PDF file (HTML version for printing)
     */
    public function downloadPdf(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if (!$project->canAccessBOQ($admin)) {
            abort(403, 'Project ini tidak di-assign kepada Anda. Hanya bisa mengakses BOQ project yang di-assign.');
        }

        // Get all sections with their details in hierarchical order
        $sections = $boq->sections()->with([
            'details' => function($query) {
                $query->whereNull('parent_no')->orderBy('no', 'asc');
            },
            'details.children' => function($query) {
                $query->orderBy('no', 'asc');
            },
            'details.children.children' => function($query) {
                $query->orderBy('no', 'asc');
            },
            'details.children.children.children' => function($query) {
                $query->orderBy('no', 'asc');
            }
        ])->orderBy('id', 'asc')->get();

        // Return HTML view that can be printed as PDF
        return response()->view('exports.boq-pdf', compact('project', 'boq', 'sections'))
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="BOQ_' . $project->title_project . '_' . $boq->nomorBoq . '.html"');
    }
    
    /**
     * Lock BOQ if it passes validation
     */
    public function lockBoq(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if (!$project->canAccessBOQ($admin)) {
            return redirect()->route('projects.index')->with('error', 'This Project is not assigned to you. You can only access BOQ for projects that are assigned to you.');
        }
        
        // Verify BOQ belongs to this project
        if ($boq->project_no_io !== $project->no_IO) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'BOQ not found for this project.');
        }
        
        // Check if BOQ is already locked
        if ($boq->status === 'Locked') {
            return redirect()->route('projects.boq.index', $project)->with('info', 'BOQ is already locked.');
        }

        if (!$admin->canManageBoq($project)) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'Cannot lock BOQ. You do not have permission to manage this BOQ.');
        }

        $errors = [];
        $boq->loadMissing('sections.details.children');

        if ($boq->sections->count() === 0) {
            $errors[] = 'BOQ must have at least one section.';
        } else {
            foreach ($boq->sections as $section) {
                // Memanggil helper function Anda yang sudah ada
                $sectionErrors = $this->getSectionValidationErrors($section); 
                if (!empty($sectionErrors)) {
                    // Cek jika $sectionErrors adalah array, jika ya, gabungkan
                    $errorString = is_array($sectionErrors) ? implode(', ', $sectionErrors) : $sectionErrors;
                    $errors[] = "Section '{$section->nama}': " . $errorString;
                }
            }
        }

        if (!empty($errors)) {
            $errorMessage = 'Cannot lock BOQ. Please fix the following issues: ' . implode(' | ', $errors);
            return redirect()->route('projects.boq.index', $project)->with('error', $errorMessage);
        }

        $boq->status = 'Locked';
        $boq->locked_by_admin_id = $admin->getKey(); // getKey() akan ambil 'admin_id'
        $boq->save();

        if ($admin->role === 'Project Manager') {
            $admin->revokeBoqPrivileges(); 
        }

        return redirect()->route('projects.boq.index', $project)->with('success', 'BOQ has been locked successfully. Your management access for this specific project is now revoked.');
    }
    
    public function unlock(Project $project, Boq $boq)
    {   
        $admin = Auth::guard('admin')->user();

        if(!in_array($admin->role, ['Admin', 'VP'])) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'You do not have permission to unlock this BOQ.');
        }
        if ($boq->status !== 'Locked') {
            return back()->with('info', 'This BOQ is not locked yet.');
        }

        $originalAdmin = Admin::find($boq->locked_by_admin_id);

        if ($originalAdmin) {
            $originalAdmin->restoreBoqPrivileges();
        }
    
        $boq->status = 'Open';
        $boq->locked_by_admin_id = null;
        $boq->save();

        return redirect()->route('projects.boq.index', $project)->with('success', 'BOQ has been unlocked. Privileges have been restored to the original manager.');
    }
    
    /**
     * Get validation errors for a specific section
     */
    private function getSectionValidationErrors($section)
    {
        $errors = [];
        $allDetails = $this->getAllDetailsFromSection($section);
        
        if ($allDetails->count() === 0) {
            $errors[] = 'Section must have at least one detail';
            return $errors;
        }
        
        foreach ($allDetails as $detail) {
            // Only check leaf details (details without children)
            if ($detail->children->count() === 0) {
                $detailErrors = [];
                
                if (empty($detail->quantity) || $detail->quantity <= 0) {
                    $detailErrors[] = 'missing quantity';
                }
                if (empty($detail->harga_satuan) || $detail->harga_satuan <= 0) {
                    $detailErrors[] = 'missing unit price';
                }
                if (empty($detail->unit)) {
                    $detailErrors[] = 'missing unit';
                }
                
                if (!empty($detailErrors)) {
                    $errors[] = "Detail '{$detail->nama_detail}' has " . implode(', ', $detailErrors);
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Get all details from a section recursively
     */
    private function getAllDetailsFromSection($section)
    {
        $allDetails = collect();
        
        foreach ($section->details as $detail) {
            $allDetails->push($detail);
            $allDetails = $allDetails->merge($this->getChildrenRecursive($detail));
        }
        
        return $allDetails;
    }
    
    /**
     * Get children recursively
     */
    private function getChildrenRecursive($detail)
    {
        $children = collect();
        
        foreach ($detail->children as $child) {
            $children->push($child);
            $children = $children->merge($this->getChildrenRecursive($child));
        }
        
        return $children;
    }
}
