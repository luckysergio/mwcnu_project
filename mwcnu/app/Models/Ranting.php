<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranting extends Model
{
    protected $table = "rantings";

    protected $guarded = [];

    public function anggota()
    {
        return $this->hasOne(Anggota::class);
    }
}
