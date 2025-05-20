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
        Schema::create('ternak', function (Blueprint $table) {
            $table->id('idTernak');
            $table->unsignedBigInteger('idPemilik');
            $table->string('namaTernak', 100);
            $table->date('tanggalLahir');
            $table->integer('berat');
            $table->text('fotoTernak')->nullable();
            $table->timestamps();
            $table->foreign('idPemilik')->references('idUser')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ternak');
    }
};
