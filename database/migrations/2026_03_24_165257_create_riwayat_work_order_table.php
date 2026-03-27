<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riwayat_work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
            $table->enum('status', ['open', 'on_progress', 'waiting', 'close']);
            $table->text('keterangan');
            $table->json('lampiran')->nullable();
            $table->foreignId('penanggung_jawab')->nullable()->constrained('penggunas')->nullOnDelete();
            $table->timestamp('waktu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_work_orders');
    }
};