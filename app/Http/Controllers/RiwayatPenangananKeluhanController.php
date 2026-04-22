<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluhan;
use App\Models\RiwayatPenangananKeluhan;
use Illuminate\Support\Facades\Validator;

class RiwayatPenangananKeluhanController extends Controller
{
    public function simpanPenanganan(Request $request, $id)
    {
        try {
            // 🔥 VALIDASI
            $validator = Validator::make($request->all(), [
                'judul' => 'required|string',
                'deskripsi' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // 🔥 AUTH
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'message' => 'User tidak login'
                ], 401);
            }

            // 🔥 AMBIL KELUHAN
            $keluhan = Keluhan::findOrFail($id);

            $lampiran = [];

            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    if ($file->isValid()) {
                        $lampiran[] = $file->store('keluhan_lampiran', 'public');
                    }
                }
            }

            // 🔥 SIMPAN RIWAYAT (SAMA KAYAK WO)
            $riwayat = RiwayatPenangananKeluhan::create([
                'keluhan_id' => $keluhan->id,
                'status' => $keluhan->status, // 🔥 ambil dari keluhan
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'lampiran' => $lampiran,
                'penanggung_jawab_id' => $user->id,
                'waktu' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Penanganan berhasil disimpan',
                'data' => [
                    'id' => $riwayat->id,
                    'judul' => $riwayat->judul,
                    'deskripsi' => $riwayat->deskripsi,
                    'status' => $riwayat->status,
                    'waktu' => $riwayat->waktu->format('d-m-Y H:i'),
                    'lampiran' => $riwayat->lampiran,
                    'penanggung_jawab' => $user->nama ?? null
                ]
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}