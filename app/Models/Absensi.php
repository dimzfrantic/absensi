<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = ['pegawai_id', 'kegiatan_id', 'waktu'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
