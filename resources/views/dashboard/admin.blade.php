@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    /* ==========================================
       ANIMASI & TRANSISI
    ========================================== */
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        position: relative;
        text-decoration: none;
        color: white;
        display: block;
    }
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        color: white;
        text-decoration: none;
    }
    .stat-card .stat-icon {
        font-size: 3rem;
        opacity: 0.2;
        position: absolute;
        right: 15px;
        bottom: 10px;
        transition: all 0.3s ease;
    }
    .stat-card:hover .stat-icon {
        opacity: 0.4;
        transform: scale(1.1) rotate(-5deg);
    }
    .stat-card .card-body {
        padding: 1.5rem;
        position: relative;
        z-index: 1;
    }
    .stat-card .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0;
        line-height: 1.2;
    }
    .stat-card .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-card .stat-sub {
        font-size: 0.75rem;
        opacity: 0.8;
    }
    .stat-card .stat-link {
        color: rgba(255,255,255,0.7);
        font-size: 0.8rem;
        transition: all 0.3s;
    }
    .stat-card .stat-link:hover {
        color: white;
        text-decoration: underline;
    }

    /* ==========================================
       QUICK ACTION CARDS
    ========================================== */
    .quick-action-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        text-decoration: none;
        color: #1a1a2e;
        position: relative;
        overflow: hidden;
    }
    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        color: #1a1a2e;
        text-decoration: none;
    }
    .quick-action-card .action-icon {
        font-size: 2rem;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 0.75rem;
        transition: all 0.3s;
    }
    .quick-action-card:hover .action-icon {
        transform: scale(1.1);
    }
    .quick-action-card .action-title {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }
    .quick-action-card .action-desc {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0;
    }
    .quick-action-card .action-arrow {
        opacity: 0;
        transition: all 0.3s;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
    }
    .quick-action-card:hover .action-arrow {
        opacity: 1;
        right: 20px;
    }

    /* ==========================================
       TABLE MODERN
    ========================================== */
    .table-modern {
        border-radius: 12px;
        overflow: hidden;
    }
    .table-modern thead {
        background: linear-gradient(135deg, #1a1a2e, #0f3460);
        color: white;
    }
    .table-modern thead th {
        padding: 0.75rem 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border: none;
    }
    .table-modern tbody td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }
    .table-modern tbody tr {
        transition: all 0.2s ease;
    }
    .table-modern tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* ==========================================
       SECTION HEADER
    ========================================== */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    .section-header h5 {
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    .section-header .badge-header {
        background: #0d6efd;
        color: white;
        padding: 0.3rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */
    @media (max-width: 768px) {
        .stat-card .stat-number {
            font-size: 1.8rem;
        }
        .quick-action-card .action-icon {
            width: 45px;
            height: 45px;
            font-size: 1.5rem;
        }
        .section-header {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
{{-- ==========================================
    1. HEADER
========================================== --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="fas fa-tachometer-alt text-primary me-2"></i>
                    Dashboard Administrator
                </h4>
                <p class="text-muted mb-0">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ date('l, d F Y') }}
                </p>
            </div>
            <div class="mt-2 mt-sm-0">
                <span class="badge bg-success p-2">
                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                    Sistem Aktif
                </span>
            </div>
        </div>
        <hr class="mt-2">
    </div>
</div>

{{-- ==========================================
    2. STATISTIK CARDS (DENGAN LINK CRUD)
========================================== --}}
<div class="row mb-4">
    {{-- Total Mahasiswa --}}
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('mahasiswa.index') }}" class="stat-card bg-primary">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h6 class="stat-label">Mahasiswa</h6>
                <h2 class="stat-number">{{ $stats['total_mahasiswa'] ?? 0 }}</h2>
                <small class="stat-sub">
                    <i class="fas fa-users me-1"></i>
                    Total Mahasiswa
                </small>
                <br>
                <small class="stat-link">
                    <i class="fas fa-arrow-right me-1"></i>Kelola
                </small>
            </div>
        </a>
    </div>

    {{-- Total Dosen --}}
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('dosen.index') }}" class="stat-card bg-success">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h6 class="stat-label">Dosen</h6>
                <h2 class="stat-number">{{ $stats['total_dosen'] ?? 0 }}</h2>
                <small class="stat-sub">
                    <i class="fas fa-users me-1"></i>
                    Total Dosen
                </small>
                <br>
                <small class="stat-link">
                    <i class="fas fa-arrow-right me-1"></i>Kelola
                </small>
            </div>
        </a>
    </div>

    {{-- Total Mata Kuliah --}}
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('matakuliah.index') }}" class="stat-card bg-info">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h6 class="stat-label">Mata Kuliah</h6>
                <h2 class="stat-number">{{ $stats['total_mata_kuliah'] ?? 0 }}</h2>
                <small class="stat-sub">
                    <i class="fas fa-list me-1"></i>
                    Total Mata Kuliah
                </small>
                <br>
                <small class="stat-link">
                    <i class="fas fa-arrow-right me-1"></i>Kelola
                </small>
            </div>
        </a>
    </div>

    {{-- Total Jadwal Aktif --}}
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('jadwal.index') }}" class="stat-card bg-warning">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h6 class="stat-label">Jadwal Aktif</h6>
                <h2 class="stat-number">{{ $stats['total_jadwal'] ?? 0 }}</h2>
                <small class="stat-sub">
                    <i class="fas fa-clock me-1"></i>
                    Jadwal Aktif
                </small>
                <br>
                <small class="stat-link">
                    <i class="fas fa-arrow-right me-1"></i>Kelola
                </small>
            </div>
        </a>
    </div>

    {{-- Total KRS Disetujui --}}
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('krs.index') }}" class="stat-card bg-danger">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h6 class="stat-label">KRS Disetujui</h6>
                <h2 class="stat-number">{{ $stats['total_krs'] ?? 0 }}</h2>
                <small class="stat-sub">
                    <i class="fas fa-check-circle me-1"></i>
                    Total KRS
                </small>
                <br>
                <small class="stat-link">
                    <i class="fas fa-arrow-right me-1"></i>Kelola
                </small>
            </div>
        </a>
    </div>

    {{-- Total User --}}
    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-3">
        <a href="#" class="stat-card bg-secondary">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h6 class="stat-label">Total User</h6>
                <h2 class="stat-number">{{ $stats['total_user'] ?? 0 }}</h2>
                <small class="stat-sub">
                    <i class="fas fa-user me-1"></i>
                    User Terdaftar
                </small>
                <br>
                <small class="stat-link">
                    <i class="fas fa-arrow-right me-1"></i>Kelola
                </small>
            </div>
        </a>
    </div>
