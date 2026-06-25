@extends('layouts.app')

@section('title', 'Data Dosen')

@section('content')
<div class="card">
<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0">
        <i class="fas fa-chalkboard-teacher me-2"></i>Data Dosen
    </h5>
    <div>
      <a href="{{ url('/dosen/export-pdf') }}" class="btn btn-danger btn-sm me-1" title="Export PDF" target="_blank">
            <i class="fas fa-file-pdf me-1"></i>PDF
        </a>
        {{-- Tombol Export Excel --}}
        <a href="{{ route('dosen.export-excel') }}" class="btn btn-success btn-sm me-1" title="Export Excel">
            <i class="fas fa-file-excel me-1"></i>Excel
        </a>
        {{-- Tombol Tambah --}}
        <a href="{{ route('dosen.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i>Tambah Dosen
        </a>
    </div>
</div>
    <div class="card-body">
        {{-- ==========================================
             FORM PENCARIAN & FILTER
        ========================================== --}}
        <form action="{{ route('dosen.index') }}" method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari dosen..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search') || request('jenis_kelamin') || request('pendidikan'))
                            <a href="{{ route('dosen.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-3">
                    <select name="jenis_kelamin" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <select name="pendidikan" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Pendidikan</option>
                        @foreach($pendidikanList ?? [] as $pendidikan)
                            <option value="{{ $pendidikan }}" {{ request('pendidikan') == $pendidikan ? 'selected' : '' }}>
                                {{ $pendidikan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-1">
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- ==========================================
             INFO PENCARIAN
        ========================================== --}}
        @if(request('search') || request('jenis_kelamin') || request('pendidikan'))
            <div class="alert alert-info alert-dismissible fade show">
                <i class="fas fa-info-circle me-2"></i>
                Menampilkan hasil pencarian: 
                @if(request('search'))
                    <strong>"{{ request('search') }}"</strong>
                @endif
                @if(request('jenis_kelamin'))
                    {{ request('search') ? ' | ' : '' }}
                    Jenis Kelamin: <strong>{{ request('jenis_kelamin') == 'L' ? 'Laki-laki' : 'Perempuan' }}</strong>
                @endif
                @if(request('pendidikan'))
                    {{ request('search') || request('jenis_kelamin') ? ' | ' : '' }}
                    Pendidikan: <strong>{{ request('pendidikan') }}</strong>
                @endif
                <span class="badge bg-primary ms-2">{{ $dosens->total() }} data ditemukan</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ==========================================
             TABLE DATA
        ========================================== --}}
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>
                            <a href="{{ route('dosen.index', array_merge(request()->all(), ['sort_by' => 'kode_dosen', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" 
                               class="text-white text-decoration-none">
                                Kode Dosen
                                @if(request('sort_by') == 'kode_dosen')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('dosen.index', array_merge(request()->all(), ['sort_by' => 'nama_dosen', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" 
                               class="text-white text-decoration-none">
                                Nama
                                @if(request('sort_by') == 'nama_dosen')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>NIDN</th>
                        <th>Email</th>
                        <th>Jenis Kelamin</th>
                        <th>Pendidikan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dosens as $dosen)
                    <tr>
                        <td>{{ $loop->iteration + ($dosens->currentPage() - 1) * $dosens->perPage() }}</td>
                        <td><span class="badge bg-secondary">{{ $dosen->kode_dosen }}</span></td>
                        <td>{{ $dosen->nama_dosen }}</td>
                        <td>{{ $dosen->nidn }}</td>
                        <td>{{ $dosen->email }}</td>
                        <td>
                            <span class="badge bg-{{ $dosen->jenis_kelamin == 'L' ? 'info' : 'warning' }}">
                                {{ $dosen->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ $dosen->pendidikan_terakhir ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('dosen.show', $dosen) }}" class="btn btn-info" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dosen.edit', $dosen) }}" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('dosen.destroy', $dosen) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Yakin ingin menghapus data dosen ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                            <p class="text-muted mb-0">Tidak ada data dosen</p>
                            @if(request('search') || request('jenis_kelamin') || request('pendidikan'))
                                <a href="{{ route('dosen.index') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-times me-1"></i>Hapus Filter
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ==========================================
             PAGINATION
        ========================================== --}}
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">
                    Menampilkan {{ $dosens->firstItem() ?? 0 }} - {{ $dosens->lastItem() ?? 0 }} 
                    dari {{ $dosens->total() }} data
                </small>
            </div>
            <div>
                {{ $dosens->links() }}
            </div>
        </div>
    </div>
</div>
@endsection