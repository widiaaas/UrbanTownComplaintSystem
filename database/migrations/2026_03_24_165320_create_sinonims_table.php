<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sinonims', function (Blueprint $table) {
            $table->id();

            // kata yang diinput user
            $table->string('kata_asli');

            // kata hasil normalisasi
            $table->string('kata_normal');

            // optional: kategori kata (biar scalable)
            $table->string('konteks')->nullable();

            $table->timestamps();

            // index untuk performa search
            $table->index('kata_asli');
            $table->index('kata_normal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sinonims');
    }
};