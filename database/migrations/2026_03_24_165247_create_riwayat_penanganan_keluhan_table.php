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
            $table->enum('status', ['open', 'on_progress', 'waiting', 'close']);
            $table->string('judul', 50);
            $table->text('deskripsi');
            $table->json('lampiran')->nullable();
            $table->timestamp('waktu');
            $table->foreignId('keluhan_id')->constrained('keluhans')->onDelete('cascade');
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('penggunas')->nullOnDelete();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_penanganan_keluhans');
    }
};