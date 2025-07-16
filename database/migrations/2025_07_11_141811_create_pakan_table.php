<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pakan', function (Blueprint $table) {
            $table->id('idPakan');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->string('sumber')->nullable();
            $table->enum('jenis_pakan', ['hijauan', 'konsentrat', 'silase', 'campuran', 'lainnya'])->default('lainnya');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi_pakan');
    }
};
