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
        Schema::create('jadwal_prokers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proker_id');
            $table->unsignedBigInteger('penanggung_jawab_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['penjadwalan', 'berjalan', 'selesai'])->default('penjadwalan');
            $table->string('catatan', 100)->nullable();
            $table->timestamps();

            $table->foreign('proker_id')->references('id')->on('prokers')->onDelete('cascade');

            $table->foreign('penanggung_jawab_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_prokers');
    }
};
