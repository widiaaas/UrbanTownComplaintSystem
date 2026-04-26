<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->id();
        
            // IDENTITAS
            $table->string('judul', 255);
            $table->string('kategori', 100);
            $table->enum('departemen_terkait', [
                'Operational', 'Engineering', 'Finance', 'Legal', 'Developer'
            ]);
        
            // 🔥 KMS FEATURE
            $table->text('keywords')->nullable(); // hasil ekstraksi keyword
            $table->text('variasi')->nullable();  // dari input "variasi kata"
        
            // 🔥 TRACKING
            $table->unsignedInteger('usage_count')->default(0);
        
            // 🔥 TRACE (dari keluhan mana)
            $table->foreignId('keluhan_id')
                ->nullable()
                ->constrained('keluhans')
                ->nullOnDelete();
        
            // USER
            $table->string('created_by');
        
            // VALIDASI (optional ringan)
            $table->enum('status', ['draft', 'approved'])->default('approved');
        
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('knowledge_bases');
    }
};