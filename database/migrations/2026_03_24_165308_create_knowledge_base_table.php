<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->string('kategori', 50);
            $table->enum('departemen_terkait', ['Operational', 'Engineering', 'Finance', 'Legal', 'Developer']);
            $table->foreignId('created_by')->constrained('penggunas')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('knowledge_bases');
    }
};