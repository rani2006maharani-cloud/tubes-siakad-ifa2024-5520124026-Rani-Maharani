{{-- resources/views/jadwal/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Jadwal')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-calendar-alt me-2"></i>Data Jadwal
        </h5>
        <a href="{{ route('jadwal.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i>Tambah Jadwal
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $jadwal)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                        <td>{{ $jadwal->dosen->nama_dosen ?? '-' }}</td>
                        <td>{{ $jadwal->hari }}</td>
                        <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                        <td>{{ $jadwal->kelas }}</td>
                        <td>
                            <span class="badge bg-{{ $jadwal->status == 'Aktif' ? 'success' : 'secondary' }}">
                                {{ $jadwal->status }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('jadwal.show', $jadwal) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('jadwal.edit', $jadwal) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('jadwal.destroy', $jadwal) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Yakin ingin menghapus data jadwal ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data jadwal</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $jadwals->links() }}
    </div>
</div>
@endsection