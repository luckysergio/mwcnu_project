<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = "anggotas";

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
