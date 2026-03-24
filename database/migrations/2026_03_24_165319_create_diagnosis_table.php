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
            $table->foreignId('knowledge_base_id')->constrained('knowledge_base')->onDelete('cascade');
            $table->foreignId('keluhan_id')->nullable()->constrained('keluhan')->nullOnDelete();
            $table->text('penyebab');
            $table->text('deskripsi')->nullable();
            $table->text('langkah_penyelesaian');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('diagnosis');
    }
};