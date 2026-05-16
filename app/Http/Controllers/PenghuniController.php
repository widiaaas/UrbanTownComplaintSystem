<?php

namespace App\Http\Controllers;

use App\Models\Penghuni;
use App\Models\Unit;
use App\Models\RiwayatHunian;
use Illuminate\Http\Request;

class PenghuniController extends Controller
{
    /**
     * ================= INDEX =================
     */
    public function index()
    {
        // EAGER LOADING
        $penghunis = Penghuni::with('riwayatHunian.unit')
            ->latest()
            ->get();

        // UNIT AKTIF
        $units = Unit::where('status', 'Aktif')
            ->get();

        return view('admin.penghuni.index', compact(
            'penghunis',
            'units'
        ));
    }

    /**
     * ================= STORE =================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'nama' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z\s]+$/'
            ],

            'email' => [
                'nullable',
                'email',
                'max:100',
                'unique:penghunis,email'
            ],

            'telepon' => [
                'required',
                'regex:/^08[0-9]{8,11}$/'
            ],

            'jenis_kelamin' => [
                'required',
                'in:Laki-laki,Perempuan'
            ],

            'status' => [
                'required',
                'in:Aktif,Nonaktif'
            ],


        ], [

            'nama.required' => 'Nama wajib diisi',
            'nama.regex' => 'Nama hanya boleh huruf dan spasi',

            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',

            'telepon.required' => 'Nomor telepon wajib diisi',
            'telepon.regex' => 'Nomor telepon harus diawali 08 dan 10-13 digit',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
        ]);

        Penghuni::create($validated);

        return redirect()
            ->back()
            ->with('success', 'Penghuni berhasil ditambahkan.');
    }

    /**
     * ================= UPDATE =================
     */
    public function update(Request $request, Penghuni $penghuni)
    {
        $validated = $request->validate([

            'nama' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-Za-z\s]+$/'
            ],

            'email' => [
                'nullable',
                'email',
                'max:100',
                'unique:penghunis,email,' . $penghuni->id
            ],

            'telepon' => [
                'required',
                'regex:/^08[0-9]{8,11}$/'
            ],

            'jenis_kelamin' => [
                'required',
                'in:Laki-laki,Perempuan'
            ],

        ], [

            'nama.required' => 'Nama wajib diisi',
            'nama.regex' => 'Nama hanya boleh huruf dan spasi',

            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',

            'telepon.required' => 'Nomor telepon wajib diisi',
            'telepon.regex' => 'Nomor telepon harus diawali 08 dan 10-13 digit',
        ]);

        // // JIKA NONAKTIF → LEPAS UNIT
        // if ($validated['status'] === 'Nonaktif') {

        //     $validated['unit_id'] = null;
        // }

        $penghuni->update($validated);

        return redirect()
            ->route('admin.penghuni.index')
            ->with('success', 'Penghuni berhasil diperbarui.');
    }

    /**
     * ================= DELETE =================
     */
    public function destroy(Penghuni $penghuni)
    {
        $penghuni->delete();

        return redirect()
            ->back()
            ->with('success', 'Penghuni berhasil dihapus.');
    }

    /**
     * ================= SHOW =================
     */
    public function show(Penghuni $penghuni)
    {
        return response()->json([

            'penghuni' => $penghuni->load('riwayatHunian.unit')
        ]);
    }
}