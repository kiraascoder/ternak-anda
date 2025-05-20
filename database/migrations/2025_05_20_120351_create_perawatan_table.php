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
        Schema::create('perawatan', function (Blueprint $table) {
            $table->id('idPerawatan');
            $table->unsignedBigInteger('idTernak');
            $table->unsignedBigInteger('idPenyuluh'); 
            $table->date('tanggalPerawatan');
            $table->string('jenisPerawatan', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->foreign('idTernak')->references('idTernak')->on('ternak')->onDelete('cascade');
            $table->foreign('idPenyuluh')->references('idUser')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perawatan');
    }
};
