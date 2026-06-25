<?php
// app/Http/Controllers/KRSController.php

namespace App\Http\Controllers;

use App\Models\KRS;
use App\Models\Mahasiswa;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class KRSController extends Controller
{
    // ============================================
    // FUNGSI UNTUK ADMIN
    // ============================================

    public function index()
    {
        $krs = KRS::with(['mahasiswa', 'jadwal.dosen', 'jadwal.mataKuliah'])
            ->latest()
            ->paginate(10);
        return view('krs.index', compact('krs'));
    }

    public function create()
    {
        $mahasiswas = Mahasiswa::all();
        $jadwals = Jadwal::with(['dosen', 'mataKuliah'])
            ->where('status', 'Aktif')
            ->get();
        return view('krs.create', compact('mahasiswas', 'jadwals'));
    }

    public function store(Request $request)
    {
        // VALIDASI
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'jadwal_id' => 'required|exists:jadwals,id',
            'tahun_akademik' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'semester' => 'required|in:Ganjil,Genap',
            'status' => 'required|in:Draft,Disetujui,Batal'
        ]);

        // Cek duplikasi
        $exists = KRS::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->where('jadwal_id', $validated['jadwal_id'])
            ->where('tahun_akademik', $validated['tahun_akademik'])
            ->where('semester', $validated['semester'])
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->with('error', 'Mahasiswa sudah mengambil mata kuliah ini.');
        }

        // Cek kapasitas
        $jadwal = Jadwal::find($validated['jadwal_id']);
        $jumlah_krs = KRS::where('jadwal_id', $validated['jadwal_id'])
            ->where('status', '!=', 'Batal')
            ->count();

        if ($jumlah_krs >= $jadwal->kapasitas) {
            return back()->withInput()
                ->with('error', 'Kelas sudah penuh. Kapasitas: ' . $jadwal->kapasitas);
        }

        $validated['tanggal_pengambilan'] = now();
        KRS::create($validated);

        return redirect()->route('krs.index')
            ->with('success', 'KRS berhasil ditambahkan.');
    }

    public function show(KRS $krs)
    {
        $krs->load(['mahasiswa', 'jadwal.dosen', 'jadwal.mataKuliah']);
        return view('krs.show', compact('krs'));
    }

    public function edit(KRS $krs)
    {
        $mahasiswas = Mahasiswa::all();
        $jadwals = Jadwal::with(['dosen', 'mataKuliah'])
            ->where('status', 'Aktif')
            ->get();
        return view('krs.edit', compact('krs', 'mahasiswas', 'jadwals'));
    }

    public function update(Request $request, KRS $krs)
    {
        // VALIDASI
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'jadwal_id' => 'required|exists:jadwals,id',
            'tahun_akademik' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'semester' => 'required|in:Ganjil,Genap',
            'status' => 'required|in:Draft,Disetujui,Batal'
        ]);

        // Cek duplikasi (ignore current)
        $exists = KRS::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->where('jadwal_id', $validated['jadwal_id'])
            ->where('tahun_akademik', $validated['tahun_akademik'])
            ->where('semester', $validated['semester'])
            ->where('id', '!=', $krs->id)
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->with('error', 'Mahasiswa sudah mengambil mata kuliah ini.');
        }

        $krs->update($validated);

        return redirect()->route('krs.index')
            ->with('success', 'KRS berhasil diupdate.');
    }

    public function destroy(KRS $krs)
    {
        try {
            $krs->delete();
            return redirect()->route('krs.index')
                ->with('success', 'KRS berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('krs.index')
                ->with('error', 'KRS tidak dapat dihapus.');
        }
    }

    // ============================================
    // FUNGSI UNTUK MAHASISWA
    // ============================================

    public function krsSaya()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $krs = KRS::with(['jadwal.dosen', 'jadwal.mataKuliah'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('status', '!=', 'Batal')
            ->orderBy('tahun_akademik', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        // Mata kuliah tersedia
        $jadwalsTersedia = Jadwal::with(['dosen', 'mataKuliah'])
            ->where('status', 'Aktif')
            ->whereDoesntHave('krs', function($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id)
                    ->where('status', '!=', 'Batal');
            })
            ->get();

        $tahunAkademik = date('Y');
        $semesterAktif = date('m') >= 8 ? 'Ganjil' : 'Genap';

        return view('krs.saya', compact(
            'krs', 'jadwalsTersedia', 'tahunAkademik', 'semesterAktif', 'mahasiswa'
        ));
    }

    public function ambilKRS(Request $request)
    {
        // VALIDASI
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'tahun_akademik' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'semester' => 'required|in:Ganjil,Genap'
        ]);

        $mahasiswa = auth()->user()->mahasiswa;

        // Cek duplikasi
        $exists = KRS::where('mahasiswa_id', $mahasiswa->id)
            ->where('jadwal_id', $request->jadwal_id)
            ->where('tahun_akademik', $request->tahun_akademik)
            ->where('semester', $request->semester)
            ->where('status', '!=', 'Batal')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah mengambil mata kuliah ini.');
        }

        // Cek kapasitas
        $jadwal = Jadwal::find($request->jadwal_id);
        $jumlah_krs = KRS::where('jadwal_id', $request->jadwal_id)
            ->where('status', 'Disetujui')
            ->count();

        if ($jumlah_krs >= $jadwal->kapasitas) {
            return back()->with('error', 'Maaf, kelas sudah penuh.');
        }

        // Cek SKS (maks 24)
        $sksDiambil = KRS::where('mahasiswa_id', $mahasiswa->id)
            ->where('tahun_akademik', $request->tahun_akademik)
            ->where('semester', $request->semester)
            ->where('status', 'Disetujui')
            ->sum(DB::raw('(SELECT sks FROM mata_kuliahs WHERE mata_kuliahs.id = jadwals.mata_kuliah_id)'));

        $sksBaru = $jadwal->mataKuliah->sks ?? 0;

        if (($sksDiambil + $sksBaru) > 24) {
            return back()->with('error', 'Total SKS melebihi batas maksimum 24 SKS.');
        }

        // Buat KRS
        KRS::create([
            'mahasiswa_id' => $mahasiswa->id,
            'jadwal_id' => $request->jadwal_id,
            'tahun_akademik' => $request->tahun_akademik,
            'semester' => $request->semester,
            'tanggal_pengambilan' => now(),
            'status' => 'Disetujui'
        ]);

        return back()->with('success', 'KRS berhasil diambil! 📚');
    }

    public function dropKRS(KRS $krs)
    {
        // Cek kepemilikan
        if ($krs->mahasiswa_id !== auth()->user()->mahasiswa->id) {
            abort(403);
        }

        $krs->update(['status' => 'Batal']);

        return back()->with('success', 'Mata kuliah berhasil dibatalkan.');
    }

    // Export PDF (Bonus)
    public function exportPDF($id)
    {
        $krs = KRS::with(['mahasiswa', 'jadwal.dosen', 'jadwal.mataKuliah'])
            ->findOrFail($id);
        
        if (auth()->user()->role == 'mahasiswa' && 
            $krs->mahasiswa_id !== auth()->user()->mahasiswa->id) {
            abort(403);
        }

        $pdf = Pdf::loadView('krs.export-pdf', compact('krs'));
        return $pdf->download('KRS-' . $krs->mahasiswa->npm . '.pdf');
    }
}