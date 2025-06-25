<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sasaran extends Model
{
    use HasFactory;

    protected $table = 'sasarans';
    protected $guarded = [];

    public function prokers()
    {
        return $this->hasMany(Proker::class);
    }
}
