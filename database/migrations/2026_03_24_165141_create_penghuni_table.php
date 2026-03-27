<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penghunis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->string('email', 100)->nullable();
            $table->string('telepon', 15);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penghunis');
    }
};