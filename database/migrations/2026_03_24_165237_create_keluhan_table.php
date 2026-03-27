<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('keluhans', function (Blueprint $table) {
            $table->id();
            $table->string('ticket', 20)->unique();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('penghuni_id')->constrained('penghunis')->onDelete('cascade');
            $table->string('judul', 50);
            $table->text('deskripsi');
            $table->enum('status', ['unassigned', 'open', 'on_progress', 'closed'])->default('unassigned');
            $table->foreignId('penanggung_jawab')->nullable()->constrained('penggunas')->nullOnDelete();
            $table->timestamp('taken_at')->nullable();
            $table->text('keputusan')->nullable();
            $table->timestamp('tanggal_keputusan')->nullable();
            $table->json('lampiran')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('keluhans');
    }
};