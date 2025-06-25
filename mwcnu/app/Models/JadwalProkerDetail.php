<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalProkerDetail extends Model
{
    protected $table = 'jadwal_proker_details';
    protected $guarded = [];

    public function jadwalProker()
    {
        return $this->belongsTo(JadwalProker::class);
    }
}
