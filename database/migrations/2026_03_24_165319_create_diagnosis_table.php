
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnosis', function (Blueprint $table) {

            $table->id();

            $table->foreignId('knowledge_base_id')
                ->constrained()
                ->cascadeOnDelete();

            // PENYEBAB
            $table->text('penyebab');

            // DESKRIPSI
            $table->text('deskripsi')->nullable();

            // SOLUSI
            $table->longText('langkah_penyelesaian');

            // LAMPIRAN
            $table->json('lampiran')->nullable();

            // ANALYTICS
            $table->unsignedInteger('usage_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnosis');
    }
};

