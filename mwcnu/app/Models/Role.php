<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "roles";

    protected $guarded = [];

    public function anggota()
    {
        return $this->hasOne(Anggota::class);
    }
}
