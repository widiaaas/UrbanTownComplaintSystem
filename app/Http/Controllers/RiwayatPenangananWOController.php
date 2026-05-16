<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPenangananWorkOrder;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RiwayatPenangananWOController extends Controller
{
    public function simpanPenanganan(Request $request, $id)
    {
        try {

            /**
             * ============================================
             * VALIDASI
             * ============================================
             */
            $validator = Validator::make($request->all(), [

                'judul' => [
                    'required',
                    'string',
                    'max:100'
                ],

                'deskripsi' => [
                    'required',
                    'string',
                    'min:5'
                ],

                'lampiran' => [
                    'nullable',
                    'array'
                ],

                'lampiran.*' => [
                    'file',
                    'mimes:jpg,jpeg,png,pdf',
                    'max:1024'
                ]

            ], [

                'judul.required' =>
                    'Judul wajib diisi',

                'judul.max' =>
                    'Judul maksimal 100 karakter',

                'deskripsi.required' =>
                    'Deskripsi wajib diisi',

                'deskripsi.min' =>
                    'Deskripsi minimal 5 karakter',

                'lampiran.*.mimes' =>
                    'Lampiran hanya boleh JPG, PNG, atau PDF',

                'lampiran.*.max' =>
                    'Ukuran file maksimal 1MB',
            ]);

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

                    'message' =>
                        'User tidak login'

                ], 401);
            }

            $karyawan = $user->karyawan;

            if (!$karyawan) {

                return response()->json([

                    'message' =>
                        'Data karyawan tidak ditemukan'

                ], 404);
            }

            /**
             * ============================================
             * AMBIL WORK ORDER
             * ============================================
             */
            $wo = WorkOrder::findOrFail($id);

            /**
             * ============================================
             * VALIDASI PETUGAS
             * ============================================
             */
            if (
                $wo->penanggung_jawab_id !==
                $karyawan->id
            ) {

                return response()->json([

                    'message' =>
                        'Anda tidak memiliki akses ke WO ini'

                ], 403);
            }

            /**
             * ============================================
             * STATUS WO
             * ============================================
             */
            $status = $wo->status;

            /**
             * ============================================
             * UPLOAD FILE
             * ============================================
             */
            $lampiran = [];

            if ($request->hasFile('lampiran')) {

                foreach (
                    $request->file('lampiran')
                    as $file
                ) {

                    if ($file->isValid()) {

                        $lampiran[] = $file->store(
                            'wo_lampiran',
                            'public'
                        );
                    }
                }
            }

            /**
             * ============================================
             * SIMPAN RIWAYAT
             * ============================================
             */
            $riwayat =
                RiwayatPenangananWorkOrder::create([

                    'work_order_id' =>
                        $wo->id,

                    'status' =>
                        $status,

                    'judul' =>
                        trim($request->judul),

                    'deskripsi' =>
                        trim($request->deskripsi),

                    'lampiran' =>
                        $lampiran,
                    
                    'waktu' => now(),
                ]);

            /**
             * ============================================
             * UPDATE WO
             * ============================================
             */
            $updateData = [

                'status' =>
                    $status
            ];

            if ($status === 'close') {

                $updateData['tanggal_selesai'] =
                    now();
            }

            $wo->update($updateData);

            /**
             * ============================================
             * RESPONSE
             * ============================================
             */
            return response()->json([

                'message' =>
                    'Penanganan berhasil disimpan',

                'data' => [

                    'id' =>
                        $riwayat->id,

                    'judul' =>
                        $riwayat->judul,

                    'deskripsi' =>
                        $riwayat->deskripsi,

                    'status' =>
                        $riwayat->status,

                    'lampiran' =>
                        $riwayat->lampiran ?? [],

                    'waktu' =>
                        optional(
                            $riwayat->created_at
                        )->format('d-m-Y H:i')
                ]
            ]);

        } catch (\Throwable $e) {

            return response()->json([

                'message' =>'Terjadi kesalahan pada server',
                'error' =>$e->getMessage(),


            ], 500);
        }
    }
}