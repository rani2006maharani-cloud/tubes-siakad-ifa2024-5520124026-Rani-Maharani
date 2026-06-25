<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswas';

    protected $fillable = [
        'npm',
        'nama_mahasiswa',
        'email',
        'no_telepon',
        'alamat',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'tahun_masuk',
        'user_id'
    ];

   public function user()
{
    return $this->belongsTo(User::class);
}

    public function krs()
    {
        return $this->hasMany(KRS::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function getNamaLengkapAttribute()
    {
        return "{$this->nama_mahasiswa} ({$this->npm})";
    }
}