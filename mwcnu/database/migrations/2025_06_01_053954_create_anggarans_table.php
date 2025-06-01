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
        Schema::create('anggarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jadwal_proker_id');
            $table->string('pendana', 100);
            $table->decimal('jumlah', 15, 2);
            $table->string('catatan', 100)->nullable();
            $table->timestamps();

            $table->foreign('jadwal_proker_id')->references('id')->on('jadwal_prokers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
};
