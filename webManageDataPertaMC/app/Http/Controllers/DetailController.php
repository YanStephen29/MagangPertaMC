<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Section;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    /**
     * Display a listing of the resource for a specific section.
     */
    public function index(Project $project, Section $section)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Verify section belongs to this project's BOQ
        if (!$project->boq || $section->boq_nomorBoq !== $project->boq->nomorBoq) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'Section not found for this project.');
        }
        
        // Get details for this section with hierarchical structure
        $details = $section->rootDetails()->with(['children' => function($query) {
            $query->orderBy('no', 'asc');
        }, 'children.children' => function($query) {
            $query->orderBy('no', 'asc');
        }, 'children.children.children' => function($query) {
            $query->orderBy('no', 'asc');
        }])->orderBy('no', 'asc')->get();
        
        return view('details.index', compact('details', 'section', 'project'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project, Section $section, Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Verify section belongs to this project's BOQ
        if (!$project->boq || $section->boq_nomorBoq !== $project->boq->nomorBoq) {
            return redirect()->route('projects.boq.index', $project)->with('error', 'Section not found for this project.');
        }
        
        // Get parent detail if specified
        $parentDetail = null;
        if ($request->has('parent_no')) {
            $parentDetail = Detail::where('no', $request->parent_no)
                                 ->where('section_id', $section->id)
                                 ->first();
        }
        
        // Get potential parent details for this section
        $potentialParents = $section->details()->get();
        
        return view('details.create', compact('project', 'section', 'parentDetail', 'potentialParents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project, Section $section)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Validate all detail information
        $validated = $request->validate([
            'nama_detail' => 'required|string|max:45',
            'parent_no' => 'nullable|exists:details,no',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:5',
            'harga_satuan' => 'required|integer|min:0',
            'note' => 'nullable|string|max:1000'
        ]);
        
        // Validate parent belongs to same section if specified
        if ($validated['parent_no']) {
            $parent = Detail::find($validated['parent_no']);
            if (!$parent || $parent->section_id !== $section->id) {
                return back()->withErrors(['parent_no' => 'Parent detail must belong to the same section.'])->withInput();
            }
        }
        
        // Add section reference
        $validated['section_id'] = $section->id;
        
        $detail = Detail::create($validated);
        
        return redirect()->route('sections.details.index', [$project, $section])
                       ->with('success', 'Detail created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Section $section, Detail $detail)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Verify detail belongs to this section
        if ($detail->section_id !== $section->id) {
            return redirect()->route('sections.details.index', [$project, $section])->with('error', 'Detail not found in this section.');
        }
        
        $detail->load(['parent', 'children', 'section']);
        
        return view('details.show', compact('detail', 'section', 'project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Section $section, Detail $detail)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Verify detail belongs to this section
        if ($detail->section_id !== $section->id) {
            return redirect()->route('sections.details.index', [$project, $section])->with('error', 'Detail not found in this section.');
        }
        
        // Get potential parent details (excluding self and descendants to prevent circular references)
        $potentialParents = $section->details()
                                   ->where('no', '!=', $detail->no)
                                   ->get()
                                   ->reject(function ($item) use ($detail) {
                                       return $item->isDescendantOf($detail);
                                   });
        
        return view('details.edit', compact('detail', 'section', 'project', 'potentialParents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, Section $section, Detail $detail)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Verify detail belongs to this section
        if ($detail->section_id !== $section->id) {
            return redirect()->route('sections.details.index', [$project, $section])->with('error', 'Detail not found in this section.');
        }
        
        $step = $request->input('step', 'edit');
        
        if ($step == 2 || $step == 'edit') {
            // Full update with all specifications
            $validated = $request->validate([
                'nama_detail' => 'required|string|max:45',
                'parent_no' => 'nullable|exists:details,no',
                'quantity' => 'required|numeric|min:0.01',
                'unit' => 'required|string|max:5',
                'harga_satuan' => 'required|integer|min:0',
                'note' => 'nullable|string|max:1000'
            ]);
            
            // Validate parent belongs to same section and prevent circular references
            if ($validated['parent_no']) {
                $parent = Detail::find($validated['parent_no']);
                if (!$parent || $parent->section_id !== $section->id) {
                    return back()->withErrors(['parent_no' => 'Parent detail must belong to the same section.'])->withInput();
                }
                
                // Prevent circular references
                if ($validated['parent_no'] == $detail->no || $parent->isDescendantOf($detail)) {
                    return back()->withErrors(['parent_no' => 'Cannot set parent that would create a circular reference.'])->withInput();
                }
            }
            
            $detail->update($validated);
            
            // Update section total harga
            $section->updateTotalHarga();
            
            return redirect()->route('sections.details.index', [$project, $section])->with('success', 'Detail updated successfully.');
        }
        
        return back()->withErrors(['general' => 'Invalid step provided.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Section $section, Detail $detail)
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if PM has access to this project
        if ($admin->role === 'Project Manager' && $project->assigned_to !== $admin->username) {
            return redirect()->route('projects.index')->with('error', 'Access denied to this project.');
        }
        
        // Verify detail belongs to this section
        if ($detail->section_id !== $section->id) {
            return redirect()->route('sections.details.index', [$project, $section])->with('error', 'Detail not found in this section.');
        }
        
        $detail->delete();
        return redirect()->route('sections.details.index', [$project, $section])->with('success', 'Detail deleted successfully.');
    }
}
