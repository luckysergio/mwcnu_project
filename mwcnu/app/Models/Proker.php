<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proker extends Model
{
    use HasFactory;

    protected $table = 'prokers';

    protected $guarded = [];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function jenis()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_id');
    }

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class);
    }

    public function sasaran()
    {
        return $this->belongsTo(Sasaran::class);
    }

    public function jadwalProker()
    {
        return $this->hasOne(JadwalProker::class);
    }

    public function laporan()
    {
        return $this->hasOne(Laporan::class);
    }
}
