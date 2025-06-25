<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisKegiatan extends Model
{
    use HasFactory;

    protected $table = 'jenis_kegiatans';
    protected $guarded = [];

    public function prokers()
    {
        return $this->hasMany(Proker::class, 'jenis_id');
    }
}
