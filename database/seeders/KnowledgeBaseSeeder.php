<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KnowledgeBase;

class KnowledgeBaseSeeder extends Seeder
{
    public function run()
    {
        KnowledgeBase::create(['judul' => 'AC Tidak Dingin', 'kategori' => 'AC', 'departemen_terkait' => 'Engineering', 'created_by' => 2]);
        KnowledgeBase::create(['judul' => 'Kran Bocor', 'kategori' => 'Plumbing', 'departemen_terkait' => 'Engineering', 'created_by' => 2]);
        KnowledgeBase::create(['judul' => 'Lampu Mati', 'kategori' => 'Listrik', 'departemen_terkait' => 'Engineering', 'created_by' => 2]);
    }
}