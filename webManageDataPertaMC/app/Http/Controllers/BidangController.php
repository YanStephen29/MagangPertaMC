<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;

class BidangController extends Controller
{
    public function index()
    {
        $bidangs = Bidang::orderBy('kode_GL')->get();
        return view('bidangs.index', compact('bidangs'));
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

        return redirect()->route('bidangs.index')->with('success', 'Bidang berhasil ditambahkan!');
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

        return redirect()->route('bidangs.index')->with('success', 'Bidang berhasil diperbarui!');
    }

    public function destroy(Bidang $bidang)
    {
        $bidang->delete();
        return redirect()->route('bidangs.index')->with('success', 'Bidang berhasil dihapus!');
    }
}
