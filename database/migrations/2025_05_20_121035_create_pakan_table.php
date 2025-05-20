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
        Schema::create('rekomendasi_pakan', function (Blueprint $table) {
            $table->id('idRekomendasi');
            $table->unsignedBigInteger('idTernak');
            $table->unsignedBigInteger('idPenyuluh'); 
            $table->date('tanggalRekomendasi');
            $table->string('jenisPakan', 100);
            $table->integer('jumlah');
            $table->text('saran')->nullable();
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
        Schema::dropIfExists('pakan');
    }
};
