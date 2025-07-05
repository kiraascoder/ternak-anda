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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_type', 50); // 'penyuluh', 'peternak', 'admin'
            $table->string('action', 100);
            $table->json('data')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at');

            // Foreign key
            $table->foreign('user_id')->references('idUser')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index('user_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
