<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riwayat_penanganan_work_orders', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['open', 'on_progress', 'waiting', 'close']);
            $table->string('judul', 100);
            $table->text('deskripsi');
            $table->json('lampiran')->nullable();
            $table->timestamp('waktu');
            $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_penanganan_work_orders');
    }
};