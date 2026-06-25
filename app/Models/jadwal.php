<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'dosen_id',
        'mata_kuliah_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kelas',
        'ruangan',
        'kapasitas',
        'status'
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function krs()
    {
        return $this->hasMany(KRS::class);
    }

    public function getJamKuliahAttribute()
    {
        return "{$this->jam_mulai} - {$this->jam_selesai}";
    }

    public function scopeHari($query, $hari)
    {
        return $query->where('hari', $hari);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }
}