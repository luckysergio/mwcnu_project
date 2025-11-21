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
        Schema::create('jadwal_proker_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_proker_id');
            $table->string('kegiatan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->json('foto')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('jadwal_proker_id')->references('id')->on('jadwal_prokers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_proker_details');
    }
};
