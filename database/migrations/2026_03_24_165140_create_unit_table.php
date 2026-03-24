<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('unit', function (Blueprint $table) {
            $table->id();
            $table->string('no_unit', 10)->unique();
            $table->string('gedung', 50);
            $table->integer('lantai');
            $table->integer('nomor_kamar');
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->foreignId('user_id')->constrained('pengguna')->onDelete('cascade');
            // Kolom penghuni_aktif_id tanpa foreign key (hanya integer)
            $table->unsignedBigInteger('penghuni_aktif_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit');
    }
};