<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatKeluhan;
use Carbon\Carbon;

class RiwayatKeluhanSeeder extends Seeder
{
    public function run()
    {
        RiwayatKeluhan::create(['keluhan_id' => 2, 'judul' => 'Keluhan Masuk', 'keterangan' => 'Keluhan diterima oleh sistem.', 'waktu' => Carbon::now()->subDays(3)]);
        RiwayatKeluhan::create(['keluhan_id' => 2, 'judul' => 'TR Mengambil Keluhan', 'keterangan' => 'Keluhan diambil oleh Tenant Relation.', 'waktu' => Carbon::now()->subDays(1)]);

        RiwayatKeluhan::create(['keluhan_id' => 3, 'judul' => 'Keluhan Masuk', 'keterangan' => 'Keluhan diterima oleh sistem.', 'waktu' => Carbon::now()->subDays(4)]);
        RiwayatKeluhan::create(['keluhan_id' => 3, 'judul' => 'TR Mengambil Keluhan', 'keterangan' => 'Keluhan diambil oleh Tenant Relation.', 'waktu' => Carbon::now()->subDays(3)]);
        RiwayatKeluhan::create(['keluhan_id' => 3, 'judul' => 'Keputusan TR', 'keterangan' => 'TR memutuskan untuk membuat Work Order ke departemen Engineering.', 'waktu' => Carbon::now()->subDays(2)]);

        RiwayatKeluhan::create(['keluhan_id' => 4, 'judul' => 'Keluhan Masuk', 'keterangan' => 'Keluhan diterima oleh sistem.', 'waktu' => Carbon::now()->subDays(7)]);
        RiwayatKeluhan::create(['keluhan_id' => 4, 'judul' => 'TR Mengambil Keluhan', 'keterangan' => 'Keluhan diambil oleh Tenant Relation.', 'waktu' => Carbon::now()->subDays(6)]);
        RiwayatKeluhan::create(['keluhan_id' => 4, 'judul' => 'Keputusan TR', 'keterangan' => 'Keputusan diberikan: Kipas diganti baru.', 'waktu' => Carbon::now()->subDays(4)]);
        RiwayatKeluhan::create(['keluhan_id' => 4, 'judul' => 'Feedback Penghuni', 'keterangan' => 'Penghuni memberikan feedback puas.', 'waktu' => Carbon::now()->subDays(3)]);
    }
}