<?php
// app/Http/Controllers/MahasiswaController.php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with('user')->latest()->paginate(10);
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        // VALIDASI
        $validated = $request->validate([
            'npm' => 'required|string|max:20|unique:mahasiswas',
            'nama_mahasiswa' => 'required|string|max:100',
            'email' => 'required|email|unique:mahasiswas|unique:users',
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'tahun_masuk' => 'required|integer|min:2000|max:' . date('Y'),
            'password' => 'required|string|min:8|confirmed'
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['nama_mahasiswa'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa'
        ]);

        // Create mahasiswa
        $mahasiswa = Mahasiswa::create([
            'npm' => $validated['npm'],
            'nama_mahasiswa' => $validated['nama_mahasiswa'],
            'email' => $validated['email'],
            'no_telepon' => $validated['no_telepon'],
            'alamat' => $validated['alamat'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'tahun_masuk' => $validated['tahun_masuk'],
            'user_id' => $user->id
        ]);

        // Update user dengan mahasiswa_id
        $user->mahasiswa_id = $mahasiswa->id;
        $user->save();

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user', 'krs.jadwal.mataKuliah');
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        // VALIDASI
        $validated = $request->validate([
            'npm' => ['required', 'string', 'max:20', Rule::unique('mahasiswas')->ignore($mahasiswa->id)],
            'nama_mahasiswa' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('mahasiswas')->ignore($mahasiswa->id), Rule::unique('users')->ignore($mahasiswa->user_id)],
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'tahun_masuk' => 'required|integer|min:2000|max:' . date('Y')
        ]);

        // Update mahasiswa
        $mahasiswa->update($validated);

        // Update user terkait
        $mahasiswa->user->update([
            'name' => $validated['nama_mahasiswa'],
            'email' => $validated['email']
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed'
            ]);
            $mahasiswa->user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diupdate.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        try {
            $user = $mahasiswa->user;
            $mahasiswa->delete();
            if ($user) {
                $user->delete();
            }
            return redirect()->route('mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.index')
                ->with('error', 'Data mahasiswa tidak dapat dihapus karena masih memiliki relasi KRS.');
        }
    }
}