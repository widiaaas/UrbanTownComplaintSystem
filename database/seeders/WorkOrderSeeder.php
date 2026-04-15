<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkOrder;
use Carbon\Carbon;

class WorkOrderSeeder extends Seeder
{
    public function run()
    {
        WorkOrder::create([
            'nomor_wo' => 'WO-001',
            'keluhan_id' => 3,
            'departemen_tujuan' => 'Engineering',
            'instruksi' => 'Periksa dan perbaiki kran wastafel yang bocor di unit B-205.',
            'lokasi' => 'Kamar mandi utama',
            'status' => 'unassigned',
            'penanggung_jawab_id' => null,
            'taken_at' => null,
            'laporan' => null,
            'lampiran' => null,
            'tanggal_dibuat' => Carbon::now()->subDays(2),
            'tanggal_selesai' => null,
        ]);

        WorkOrder::create([
            'nomor_wo' => 'WO-002',
            'keluhan_id' => 4,
            'departemen_tujuan' => 'Engineering',
            'instruksi' => 'Ganti kipas angin di unit A-101.',
            'lokasi' => 'Kamar Utama',
            'status' => 'close',
            'penanggung_jawab_id' => 3,
            'taken_at' => Carbon::now()->subDays(5),
            'laporan' => 'Kipas angin diganti dengan yang baru, berfungsi normal.',
            'lampiran' => json_encode(['kipas_before.jpg', 'kipas_after.jpg']),
            'tanggal_dibuat' => Carbon::now()->subDays(6),
            'tanggal_selesai' => Carbon::now()->subDays(5),
        ]);
    }
}