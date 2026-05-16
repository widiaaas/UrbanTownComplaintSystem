<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * =====================================================
         * ADMIN
         * =====================================================
         */
        Pengguna::create([

            'username' => 'ADM001',

            'password' => Hash::make('password'),

            'role' => 'admin',

            'is_active' => true,

            'must_change_password' => false,
        ]);

        /**
         * =====================================================
         * TENANT RELATION
         * =====================================================
         */
        Pengguna::create([

            'username' => 'TR001',

            'password' => Hash::make('password'),

            'role' => 'tenant_relation',

            'is_active' => true,

            'must_change_password' => false,
        ]);

        /**
         * =====================================================
         * STAFF DEPARTEMEN ENGINEERING
         * =====================================================
         */
        Pengguna::create([

            'username' => 'ENG001',

            'password' => Hash::make('password'),

            'role' => 'departemen',

            'is_active' => true,

            'must_change_password' => false,
        ]);

        /**
         * =====================================================
         * UNIT
         * =====================================================
         */
        Pengguna::create([

            'username' => 'A-1201',

            'password' => Hash::make('password'),

            'role' => 'unit',

            'is_active' => true,

            'must_change_password' => false,
        ]);
    }
}