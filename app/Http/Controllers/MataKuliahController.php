<?php
// app/Http/Controllers/MataKuliahController.php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    /**
     * Display a listing of mata kuliah.
     */
    public function index(Request $request)
    {
        $query = MataKuliah::query();

        // Fitur Pencarian (Bonus)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_mk', 'LIKE', "%{$search}%")
                  ->orWhere('nama_mk', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }

        // Fitur Filter Semester (Bonus)
        if ($request->has('semester') && $request->semester != '') {
            $query->where('semester', $request->semester);
        }

        // Fitur Filter SKS (Bonus)
        if ($request->has('sks') && $request->sks != '') {
            $query->where('sks', $request->sks);
        }

        $mataKuliahs = $query->latest()->paginate(10);
        
        // Data untuk filter
        $semesters = MataKuliah::select('semester')->distinct()->orderBy('semester')->pluck('semester');
        $sksList = MataKuliah::select('sks')->distinct()->orderBy('sks')->pluck('sks');

        return view('matakuliah.index', compact('mataKuliahs', 'semesters', 'sksList'));
    }

    /**
     * Show the form for creating a new mata kuliah.
     */
    public function create()
    {
        return view('matakuliah.create');
    }

    /**
     * Store a newly created mata kuliah in storage.
     */
    public function store(Request $request)
    {
        // VALIDASI
        $validated = $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliahs|regex:/^[A-Z0-9]+$/',
            'nama_mk' => 'required|string|max:100|min:3',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:14',
            'deskripsi' => 'nullable|string|max:500'
        ], [
            // Custom error messages
            'kode_mk.required' => 'Kode mata kuliah wajib diisi.',
            'kode_mk.unique' => 'Kode mata kuliah sudah digunakan.',
            'kode_mk.regex' => 'Kode mata kuliah hanya boleh huruf kapital dan angka.',
            'nama_mk.required' => 'Nama mata kuliah wajib diisi.',
            'nama_mk.min' => 'Nama mata kuliah minimal 3 karakter.',
            'sks.required' => 'Jumlah SKS wajib diisi.',
            'sks.min' => 'SKS minimal 1.',
            'sks.max' => 'SKS maksimal 6.',
            'semester.required' => 'Semester wajib diisi.',
            'semester.min' => 'Semester minimal 1.',
            'semester.max' => 'Semester maksimal 14.',
        ]);

        MataKuliah::create($validated);

        return redirect()->route('matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil ditambahkan.');
    }

    /**
     * Display the specified mata kuliah.
     */
    public function show(MataKuliah $matakuliah)
    {
        // Load relasi jadwal
        $matakuliah->load(['jadwals.dosen', 'jadwals.krs.mahasiswa']);
        
        // Hitung statistik
        $totalJadwal = $matakuliah->jadwals->count();
        $totalMahasiswa = $matakuliah->jadwals->sum(function($jadwal) {
            return $jadwal->krs->where('status', 'Disetujui')->count();
        });

        return view('matakuliah.show', compact('matakuliah', 'totalJadwal', 'totalMahasiswa'));
    }

    /**
     * Show the form for editing the specified mata kuliah.
     */
    public function edit(MataKuliah $matakuliah)
    {
        return view('matakuliah.edit', compact('matakuliah'));
    }

    /**
     * Update the specified mata kuliah in storage.
     */
    public function update(Request $request, MataKuliah $matakuliah)
    {
        // VALIDASI dengan ignore unique
        $validated = $request->validate([
            'kode_mk' => ['required', 'string', 'max:20', Rule::unique('mata_kuliahs')->ignore($matakuliah->id), 'regex:/^[A-Z0-9]+$/'],
            'nama_mk' => 'required|string|max:100|min:3',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:14',
            'deskripsi' => 'nullable|string|max:500'
        ], [
            'kode_mk.required' => 'Kode mata kuliah wajib diisi.',
            'kode_mk.unique' => 'Kode mata kuliah sudah digunakan.',
            'kode_mk.regex' => 'Kode mata kuliah hanya boleh huruf kapital dan angka.',
            'nama_mk.required' => 'Nama mata kuliah wajib diisi.',
            'nama_mk.min' => 'Nama mata kuliah minimal 3 karakter.',
            'sks.required' => 'Jumlah SKS wajib diisi.',
            'sks.min' => 'SKS minimal 1.',
            'sks.max' => 'SKS maksimal 6.',
            'semester.required' => 'Semester wajib diisi.',
            'semester.min' => 'Semester minimal 1.',
            'semester.max' => 'Semester maksimal 14.',
        ]);

        $matakuliah->update($validated);

        return redirect()->route('matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil diupdate.');
    }

    /**
     * Remove the specified mata kuliah from storage.
     */
    public function destroy(MataKuliah $matakuliah)
    {
        try {
            // Cek apakah mata kuliah masih digunakan di jadwal
            if ($matakuliah->jadwals()->count() > 0) {
                return redirect()->route('matakuliah.index')
                    ->with('error', 'Data mata kuliah tidak dapat dihapus karena masih digunakan di jadwal.');
            }

            $matakuliah->delete();
            
            return redirect()->route('matakuliah.index')
                ->with('success', 'Data mata kuliah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('matakuliah.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data mata kuliah.');
        }
    }

    /**
     * API: Get mata kuliah by semester (untuk AJAX)
     */
    public function getBySemester(Request $request)
    {
        $request->validate([
            'semester' => 'required|integer|min:1|max:14'
        ]);

        $mataKuliahs = MataKuliah::where('semester', $request->semester)
            ->orderBy('kode_mk')
            ->get(['id', 'kode_mk', 'nama_mk', 'sks']);

        return response()->json($mataKuliahs);
    }

    /**
     * API: Get mata kuliah for select2
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $mataKuliahs = MataKuliah::where('nama_mk', 'LIKE', "%{$query}%")
            ->orWhere('kode_mk', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'kode_mk', 'nama_mk', 'sks']);

        return response()->json($mataKuliahs);
    }

    /**
     * Export all mata kuliah to PDF (Bonus)
     */
    public function exportPDF()
    {
        $mataKuliahs = MataKuliah::all();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('matakuliah.export-pdf', compact('mataKuliahs'));
        return $pdf->download('Daftar-Mata-Kuliah.pdf');
    }
}