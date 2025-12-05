<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jadwal_prokers', function (Blueprint $table) {
            $table->date('estimasi_mulai')->nullable()->after('penanggung_jawab_id');
            $table->date('estimasi_selesai')->nullable()->after('estimasi_mulai');
        });
    }

    public function down()
    {
        Schema::table('jadwal_prokers', function (Blueprint $table) {
            $table->dropColumn(['estimasi_mulai', 'estimasi_selesai']);
        });
    }
};
