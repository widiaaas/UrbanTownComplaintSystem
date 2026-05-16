<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Keluhan;

class KeluhanSeeder extends Seeder
{
    public function run(): void
    {
        Keluhan::create([
            'nomor_tiket' => 'KLH-0001',

            'unit_id' => 1,
            'penghuni_id' => 1,

            'judul' => 'AC Tidak Dingin',

            'deskripsi' => 'AC pada kamar utama tidak mengeluarkan udara dingin.',

            'status' => 'on_progress',

            'penanggung_jawab_id' => 2,

            'taken_at' => now(),

            'tanggal_pengajuan' => now(),

            'lampiran_pengajuan' => json_encode([
                'keluhan/ac1.jpg',
                'keluhan/ac2.jpg'
            ]),
        ]);
    }
}