<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        Unit::create([
            'pengguna_id' => 4,
            'nomor_unit' => 'A-1201',
            'gedung' => 'Tower A',
            'lantai' => 12,
            'nomor_kamar' => 1201,
            'status' => 'Aktif',
        ]);
    }
}