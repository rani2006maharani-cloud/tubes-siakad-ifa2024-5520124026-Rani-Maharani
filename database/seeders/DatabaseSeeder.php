<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Jadwal;
use App\Models\KRS;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@siakad.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        /*
        |--------------------------------------------------------------------------
        | DOSEN
        |--------------------------------------------------------------------------
        */

        $dosen1 = Dosen::create([
            'kode_dosen' => 'DSN001',
            'nama_dosen' => 'Dr. Ahmad Fauzi, M.Kom',
            'nidn' => '1234567890',
            'email' => 'ahmad.fauzi@univ.ac.id',
            'no_telepon' => '08123456789',
            'alamat' => 'Jakarta',
            'jenis_kelamin' => 'L',
            'pendidikan_terakhir' => 'S3 Ilmu Komputer',
        ]);

        $dosen2 = Dosen::create([
            'kode_dosen' => 'DSN002',
            'nama_dosen' => 'Dra. Siti Rahayu, M.Sc',
            'nidn' => '0987654321',
            'email' => 'siti.rahayu@univ.ac.id',
            'no_telepon' => '08129876543',
            'alamat' => 'Jakarta',
            'jenis_kelamin' => 'P',
            'pendidikan_terakhir' => 'S2 Sistem Informasi',
        ]);



        /*
        |--------------------------------------------------------------------------
        | MAHASISWA
        |--------------------------------------------------------------------------
        */

        $userMahasiswa = User::create([
            'name' => 'Muhammad Rizky',
            'email' => 'rizky@student.univ.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);

        $mahasiswa = Mahasiswa::create([
            'npm' => '20241001',
            'nama_mahasiswa' => 'Muhammad Rizky',
            'email' => 'rizky@student.univ.ac.id',
            'no_telepon' => '08567890123',
            'alamat' => 'Jakarta',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-15',
            'tahun_masuk' => 2024,
            'user_id' => $userMahasiswa->id,
        ]);


        /*
        |--------------------------------------------------------------------------
        | MATA KULIAH
        |--------------------------------------------------------------------------
        */

        $mk1 = MataKuliah::create([
            'kode_mk' => 'IF53413',
            'nama_mk' => 'Pemrograman Web II',
            'sks' => 3,
            'semester' => 4,
            'deskripsi' => 'Pemrograman web menggunakan Laravel',
        ]);

        $mk2 = MataKuliah::create([
            'kode_mk' => 'IF53412',
            'nama_mk' => 'Basis Data II',
            'sks' => 3,
            'semester' => 4,
            'deskripsi' => 'Basis data lanjutan',
        ]);

        /*
        |--------------------------------------------------------------------------
        | JADWAL
        |--------------------------------------------------------------------------
        */

        $jadwal1 = Jadwal::create([
            'dosen_id' => $dosen1->id,
            'mata_kuliah_id' => $mk1->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:30:00',
            'kelas' => 'A-01',
            'ruangan' => 'Lab Komputer 1',
            'kapasitas' => 40,
            'status' => 'Aktif',
        ]);

        $jadwal2 = Jadwal::create([
            'dosen_id' => $dosen2->id,
            'mata_kuliah_id' => $mk2->id,
            'hari' => 'Selasa',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:30:00',
            'kelas' => 'B-02',
            'ruangan' => 'Ruang 201',
            'kapasitas' => 35,
            'status' => 'Aktif',
        ]);

        /*
        |--------------------------------------------------------------------------
        | KRS
        |--------------------------------------------------------------------------
        */

        KRS::create([
            'mahasiswa_id' => $mahasiswa->id,
            'jadwal_id' => $jadwal1->id,
            'tahun_akademik' => 2024,
            'semester' => 'Ganjil',
            'tanggal_pengambilan' => now(),
            'status' => 'Disetujui',
        ]);
    }
}
