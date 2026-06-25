@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

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
    }
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
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

    /* ==========================================
       CARD JADWAL
    ========================================== */
    .schedule-card {
        border-left: 5px solid #0d6efd;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .schedule-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    }
    .schedule-card.morning {
        border-left-color: #ffc107;
        background: linear-gradient(135deg, #fff8e1 0%, #ffffff 100%);
    }
    .schedule-card.afternoon {
        border-left-color: #198754;
        background: linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%);
    }
    .schedule-card.evening {
        border-left-color: #dc3545;
        background: linear-gradient(135deg, #fce4ec 0%, #ffffff 100%);
    }
    .schedule-card .card-title {
        font-weight: 600;
        color: #1a1a2e;
    }
    .schedule-card .badge-time {
        font-size: 0.7rem;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
    }

    /* ==========================================
       CARD PROFILE
    ========================================== */
    .profile-card {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        border: none;
        border-radius: 15px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .profile-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .profile-card .profile-icon {
        font-size: 4rem;
        opacity: 0.15;
        position: absolute;
        right: 20px;
        bottom: 10px;
    }
    .profile-card .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
    }
    .profile-card .profile-detail {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    .profile-card .profile-badge {
        background: rgba(255,255,255,0.15);
        padding: 0.3rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    /* ==========================================
       TABLE KRS
    ========================================== */
    .table-krs {
        border-radius: 12px;
        overflow: hidden;
    }
    .table-krs thead {
        background: linear-gradient(135deg, #1a1a2e, #0f3460);
        color: white;
    }
    .table-krs thead th {
        padding: 0.75rem 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: none;
    }
    .table-krs tbody td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }
    .table-krs tbody tr {
        transition: all 0.2s ease;
    }
    .table-krs tbody tr:hover {
        background-color: #f8f9fa;
    }
    .table-krs tfoot {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    /* ==========================================
       SECTION HEADER
    ========================================== */
    .section-header {
        padding: 0.5rem 0;
        margin-bottom: 1.5rem;
        border-bottom: 3px solid #0d6efd;
    }
    .section-header h5 {
        font-weight: 700;
        color: #1a1a2e;
    }
    .section-header .badge-header {
        background: #0d6efd;
        color: white;
        padding: 0.3rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    /* ==========================================
       EMPTY STATE
    ========================================== */
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }
    .empty-state .empty-icon {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    .empty-state .empty-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #495057;
    }
    .empty-state .empty-text {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */
    @media (max-width: 768px) {
        .stat-card .stat-number {
            font-size: 1.8rem;
        }
        .profile-card .profile-name {
            font-size: 1.2rem;
        }
        .profile-card .profile-detail {
            font-size: 0.8rem;
        }
        .table-krs {
            font-size: 0.85rem;
        }
        .table-krs thead th,
        .table-krs tbody td {
            padding: 0.5rem 0.6rem;
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
                    Dashboard Mahasiswa
                </h4>
                <p class="text-muted mb-0">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ date('l, d F Y') }}
                </p>
            </div>
            <div class="mt-2 mt-sm-0">
                <span class="badge bg-success p-2">
                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                    Aktif
                </span>
            </div>
        </div>
        <hr class="mt-2">
    </div>
</div>

{{-- ==========================================
    2. PROFILE CARD
========================================== --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card profile-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                    <i class="fas fa-user-graduate fa-2x"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="profile-name mb-1">
                                    {{ $mahasiswa->nama_mahasiswa ?? 'N/A' }}
                                </h5>
                                <div class="profile-detail mb-1">
                                    <i class="fas fa-id-card me-1"></i>
                                    NPM: {{ $infoAkademik['npm'] ?? '-' }}
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-flag me-1"></i>
                                    Semester: {{ $infoAkademik['semester_aktif'] ?? '-' }}
                                </div>
                                <div class="profile-detail">
                                    <i class="fas fa-envelope me-1"></i> {{ $mahasiswa->email ?? '-' }}
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-phone me-1"></i> {{ $mahasiswa->no_telepon ?? '-' }}
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-calendar me-1"></i>
                                    Angkatan {{ $mahasiswa->tahun_masuk ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 text-md-end mt-3 mt-md-0">
                        <span class="profile-badge me-2">
                            <i class="fas fa-graduation-cap me-1"></i>
                            Mahasiswa Aktif
                        </span>
                        <span class="profile-badge">
                            <i class="fas fa-clock me-1"></i>
                            Semester {{ $infoAkademik['semester_aktif'] ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="profile-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>
</div>

{{-- ==========================================
    3. STATISTIK CARDS
========================================== --}}
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h6 class="stat-label">Total KRS</h6>
                <h2 class="stat-number">{{ $stats['total_krs'] ?? 0 }}</h2>
                <small class="stat-sub">
                    <i class="fas fa-check-circle me-1"></i>
                    Mata kuliah diambil
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h6 class="stat-label">Total SKS</h6>
                <h2 class="stat-number">{{ $stats['total_sks'] ?? 0 }}</h2>
                <small class="stat-sub">
                    <i class="fas fa-book me-1"></i>
                    SKS semester ini
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <h6 class="stat-label">Rata-rata SKS</h6>
                <h2 class="stat-number">{{ $stats['rata_rata_sks'] ?? 0 }}</h2>
                <small class="stat-sub">
                    <i class="fas fa-chart-line me-1"></i>
                    Per mata kuliah
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h6 class="stat-label">Jadwal Hari Ini</h6>
                <h2 class="stat-number">{{ $jadwalHariIni->count() }}</h2>
                <small class="stat-sub">
                    <i class="fas fa-calendar-day me-1"></i>
                    Mata kuliah hari ini
                </small>
            </div>
        </div>
    </div>
</div>

{{-- ==========================================
    4. JADWAL HARI INI
========================================== --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-day me-2"></i>
                    Jadwal Hari Ini
                </h5>
                <span class="badge bg-light text-dark">
                    {{ date('l, d F Y') }}
                </span>
            </div>
            <div class="card-body">
                @if($jadwalHariIni->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-smile-wink"></i>
                        </div>
                        <h6 class="empty-title">Tidak Ada Jadwal Hari Ini</h6>
                        <p class="empty-text">Selamat beristirahat! 🎉</p>
                    </div>
                @else
                    <div class="row">
                        @foreach($jadwalHariIni as $item)
                        <div class="col-lg-6 col-md-12 mb-3">
                            <div class="card schedule-card 
                                @php
                                    $jam = strtotime($item->jadwal->jam_mulai);
                                    if($jam < strtotime('12:00')) echo 'morning';
                                    elseif($jam < strtotime('17:00')) echo 'afternoon';
                                    else echo 'evening';
                                @endphp
                            ">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="card-title mb-1">
                                                <i class="fas fa-book-open me-1"></i>
                                                {{ $item->jadwal->mataKuliah->nama_mk ?? 'N/A' }}
                                            </h6>
                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                <span class="badge bg-secondary badge-time">
                                                    <i class="fas fa-code me-1"></i>
                                                    {{ $item->jadwal->mataKuliah->kode_mk ?? '-' }}
                                                </span>
                                                <span class="badge bg-primary badge-time">
                                                    <i class="fas fa-hashtag me-1"></i>
                                                    SKS: {{ $item->jadwal->mataKuliah->sks ?? 0 }}
                                                </span>
                                            </div>
                                            <p class="card-text small text-muted mb-1 mt-2">
                                                <i class="fas fa-chalkboard-teacher me-1"></i>
                                                <strong>Dosen:</strong> {{ $item->jadwal->dosen->nama_dosen ?? 'N/A' }}
                                            </p>
                                            <p class="card-text small text-muted mb-0">
                                                <i class="fas fa-clock me-1"></i>
                                                <strong>Waktu:</strong> {{ $item->jadwal->jam_mulai }} - {{ $item->jadwal->jam_selesai }}
                                                <span class="mx-2">|</span>
                                                <i class="fas fa-door-open me-1"></i>
                                                <strong>Ruang:</strong> {{ $item->jadwal->ruangan ?? '-' }}
                                                <span class="mx-2">|</span>
                                                <i class="fas fa-users me-1"></i>
                                                <strong>Kelas:</strong> {{ $item->jadwal->kelas }}
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Aktif
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                @php
                                                    $jamMulai = strtotime($item->jadwal->jam_mulai);
                                                    $jamSekarang = strtotime(date('H:i'));
                                                    if ($jamSekarang < $jamMulai) {
                                                        $selisih = $jamMulai - $jamSekarang;
                                                        $jam = floor($selisih / 3600);
                                                        $menit = floor(($selisih % 3600) / 60);
                                                        echo '<i class="fas fa-hourglass-half me-1"></i> ' . $jam . 'j ' . $menit . 'm lagi';
                                                    } elseif ($jamSekarang < strtotime($item->jadwal->jam_selesai)) {
                                                        echo '<i class="fas fa-play-circle me-1"></i> Sedang berlangsung';
                                                    } else {
                                                        echo '<i class="fas fa-check-double me-1"></i> Selesai';
                                                    }
                                                @endphp
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ==========================================
    5. DAFTAR KRS
========================================== --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-list text-primary me-2"></i>
                    Daftar KRS Semester Ini
                </h5>
                <div>
                    <span class="badge bg-primary me-2">
                        <i class="fas fa-book me-1"></i>
                        {{ $krs->count() }} Mata Kuliah
                    </span>
                    <a href="{{ route('krs.saya') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus me-1"></i>Ambil KRS
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($krs->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h6 class="empty-title">Belum Ada KRS</h6>
                        <p class="empty-text">Anda belum mengambil mata kuliah semester ini.</p>
                        <a href="{{ route('krs.saya') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Ambil KRS Sekarang
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-krs mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode MK</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th class="text-center">SKS</th>
                                    <th>Dosen</th>
                                    <th>Jadwal</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($krs as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $item->jadwal->mataKuliah->kode_mk ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->jadwal->mataKuliah->nama_mk ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">
                                            {{ $item->jadwal->mataKuliah->sks ?? 0 }}
                                        </span>
                                    </td>
                                    <td>{{ $item->jadwal->dosen->nama_dosen ?? '-' }}</td>
                                    <td>
                                        <small>
                                            <span class="badge bg-light text-dark">
                                                {{ $item->jadwal->hari ?? '-' }}
                                            </span>
                                            <br>
                                            <span class="text-muted">
                                                {{ $item->jadwal->jam_mulai ?? '-' }} - 
                                                {{ $item->jadwal->jam_selesai ?? '-' }}
                                            </span>
                                            <br>
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-door-open me-1"></i>
                                                {{ $item->jadwal->kelas ?? '-' }}
                                            </span>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $item->status == 'Disetujui' ? 'success' : ($item->status == 'Draft' ? 'warning' : 'danger') }} p-2">
                                            <i class="fas fa-{{ $item->status == 'Disetujui' ? 'check-circle' : ($item->status == 'Draft' ? 'clock' : 'times-circle') }} me-1"></i>
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">
                                        <i class="fas fa-calculator me-1"></i>Total SKS:
                                    </td>
                                    <td colspan="4">
                                        <span class="badge bg-success fs-6 p-2">
                                            <i class="fas fa-graduation-cap me-1"></i>
                                            {{ $total_sks ?? 0 }} SKS
                                        </span>
                                        <span class="text-muted ms-2">
                                            <small>
                                                (Maksimal 24 SKS)
                                            </small>
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh setiap 5 menit untuk update jadwal
    let refreshInterval = setInterval(function() {
        location.reload();
    }, 300000); // 5 menit

    // Hentikan auto-refresh jika user tidak aktif
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            clearInterval(refreshInterval);
        } else {
            refreshInterval = setInterval(function() {
                location.reload();
            }, 300000);
        }
    });

    // Tooltip untuk badge status
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush