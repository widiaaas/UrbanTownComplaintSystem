<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_base_keluhans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('knowledge_base_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('diagnosis_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('keluhan_id')
                ->constrained()
                ->cascadeOnDelete();

            // OPTIONAL
            $table->text('catatan')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_base_keluhan');
    }
};

