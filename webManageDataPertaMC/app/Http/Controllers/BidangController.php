<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Project;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BidangsExport;

class BidangController extends Controller
{
    // BidangController.php

public function index(Request $request)
{
    $search = $request->input('search');
    $bidangsQuery = Bidang::query();
    
    if ($search) {
        $bidangsQuery->where(function ($query) use ($search) {
            $query->where('kode_GL', 'like', "%{$search}%")
                  ->orWhere('nama_Bidang', 'like', "%{$search}%");
        });
    }

    $bidangsQuery->addSelect(['total_expenditure'=>Tool::selectRaw('SUM(COALESCE(request_details.requested_quantity, 0)* COALESCE(details.harga_satuan, 0))')
        ->join('requests', 'tools.request_id', '=', 'requests.id_req')
        ->join('request_details', 'requests.id_req', '=', 'request_details.request_id')
        ->join('details', 'request_details.detail_id', '=', 'details.no')
        ->whereColumn('tools.kode_GL', 'bidangs.kode_GL')
    ]);
    
    $bidangs = $bidangsQuery->orderBy('kode_GL')->get();

    $projects = Project::orderBy('title_project')->select('no_IO', 'title_project')->get();
    
    return view('bidangs.index', compact('bidangs','search','projects'));
}


    public function create()
    {
        return view('bidangs.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'kode_GL' => 'required|string|max:20|unique:bidangs,kode_GL',
            'nama_Bidang' => 'required|string|max:55',
        ]);

        Bidang::create($request->all());

        return redirect()->route('bidangs.index')->with('success', 'GL Code added successfully!');
    }


    public function edit(Bidang $bidang)
    {
        return view('bidangs.edit', compact('bidang'));
    }


    public function update(Request $request, Bidang $bidang)
    {
        $request->validate([
            'kode_GL' => 'required|string|max:20|unique:bidangs,kode_GL,' . $bidang->kode_GL . ',kode_GL',
            'nama_Bidang' => 'required|string|max:55',
        ]);

        $bidang->update($request->all());

        return redirect()->route('bidangs.index')->with('success', 'GL Code updated successfully!');
    }


    public function destroy(Bidang $bidang)
    {
        $bidang->delete();
        return redirect()->route('bidangs.index')->with('success', 'GL Code deleted successfully!');
    }


    public function toolsForGL(Request $request, $kodeGL)
     {
        $bidang = Bidang::where('kode_GL', $kodeGL)->first();
        $toolsQuery = Tool::with(['project', 'request.requestDetails.detail'])
                          ->where('kode_GL', $kodeGL);
        $search = $request->input('search');
        if ($search) {
            $toolsQuery->where(function ($query) use ($search) {
                $query->where('Description', 'like', "%{$search}%")
                      ->orWhereHas('project', function ($projectQuery) use ($search) {
                          $projectQuery->where('title_project', 'like', "%{$search}%")
                                       ->orWhere('no_IO', 'like', "%{$search}%");
                      });
            });
        }
        $tools = $toolsQuery->get();
        return view('bidangs.tools', compact('tools', 'kodeGL', 'bidang', 'search'));
    }


    public function showToolDetails(Tool $tool)
    {
        $tool->load('request.requestDetails.detail.section');
        return view('bidangs.tool-details', compact('tool'));
    }


    public function exportExcel(Request $request)
    {   
        $request->validate([
            'selected_projects' => 'required|array|min:1',
            'selected_projects.*' => 'string|exists:projects,no_IO',
        ],[
            'selected_projects.required' => 'Please select at least one Project.',
            'selected_projects.min' => 'Please select at least one Project to export.',
        ]);

        $selectedProjectIds = $request->input('selected_projects');

        $fileName = 'gl_codes_expenditure_' . now()->format('YmdHis') . '.xlsx';

        try{
            return Excel::download(new BidangsExport($selectedProjectIds), $fileName);
        }catch(\Exception $e){
            \Log::error('Excel Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export data to Excel. Please try again.');
        }
    }
}
