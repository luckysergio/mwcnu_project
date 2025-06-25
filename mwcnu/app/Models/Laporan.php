<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporans';

    protected $casts = [
        'foto' => 'array',
    ];

    protected $guarded = [];

    public function proker()
    {
        return $this->belongsTo(Proker::class);
    }
}
