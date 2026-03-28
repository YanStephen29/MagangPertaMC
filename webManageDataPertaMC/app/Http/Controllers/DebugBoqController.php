<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\RequestDetail;
use Illuminate\Http\Request;

class DebugBoqController extends Controller
{
    /**
     * Debug BOQ quantity usage
     */
    public function debugBoqUsage(Request $request)
    {
        $detailId = $request->get('detail_id');
        
        if (!$detailId) {
            return response()->json(['error' => 'detail_id required']);
        }
        
        $detail = Detail::find($detailId);
        
        if (!$detail) {
            return response()->json(['error' => 'Detail not found']);
        }
        
        // Get all request details for this BOQ item
        $requestDetails = RequestDetail::where('detail_id', $detailId)
            ->with(['request.tool'])
            ->get();
        
        $usage = $requestDetails->map(function($rd) {
            return [
                'request_id' => $rd->request_id,
                'tool_description' => $rd->request->tools->first()->Description ?? 'No Tool',
                'requested_quantity' => $rd->requested_quantity,
                'status' => $rd->request->status_req ?? 'unknown',
                'created_at' => $rd->created_at
            ];
        });
        
        // Calculate used quantity by status
        $usedByStatus = [
            'approved' => $requestDetails->where('request.status_req', 'approved')->sum('requested_quantity'),
            'pending' => $requestDetails->where('request.status_req', 'pending')->sum('requested_quantity'),
            'hold' => $requestDetails->where('request.status_req', 'hold')->sum('requested_quantity'),
            'rejected' => $requestDetails->where('request.status_req', 'rejected')->sum('requested_quantity'),
        ];
        
        $totalUsed = $usedByStatus['approved'] + $usedByStatus['pending'] + $usedByStatus['hold'];
        $availableQuantity = max(0, $detail->quantity - $totalUsed);
        
        return response()->json([
            'detail' => [
                'id' => $detail->no,
                'name' => $detail->nama_detail,
                'total_quantity' => $detail->quantity,
                'unit' => $detail->unit
            ],
            'usage_by_status' => $usedByStatus,
            'total_used' => $totalUsed,
            'available_quantity' => $availableQuantity,
            'usage_details' => $usage
        ]);
    }
    
    /**
     * Debug semua BOQ items dalam project
     */
    public function debugProjectBoq($projectId)
    {
        $project = \App\Models\Project::where('no_IO', $projectId)->first();
        
        if (!$project || !$project->boq) {
            return response()->json(['error' => 'Project or BOQ not found']);
        }
        
        $details = Detail::whereHas('section.boq', function($q) use ($projectId) {
            $q->where('project_no_io', $projectId);
        })->with(['requestDetails.request.tool'])->get();
        
        $summary = $details->map(function($detail) {
            $requestDetails = $detail->requestDetails;
            
            $usedByStatus = [
                'approved' => $requestDetails->where('request.status_req', 'approved')->sum('requested_quantity'),
                'pending' => $requestDetails->where('request.status_req', 'pending')->sum('requested_quantity'),
                'hold' => $requestDetails->where('request.status_req', 'hold')->sum('requested_quantity'),
                'rejected' => $requestDetails->where('request.status_req', 'rejected')->sum('requested_quantity'),
            ];
            
            $totalUsed = $usedByStatus['approved'] + $usedByStatus['pending'] + $usedByStatus['hold'];
            
            return [
                'detail_id' => $detail->no,
                'detail_name' => $detail->nama_detail,
                'total_quantity' => $detail->quantity,
                'used_quantity' => $totalUsed,
                'available_quantity' => max(0, $detail->quantity - $totalUsed),
                'usage_by_status' => $usedByStatus,
                'request_count' => $requestDetails->count()
            ];
        });
        
        return response()->json([
            'project' => $project->title_project,
            'project_id' => $projectId,
            'total_boq_items' => $details->count(),
            'items' => $summary
        ]);
    }
}