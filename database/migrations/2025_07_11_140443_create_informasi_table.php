<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informasis', function (Blueprint $table) {
            $table->id('idInformasi');
            $table->string('judul');
            $table->text('deskripsi');
            $table->dateTime('tanggal_kegiatan')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('kategori')->default('umum');
            $table->unsignedBigInteger('idPenyuluh')->nullable();
            $table->string('foto')->nullable();
            $table->foreign('idPenyuluh')->references('idUser')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi');
    }
};
