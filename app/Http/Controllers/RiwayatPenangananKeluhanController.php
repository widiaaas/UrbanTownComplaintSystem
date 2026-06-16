<?php

namespace App\Http\Controllers;

use App\Models\Keluhan;
use App\Models\RiwayatPenangananKeluhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RiwayatPenangananKeluhanController extends Controller
{
    public function simpanPenanganan(
        Request $request,
        $id
    ) {
        try {

            // vALIDASI 
            $validator = Validator::make(
                $request->all(),

                [
                    'judul' => ['required','string','max:100'],
                    'deskripsi' => ['required', 'string', 'min:5'],
                    'lampiran' => ['nullable','array'],
                    'lampiran.*' => [ 'file','mimes:jpg,jpeg,png,pdf','max:1024']
                ],

                [
                    'judul.required' =>'Judul wajib diisi',
                    'judul.max' =>'Judul maksimal 100 karakter',
                    'deskripsi.required' =>     'Deskripsi wajib diisi',
                    'deskripsi.min' =>'Deskripsi minimal 5 karakter',
                    'lampiran.*.mimes' => 'Lampiran hanya boleh JPG, PNG, atau PDF',
                    'lampiran.*.max' =>  'Ukuran file maksimal 1MB',
                ]
            );

            // VALIDATION ERROR
            if ($validator->fails()) {
                return response()->json([
                    'errors' =>
                        $validator->errors()

                ], 422);
            }

            /**
             * ============================================
             * AUTH
             * ============================================
             */
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' =>'User tidak login'
                ], 401);
            }

            $karyawan = $user->karyawan;

            if (!$karyawan) {
                return response()->json([
                    'message' =>'Data karyawan tidak ditemukan'

                ], 404);
            }

            // AMBIL KELUHAN 
            $keluhan = Keluhan::findOrFail($id);

            // VALIDASI PENANGGUNGJAWAB
            if (
                $keluhan->penanggung_jawab_id !==
                $karyawan->id
            ) {

                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke keluhan ini'
                ], 403);
            }

            // UPLOAD FILE
            $lampiran = [];

            if ($request->hasFile('lampiran')) {
                foreach (
                    $request->file('lampiran')
                    as $file
                ) {

                    if ($file->isValid()) {
                        $lampiran[] = $file->store(
                            'keluhan_lampiran',
                            'public'
                        );
                    }
                }
            }

            // SIMPAN RIWAYAT
            $riwayat =
                RiwayatPenangananKeluhan::create([
                    'keluhan_id' => $keluhan->id,
                    'status' => $keluhan->status,
                    'judul' => trim($request->judul),
                    'deskripsi' => trim($request->deskripsi),
                    'lampiran' =>$lampiran,
                    'waktu' => now(),
                ]);

            // JSON
            return response()->json([
                'success' => true,
                'message' =>'Penanganan berhasil disimpan',
                'data' => [
                    'id' => $riwayat->id,
                    'judul' => $riwayat->judul,
                    'deskripsi' => $riwayat->deskripsi,
                    'status' => $riwayat->status,
                    'waktu' =>
                        optional(
                            $riwayat->waktu
                        )->format('d-m-Y H:i'),

                    'lampiran' => $riwayat->lampiran ?? [],
                    'penanggung_jawab' => $karyawan->nama
                ],
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' =>
                    $e->getMessage()

            ], 500);
        }
    }
}