<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalProker extends Model
{
    protected $table = 'jadwal_prokers';
    protected $guarded = [];

    public function proker()
    {
        return $this->belongsTo(Proker::class);
    }

    public function penanggungJawab()
    {
        return $this->belongsTo(Anggota::class, 'penanggung_jawab_id');
    }

    public function details()
    {
        return $this->hasMany(JadwalProkerDetail::class);
    }
}
