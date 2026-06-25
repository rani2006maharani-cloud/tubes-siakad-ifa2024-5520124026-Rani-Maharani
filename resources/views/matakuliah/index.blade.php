{{-- resources/views/matakuliah/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Mata Kuliah')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-book me-2"></i>Data Mata Kuliah
        </h5>
        <a href="{{ route('matakuliah.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i>Tambah Mata Kuliah
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mataKuliahs as $mk)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $mk->kode_mk }}</td>
                        <td>{{ $mk->nama_mk }}</td>
                        <td>
                            <span class="badge bg-info">{{ $mk->sks }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">Semester {{ $mk->semester }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('matakuliah.show', $mk) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('matakuliah.edit', $mk) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('matakuliah.destroy', $mk) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Yakin ingin menghapus data mata kuliah ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data mata kuliah</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $mataKuliahs->links() }}
    </div>
</div>
@endsection