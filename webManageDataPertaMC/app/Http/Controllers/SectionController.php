<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Boq;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    /**
     * Show the form for creating a new section for a BOQ.
     */
    public function create(Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Verify BOQ belongs to this project
        if ($boq->project_no_io !== $project->no_IO) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'BOQ not found for this project.');
        }
        
        // Get next section ID
        $nextSectionId = $boq->sections()->count() + 1;
        
        return view('sections.create', compact('project', 'boq', 'nextSectionId'));
    }

    /**
     * Store a newly created section in storage.
     */
    public function store(Request $request, Project $project, Boq $boq)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Verify BOQ belongs to this project
        if ($boq->project_no_io !== $project->no_IO) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'BOQ not found for this project.');
        }
        
        $validated = $request->validate([
            'nama' => 'required|string|max:45'
        ]);
        
        // Add BOQ reference and initial total_harga
        $validated['boq_nomorBoq'] = $boq->nomorBoq;
        $validated['total_harga'] = 0;
        
        $section = Section::create($validated);
        
        return redirect()->route('projects.boq.index', $project)->with('success', 'Section created successfully.');
    }

    /**
     * Show the form for editing the specified section.
     */
    public function edit(Project $project, Boq $boq, Section $section)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Verify section belongs to this BOQ
        if ($section->boq_nomorBoq !== $boq->nomorBoq) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'Section not found for this BOQ.');
        }
        
        return view('sections.edit', compact('project', 'boq', 'section'));
    }

    /**
     * Update the specified section in storage.
     */
    public function update(Request $request, Project $project, Boq $boq, Section $section)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Verify section belongs to this BOQ
        if ($section->boq_nomorBoq !== $boq->nomorBoq) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'Section not found for this BOQ.');
        }
        
        $validated = $request->validate([
            'nama' => 'required|string|max:45'
        ]);
        
        $section->update($validated);
        
        return redirect()->route('projects.boq.index', $project)->with('success', 'Section updated successfully.');
    }

    /**
     * Remove the specified section from storage.
     */
    public function destroy(Project $project, Boq $boq, Section $section)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Verify section belongs to this BOQ
        if ($section->boq_nomorBoq !== $boq->nomorBoq) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'Section not found for this BOQ.');
        }
        
        $section->delete();
        
        return redirect()->route('projects.boq.index', $project)->with('success', 'Section deleted successfully.');
    }
}
