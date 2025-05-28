<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'tanggal',
        'lokasi',
        'deskripsi',
        'status',
    ];
    protected $casts = [
    'tanggal' => 'date',
    ];
    
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function getStatusLabelAttribute()
    {
        return $this->status == 1 ? 'Aktif' : 'Selesai';
    }

}
