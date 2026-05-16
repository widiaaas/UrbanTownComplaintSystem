<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * =====================================================
         * ADMIN
         * =====================================================
         */
        Karyawan::create([

            'pengguna_id' => 1,

            'departemen_id' => null,

            'nip' => 'ADM001',

            'nama' => 'Administrator',

            'no_telepon' => '081234567890',

            'email' => 'admin@apartemen.com',

            'jenis_kelamin' => 'Laki-laki',

            'status' => 'Aktif',
        ]);

        /**
         * =====================================================
         * TENANT RELATION
         * =====================================================
         */
        Karyawan::create([

            'pengguna_id' => 2,

            'departemen_id' => null,

            'nip' => 'TR001',

            'nama' => 'Budi Santoso',

            'no_telepon' => '081234567891',

            'email' => 'tenantrelation@apartemen.com',

            'jenis_kelamin' => 'Laki-laki',

            'status' => 'Aktif',
        ]);

        /**
         * =====================================================
         * STAFF DEPARTEMEN ENGINEERING
         * =====================================================
         */
        Karyawan::create([

            'pengguna_id' => 3,

            'departemen_id' => 1,

            'nip' => 'ENG001',

            'nama' => 'Andi Wijaya',

            'no_telepon' => '081234567892',

            'email' => 'engineering@apartemen.com',

            'jenis_kelamin' => 'Laki-laki',

            'status' => 'Aktif',
        ]);
    }
}