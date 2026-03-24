<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Keluhan;
use Carbon\Carbon;

class KeluhanSeeder extends Seeder
{
    public function run()
    {
        Keluhan::create([
            'ticket' => 'CMP-001',
            'unit_id' => 1,
            'penghuni_id' => 1,
            'judul' => 'AC Tidak Dingin',
            'deskripsi' => 'AC ruang tamu tidak dingin sejak pagi.',
            'status' => 'unassigned',
            'penanggung_jawab' => null,
            'taken_at' => null,
            'keputusan' => null,
            'tanggal_keputusan' => null,
            'lampiran' => null,
            'created_at' => Carbon::now()->subDays(2),
        ]);

        Keluhan::create([
            'ticket' => 'CMP-002',
            'unit_id' => 2,
            'penghuni_id' => 2,
            'judul' => 'Lampu Mati',
            'deskripsi' => 'Lampu kamar mandi mati.',
            'status' => 'open',
            'penanggung_jawab' => 2,
            'taken_at' => Carbon::now()->subDays(1),
            'keputusan' => null,
            'tanggal_keputusan' => null,
            'lampiran' => null,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        Keluhan::create([
            'ticket' => 'CMP-003',
            'unit_id' => 3,
            'penghuni_id' => 3,
            'judul' => 'Kran Wastafel Bocor',
            'deskripsi' => 'Kran wastafel kamar mandi bocor.',
            'status' => 'on_progress',
            'penanggung_jawab' => 2,
            'taken_at' => Carbon::now()->subDays(2),
            'keputusan' => 'Akan dikerjakan oleh departemen Engineering.',
            'tanggal_keputusan' => Carbon::now()->subDays(2),
            'lampiran' => null,
            'created_at' => Carbon::now()->subDays(4),
        ]);

        Keluhan::create([
            'ticket' => 'CMP-004',
            'unit_id' => 1,
            'penghuni_id' => 1,
            'judul' => 'Kipas Angin Rusak',
            'deskripsi' => 'Kipas angin di ruang tamu tidak berputar.',
            'status' => 'closed',
            'penanggung_jawab' => 2,
            'taken_at' => Carbon::now()->subDays(5),
            'keputusan' => 'Kipas diganti dengan yang baru.',
            'tanggal_keputusan' => Carbon::now()->subDays(3),
            'lampiran' => null,
            'created_at' => Carbon::now()->subDays(7),
        ]);
    }
}