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
        Schema::create('prokers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('anggota_id');
            $table->string('judul', 100);

            $table->unsignedBigInteger('bidang_id');
            $table->unsignedBigInteger('jenis_id');
            $table->unsignedBigInteger('tujuan_id');
            $table->unsignedBigInteger('sasaran_id');

            $table->string('proposal');
            $table->text('keterangan')->nullable();

            $table->enum('status', ['pengajuan', 'disetujui', 'ditolak'])->default('pengajuan');

            $table->timestamps();

            $table->foreign('anggota_id')->references('id')->on('anggotas')->onDelete('cascade');
            $table->foreign('bidang_id')->references('id')->on('bidangs')->onDelete('cascade');
            $table->foreign('jenis_id')->references('id')->on('jenis_kegiatans')->onDelete('cascade');
            $table->foreign('tujuan_id')->references('id')->on('tujuans')->onDelete('cascade');
            $table->foreign('sasaran_id')->references('id')->on('sasarans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prokers');
    }
};
