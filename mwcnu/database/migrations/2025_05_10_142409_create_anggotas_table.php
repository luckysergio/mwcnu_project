<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('ranting_id')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->string('phone', 15);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('ranting_id')
                ->references('id')
                ->on('rantings')
                ->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('anggota_statuses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