</div>

{{-- ==========================================
    3. QUICK ACTION (AKSES CEPAT CRUD)
========================================== --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="section-header">
            <h5>
                <i class="fas fa-bolt text-primary me-2"></i>
                Akses Cepat Manajemen Data
            </h5>
            <span class="badge-header">
                <i class="fas fa-arrow-right me-1"></i>Pilih menu untuk mengelola data
            </span>
        </div>
    </div>

    {{-- Quick Action: Mahasiswa --}}
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('mahasiswa.index') }}" class="card quick-action-card">
            <div class="card-body text-center">
                <div class="action-icon bg-primary bg-opacity-10 text-primary mx-auto">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h6 class="action-title">Mahasiswa</h6>
                <p class="action-desc">Tambah, edit, hapus</p>
                <div class="action-arrow text-primary">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Quick Action: Dosen --}}
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('dosen.index') }}" class="card quick-action-card">
            <div class="card-body text-center">
                <div class="action-icon bg-success bg-opacity-10 text-success mx-auto">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h6 class="action-title">Dosen</h6>
                <p class="action-desc">Tambah, edit, hapus</p>
                <div class="action-arrow text-success">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Quick Action: Mata Kuliah --}}
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('matakuliah.index') }}" class="card quick-action-card">
            <div class="card-body text-center">
                <div class="action-icon bg-info bg-opacity-10 text-info mx-auto">
                    <i class="fas fa-book"></i>
                </div>
                <h6 class="action-title">Mata Kuliah</h6>
                <p class="action-desc">Tambah, edit, hapus</p>
                <div class="action-arrow text-info">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Quick Action: Jadwal --}}
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('jadwal.index') }}" class="card quick-action-card">
            <div class="card-body text-center">
                <div class="action-icon bg-warning bg-opacity-10 text-warning mx-auto">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h6 class="action-title">Jadwal</h6>
                <p class="action-desc">Tambah, edit, hapus</p>
                <div class="action-arrow text-warning">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Quick Action: KRS --}}
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('krs.index') }}" class="card quick-action-card">
            <div class="card-body text-center">
                <div class="action-icon bg-danger bg-opacity-10 text-danger mx-auto">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h6 class="action-title">KRS</h6>
                <p class="action-desc">Kelola KRS Mahasiswa</p>
                <div class="action-arrow text-danger">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- Quick Action: Tambah Cepat --}}
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <a href="{{ route('mahasiswa.create') }}" class="card quick-action-card">
            <div class="card-body text-center">
                <div class="action-icon bg-primary bg-opacity-10 text-primary mx-auto">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h6 class="action-title">Tambah Data</h6>
                <p class="action-desc">Tambah data baru</p>
                <div class="action-arrow text-primary">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- ==========================================
    4. GRAFIK & STATISTIK
