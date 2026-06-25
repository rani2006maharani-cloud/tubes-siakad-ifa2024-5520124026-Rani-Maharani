<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Jadwal;
use App\Models\KRS;
use App\Models\User; 
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk Admin
     */
    public function admin()
    {
        // Statistik utama
        $stats = [
            'total_mahasiswa' => Mahasiswa::count(),
            'total_dosen' => Dosen::count(),
            'total_mata_kuliah' => MataKuliah::count(),
            'total_jadwal' => Jadwal::where('status', 'Aktif')->count(),
            'total_krs' => KRS::where('status', 'Disetujui')->count(),
            'total_user' => User::count(),
        ];

        // Data untuk chart
        $chartData = [
            'mahasiswa_per_tahun' => Mahasiswa::select(
                    DB::raw('tahun_masuk as tahun'),
                    DB::raw('count(*) as total')
                )
                ->groupBy('tahun_masuk')
                ->orderBy('tahun_masuk')
                ->get(),
            'krs_per_status' => KRS::select(
                    'status',
                    DB::raw('count(*) as total')
                )
                ->groupBy('status')
                ->get(),
        ];
        
        // KRS per semester
        $krsPerSemester = KRS::select(
                'tahun_akademik', 
                'semester', 
                DB::raw('count(*) as total')
            )
            ->groupBy('tahun_akademik', 'semester')
            ->orderBy('tahun_akademik', 'desc')
            ->get();

        // 5 Data Terbaru untuk setiap entitas
        $mahasiswaTerbaru = Mahasiswa::latest()->take(5)->get();
        $dosenTerbaru = Dosen::latest()->take(5)->get();
        $jadwalTerbaru = Jadwal::with(['dosen', 'mataKuliah'])->latest()->take(5)->get();
        $matakuliahTerbaru = MataKuliah::latest()->take(5)->get();

        return view('dashboard.admin', compact(
            'stats', 
            'chartData', 
            'krsPerSemester',
            'mahasiswaTerbaru',
            'dosenTerbaru',
            'jadwalTerbaru',
            'matakuliahTerbaru'
        ));
    }
    /**
     * Dashboard untuk Mahasiswa
     */
    public function mahasiswa()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil data KRS yang disetujui
        $krs = KRS::with(['jadwal.dosen', 'jadwal.mataKuliah'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'Disetujui')
            ->get();

        // ==========================================
        // PERBAIKAN: Hitung total SKS
        // ==========================================
        $total_sks = $krs->sum(function($item) {
            return $item->jadwal->mataKuliah->sks ?? 0;
        });

        // Statistik pribadi
        $stats = [
            'total_krs' => $krs->count(),
            'total_sks' => $total_sks,
            'rata_rata_sks' => $krs->count() > 0 ? round($total_sks / $krs->count(), 1) : 0,
        ];

        // Jadwal hari ini
        $hariIni = now()->translatedFormat('l');
        $jadwalHariIni = $krs->filter(function($item) use ($hariIni) {
            return $item->jadwal->hari === $hariIni;
        })->sortBy('jadwal.jam_mulai');

        // Informasi akademik
        $infoAkademik = [
            'npm' => $mahasiswa->npm,
            'nama' => $mahasiswa->nama_mahasiswa,
            'tahun_masuk' => $mahasiswa->tahun_masuk,
            'semester_aktif' => $this->getSemesterAktif($mahasiswa->tahun_masuk),
        ];

        return view('dashboard.mahasiswa', compact(
            'mahasiswa', 
            'krs', 
            'total_sks',      // <-- PASTIKAN INI ADA
            'stats',
            'jadwalHariIni',
            'infoAkademik'
        ));
    }

    /**
     * Helper: Mendapatkan data untuk chart
     */
    private function getChartData()
    {
        // Data mahasiswa per tahun masuk
        $mahasiswaPerTahun = Mahasiswa::select(
                DB::raw('tahun_masuk as tahun'),
                DB::raw('count(*) as total')
            )
            ->groupBy('tahun_masuk')
            ->orderBy('tahun_masuk')
            ->get();

        // Data KRS per status
        $krsPerStatus = KRS::select(
                'status',
                DB::raw('count(*) as total')
            )
            ->groupBy('status')
            ->get();

        return [
            'mahasiswa_per_tahun' => $mahasiswaPerTahun,
            'krs_per_status' => $krsPerStatus,
        ];
    }

    /**
     * Helper: Menghitung semester aktif
     */
    private function getSemesterAktif($tahunMasuk)
    {
        $tahunSekarang = date('Y');
        $bulanSekarang = date('m');
        
        // Asumsi: semester ganjil (Agustus-Desember) dan genap (Februari-Juni)
        $semesterSekarang = ($bulanSekarang >= 8 || $bulanSekarang <= 12) ? 'Ganjil' : 'Genap';
        
        // Hitung semester aktif (1 tahun = 2 semester)
        $tahunAktif = $tahunSekarang - $tahunMasuk;
        $semesterAktif = ($tahunAktif * 2) + ($semesterSekarang == 'Ganjil' ? 1 : 0);
        
        return $semesterAktif;
    }
}