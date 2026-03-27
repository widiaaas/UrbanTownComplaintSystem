<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('penggunas')->onDelete('cascade');
            $table->string('nip', 20)->unique();
            $table->string('nama', 100);
            $table->string('telp', 20);
            $table->string('email', 100)->unique();
            $table->string('jabatan', 50);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};