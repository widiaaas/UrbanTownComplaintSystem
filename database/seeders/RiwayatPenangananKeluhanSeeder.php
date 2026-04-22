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
            'status' => 'open',
            'judul' => 'Keluhan Masuk',
            'deskripsi' => 'Keluhan diterima oleh sistem.',
            'lampiran' => null,
            'penanggung_jawab_id' => 2,
            'waktu' => Carbon::now()->subDays(3)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 2,
            'status' => 'on_progress',
            'judul' => 'TR Mengambil Keluhan',
            'deskripsi' => 'Keluhan diambil oleh Tenant Relation.',
            'lampiran' => null,
            'penanggung_jawab_id' => 2,
            'waktu' => Carbon::now()->subDays(1)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 3,
            'status' => 'open',
            'judul' => 'Keluhan Masuk',
            'deskripsi' => 'Keluhan diterima oleh sistem.',
            'lampiran' => null,
            'penanggung_jawab_id' => 2,
            'waktu' => Carbon::now()->subDays(4)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 3,
            'status' => 'on_progress',
            'judul' => 'TR Mengambil Keluhan',
            'deskripsi' => 'Keluhan diambil oleh Tenant Relation.',
            'lampiran' => null,
            'penanggung_jawab_id' => 2,
            'waktu' => Carbon::now()->subDays(3)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 3,
            'status' => 'waiting',
            'judul' => 'Keputusan TR',
            'deskripsi' => 'TR memutuskan membuat Work Order.',
            'lampiran' => null,
            'penanggung_jawab_id' => 2,
            'waktu' => Carbon::now()->subDays(2)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 4,
            'status' => 'open',
            'judul' => 'Keluhan Masuk',
            'deskripsi' => 'Keluhan diterima oleh sistem.',
            'lampiran' => null,
            'penanggung_jawab_id' => 2,
            'waktu' => Carbon::now()->subDays(7)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 4,
            'status' => 'on_progress',
            'judul' => 'TR Mengambil Keluhan',
            'deskripsi' => 'Keluhan diambil oleh Tenant Relation.',
            'lampiran' => null,
            'penanggung_jawab_id' => 2,
            'waktu' => Carbon::now()->subDays(6)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 4,
            'status' => 'close',
            'judul' => 'Keputusan TR',
            'deskripsi' => 'Kipas diganti baru dan berfungsi normal.',
            'lampiran' => null,
            'penanggung_jawab_id' => 2,
            'waktu' => Carbon::now()->subDays(4)
        ]);

        RiwayatPenangananKeluhan::create([
            'keluhan_id' => 4,
            'status' => 'close',
            'judul' => 'Feedback Penghuni',
            'deskripsi' => 'Penghuni merasa puas dengan hasil perbaikan.',
            'lampiran' => null,
            'penanggung_jawab_id' => 5,
            'waktu' => Carbon::now()->subDays(3)
        ]);
    }
}