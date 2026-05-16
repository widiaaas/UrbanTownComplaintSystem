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
            $table->foreignId('keluhan_id')->constrained('keluhans')->onDelete('cascade');
            $table->enum('status', ['open', 'on_progress', 'close']);
            $table->string('judul', 100);
            $table->text('deskripsi');
            $table->json('lampiran')->nullable();
            $table->timestamp('waktu');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_penanganan_keluhans');
    }
};