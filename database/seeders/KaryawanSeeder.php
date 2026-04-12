<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan;

class KaryawanSeeder extends Seeder
{
    public function run()
    {
        Karyawan::create([
            'user_id' => 1,
            'nip' => 'ADM001',
            'nama' => 'Admin Sistem',
            'telp' => '081234567890',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'departemen' => null,
            'jenis_kelamin' => 'Laki-laki',
            'status' => 'Aktif'
        ]);

        Karyawan::create([
            'user_id' => 2,
            'nip' => 'TR001',
            'nama' => 'Budi Santoso',
            'telp' => '081298765432',
            'email' => 'tr1@example.com',
            'role' => 'tenant_relation',
            'departemen' => null,
            'jenis_kelamin' => 'Laki-laki',
            'status' => 'Aktif'
        ]);

        Karyawan::create([
            'user_id' => 3,
            'nip' => 'ENG001',
            'nama' => 'Andi Wijaya',
            'telp' => '081234567891',
            'email' => 'eng1@example.com',
            'role' => 'departemen',
            'departemen' => 'Engineering',
            'jenis_kelamin' => 'Laki-laki',
            'status' => 'Aktif'
        ]);

        Karyawan::create([
            'user_id' => 4,
            'nip' => 'OPS001',
            'nama' => 'Siti Aminah',
            'telp' => '081234567892',
            'email' => 'ops1@example.com',
            'role' => 'departemen',
            'departemen' => 'Operational',
            'jenis_kelamin' => 'Perempuan',
            'status' => 'Aktif'
        ]);
    }
}