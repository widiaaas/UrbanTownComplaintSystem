<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class PenggunaSeeder extends Seeder
{
    public function run()
    {
        // Karyawan
        Pengguna::create([
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
            'is_active' => true,
            'must_change_password' => false
        ]);

        Pengguna::create([
            'username' => 'tr1',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
            'is_active' => true,
            'must_change_password' => false
        ]);

        Pengguna::create([
            'username' => 'eng1',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
            'is_active' => true,
            'must_change_password' => false
        ]);

        Pengguna::create([
            'username' => 'ops1',
            'password' => Hash::make('password123'),
            'role' => 'karyawan',
            'is_active' => true,
            'must_change_password' => false
        ]);

        // Unit
        Pengguna::create([
            'username' => 'A-101',
            'password' => Hash::make('password123'),
            'role' => 'unit',
            'is_active' => true,
            'must_change_password' => false
        ]);

        Pengguna::create([
            'username' => 'B-205',
            'password' => Hash::make('password123'),
            'role' => 'unit',
            'is_active' => true,
            'must_change_password' => false
        ]);

        Pengguna::create([
            'username' => 'C-310',
            'password' => Hash::make('password123'),
            'role' => 'unit',
            'is_active' => true,
            'must_change_password' => false
        ]);
    }
}