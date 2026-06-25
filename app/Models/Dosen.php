<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_dosen',
        'nama_dosen',
        'nidn',
        'email',
        'no_telepon',
        'alamat',
        'jenis_kelamin',
        'pendidikan_terakhir',
    ];

    /**
     * Relasi ke Jadwal
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    /**
     * Accessor nama lengkap
     */
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->nama_dosen} ({$this->nidn})";
    }
}