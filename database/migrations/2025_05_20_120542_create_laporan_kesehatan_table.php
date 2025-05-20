<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_kesehatan', function (Blueprint $table) {
            $table->id('idLaporan');
            $table->unsignedBigInteger('idTernak');
            $table->unsignedBigInteger('idPeternak');
            $table->unsignedBigInteger('idPenyuluh')->nullable();
            $table->date('tanggalLaporan');
            $table->text('kondisi');
            $table->text('catatan')->nullable(); 
            $table->timestamps();
            $table->foreign('idTernak')->references('idTernak')->on('ternak')->onDelete('cascade');
            $table->foreign('idPeternak')->references('idUser')->on('users')->onDelete('cascade');
            $table->foreign('idPenyuluh')->references('idUser')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kesehatan');
    }
};