========================================== --}}
<div class="row mb-4">
    {{-- Chart Mahasiswa per Tahun --}}
    <div class="col-lg-8 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar text-primary me-2"></i>
                    Statistik Mahasiswa per Tahun
                </h5>
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-arrow-right me-1"></i>Detail
                </a>
            </div>
            <div class="card-body">
                <canvas id="mahasiswaChart" height="200"></canvas>
            </div>
        </div>
    </div>

  
</div>

{{-- ==========================================
    5. DATA TERBARU DENGAN LINK SHOW
========================================== --}}
<div class="row">
    {{-- Mahasiswa Terbaru --}}
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus text-primary me-2"></i>
                    Mahasiswa Terbaru
                </h5>
                <div>
                    <a href="{{ route('mahasiswa.create') }}" class="btn btn-sm btn-success me-1">
                        <i class="fas fa-plus me-1"></i>Tambah
                    </a>
                    <a href="{{ route('mahasiswa.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-right me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if(isset($mahasiswaTerbaru) && $mahasiswaTerbaru->isNotEmpty())
                    <div class="list-group list-group-flush">
                        @foreach($mahasiswaTerbaru as $mhs)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $mhs->nama_mahasiswa }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-id-card me-1"></i>
                                        {{ $mhs->npm }}
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $mhs->tahun_masuk }}
                                    </small>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $mhs->jenis_kelamin == 'L' ? 'info' : 'warning' }} me-2">
                                        {{ $mhs->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('mahasiswa.show', $mhs) }}" class="btn btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('mahasiswa.edit', $mhs) }}" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('mahasiswa.destroy', $mhs) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada data mahasiswa</p>
                        <a href="{{ route('mahasiswa.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i>Tambah Mahasiswa
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Dosen Terbaru --}}
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chalkboard-teacher text-success me-2"></i>
                    Dosen Terbaru
                </h5>
                <div>
                    <a href="{{ route('dosen.create') }}" class="btn btn-sm btn-success me-1">
                        <i class="fas fa-plus me-1"></i>Tambah
                    </a>
                    <a href="{{ route('dosen.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-right me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if(isset($dosenTerbaru) && $dosenTerbaru->isNotEmpty())
                    <div class="list-group list-group-flush">
                        @foreach($dosenTerbaru as $dosen)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $dosen->nama_dosen }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-id-card me-1"></i>
                                        {{ $dosen->kode_dosen }}
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ $dosen->email }}
                                    </small>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $dosen->jenis_kelamin == 'L' ? 'info' : 'warning' }} me-2">
                                        {{ $dosen->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('dosen.show', $dosen) }}" class="btn btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('dosen.edit', $dosen) }}" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dosen.destroy', $dosen) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada data dosen</p>
                        <a href="{{ route('dosen.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i>Tambah Dosen
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ==========================================
    6. JADWAL & MATA KULIAH TERBARU
