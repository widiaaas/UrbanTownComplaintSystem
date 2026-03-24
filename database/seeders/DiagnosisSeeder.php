<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Diagnosis;

class DiagnosisSeeder extends Seeder
{
    public function run(): void
    {
        // Diagnosis untuk KB AC Tidak Dingin
        Diagnosis::create([
            'knowledge_base_id' => 1,
            'keluhan_id' => null,
            'penyebab' => 'Freon habis atau tekanan tidak stabil',
            'deskripsi' => 'Tekanan freon rendah menyebabkan AC tidak mendingin optimal.',
            'langkah_penyelesaian' => "1. Cek tekanan freon menggunakan manifold gauge\n2. Isi ulang freon jika tekanan rendah\n3. Periksa kebocoran pada sambungan pipa\n4. Pastikan tidak ada kebocoran pada evaporator",
        ]);
        Diagnosis::create([
            'knowledge_base_id' => 1,
            'keluhan_id' => null,
            'penyebab' => 'Saluran drainase tersumbat',
            'deskripsi' => 'Air tidak bisa mengalir keluar sehingga AC bocor atau tidak dingin.',
            'langkah_penyelesaian' => "1. Bersihkan selang pembuangan dengan air bertekanan\n2. Pastikan selang tidak tertekuk\n3. Cek posisi kemiringan AC indoor\n4. Bersihkan bak penampung air",
        ]);

        // Diagnosis untuk KB Kran Bocor
        Diagnosis::create([
            'knowledge_base_id' => 2,
            'keluhan_id' => 3, // dari keluhan CMP-003
            'penyebab' => 'Karet seal aus',
            'deskripsi' => 'Karet seal pada kran aus sehingga air merembes.',
            'langkah_penyelesaian' => "1. Matikan aliran air utama\n2. Buka tutup kran\n3. Ganti karet seal dengan yang baru\n4. Pasang kembali dan tes aliran air",
        ]);

        // Diagnosis untuk KB Lampu Mati
        Diagnosis::create([
            'knowledge_base_id' => 3,
            'keluhan_id' => 2, // dari keluhan CMP-002
            'penyebab' => 'Bohlam lampu putus',
            'deskripsi' => 'Bohlam lampu sudah mencapai masa pakai atau rusak.',
            'langkah_penyelesaian' => "1. Matikan saklar lampu\n2. Buka penutup lampu\n3. Ganti bohlam dengan yang baru\n4. Pasang kembali penutup dan nyalakan",
        ]);
    }
}