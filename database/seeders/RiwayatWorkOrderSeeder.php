<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatWorkOrder;
use Carbon\Carbon;

class RiwayatWorkOrderSeeder extends Seeder
{
    public function run()
    {
        RiwayatWorkOrder::create(['work_order_id' => 2, 'status' => 'open', 'keterangan' => 'Work Order dibuat oleh Tenant Relation.', 'lampiran' => null, 'penanggung_jawab' => 2, 'waktu' => Carbon::now()->subDays(6)]);
        RiwayatWorkOrder::create(['work_order_id' => 2, 'status' => 'on_progress', 'keterangan' => 'Pekerjaan dimulai, teknisi menuju lokasi.', 'lampiran' => null, 'penanggung_jawab' => 3, 'waktu' => Carbon::now()->subDays(5)]);
        RiwayatWorkOrder::create(['work_order_id' => 2, 'status' => 'close', 'keterangan' => 'Pekerjaan selesai, kipas diganti dan berfungsi normal.', 'lampiran' => json_encode(['laporan_selesai.pdf']), 'penanggung_jawab' => 3, 'waktu' => Carbon::now()->subDays(5)]);
    }
}