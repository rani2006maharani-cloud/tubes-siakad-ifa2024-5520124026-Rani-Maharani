{{-- resources/views/dosen/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Dosen')

@section('content')
<div class="card">
    <div class="card-header bg-warning">
        <h5 class="mb-0">
            <i class="fas fa-edit me-2"></i>Edit Data Dosen
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('dosen.update', $dosen) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kode_dosen" class="form-label">Kode Dosen <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kode_dosen') is-invalid @enderror" 
                           id="kode_dosen" name="kode_dosen" value="{{ old('kode_dosen', $dosen->kode_dosen) }}" required>
                    @error('kode_dosen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="nama_dosen" class="form-label">Nama Dosen <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_dosen') is-invalid @enderror" 
                           id="nama_dosen" name="nama_dosen" value="{{ old('nama_dosen', $dosen->nama_dosen) }}" required>
                    @error('nama_dosen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nidn" class="form-label">NIDN <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nidn') is-invalid @enderror" 
                           id="nidn" name="nidn" value="{{ old('nidn', $dosen->nidn) }}" required>
                    @error('nidn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $dosen->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="no_telepon" class="form-label">No. Telepon</label>
                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                           id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $dosen->no_telepon) }}">
                    @error('no_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                            id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $dosen->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                          id="alamat" name="alamat" rows="2">{{ old('alamat', $dosen->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                <input type="text" class="form-control @error('pendidikan_terakhir') is-invalid @enderror" 
                       id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $dosen->pendidikan_terakhir) }}">
                @error('pendidikan_terakhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('dosen.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-1"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection