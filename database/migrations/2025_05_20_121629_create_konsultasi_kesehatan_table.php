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
        Schema::create('konsultasi_kesehatan', function (Blueprint $table) {
            $table->id('idKonsultasi');
            $table->unsignedBigInteger('idPeternak');
            $table->unsignedBigInteger('idPenyuluh');
            $table->unsignedBigInteger('idTernak');
            $table->date('tanggalKonsultasi');
            $table->text('keluhan');
            $table->text('respon')->nullable(); 
            $table->timestamps();
            $table->foreign('idPeternak')->references('idUser')->on('users')->onDelete('cascade');
            $table->foreign('idPenyuluh')->references('idUser')->on('users')->onDelete('cascade');
            $table->foreign('idTernak')->references('idTernak')->on('ternak')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasi_kesehatan');
    }
};
