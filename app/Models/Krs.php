<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KRS extends Model
{
    use HasFactory;

    protected $table = 'krs';

    protected $fillable = [
        'mahasiswa_id',
        'jadwal_id',
        'tahun_akademik',
        'semester',
        'tanggal_pengambilan',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal_pengambilan' => 'datetime',
        'tahun_akademik' => 'integer',
    ];

    /**
     * Relasi ke Mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Relasi ke Jadwal
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    /**
     * Relasi ke MataKuliah melalui Jadwal
     */
    public function mataKuliah()
    {
        return $this->hasOneThrough(
            MataKuliah::class,
            Jadwal::class,
            'id', // Foreign key on Jadwal
            'id', // Foreign key on MataKuliah
            'jadwal_id', // Local key on KRS
            'mata_kuliah_id' // Local key on Jadwal
        );
    }

    /**
     * Scope untuk filter status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter semester
     */
    public function scopeSemester($query, $tahun, $semester)
    {
        return $query->where('tahun_akademik', $tahun)
                     ->where('semester', $semester);
    }

    /**
     * Scope untuk KRS yang aktif (tidak batal)
     */
    public function scopeAktif($query)
    {
        return $query->where('status', '!=', 'Batal');
    }

    /**
     * Scope untuk KRS yang disetujui
     */
    public function scopeDisetujui($query)
    {
        return $query->where('status', 'Disetujui');
    }

    /**
     * Accessor: Mendapatkan total SKS dari mata kuliah
     */
    public function getSksAttribute()
    {
        return $this->jadwal->mataKuliah->sks ?? 0;
    }

    /**
     * Accessor: Mendapatkan nama mata kuliah
     */
    public function getNamaMataKuliahAttribute()
    {
        return $this->jadwal->mataKuliah->nama_mk ?? '-';
    }

    /**
     * Accessor: Mendapatkan nama dosen
     */
    public function getNamaDosenAttribute()
    {
        return $this->jadwal->dosen->nama_dosen ?? '-';
    }

    /**
     * Accessor: Mendapatkan jadwal lengkap
     */
    public function getJadwalLengkapAttribute()
    {
        $j = $this->jadwal;
        return $j->hari . ', ' . $j->jam_mulai . ' - ' . $j->jam_selesai . ' (Kelas ' . $j->kelas . ')';
    }

    /**
     * Mutator: Set tanggal pengambilan otomatis jika belum diisi
     */
    public function setTanggalPengambilanAttribute($value)
    {
        $this->attributes['tanggal_pengambilan'] = $value ?? now();
    }

    /**
     * Boot method untuk event listener
     */
    protected static function booted()
    {
        static::creating(function ($krs) {
            if (!$krs->tanggal_pengambilan) {
                $krs->tanggal_pengambilan = now();
            }
        });
    }
}