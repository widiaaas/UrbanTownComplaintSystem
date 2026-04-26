<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('diagnosis', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('knowledge_base_id')
                ->constrained('knowledge_bases')
                ->cascadeOnDelete();
        
            // 🔥 TIPE (opsional tapi bagus)
            $table->enum('tipe', ['utama', 'alternatif'])->default('utama');
        
            // 🔥 KONTEN
            $table->text('penyebab');                 // root cause
            $table->text('deskripsi')->nullable();    // penjelasan
            $table->text('langkah_penyelesaian');     // solusi
        
            // 🔥 PRIORITAS
            $table->integer('urutan')->default(1);
        
            // 🔥 TRACKING
            $table->unsignedInteger('usage_count')->default(0);
        
            // 🔥 TRACE (optional)
            $table->foreignId('keluhan_id')
                ->nullable()
                ->constrained('keluhans')
                ->nullOnDelete();
        
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('diagnosis');
    }
};