<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riwayat_keluhans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keluhan_id')->constrained('keluhans')->onDelete('cascade');
            $table->string('judul', 50);
            $table->text('keterangan');
            $table->timestamp('waktu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_keluhans');
    }
};