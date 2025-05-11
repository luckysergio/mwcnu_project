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
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('email',100)->unique();
            $table->string('phone',15);
            $table->enum('jabatan', ['mustasyar','syuriyah','ross syuriah','katib','awan','tanfidiyah','wakil ketua','sekertaris','bendahara','anggota']);
            $table->enum('ranting', ['karang tengah','karang mulya','karang timur','pedurenan','pondok bahar','pondok pucung','parung jaya']);
            $table->enum('status', ['active','inactive']);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
