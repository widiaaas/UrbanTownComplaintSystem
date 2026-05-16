<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departemen;

class DepartemenSeeder extends Seeder
{
    public function run(): void
    {
        $departemens = [
            'Operational',
            'Engineering',
            'Finance',
            'Legal',
            'Developer',
        ];

        foreach ($departemens as $departemen) {
            Departemen::create([
                'nama_departemen' => $departemen
            ]);
        }
    }
}