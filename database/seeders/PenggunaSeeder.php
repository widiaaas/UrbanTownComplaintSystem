<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class PenggunaSeeder extends Seeder
{
    public function run()
    {
        Pengguna::create(['username' => 'admin', 'password_hash' => Hash::make('password123'), 'role' => 'admin', 'is_active' => true, 'must_change_password' => false]);
        Pengguna::create(['username' => 'tr1', 'password_hash' => Hash::make('password123'), 'role' => 'tenant_relation', 'is_active' => true, 'must_change_password' => false]);
        Pengguna::create(['username' => 'eng1', 'password_hash' => Hash::make('password123'), 'role' => 'departemen', 'is_active' => true, 'must_change_password' => false]);
        Pengguna::create(['username' => 'ops1', 'password_hash' => Hash::make('password123'), 'role' => 'departemen', 'is_active' => true, 'must_change_password' => false]);
        Pengguna::create(['username' => 'A-101', 'password_hash' => Hash::make('password123'), 'role' => 'unit', 'is_active' => true, 'must_change_password' => false]);
        Pengguna::create(['username' => 'B-205', 'password_hash' => Hash::make('password123'), 'role' => 'unit', 'is_active' => true, 'must_change_password' => false]);
        Pengguna::create(['username' => 'C-310', 'password_hash' => Hash::make('password123'), 'role' => 'unit', 'is_active' => true, 'must_change_password' => false]);
    }
}