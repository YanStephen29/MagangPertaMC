<?php

namespace App\Http\Controllers;

use App\Models\tools;
use App\Models\event;
use App\Models\bidang;
use App\Models\document;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function create($eventId)
    {
        $event = event::findOrFail($eventId);
        $bidangs = bidang::all();
        $documents = document::all();
        
        return view('tools.create', compact('event', 'bidangs', 'documents'));
    }

    public function store(Request $request, $eventId)
    {
        $event = event::findOrFail($eventId);
        
        $request->validate([
            'description' => 'required|string|max:50',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'delivery_date' => 'required|date',
            'remarks' => 'required|string|max:50',
            'Document_no_request' => 'required|exists:documents,no_request',
            'Bidang_kodeGl' => 'required|exists:bidangs,kodeGl',
        ]);

        tools::create([
            'description' => $request->description,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'deliveryDate' => $request->delivery_date,
            'remarks' => $request->remarks,
            'event_no_I/O' => $event->{'no_I/O'},
            'Document_no_request' => $request->Document_no_request,
            'Bidang_kodeGl' => $request->Bidang_kodeGl,
        ]);

        return redirect()->route('events.show', $eventId)->with('success', 'Tool berhasil ditambahkan!');
    }

    public function edit($eventId, $toolId)
    {
        $event = event::findOrFail($eventId);
        $tool = tools::findOrFail($toolId);
        $bidangs = bidang::all();
        $documents = document::all();
        
        return view('tools.edit', compact('event', 'tool', 'bidangs', 'documents'));
    }

    public function update(Request $request, $eventId, $toolId)
    {
        $event = event::findOrFail($eventId);
        $tool = tools::findOrFail($toolId);
        
        $request->validate([
            'description' => 'required|string|max:50',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'delivery_date' => 'required|date',
            'remarks' => 'required|string|max:50',
            'Document_no_request' => 'required|exists:documents,no_request',
            'Bidang_kodeGl' => 'required|exists:bidangs,kodeGl',
        ]);

        $tool->update([
            'description' => $request->description,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'deliveryDate' => $request->delivery_date,
            'remarks' => $request->remarks,
            'Document_no_request' => $request->Document_no_request,
            'Bidang_kodeGl' => $request->Bidang_kodeGl,
        ]);

        return redirect()->route('events.show', $eventId)->with('success', 'Tool berhasil diupdate!');
    }

    public function destroy($eventId, $toolId)
    {
        $event = event::findOrFail($eventId);
        $tool = tools::findOrFail($toolId);
        $tool->delete();

        return redirect()->route('events.show', $eventId)->with('success', 'Tool berhasil dihapus!');
    }
}
