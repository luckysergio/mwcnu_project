<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaStatus extends Model
{
    protected $table = "anggota_statuses";

    protected $guarded = [];

    public function anggotas()
    {
        return $this->hasMany(Anggota::class, 'status_id');
    }
}