========================================== --}}
<div class="row">
    {{-- Jadwal Terbaru --}}
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clock text-warning me-2"></i>
                    Jadwal Terbaru
                </h5>
                <div>
                    <a href="{{ route('jadwal.create') }}" class="btn btn-sm btn-success me-1">
                        <i class="fas fa-plus me-1"></i>Tambah
                    </a>
                    <a href="{{ route('jadwal.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-right me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if(isset($jadwalTerbaru) && $jadwalTerbaru->isNotEmpty())
                    <div class="list-group list-group-flush">
                        @foreach($jadwalTerbaru as $jdw)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $jdw->mataKuliah->nama_mk ?? 'N/A' }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-chalkboard-teacher me-1"></i>
                                        {{ $jdw->dosen->nama_dosen ?? 'N/A' }}
                                        <br>
                                        <i class="fas fa-calendar-day me-1"></i>
                                        {{ $jdw->hari }} {{ $jdw->jam_mulai }} - {{ $jdw->jam_selesai }}
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-door-open me-1"></i>
                                        Kelas: {{ $jdw->kelas }}
                                    </small>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $jdw->status == 'Aktif' ? 'success' : 'secondary' }} me-2">
                                        {{ $jdw->status }}
                                    </span>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('jadwal.show', $jdw) }}" class="btn btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('jadwal.edit', $jdw) }}" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('jadwal.destroy', $jdw) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada data jadwal</p>
                        <a href="{{ route('jadwal.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i>Tambah Jadwal
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Mata Kuliah Terbaru --}}
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-book text-info me-2"></i>
                    Mata Kuliah Terbaru
                </h5>
                <div>
                    <a href="{{ route('matakuliah.create') }}" class="btn btn-sm btn-success me-1">
                        <i class="fas fa-plus me-1"></i>Tambah
                    </a>
                    <a href="{{ route('matakuliah.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-right me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if(isset($matakuliahTerbaru) && $matakuliahTerbaru->isNotEmpty())
                    <div class="list-group list-group-flush">
                        @foreach($matakuliahTerbaru as $mk)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $mk->nama_mk }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-code me-1"></i>
                                        {{ $mk->kode_mk }}
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-hashtag me-1"></i>
                                        SKS: {{ $mk->sks }}
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-layer-group me-1"></i>
                                        Semester: {{ $mk->semester }}
                                    </small>
                                </div>
                                <div>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('matakuliah.show', $mk) }}" class="btn btn-info" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('matakuliah.edit', $mk) }}" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('matakuliah.destroy', $mk) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-book-open fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada data mata kuliah</p>
                        <a href="{{ route('matakuliah.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i>Tambah Mata Kuliah
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // CHART MAHASISWA PER TAHUN
    // ==========================================
    @if(isset($chartData['mahasiswa_per_tahun']) && $chartData['mahasiswa_per_tahun']->isNotEmpty())
    const ctx1 = document.getElementById('mahasiswaChart').getContext('2d');
    const mahasiswaData = @json($chartData['mahasiswa_per_tahun']);
    
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: mahasiswaData.map(item => item.tahun),
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: mahasiswaData.map(item => item.total),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    @endif

    // ==========================================
    // CHART KRS PER STATUS
    // ==========================================
    @if(isset($chartData['krs_per_status']) && $chartData['krs_per_status']->isNotEmpty())
    const ctx2 = document.getElementById('krsChart').getContext('2d');
    const krsStatusData = @json($chartData['krs_per_status']);
    
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: krsStatusData.map(item => item.status),
            datasets: [{
                data: krsStatusData.map(item => item.total),
                backgroundColor: [
                    '#FF6384', // Draft
                    '#36A2EB', // Disetujui
                    '#FFCE56'  // Batal
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 10,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            },
            cutout: '65%'
        }
    });
    @endif
});
</script>
@endpush