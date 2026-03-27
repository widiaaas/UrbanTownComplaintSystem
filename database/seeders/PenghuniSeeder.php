<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penghuni;
use App\Models\Unit;

class PenghuniSeeder extends Seeder
{
    public function run()
    {
        $p1 = Penghuni::create(['nama' => 'Widiawati Sihaloho', 'email' => 'widiawati@example.com', 'telepon' => '081234567890', 'jenis_kelamin' => 'Perempuan', 'status' => 'Aktif', 'unit_id' => 1, 'tanggal_masuk' => '2024-01-01', 'tanggal_keluar' => null]);
        $p2 = Penghuni::create(['nama' => 'Budi Santoso', 'email' => 'budi@example.com', 'telepon' => '081298765432', 'jenis_kelamin' => 'Laki-laki', 'status' => 'Aktif', 'unit_id' => 2, 'tanggal_masuk' => '2024-02-15', 'tanggal_keluar' => null]);
        $p3 = Penghuni::create(['nama' => 'Siti Aminah', 'email' => 'siti@example.com', 'telepon' => '081212345678', 'jenis_kelamin' => 'Perempuan', 'status' => 'Aktif', 'unit_id' => 3, 'tanggal_masuk' => '2024-03-10', 'tanggal_keluar' => null]);
    }
}