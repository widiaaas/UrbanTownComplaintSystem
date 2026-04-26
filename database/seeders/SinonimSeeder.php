<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SinonimSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sinonims')->insert([

            // ================= AC =================
            ['kata_asli' => 'panas', 'kata_normal' => 'tidak dingin', 'konteks' => 'AC'],
            ['kata_asli' => 'ga dingin', 'kata_normal' => 'tidak dingin', 'konteks' => 'AC'],
            ['kata_asli' => 'tidak sejuk', 'kata_normal' => 'tidak dingin', 'konteks' => 'AC'],
            ['kata_asli' => 'angin doang', 'kata_normal' => 'tidak dingin', 'konteks' => 'AC'],
            ['kata_asli' => 'berangin', 'kata_normal' => 'tidak dingin', 'konteks' => 'AC'],

            ['kata_asli' => 'air netes', 'kata_normal' => 'bocor', 'konteks' => 'AC'],
            ['kata_asli' => 'air keluar', 'kata_normal' => 'bocor', 'konteks' => 'AC'],
            ['kata_asli' => 'menetes', 'kata_normal' => 'bocor', 'konteks' => 'AC'],

            // ================= LISTRIK =================
            ['kata_asli' => 'lampu mati', 'kata_normal' => 'lampu mati', 'konteks' => 'Listrik'],
            ['kata_asli' => 'tidak nyala', 'kata_normal' => 'lampu mati', 'konteks' => 'Listrik'],
            ['kata_asli' => 'padam', 'kata_normal' => 'lampu mati', 'konteks' => 'Listrik'],

            ['kata_asli' => 'mati total', 'kata_normal' => 'listrik mati', 'konteks' => 'Listrik'],
            ['kata_asli' => 'tidak ada listrik', 'kata_normal' => 'listrik mati', 'konteks' => 'Listrik'],

            // ================= AIR =================
            ['kata_asli' => 'air tidak keluar', 'kata_normal' => 'air mati', 'konteks' => 'Air'],
            ['kata_asli' => 'tidak ada air', 'kata_normal' => 'air mati', 'konteks' => 'Air'],

            // ================= UMUM =================
            ['kata_asli' => 'rusak', 'kata_normal' => 'tidak berfungsi', 'konteks' => 'Umum'],
            ['kata_asli' => 'error', 'kata_normal' => 'tidak berfungsi', 'konteks' => 'Umum'],
            ['kata_asli' => 'bermasalah', 'kata_normal' => 'tidak berfungsi', 'konteks' => 'Umum'],

        ]);
    }
}