{{-- resources/views/dosen/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Dosen')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">
            <i class="fas fa-user-circle me-2"></i>Detail Dosen
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Kode Dosen</th>
                        <td>{{ $dosen->kode_dosen }}</td>
                    </tr>
                    <tr>
                        <th>Nama Dosen</th>
                        <td>{{ $dosen->nama_dosen }}</td>
                    </tr>
                    <tr>
                        <th>NIDN</th>
                        <td>{{ $dosen->nidn }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $dosen->email }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">No. Telepon</th>
                        <td>{{ $dosen->no_telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $dosen->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <th>Pendidikan Terakhir</th>
                        <td>{{ $dosen->pendidikan_terakhir ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $dosen->alamat ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('dosen.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <div>
                <a href="{{ route('dosen.edit', $dosen) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection