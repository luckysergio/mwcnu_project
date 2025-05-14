<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal_proker extends Model
{
    protected $table = 'jadwal_prokers';
    protected $guarded = [];

    // Relasi ke proker
    public function proker()
    {
        return $this->belongsTo(Proker::class);
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }
}
