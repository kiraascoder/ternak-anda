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
            $table->id();
            $table->unsignedBigInteger('idPenyuluh');
            $table->unsignedBigInteger('idPeternak');
            $table->unsignedBigInteger('idTernak');
            $table->dateTime('tanggal_pemeriksaan');
            $table->decimal('berat_badan', 8, 2)->nullable();
            $table->decimal('suhu_tubuh', 4, 1);
            $table->enum('nafsu_makan', ['baik', 'menurun', 'tidak_ada']);
            $table->enum('pernafasan', ['normal', 'cepat', 'lambat', 'sesak']);
            $table->enum('kulit_bulu', ['normal', 'kusam', 'luka', 'parasit']);
            $table->enum('mata_hidung', ['normal', 'berair', 'bengkak', 'bernanah']);
            $table->enum('feses', ['normal', 'encer', 'keras', 'berdarah']);
            $table->enum('aktivitas', ['aktif', 'lesu', 'gelisah', 'lemas']);
            $table->text('tindakan');
            $table->text('rekomendasi');
            $table->enum('status_kesehatan', ['sehat', 'perlu_perhatian', 'sakit'])->default('perlu_perhatian');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('idPenyuluh')->references('idUser')->on('users')->onDelete('cascade');
            $table->foreign('idPeternak')->references('idUser')->on('users')->onDelete('cascade');
            $table->foreign('idTernak')->references('idTernak')->on('ternak')->onDelete('cascade');

            // Indexes for better performance
            $table->index(['idPenyuluh', 'tanggal_pemeriksaan']);
            $table->index(['idPeternak', 'tanggal_pemeriksaan']);
            $table->index(['idTernak', 'tanggal_pemeriksaan']);
            $table->index('status_kesehatan');
            $table->index('tanggal_pemeriksaan');
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
