<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatHunian;

class RiwayatHunianSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * =========================================
         * PENGHUNI 1
         * MASIH AKTIF DI UNIT A-1201
         * =========================================
         */
        RiwayatHunian::create([
            'penghuni_id' => 1,
            'unit_id' => 1,
            'status' => 'Aktif',
            'tanggal_masuk' => '2026-01-10',
            'tanggal_keluar' => null,
        ]);

    }
}