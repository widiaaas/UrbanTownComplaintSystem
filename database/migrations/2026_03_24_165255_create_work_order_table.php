<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_wo', 20)->unique();
            $table->foreignId('keluhan_id')->constrained('keluhans')->onDelete('cascade');
            $table->enum('departemen_tujuan', ['Operational', 'Engineering', 'Finance', 'Legal', 'Developer']);
            $table->text('instruksi');
            $table->enum('status', ['unassigned', 'open', 'on_progress', 'waiting', 'closed'])->default('open');
            $table->foreignId('penanggung_jawab')->nullable()->constrained('penggunas')->nullOnDelete();
            $table->timestamp('taken_at')->nullable();
            $table->text('laporan')->nullable();
            $table->json('lampiran')->nullable();
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_orders');
    }
};