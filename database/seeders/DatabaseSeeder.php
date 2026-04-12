<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PenggunaSeeder::class,
            KaryawanSeeder::class,
            UnitSeeder::class,
            PenghuniSeeder::class,
            KeluhanSeeder::class,
            RiwayatPenangananKeluhanSeeder::class,
            WorkOrderSeeder::class,
            RiwayatPenangananWorkOrderSeeder::class,
            KnowledgeBaseSeeder::class,
        ]);
    }
}