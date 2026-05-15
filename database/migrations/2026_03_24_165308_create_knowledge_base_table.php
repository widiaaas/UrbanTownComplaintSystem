<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_bases', function (Blueprint $table) {

            $table->id();

            // IDENTITAS MASALAH
            $table->string('judul');

            // KATEGORI
            $table->string('kategori');

            // DEPARTEMEN
            $table->enum('departemen_terkait', [
                'Operational',
                'Engineering',
                'Finance',
                'Legal',
                'Developer'
            ]);

            // SEARCH ENGINE
            $table->text('keywords')->nullable();
            $table->text('variasi')->nullable();

            // ANALYTICS
            $table->unsignedInteger('usage_count')->default(0);

            // USER
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('penggunas')
                ->nullOnDelete();

            // STATUS
            $table->enum('status', [
                'draft',
                'approved'
            ])->default('approved');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_bases');
    }
};

