<?php
// app/Http/Controllers/DosenController.php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class DosenController extends Controller
{
    /**
     * Display a listing of dosen dengan pencarian dan paginate.
     */
    public function index(Request $request)
    {
        $query = Dosen::query();

        // ==========================================
        // FITUR PENCARIAN
        // ==========================================
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_dosen', 'LIKE', "%{$search}%")
                  ->orWhere('nama_dosen', 'LIKE', "%{$search}%")
                  ->orWhere('nidn', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('no_telepon', 'LIKE', "%{$search}%")
                  ->orWhere('alamat', 'LIKE', "%{$search}%")
                  ->orWhere('pendidikan_terakhir', 'LIKE', "%{$search}%");
            });
        }

        // ==========================================
        // FITUR FILTER JENIS KELAMIN
        // ==========================================
        if ($request->has('jenis_kelamin') && $request->jenis_kelamin != '') {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // ==========================================
        // FITUR FILTER PENDIDIKAN TERAKHIR
        // ==========================================
        if ($request->has('pendidikan') && $request->pendidikan != '') {
            $query->where('pendidikan_terakhir', 'LIKE', "%{$request->pendidikan}%");
        }

        // ==========================================
        // SORTIR
        // ==========================================
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['kode_dosen', 'nama_dosen', 'nidn', 'email', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        // ==========================================
        // PAGINATE
        // ==========================================
        $dosens = $query->paginate(10);
        $dosens->appends($request->all());

        $pendidikanList = Dosen::select('pendidikan_terakhir')
            ->whereNotNull('pendidikan_terakhir')
            ->distinct()
            ->pluck('pendidikan_terakhir');

        return view('dosen.index', compact('dosens', 'pendidikanList'));
    }

    /**
     * Show the form for creating a new dosen.
     */
    public function create()
    {
        return view('dosen.create');
    }

    /**
     * Store a newly created dosen.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_dosen' => 'required|string|max:20|unique:dosens|regex:/^[A-Z0-9]+$/',
            'nama_dosen' => 'required|string|max:100|min:3',
            'nidn' => 'required|string|max:20|unique:dosens|regex:/^[0-9]+$/',
            'email' => 'required|email|unique:dosens',
            'no_telepon' => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
            'alamat' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'pendidikan_terakhir' => 'nullable|string|max:50'
        ], [
            'kode_dosen.required' => 'Kode dosen wajib diisi.',
            'kode_dosen.unique' => 'Kode dosen sudah digunakan.',
            'kode_dosen.regex' => 'Kode dosen hanya boleh huruf kapital dan angka.',
            'nama_dosen.required' => 'Nama dosen wajib diisi.',
            'nama_dosen.min' => 'Nama dosen minimal 3 karakter.',
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.unique' => 'NIDN sudah digunakan.',
            'nidn.regex' => 'NIDN hanya boleh angka.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'no_telepon.regex' => 'Format nomor telepon tidak valid.',
        ]);

        Dosen::create($validated);

        return redirect()->route('dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan.');
    }

    /**
     * Display the specified dosen.
     */
    public function show(Dosen $dosen)
    {
        $dosen->load(['jadwals.mataKuliah', 'jadwals.krs']);
        
        $totalJadwal = $dosen->jadwals->count();
        $totalMahasiswa = $dosen->jadwals->sum(function($jadwal) {
            return $jadwal->krs->where('status', 'Disetujui')->count();
        });

        return view('dosen.show', compact('dosen', 'totalJadwal', 'totalMahasiswa'));
    }

    /**
     * Show the form for editing the specified dosen.
     */
    public function edit(Dosen $dosen)
    {
        return view('dosen.edit', compact('dosen'));
    }

    /**
     * Update the specified dosen.
     */
    public function update(Request $request, Dosen $dosen)
    {
        $validated = $request->validate([
            'kode_dosen' => ['required', 'string', 'max:20', Rule::unique('dosens')->ignore($dosen->id), 'regex:/^[A-Z0-9]+$/'],
            'nama_dosen' => 'required|string|max:100|min:3',
            'nidn' => ['required', 'string', 'max:20', Rule::unique('dosens')->ignore($dosen->id), 'regex:/^[0-9]+$/'],
            'email' => ['required', 'email', Rule::unique('dosens')->ignore($dosen->id)],
            'no_telepon' => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
            'alamat' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'pendidikan_terakhir' => 'nullable|string|max:50'
        ], [
            'kode_dosen.required' => 'Kode dosen wajib diisi.',
            'kode_dosen.unique' => 'Kode dosen sudah digunakan.',
            'kode_dosen.regex' => 'Kode dosen hanya boleh huruf kapital dan angka.',
            'nama_dosen.required' => 'Nama dosen wajib diisi.',
            'nama_dosen.min' => 'Nama dosen minimal 3 karakter.',
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.unique' => 'NIDN sudah digunakan.',
            'nidn.regex' => 'NIDN hanya boleh angka.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'no_telepon.regex' => 'Format nomor telepon tidak valid.',
        ]);

        $dosen->update($validated);

        return redirect()->route('dosen.index')
            ->with('success', 'Data dosen berhasil diupdate.');
    }

    /**
     * Remove the specified dosen.
     */
    public function destroy(Dosen $dosen)
    {
        try {
            if ($dosen->jadwals()->count() > 0) {
                return redirect()->route('dosen.index')
                    ->with('error', 'Data dosen tidak dapat dihapus karena masih memiliki jadwal.');
            }

            $dosen->delete();
            
            return redirect()->route('dosen.index')
                ->with('success', 'Data dosen berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('dosen.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data dosen.');
        }
    }

    /**
     * Search dosen for AJAX (Select2).
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $dosens = Dosen::where('nama_dosen', 'LIKE', "%{$query}%")
            ->orWhere('nidn', 'LIKE', "%{$query}%")
            ->orWhere('kode_dosen', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'kode_dosen', 'nama_dosen', 'nidn']);

        return response()->json($dosens);
    }

     public function exportPDF()
    {
        // Ambil semua data dosen
        $dosens = Dosen::all();
        
        // Jika DomPDF sudah terinstall
        if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dosen.export-pdf', compact('dosens'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download('Daftar-Dosen.pdf');
        }
        
        // Jika DomPDF belum terinstall, redirect dengan pesan
        return redirect()->route('dosen.index')
            ->with('info', 'Fitur export PDF memerlukan instalasi DomPDF. Jalankan: composer require barryvdh/laravel-dompdf');
    }

    /**
     * Export all dosen to Excel.
     */
  public function exportExcel()
    {
        return redirect()->route('dosen.index')
            ->with('info', 'Fitur export Excel sedang dalam pengembangan.');
    }
}