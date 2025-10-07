<?php

namespace App\Http\Controllers;

use App\Models\Boq;
use App\Models\Project;
use App\Exports\BoqExport;
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
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Get BOQ for this project with sections and details
        $boq = $project->boq()->with(['sections.details.children'])->first();
        
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
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Check if project already has BOQ
        if ($project->boq) {
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
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
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
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
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
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
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
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
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
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
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
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
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
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
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
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }

        // Get all sections with all details (including nested children) for comprehensive view
        $sections = $boq->sections()->with(['details' => function($query) {
            $query->whereNull('parent_no')->orderBy('no', 'asc');
        }, 'details.children' => function($query) {
            $query->orderBy('no', 'asc');
        }, 'details.children.children' => function($query) {
            $query->orderBy('no', 'asc');
        }, 'details.children.children.children' => function($query) {
            $query->orderBy('no', 'asc');
        }])->orderBy('id', 'asc')->get();

        return view('boqs.manage-details', compact('project', 'boq', 'sections'));
    }

    /**
     * Download BOQ details as Excel file
     */
    public function downloadExcel(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            abort(403, 'Access denied to this project.');
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
     * Download BOQ details as PDF file (HTML version for printing)
     */
    public function downloadPdf(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            abort(403, 'Access denied to this project.');
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
}
