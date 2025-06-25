<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidangs';
    protected $guarded = [];

    public function prokers()
    {
        return $this->hasMany(Proker::class);
    }
}
