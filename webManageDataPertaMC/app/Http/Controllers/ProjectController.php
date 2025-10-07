<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Admin;
use App\Models\Boq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            // Always render the view, but show empty data if no read access
            if (!$admin->canRead('project')) {
                $projects = collect(); // Empty collection
                return view('projects.index', compact('projects', 'admin'));
            }
            
            $query = Project::with(['admin', 'assignedTo', 'tools', 'boq.sections.details']);

            // Apply access control - PM hanya bisa lihat project yang di-assign
            $query->accessibleBy($admin);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('no_IO', 'LIKE', "%{$search}%")
                      ->orWhere('title_project', 'LIKE', "%{$search}%");
                });
            }

            // Sorting functionality
            $sort = $request->get('sort', 'created_at_desc');
            switch ($sort) {
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'no_IO_asc':
                    $query->orderBy('no_IO', 'asc');
                    break;
                case 'no_IO_desc':
                    $query->orderBy('no_IO', 'desc');
                    break;
                case 'title_asc':
                    $query->orderBy('title_project', 'asc');
                    break;
                case 'title_desc':
                    $query->orderBy('title_project', 'desc');
                    break;
                default: // created_at_desc
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $projects = $query->get();
            
            return view('projects.index', compact('projects', 'admin'));
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error in ProjectController@index: ' . $e->getMessage());
            
            // Return empty collection as fallback
            $projects = collect();
            $admin = Auth::guard('admin')->user();
            return view('projects.index', compact('projects', 'admin'))->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    public function create()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin->canCreate('project')) {
            return redirect()->route('projects.index')
                           ->with('error', 'Anda tidak memiliki akses untuk membuat project. Hubungi admin untuk mendapatkan akses.');
        }
        
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin->canCreate('project')) {
            return redirect()->route('projects.index')
                           ->with('error', 'Anda tidak memiliki akses untuk membuat project. Hubungi admin untuk mendapatkan akses.');
        }
        
        $request->validate([
            'no_IO' => 'required|string|max:10|unique:projects,no_IO',
            'title_project' => 'required|string|max:100',
        ]);
        
        Project::create([
            'no_IO' => $request->no_IO,
            'title_project' => $request->title_project,
            'admin_id' => $admin->admin_id,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project berhasil ditambahkan!');
    }

    public function show(Project $project)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if admin can access this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->admin_id) {
            abort(403, 'You do not have access to this project.');
        }
        
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin->canUpdate('project')) {
            return redirect()->route('projects.index')
                           ->with('error', 'Anda tidak memiliki akses untuk mengedit project. Hubungi admin untuk mendapatkan akses.');
        }
        
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'no_IO' => 'required|string|max:10|unique:projects,no_IO,' . $project->no_IO . ',no_IO',
            'title_project' => 'required|string|max:100',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', 'Project berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project berhasil dihapus!');
    }

    /**
     * Show form to assign PM to project
     */
    public function showAssignForm(Project $project)
    {
        try {
            $projectManagers = Admin::where('role', 'Project Manager')->get();
            return view('projects.assign', compact('project', 'projectManagers'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading assign form: ' . $e->getMessage());
        }
    }

    /**
     * Assign PM to project
     */
    public function assignPM(Request $request, Project $project)
    {
        try {
            $request->validate([
                'assigned_to' => 'nullable|exists:admins,admin_id',
            ]);

            $project->update([
                'assigned_to' => $request->assigned_to
            ]);

            $message = $request->assigned_to 
                ? 'Project berhasil di-assign ke PM!' 
                : 'Assignment project berhasil dihapus!';
                
            return redirect()->route('projects.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error during assignment: ' . $e->getMessage());
        }
    }
}
