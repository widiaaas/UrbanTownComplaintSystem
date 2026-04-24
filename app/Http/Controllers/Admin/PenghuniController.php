<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class PenghuniController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $query = Penghuni::with('unit');
    
        // ================= FILTER NAMA + UNIT =================
        if ($request->filled('nama')) {
            $nama = trim($request->nama);
    
            $query->where(function ($q) use ($nama) {
                $q->where('nama', 'like', "%$nama%")
                  ->orWhereHas('unit', function ($q2) use ($nama) {
                      $q2->where('no_unit', 'like', "%$nama%");
                  });
            });
        }
    
        // ================= FILTER STATUS =================
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // ================= DATA =================
        $penghunis = $query->latest()->get();
    
        $jenisKelamin = ['Laki-laki', 'Perempuan'];
    
        return view('admin.penghuni.index', compact('penghunis', 'jenisKelamin'));
    }

    // ================= STORE =================
    
    public function store(Request $request)
    {
        
        if (!$request->expectsJson()) {
            return abort(404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => ['required','string','max:100','regex:/^[A-Za-z\s]+$/'],
            'telepon' => ['required','regex:/^08[0-9]{8,11}$/'],
            'email' => [
                'required',
                'max:100',
                'unique:penghunis,email',
                'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/'],
            'status' => 'required|in:Aktif,Nonaktif',

            
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            
        ], [
            'nama.required' => 'Nama wajib diisi',
            'nama.regex' => 'Nama hanya boleh huruf dan spasi',

            'telepon.required' => 'No. Telepon wajib diisi',
            'telepon.regex' => 'No. Telepon harus diawali 08 dan 10-13 digit',

            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'email.regex' => 'Email harus mengandung titik (.)',

            'status.required' => 'Status wajib dipilih',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
        
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $penghuni = Penghuni::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'status' => $request->status,
                'jenis_kelamin' => $request->jenis_kelamin,
                'unit_id' => null,
                'tanggal_masuk' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penghuni berhasil ditambahkan',
                'data' => $penghuni
            ]);

        } catch (\Exception $e) {

            DB::rollBack();
        
            return response()->json([
                'success' => false,
                'errors' => [
                    'system' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    // ================= UPDATE =================
    public function update(Request $request, Penghuni $penghuni)
    {

        $validator = Validator::make($request->all(), [

            'nama' => ['required','string','max:100','regex:/^[A-Za-z\s]+$/'],

            'telepon' => ['required','regex:/^08[0-9]{8,11}$/'],

            'email' => [
                'required',
                'max:100',
                'unique:penghunis,email,' . $penghuni->id,
                'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/'
            ],

            'status' => 'required|in:Aktif,Nonaktif',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',

        ], [
            'nama.regex' => 'Nama hanya boleh huruf dan spasi.',
            'telepon.regex' => 'No. Telepon harus diawali 08 dan 10-13 digit.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Email harus format benar ',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $penghuni->update($validator->validated());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penghuni berhasil diupdate',
                'data' => $penghuni
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ================= DELETE =================
    public function destroy(Request $request, Penghuni $penghuni)
    {

        $penghuni->delete();

        return response()->json([
            'success' => true,
            'message' => 'Penghuni berhasil dihapus'
        ]);
    }
}