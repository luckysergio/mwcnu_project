<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    protected $table = 'anggarans';
    protected $guarded = [];

    public function jadwalProker()
    {
        return $this->belongsTo(Jadwal_proker::class);
    }
}
