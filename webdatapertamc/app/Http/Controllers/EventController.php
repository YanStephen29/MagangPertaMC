<?php

namespace App\Http\Controllers;

use App\Models\event;
use App\Models\tools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $events = event::when($search, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('no_I/O', 'like', '%' . $search . '%');
        })->withCount('tools')->get();

        return view('events.index', compact('events', 'search'));
    }

    public function show($id)
    {
        $event = event::with('tools.bidang', 'tools.document')->findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_I_O' => 'required|string|max:20|unique:events,no_I/O',
            'title' => 'required|string|max:50',
        ]);

        event::create([
            'no_I/O' => $request->no_I_O,
            'title' => $request->title,
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil dibuat!');
    }

    public function edit($id)
    {
        $event = event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = event::findOrFail($id);
        
        $request->validate([
            'no_I_O' => [
                'required',
                'string', 
                'max:20',
                'unique:events,no_I/O,' . $event->{'no_I/O'} . ',no_I/O'
            ],
            'title' => 'required|string|max:50',
        ], [
            'no_I_O.required' => 'No I/O wajib diisi.',
            'no_I_O.string' => 'No I/O harus berupa text.',
            'no_I_O.max' => 'No I/O maksimal 20 karakter.',
            'no_I_O.unique' => 'No I/O sudah terdaftar.',
            'title.required' => 'Title wajib diisi.',
            'title.string' => 'Title harus berupa text.',
            'title.max' => 'Title maksimal 50 karakter.',
        ]);

        $oldNoIO = $event->{'no_I/O'};
        $newNoIO = $request->no_I_O;
        
        // If no_I/O is being changed, we need to handle the primary key update carefully
        if ($oldNoIO !== $newNoIO) {
            try {
                \DB::transaction(function () use ($event, $newNoIO, $oldNoIO, $request) {
                    // Disable foreign key checks temporarily
                    \DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    
                    // Update the event record with new No I/O
                    \DB::table('events')
                        ->where('no_I/O', $oldNoIO)
                        ->update([
                            'no_I/O' => $newNoIO,
                            'title' => $request->title,
                            'updated_at' => now(),
                        ]);
                    
                    // Update all related tools to use the new event No I/O
                    \DB::table('tools')
                        ->where('event_no_I/O', $oldNoIO)
                        ->update(['event_no_I/O' => $newNoIO]);
                    
                    // Re-enable foreign key checks
                    \DB::statement('SET FOREIGN_KEY_CHECKS=1');
                });
            } catch (\Exception $e) {
                // Make sure to re-enable foreign key checks even if there's an error
                \DB::statement('SET FOREIGN_KEY_CHECKS=1');
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal mengupdate No I/O: ' . $e->getMessage());
            }
        } else {
            // If No I/O is not changing, just update normally
            $event->update([
                'title' => $request->title,
            ]);
        }

        return redirect()->route('events.show', $oldNoIO !== $newNoIO ? $newNoIO : $event->{'no_I/O'})
            ->with('success', 'Event berhasil diupdate! No I/O: ' . ($oldNoIO !== $newNoIO ? $newNoIO : $event->{'no_I/O'}));
    }

    public function destroy($id)
    {
        $event = event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus!');
    }

    public function exportSingle($id)
    {
        $event = event::with('tools.bidang', 'tools.document')->findOrFail($id);
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'PT PERTAMINA MAINTENANCE & CONSTRUCTION');
        $sheet->setCellValue('A2', 'ANGGARAN PELAKSANAAN PROYEK');
        $sheet->setCellValue('A3', 'Event: ' . $event->title);
        $sheet->setCellValue('A4', 'No I/O: ' . $event->{'no_I/O'});
        
        // Table headers
        $sheet->setCellValue('A6', 'ID Tools');
        $sheet->setCellValue('B6', 'Description');
        $sheet->setCellValue('C6', 'Quantity');
        $sheet->setCellValue('D6', 'Unit');
        $sheet->setCellValue('E6', 'Delivery Date');
        $sheet->setCellValue('F6', 'Remarks');
        $sheet->setCellValue('G6', 'Bidang');
        $sheet->setCellValue('H6', 'Document No');
        
        // Data
        $row = 7;
        foreach ($event->tools as $tool) {
            $sheet->setCellValue('A' . $row, $tool->idTools);
            $sheet->setCellValue('B' . $row, $tool->description);
            $sheet->setCellValue('C' . $row, $tool->quantity);
            $sheet->setCellValue('D' . $row, $tool->unit);
            $sheet->setCellValue('E' . $row, $tool->deliveryDate);
            $sheet->setCellValue('F' . $row, $tool->remarks);
            $sheet->setCellValue('G' . $row, $tool->bidang->nama_Bidang ?? '');
            $sheet->setCellValue('H' . $row, $tool->document->no_request ?? '');
            $row++;
        }
        
        $writer = new Xlsx($spreadsheet);
        $filename = 'Event_' . str_replace('/', '_', $event->{'no_I/O'}) . '_' . date('Y-m-d') . '.xlsx';
        
        return new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportAll()
    {
        $events = event::with('tools.bidang', 'tools.document')->get();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'PT PERTAMINA MAINTENANCE & CONSTRUCTION');
        $sheet->setCellValue('A2', 'ANGGARAN PELAKSANAAN PROYEK - ALL EVENTS');
        
        // Table headers
        $sheet->setCellValue('A4', 'Event No I/O');
        $sheet->setCellValue('B4', 'Event Title');
        $sheet->setCellValue('C4', 'ID Tools');
        $sheet->setCellValue('D4', 'Description');
        $sheet->setCellValue('E4', 'Quantity');
        $sheet->setCellValue('F4', 'Unit');
        $sheet->setCellValue('G4', 'Delivery Date');
        $sheet->setCellValue('H4', 'Remarks');
        $sheet->setCellValue('I4', 'Bidang');
        $sheet->setCellValue('J4', 'Document No');
        
        // Data
        $row = 5;
        foreach ($events as $event) {
            foreach ($event->tools as $tool) {
                $sheet->setCellValue('A' . $row, $event->{'no_I/O'});
                $sheet->setCellValue('B' . $row, $event->title);
                $sheet->setCellValue('C' . $row, $tool->idTools);
                $sheet->setCellValue('D' . $row, $tool->description);
                $sheet->setCellValue('E' . $row, $tool->quantity);
                $sheet->setCellValue('F' . $row, $tool->unit);
                $sheet->setCellValue('G' . $row, $tool->deliveryDate);
                $sheet->setCellValue('H' . $row, $tool->remarks);
                $sheet->setCellValue('I' . $row, $tool->bidang->nama_Bidang ?? '');
                $sheet->setCellValue('J' . $row, $tool->document->no_request ?? '');
                $row++;
            }
        }
        
        $writer = new Xlsx($spreadsheet);
        $filename = 'All_Events_' . date('Y-m-d') . '.xlsx';
        
        return new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
