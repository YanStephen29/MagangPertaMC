<?php

namespace App\Http\Controllers;

use App\Models\RequestItem;
use App\Models\Event;
use App\Models\Vendor;
use App\Models\RequestType;
use App\Models\WorkflowStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $bidang_req_type = $request->get('bidang_req_type');
        $request_type = $request->get('request_type');

        $requestItems = RequestItem::with('event')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', '%' . $search . '%')
                            ->orWhere('event_no_io', 'like', '%' . $search . '%')
                            ->orWhere('document_number', 'like', '%' . $search . '%')
                            ->orWhere('vendor_name', 'like', '%' . $search . '%');
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($bidang_req_type, function ($query, $bidang_req_type) {
                return $query->where('bidang_req_type', $bidang_req_type);
            })
            ->when($request_type, function ($query, $request_type) {
                return $query->where('request_type', $request_type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('request-items.index', compact('requestItems', 'search', 'status', 'bidang_req_type', 'request_type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $eventNoIo = $request->get('event_no_io');
        $events = Event::all();
        $vendors = Vendor::active()->get();
        $requestTypes = RequestType::active()->get();
        
        return view('request-items.create', compact('events', 'vendors', 'requestTypes', 'eventNoIo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'event_no_io' => 'required|string|exists:events,no_I/O',
            'bidang_req_type' => 'required|in:material_req,service_req,facility_req,aset_req',
            'document_number' => 'nullable|string|max:50',
            'document_date' => 'nullable|date',
            'project_to_epc_date' => 'nullable|date',
            'pmo_to_epc_date' => 'nullable|date',
            'epc_to_procurement_date' => 'nullable|date',
            'request_type' => 'nullable|in:SPS,PO,PCM',
            'request_type_doc_number' => 'nullable|string|max:50',
            'request_type_doc_date' => 'nullable|date',
            'vendor_name' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'payment_po_pcm' => 'nullable|integer',
            'payment_status' => 'required|in:pending,paid,partial,cancelled',
            'remarks' => 'nullable|string',
            'status' => 'required|in:draft,in_progress,completed,cancelled',
        ]);

        // Calculate total price if price and quantity are provided
        if ($validated['price'] && $validated['quantity']) {
            $validated['total_price'] = $validated['price'] * $validated['quantity'];
        }

        RequestItem::create($validated);

        return redirect()->route('request-items.index')
            ->with('success', 'Request item berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestItem $requestItem)
    {
        $requestItem->load('event');
        return view('request-items.show', compact('requestItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestItem $requestItem)
    {
        $events = Event::all();
        $vendors = Vendor::active()->get();
        $requestTypes = RequestType::active()->get();
        
        return view('request-items.edit', compact('requestItem', 'events', 'vendors', 'requestTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestItem $requestItem)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'event_no_io' => 'required|string|exists:events,no_I/O',
            'bidang_req_type' => 'required|in:material_req,service_req,facility_req,aset_req',
            'document_number' => 'nullable|string|max:50',
            'document_date' => 'nullable|date',
            'project_to_epc_date' => 'nullable|date',
            'pmo_to_epc_date' => 'nullable|date',
            'epc_to_procurement_date' => 'nullable|date',
            'request_type' => 'nullable|in:SPS,PO,PCM',
            'request_type_doc_number' => 'nullable|string|max:50',
            'request_type_doc_date' => 'nullable|date',
            'vendor_name' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'payment_po_pcm' => 'nullable|integer',
            'payment_status' => 'required|in:pending,paid,partial,cancelled',
            'remarks' => 'nullable|string',
            'status' => 'required|in:draft,in_progress,completed,cancelled',
        ]);

        // Calculate total price if price and quantity are provided
        if ($validated['price'] && $validated['quantity']) {
            $validated['total_price'] = $validated['price'] * $validated['quantity'];
        }

        $requestItem->update($validated);

        return redirect()->route('request-items.show', $requestItem)
            ->with('success', 'Request item berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestItem $requestItem)
    {
        $requestItem->delete();

        return redirect()->route('request-items.index')
            ->with('success', 'Request item berhasil dihapus!');
    }

    /**
     * Update workflow stage
     */
    public function updateWorkflowStage(Request $request, RequestItem $requestItem)
    {
        $validated = $request->validate([
            'stage' => 'required|in:project_to_epc,pmo_to_epc,epc_to_procurement',
            'date' => 'required|date',
        ]);

        $stageField = $validated['stage'] . '_date';
        $requestItem->update([$stageField => $validated['date']]);

        return redirect()->back()
            ->with('success', 'Workflow stage berhasil diupdate!');
    }

    /**
     * Get items by event
     */
    public function byEvent($eventNoIo)
    {
        $requestItems = RequestItem::where('event_no_io', $eventNoIo)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($requestItems);
    }
}
