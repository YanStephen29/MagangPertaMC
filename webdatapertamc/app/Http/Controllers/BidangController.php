<?php

namespace App\Http\Controllers;

use App\Models\bidang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BidangController extends Controller
{
    /**
     * Display a listing of the bidang.
     */
    public function index()
    {
        $bidangs = bidang::withCount('tools')->orderBy('kodeGl')->get();
        return view('bidangs.index', compact('bidangs'));
    }

    /**
     * Show the form for creating a new bidang.
     */
    public function create()
    {
        return view('bidangs.create');
    }

    /**
     * Store a newly created bidang in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kodeGl' => 'required|integer|unique:bidangs,kodeGl',
            'nama_Bidang' => 'required|string|max:255|unique:bidangs,nama_Bidang',
        ], [
            'kodeGl.required' => 'Kode GL wajib diisi.',
            'kodeGl.integer' => 'Kode GL harus berupa angka.',
            'kodeGl.unique' => 'Kode GL sudah terdaftar.',
            'nama_Bidang.required' => 'Nama Bidang wajib diisi.',
            'nama_Bidang.max' => 'Nama Bidang maksimal 255 karakter.',
            'nama_Bidang.unique' => 'Nama Bidang sudah terdaftar.',
        ]);

        bidang::create([
            'kodeGl' => $request->kodeGl,
            'nama_Bidang' => $request->nama_Bidang,
        ]);

        return redirect()->route('bidangs.index')
            ->with('success', 'Bidang berhasil ditambahkan!');
    }

    /**
     * Display the specified bidang.
     */
    public function show($kodeGl)
    {
        $bidang = bidang::where('kodeGl', $kodeGl)->firstOrFail();
        
        // Load tools relationship
        $bidang->load(['tools' => function($query) {
            $query->with('event');
        }]);
        
        return view('bidangs.show', compact('bidang'));
    }

    /**
     * Show the form for editing the specified bidang.
     */
    public function edit($kodeGl)
    {
        $bidang = bidang::where('kodeGl', $kodeGl)->firstOrFail();
        return view('bidangs.edit', compact('bidang'));
    }

    /**
     * Update the specified bidang in storage.
     */
    public function update(Request $request, $kodeGl)
    {
        $bidang = bidang::where('kodeGl', $kodeGl)->firstOrFail();
        
        $request->validate([
            'kodeGl' => [
                'required',
                'integer',
                Rule::unique('bidangs', 'kodeGl')->ignore($bidang->kodeGl, 'kodeGl')
            ],
            'nama_Bidang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('bidangs', 'nama_Bidang')->ignore($bidang->kodeGl, 'kodeGl')
            ],
        ], [
            'kodeGl.required' => 'Kode GL wajib diisi.',
            'kodeGl.integer' => 'Kode GL harus berupa angka.',
            'kodeGl.unique' => 'Kode GL sudah terdaftar.',
            'nama_Bidang.required' => 'Nama Bidang wajib diisi.',
            'nama_Bidang.max' => 'Nama Bidang maksimal 255 karakter.',
            'nama_Bidang.unique' => 'Nama Bidang sudah terdaftar.',
        ]);

        $bidang->update([
            'kodeGl' => $request->kodeGl,
            'nama_Bidang' => $request->nama_Bidang,
        ]);

        return redirect()->route('bidangs.index')
            ->with('success', 'Bidang berhasil diperbarui!');
    }

    /**
     * Remove the specified bidang from storage.
     */
    public function destroy($kodeGl)
    {
        $bidang = bidang::where('kodeGl', $kodeGl)->firstOrFail();
        
        // Check if bidang is being used by any tools
        if ($bidang->tools()->count() > 0) {
            return redirect()->route('bidangs.index')
                ->with('error', 'Bidang tidak dapat dihapus karena masih digunakan oleh ' . $bidang->tools()->count() . ' tools.');
        }

        $bidang->delete();

        return redirect()->route('bidangs.index')
            ->with('success', 'Bidang berhasil dihapus!');
    }
}
