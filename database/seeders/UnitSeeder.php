<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run()
    {
        Unit::create(['no_unit' => 'A-101', 'gedung' => 'Tower A', 'lantai' => 10, 'nomor_kamar' => 1, 'status' => 'Aktif', 'user_id' => 5]);
        Unit::create(['no_unit' => 'B-205', 'gedung' => 'Tower B', 'lantai' => 2, 'nomor_kamar' => 5, 'status' => 'Aktif', 'user_id' => 6]);
        Unit::create(['no_unit' => 'C-310', 'gedung' => 'Tower C', 'lantai' => 3, 'nomor_kamar' => 10, 'status' => 'Aktif', 'user_id' => 7]);
    }
}