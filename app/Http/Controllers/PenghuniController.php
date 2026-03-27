<?php

namespace App\Http\Controllers;

use App\Models\Penghuni;
use App\Models\Unit;
use Illuminate\Http\Request;

class PenghuniController extends Controller
{
    // Menampilkan semua penghuni dan unit
    public function index()
    {
        $penghunis = Penghuni::with('unit')->get(); // eager loading
        $units = Unit::all();

        return view('admin.penghuni.index', compact('penghunis', 'units'));
    }

    // Simpan penghuni baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'email' => 'nullable|email|max:100',
            'telepon' => 'nullable|string|max:15',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status' => 'required|in:Aktif,Nonaktif',
            'unit_id' => 'required|exists:units,id',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
        ]);

        Penghuni::create($validated);

        return redirect()->back()->with('success', 'Penghuni berhasil ditambahkan.');
    }

    // Update penghuni
    public function update(Request $request, Penghuni $penghuni)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'email' => 'nullable|email|max:100',
            'telepon' => 'nullable|string|max:15',
            'unit_id' => 'required|exists:units,id',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $penghuni->update($validated);

        return redirect()->route('admin.penghuni.index')->with('success', 'Penghuni berhasil diperbarui.');
    }

    // Hapus penghuni
    public function destroy(Penghuni $penghuni)
    {
        $penghuni->delete();

        return redirect()->back()->with('success', 'Penghuni berhasil dihapus.');
    }

    // Menampilkan detail penghuni (opsional jika pakai modal dari Blade)
    public function show(Penghuni $penghuni)
    {
        return response()->json([
            'penghuni' => $penghuni->load('unit')
        ]);
    }
}