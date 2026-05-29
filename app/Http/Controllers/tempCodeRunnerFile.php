<?php
public function ambilWO($id)
    {
        $user = auth()->user();

        $wo = WorkOrder::findOrFail($id);

        $wo->update([
            'penanggung_jawab_id' => $user->karyawan->id,
            'status' => 'open',
            'taken_at' => now()
        ]);

        RiwayatPenangananWorkOrder::create([
            'work_order_id' => $wo->id,
            'judul' => 'Work Order Diambil Alih',
            'deskripsi' =>'Work Order telah diambil dan mulai diproses oleh departemen',
            'status' => 'open',
            'waktu' => now(),
            'lampiran' => []
        ]);

        return response()->json([
            'message' => 'WO berhasil diambil'
        ]);
    }