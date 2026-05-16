<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penghuni;

class PenghuniSeeder extends Seeder
{
    public function run(): void
    {
        Penghuni::create([
            'unit_id' => 1,
            'nama' => 'Widiawati Haloho',
            'email' => 'widiawati@example.com',
            'telepon' => '081234567893',
            'jenis_kelamin' => 'Perempuan',
            'status' => 'Aktif',
        ]);
    }
}