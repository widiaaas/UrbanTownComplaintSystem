<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatPenangananWorkOrder;
use Carbon\Carbon;

class RiwayatPenangananWorkOrderSeeder extends Seeder
{
    public function run()
    {
        RiwayatPenangananWorkOrder::create([
            'work_order_id' => 2,
            'status' => 'open',
            'judul' => 'Work Order Dibuat',
            'deskripsi' => 'Work Order dibuat oleh Tenant Relation.',
            'lampiran' => null,
            'penanggung_jawab_id' => 2,
            'waktu' => Carbon::now()->subDays(6)
        ]);

        RiwayatPenangananWorkOrder::create([
            'work_order_id' => 2,
            'status' => 'on_progress',
            'judul' => 'Pekerjaan Dimulai',
            'deskripsi' => 'Teknisi menuju lokasi dan mulai pengecekan.',
            'lampiran' => null,
            'penanggung_jawab_id' => 3,
            'waktu' => Carbon::now()->subDays(5)
        ]);

        RiwayatPenangananWorkOrder::create([
            'work_order_id' => 2,
            'status' => 'close',
            'judul' => 'Pekerjaan Selesai',
            'deskripsi' => 'Kipas diganti dan sudah berfungsi normal.',
            'lampiran' => ['laporan_selesai.pdf'],
            'penanggung_jawab_id' => 3,
            'waktu' => Carbon::now()->subDays(5)
        ]);
    }
}