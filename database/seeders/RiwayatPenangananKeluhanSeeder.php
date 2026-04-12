<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatPenangananKeluhan;
use Carbon\Carbon;


class RiwayatPenangananKeluhanSeeder extends Seeder
{
    public function run()
    {
        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 2,
            'judul' => 'Keluhan Masuk',
            'keterangan' => 'Keluhan diterima oleh sistem.',
            'user_id' => 2, // 👈 TAMBAH INI
            'waktu' => Carbon::now()->subDays(3)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 2,
            'judul' => 'TR Mengambil Keluhan',
            'keterangan' => 'Keluhan diambil oleh Tenant Relation.',
            'user_id' => 2,
            'waktu' => Carbon::now()->subDays(1)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 3,
            'judul' => 'Keluhan Masuk',
            'keterangan' => 'Keluhan diterima oleh sistem.',
            'user_id' => 2,
            'waktu' => Carbon::now()->subDays(4)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 3,
            'judul' => 'TR Mengambil Keluhan',
            'keterangan' => 'Keluhan diambil oleh Tenant Relation.',
            'user_id' => 2,
            'waktu' => Carbon::now()->subDays(3)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 3,
            'judul' => 'Keputusan TR',
            'keterangan' => 'TR memutuskan membuat Work Order.',
            'user_id' => 2,
            'waktu' => Carbon::now()->subDays(2)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 4,
            'judul' => 'Keluhan Masuk',
            'keterangan' => 'Keluhan diterima oleh sistem.',
            'user_id' => 2,
            'waktu' => Carbon::now()->subDays(7)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 4,
            'judul' => 'TR Mengambil Keluhan',
            'keterangan' => 'Keluhan diambil oleh Tenant Relation.',
            'user_id' => 2,
            'waktu' => Carbon::now()->subDays(6)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 4,
            'judul' => 'Keputusan TR',
            'keterangan' => 'Kipas diganti baru.',
            'user_id' => 2,
            'waktu' => Carbon::now()->subDays(4)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 4,
            'judul' => 'Feedback Penghuni',
            'keterangan' => 'Penghuni puas.',
            'user_id' => 5, // bisa user unit
            'waktu' => Carbon::now()->subDays(3)
        ]);
    }
}