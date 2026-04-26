<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KnowledgeBase;
use App\Models\Diagnosis;

class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        // ================= KB 1 =================
        $kb1 = KnowledgeBase::create([
            'judul' => 'AC tidak dingin',
            'kategori' => 'AC',
            'departemen_terkait' => 'Engineering',
            'keywords' => 'ac,tidak dingin,panas',
            'variasi' => 'panas,angin doang,ga dingin',
            'usage_count' => 5,
            'created_by' => 1,
            'status' => 'approved'
        ]);

        Diagnosis::insert([
            [
                'knowledge_base_id' => $kb1->id,
                'penyebab' => 'Freon habis',
                'deskripsi' => 'Freon AC berkurang atau habis',
                'langkah_penyelesaian' => 'Lakukan pengisian freon sesuai tekanan',
                'usage_count' => 3
            ],
            [
                'knowledge_base_id' => $kb1->id,
                'penyebab' => 'Filter kotor',
                'deskripsi' => 'Filter udara penuh debu',
                'langkah_penyelesaian' => 'Bersihkan filter AC',
                'usage_count' => 2
            ]
        ]);

        // ================= KB 2 =================
        $kb2 = KnowledgeBase::create([
            'judul' => 'AC bocor',
            'kategori' => 'AC',
            'departemen_terkait' => 'Engineering',
            'keywords' => 'ac,bocor,air',
            'variasi' => 'air netes,air keluar',
            'usage_count' => 3,
            'created_by' => 1,
            'status' => 'approved'
        ]);

        Diagnosis::create([
            'knowledge_base_id' => $kb2->id,
            'penyebab' => 'Drain tersumbat',
            'deskripsi' => 'Saluran pembuangan tersumbat',
            'langkah_penyelesaian' => 'Bersihkan saluran drain',
            'usage_count' => 3
        ]);

        // ================= KB 3 =================
        $kb3 = KnowledgeBase::create([
            'judul' => 'Lampu mati',
            'kategori' => 'Listrik',
            'departemen_terkait' => 'Engineering',
            'keywords' => 'lampu,mati,listrik',
            'variasi' => 'tidak nyala,lampu padam',
            'usage_count' => 2,
            'created_by' => 1,
            'status' => 'approved'
        ]);

        Diagnosis::create([
            'knowledge_base_id' => $kb3->id,
            'penyebab' => 'Lampu rusak',
            'deskripsi' => 'Bohlam sudah tidak berfungsi',
            'langkah_penyelesaian' => 'Ganti lampu dengan yang baru',
            'usage_count' => 2
        ]);
    }
}