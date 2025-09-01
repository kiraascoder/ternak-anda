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
        Schema::create('konsultasi_sarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idKonsultasi');
            $table->unsignedBigInteger('idPenyuluh');

            $table->string('judul')->nullable();
            $table->text('isi');
            $table->timestamps();


            $table->foreign('idKonsultasi')
                ->references('idKonsultasi')->on('konsultasi_kesehatan')
                ->cascadeOnDelete();

            $table->foreign('idPenyuluh')
                ->references('idUser')->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_konsultasi_saran');
    }
};
