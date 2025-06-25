<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tujuan extends Model
{
    use HasFactory;

    protected $table = 'tujuans';
    protected $guarded = [];

    public function prokers()
    {
        return $this->hasMany(Proker::class);
    }
}
