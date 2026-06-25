{{-- resources/views/dosen/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Dosen')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">
            <i class="fas fa-plus-circle me-2"></i>Tambah Data Dosen
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('dosen.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kode_dosen" class="form-label">Kode Dosen <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kode_dosen') is-invalid @enderror" 
                           id="kode_dosen" name="kode_dosen" value="{{ old('kode_dosen') }}" required>
                    @error('kode_dosen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="nama_dosen" class="form-label">Nama Dosen <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_dosen') is-invalid @enderror" 
                           id="nama_dosen" name="nama_dosen" value="{{ old('nama_dosen') }}" required>
                    @error('nama_dosen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nidn" class="form-label">NIDN <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nidn') is-invalid @enderror" 
                           id="nidn" name="nidn" value="{{ old('nidn') }}" required>
                    @error('nidn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="no_telepon" class="form-label">No. Telepon</label>
                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                           id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}">
                    @error('no_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                            id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                          id="alamat" name="alamat" rows="2">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir</label>
                <input type="text" class="form-control @error('pendidikan_terakhir') is-invalid @enderror" 
                       id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir') }}">
                @error('pendidikan_terakhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('dosen.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection