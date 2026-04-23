<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkOrder;
use App\Models\RiwayatPenangananWorkOrder;
use Illuminate\Support\Facades\Validator;

class RiwayatPenangananWOController extends Controller
{
    public function simpanPenanganan(Request $request, $id)
    {
        try { 
            $validator = Validator::make($request->all(), [
                'judul' => 'required|string',
                'deskripsi' => 'required|string',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $user = auth()->user();

            // 🔥 FIX WAJIB
            if (!$user) {
                return response()->json([
                    'message' => 'User tidak login'
                ], 401);
            }

            $wo = WorkOrder::findOrFail($id);

            $status = $wo->status;

            $lampiran = [];

            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $lampiran[] = $file->store('wo_lampiran', 'public');
                }
            }

            RiwayatPenangananWorkOrder::create([
                'work_order_id' => $wo->id,
                'status' => $status,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'lampiran' => $lampiran,
                'penanggung_jawab_id' => $user->id,
                'waktu' => now()
            ]);

            $updateData = ['status' => $status];

            if ($status === 'close') {
                $updateData['tanggal_selesai'] = now();
            }

            $wo->update($updateData);

            return response()->json([
                'message' => 'Penanganan berhasil disimpan'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
        }
}