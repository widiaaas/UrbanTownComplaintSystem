<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riwayat_penanganan_keluhans', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 50);
            $table->text('keterangan');
            $table->timestamp('waktu');
            $table->foreignId('keluhan_id')->constrained('keluhans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('penggunas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_penanganan_keluhans');
    }
};