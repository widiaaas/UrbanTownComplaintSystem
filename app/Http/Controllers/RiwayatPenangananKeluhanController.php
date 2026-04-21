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
                'catatan' => 'required|string',
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

            // 🔥 HANDLE LAMPIRAN
            $lampiran = [];

            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    if ($file->isValid()) {
                        $lampiran[] = $file->store('keluhan_lampiran', 'public');
                    }
                }
            }

            RiwayatPenangananKeluhan::create([
                'keluhan_id' => $keluhan->id,
                'keterangan' => $request->judul . ' - ' . $request->catatan,
                'lampiran' => $lampiran,
                'penanggung_jawab_id' => $user->id,
                'waktu' => now()
            ]);

            return response()->json([
                'message' => 'Penanganan berhasil disimpan',
                'data' => [
                    'judul' => $riwayat->judul,
                    'catatan' => $riwayat->catatan,
                    'waktu' => $riwayat->waktu->format('d-m-Y H:i'),
                    'status' => $riwayat->status,
                    'lampiran' => $riwayat->lampiran ?? []
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