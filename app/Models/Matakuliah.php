<?php
// app/Models/MataKuliah.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'deskripsi'
    ];

    /**
     * Relasi ke Jadwal
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    /**
     * Relasi ke Mahasiswa melalui Jadwal
     */
    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'krs', 'mata_kuliah_id', 'mahasiswa_id')
                    ->withPivot('tahun_akademik', 'semester', 'status')
                    ->withTimestamps();
    }

    /**
     * Scope untuk filter semester
     */
    public function scopeSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Scope untuk filter SKS
     */
    public function scopeSks($query, $sks)
    {
        return $query->where('sks', $sks);
    }

    /**
     * Accessor: Nama lengkap dengan kode
     */
    public function getNamaLengkapAttribute()
    {
        return $this->kode_mk . ' - ' . $this->nama_mk . ' (' . $this->sks . ' SKS)';
    }

    /**
     * Accessor: Status kelengkapan
     */
    public function getStatusAttribute()
    {
        if ($this->jadwals()->count() > 0) {
            return 'Sudah Dijadwalkan';
        }
        return 'Belum Dijadwalkan';
    }
}