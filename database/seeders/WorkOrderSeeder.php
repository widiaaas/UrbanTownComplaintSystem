<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkOrder;

class WorkOrderSeeder extends Seeder
{
    public function run(): void
    {
        // WorkOrder::create([
        //     'nomor_tiket' => 'WO-0001',

        //     'keluhan_id' => 1,

        //     'departemen_id' => 2,

        //     'instruksi' => 'Lakukan pemeriksaan dan perbaikan AC.',

        //     'lokasi' => 'Tower A Lt 12 Unit A-1201',

        //     'status' => 'on_progress',

        //     'penanggung_jawab_id' => 3,

        //     'taken_at' => now(),

        //     'lampiran' => json_encode([
        //         'wo/pemeriksaan1.jpg'
        //     ]),

        //     'tanggal_dibuat' => now(),
        // ]);
    }
}