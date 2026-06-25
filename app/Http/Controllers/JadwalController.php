<?php
// app/Http/Controllers/JadwalController.php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with(['dosen', 'mataKuliah'])
            ->latest()
            ->paginate(10);
        return view('jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $dosens = Dosen::all();
        $mataKuliahs = MataKuliah::all();
        return view('jadwal.create', compact('dosens', 'mataKuliahs'));
    }

    public function store(Request $request)
    {
        // VALIDASI
        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kelas' => 'required|string|max:10',
            'ruangan' => 'nullable|string|max:50',
            'kapasitas' => 'required|integer|min:1|max:100',
            'status' => 'required|in:Aktif,Nonaktif'
        ]);

        // Cek konflik jadwal
        $conflict = Jadwal::where('hari', $validated['hari'])
            ->where('kelas', $validated['kelas'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']]);
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->with('error', 'Jadwal bentrok dengan jadwal lain di kelas yang sama.');
        }

        Jadwal::create($validated);

        return redirect()->route('jadwal.index')
            ->with('success', 'Data jadwal berhasil ditambahkan.');
    }

    public function show(Jadwal $jadwal)
    {
        $jadwal->load(['dosen', 'mataKuliah', 'krs.mahasiswa']);
        return view('jadwal.show', compact('jadwal'));
    }

    public function edit(Jadwal $jadwal)
    {
        $dosens = Dosen::all();
        $mataKuliahs = MataKuliah::all();
        return view('jadwal.edit', compact('jadwal', 'dosens', 'mataKuliahs'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        // VALIDASI
        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kelas' => 'required|string|max:10',
            'ruangan' => 'nullable|string|max:50',
            'kapasitas' => 'required|integer|min:1|max:100',
            'status' => 'required|in:Aktif,Nonaktif'
        ]);

        // Cek konflik jadwal (ignore current)
        $conflict = Jadwal::where('hari', $validated['hari'])
            ->where('kelas', $validated['kelas'])
            ->where('id', '!=', $jadwal->id)
            ->where(function($query) use ($validated) {
                $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']]);
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->with('error', 'Jadwal bentrok dengan jadwal lain di kelas yang sama.');
        }

        $jadwal->update($validated);

        return redirect()->route('jadwal.index')
            ->with('success', 'Data jadwal berhasil diupdate.');
    }

    public function destroy(Jadwal $jadwal)
    {
        try {
            $jadwal->delete();
            return redirect()->route('jadwal.index')
                ->with('success', 'Data jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('jadwal.index')
                ->with('error', 'Data jadwal tidak dapat dihapus karena masih memiliki KRS.');
        }
    }

    public function getJadwal(Request $request)
    {
        $query = Jadwal::with(['dosen', 'mataKuliah'])->where('status', 'Aktif');

        if ($request->has('hari')) {
            $query->where('hari', $request->hari);
        }

        if ($request->has('dosen_id')) {
            $query->where('dosen_id', $request->dosen_id);
        }

        return response()->json($query->get());
    }
}