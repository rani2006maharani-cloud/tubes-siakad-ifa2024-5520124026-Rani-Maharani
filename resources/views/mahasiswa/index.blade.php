{{-- resources/views/mahasiswa/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-user-graduate me-2"></i>Data Mahasiswa
        </h5>
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i>Tambah Mahasiswa
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NPM</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jenis Kelamin</th>
                        <th>Tahun Masuk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $mahasiswa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $mahasiswa->npm }}</td>
                        <td>{{ $mahasiswa->nama_mahasiswa }}</td>
                        <td>{{ $mahasiswa->email }}</td>
                        <td>
                            <span class="badge bg-{{ $mahasiswa->jenis_kelamin == 'L' ? 'info' : 'warning' }}">
                                {{ $mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td>{{ $mahasiswa->tahun_masuk }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('mahasiswa.show', $mahasiswa) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('mahasiswa.edit', $mahasiswa) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('mahasiswa.destroy', $mahasiswa) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Yakin ingin menghapus data mahasiswa ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data mahasiswa</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $mahasiswas->links() }}
    </div>
</div>
@endsection