<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Detail;
use App\Models\Section;
use Illuminate\Http\Request;

class ToolDetailController extends Controller
{
    /**
     * Contoh menggunakan relasi Tool dengan Details
     * Ketika tool request berhasil ditambahkan, details akan disave ke database
     */
    public function storeToolWithDetails(Request $request)
    {
        // Validasi data tool
        $validatedTool = $request->validate([
            'Description' => 'required|string|max:45',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:20',
            'delivery_date' => 'required|date',
            'remarks' => 'nullable|string|max:45',
            'no_IO' => 'required|string|exists:projects,no_IO',
            'kode_GL' => 'required|string|exists:bidangs,kode_GL',
            'details' => 'required|array|min:1', // Array details yang akan ditambahkan
            'details.*.nama_detail' => 'required|string|max:45',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.unit' => 'required|string|max:5',
            'details.*.harga_satuan' => 'required|integer|min:0',
            'details.*.section_id' => 'required|integer|exists:sections,id',
            'details.*.parent_no' => 'nullable|integer|exists:details,no'
        ]);

        try {
            // Mulai database transaction
            \DB::beginTransaction();

            // 1. Simpan Tool Request terlebih dahulu
            $tool = Tool::create([
                'Description' => $validatedTool['Description'],
                'quantity' => $validatedTool['quantity'],
                'unit' => $validatedTool['unit'],
                'delivery_date' => $validatedTool['delivery_date'],
                'remarks' => $validatedTool['remarks'],
                'no_IO' => $validatedTool['no_IO'],
                'kode_GL' => $validatedTool['kode_GL']
            ]);

            // 2. Buat Request untuk tool ini
            $toolRequest = \App\Models\Request::create([
                'type_surat' => 'SPS',
                'jenis_req' => 'PO',
                'no_surat' => 'REQ-' . date('YmdHis') . '-' . $tool->idTools,
                'date_req' => now(),
                'status_req' => 'approved',
                'tool_id' => $tool->idTools
            ]);

            // 3. Simpan Details yang terkait dengan Tool Request melalui RequestDetails
            foreach ($validatedTool['details'] as $detailData) {
                $detail = Detail::create([
                    'nama_detail' => $detailData['nama_detail'],
                    'quantity' => $detailData['quantity'],
                    'unit' => $detailData['unit'],
                    'harga_satuan' => $detailData['harga_satuan'],
                    'harga_total' => $detailData['quantity'] * $detailData['harga_satuan'],
                    'section_id' => $detailData['section_id'],
                    'parent_no' => $detailData['parent_no'] ?? null
                ]);

                // Create relationship melalui RequestDetails (integer quantity)
                RequestDetail::create([
                    'request_id' => $toolRequest->id_req,
                    'detail_id' => $detail->no,
                    'requested_quantity' => intval($detailData['quantity']), // Pastikan integer
                    'unit_price' => $detailData['harga_satuan'],
                    'total_price' => intval($detailData['quantity']) * $detailData['harga_satuan'],
                    'status' => 'approved',
                    'notes' => 'New detail created for tool request'
                ]);
            }

            // Commit transaction
            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tool request dengan details berhasil disimpan',
                'data' => [
                    'tool' => $tool->load('details'), // Load dengan details
                    'details_count' => $tool->details->count()
                ]
            ]);

        } catch (\Exception $e) {
            // Rollback jika ada error
            \DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan tool request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Contoh mendapatkan tool dengan semua details
     */
    public function getToolWithDetails($toolId)
    {
        $tool = Tool::with(['details.section', 'details.parent', 'details.children'])
                    ->findOrFail($toolId);

        return response()->json([
            'tool' => $tool,
            'details_summary' => [
                'total_details' => $tool->details->count(),
                'total_value' => $tool->details->sum('harga_total'),
                'sections_involved' => $tool->details->pluck('section.nama_section')->unique()
            ]
        ]);
    }

    /**
     * Contoh menambahkan detail ke tool yang sudah ada
     */
    public function addDetailToTool(Request $request, $toolId)
    {
        $validated = $request->validate([
            'nama_detail' => 'required|string|max:45',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:5',
            'harga_satuan' => 'required|integer|min:0',
            'section_id' => 'required|integer|exists:sections,id',
            'parent_no' => 'nullable|integer|exists:details,no'
        ]);

        $tool = Tool::findOrFail($toolId);

        // Pastikan tool sudah memiliki request, jika belum buat request baru
        if (!$tool->request) {
            $toolRequest = \App\Models\Request::create([
                'type_surat' => 'SPS',
                'jenis_req' => 'PO',
                'no_surat' => 'REQ-' . date('YmdHis') . '-' . $tool->idTools,
                'date_req' => now(),
                'status_req' => 'approved',
                'tool_id' => $tool->idTools
            ]);
        } else {
            $toolRequest = $tool->request;
        }

        $detail = Detail::create([
            'nama_detail' => $validated['nama_detail'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'harga_satuan' => $validated['harga_satuan'],
            'harga_total' => $validated['quantity'] * $validated['harga_satuan'],
            'section_id' => $validated['section_id'],
            'parent_no' => $validated['parent_no'] ?? null
        ]);

        // Create relationship melalui RequestDetails (integer quantity)
        RequestDetail::create([
            'request_id' => $toolRequest->id_req,
            'detail_id' => $detail->no,
            'requested_quantity' => intval($validated['quantity']), // Pastikan integer
            'unit_price' => $validated['harga_satuan'],
            'total_price' => intval($validated['quantity']) * $validated['harga_satuan'],
            'status' => 'approved',
            'notes' => 'Detail added to existing tool'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail berhasil ditambahkan ke tool request',
            'data' => $detail->load('section', 'tool')
        ]);
    }

    /**
     * Contoh mendapatkan semua details berdasarkan tool
     */
    public function getDetailsByTool($toolId)
    {
        $tool = Tool::findOrFail($toolId);
        
        // Gunakan method yang lebih jelas untuk mendapatkan details dengan request info
        $details = $tool->getDetailsWithRequestInfo();

        return response()->json([
            'tool' => $tool,
            'details' => $details,
            'statistics' => [
                'total_items' => $details->count(),
                'total_value' => $details->sum('harga_total'),
                'root_items' => $details->whereNull('parent_no')->count(),
                'child_items' => $details->whereNotNull('parent_no')->count()
            ]
        ]);
    }
}