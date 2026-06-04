@extends('layouts.app')

@section('page-title', 'Dashboard Saya')
@section('page-breadcrumb', 'Siswa · ' . $siswa->nama)

@section('sidebar-menu')
    <span class="nav-section-label">Menu Saya</span>
    <a href="{{ route('siswa.dashboard') }}" class="sidebar-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
        <span class="link-icon"><i class="bi bi-speedometer2"></i></span> Dashboard
    </a>
    <a href="{{ route('siswa.nilai-saya') }}" class="sidebar-link {{ request()->routeIs('siswa.nilai-saya') ? 'active' : '' }}">
        <span class="link-icon"><i class="bi bi-journal-text"></i></span> Nilai Saya
    </a>
@endsection

@section('content')

{{-- ================================================================
     HERO WELCOME BANNER
     Colorful gradient card with student identity info
================================================================ --}}
<div class="siswa-hero-banner mb-4">
    {{-- Decorative blobs --}}
    <div class="hero-blob hero-blob-1"></div>
    <div class="hero-blob hero-blob-2"></div>

    <div class="hero-inner">
        {{-- Avatar circle with initials --}}
        <div class="hero-avatar">
            <span class="hero-avatar-initials">
                {{ strtoupper(substr($siswa->nama, 0, 1)) }}{{ strtoupper(substr(strstr($siswa->nama, ' ') ?: '_', 1, 1)) }}
            </span>
        </div>

        <div class="hero-text">
            <p class="hero-greeting mb-0">Selamat datang kembali 👋</p>
            <h2 class="hero-name mb-1">{{ $siswa->nama }}</h2>
            <div class="hero-meta d-flex flex-wrap gap-3 mt-1">
                <span class="hero-meta-item">
                    <i class="bi bi-person-badge me-1"></i>
                    NIS: <strong>{{ $siswa->nis }}</strong>
                </span>
                <span class="hero-meta-item">
                    <i class="bi bi-building me-1"></i>
                    Kelas: <strong>{{ $siswa->kelas }}</strong>
                </span>
            </div>
        </div>

        {{-- Overall lulus status pill --}}
        <div class="hero-status ms-auto">
            @if($lulusSemua)
                <div class="hero-status-pill hero-status-pill--lulus">
                    <i class="bi bi-patch-check-fill me-2"></i>
                    <span>Lulus Semua</span>
                </div>
            @else
                <div class="hero-status-pill hero-status-pill--proses">
                    <i class="bi bi-hourglass-split me-2"></i>
                    <span>Dalam Proses</span>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ================================================================
     STAT CARDS ROW
================================================================ --}}
<div class="row g-3 mb-4">

    {{-- Mata Pelajaran --}}
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card--indigo">
            <div class="stat-card-icon-wrap">
                <i class="bi bi-book-half"></i>
            </div>
            <div class="stat-card-body">
                <span class="stat-card-label">Mata Pelajaran</span>
                <span class="stat-card-value">{{ $jumlahNilai }}</span>
            </div>
            <div class="stat-card-bg-icon"><i class="bi bi-book-half"></i></div>
        </div>
    </div>

    {{-- Lulus --}}
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card--emerald">
            <div class="stat-card-icon-wrap">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-card-body">
                <span class="stat-card-label">Lulus</span>
                <span class="stat-card-value">{{ $jumlahLulus }}</span>
            </div>
            <div class="stat-card-bg-icon"><i class="bi bi-check-circle-fill"></i></div>
        </div>
    </div>

    {{-- Belum Lulus --}}
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card--rose">
            <div class="stat-card-icon-wrap">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div class="stat-card-body">
                <span class="stat-card-label">Belum Lulus</span>
                <span class="stat-card-value">{{ $jumlahNilai - $jumlahLulus }}</span>
            </div>
            <div class="stat-card-bg-icon"><i class="bi bi-x-circle-fill"></i></div>
        </div>
    </div>

    {{-- Rata-rata --}}
    <div class="col-6 col-md-3">
        @php
            $rataClass = $rataRata >= 75 ? 'stat-card--cyan' : ($rataRata >= 60 ? 'stat-card--amber' : 'stat-card--rose');
        @endphp
        <div class="stat-card {{ $rataClass }}">
            <div class="stat-card-icon-wrap">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="stat-card-body">
                <span class="stat-card-label">Rata-rata Nilai</span>
                <span class="stat-card-value">{{ number_format($rataRata, 1) }}</span>
            </div>
            <div class="stat-card-bg-icon"><i class="bi bi-graph-up-arrow"></i></div>
        </div>
    </div>

</div>

{{-- ================================================================
     OVERALL STATUS + RECENT NILAI  (2-column layout on lg+)
================================================================ --}}
<div class="row g-4">

    {{-- LEFT: Overall status card --}}
    <div class="col-lg-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="fw-semibold text-dark mb-0">
                    <i class="bi bi-award me-2 text-primary"></i>Status Kelulusan
                </h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center py-4">

                @if($lulusSemua)
                    {{-- Big LULUS animation --}}
                    <div class="status-big-icon status-big-icon--lulus mb-3">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <div class="badge-lulus fs-5 px-4 py-2 mb-3">
                        <i class="bi bi-check2-all me-1"></i> LULUS SEMUA
                    </div>
                    <p class="text-muted small mb-0">
                        Selamat! Kamu telah lulus di semua mata pelajaran.
                        Pertahankan prestasi ini!
                    </p>
                @else
                    @php
                        $belumLulus = $jumlahNilai - $jumlahLulus;
                    @endphp
                    <div class="status-big-icon status-big-icon--proses mb-3">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="badge-proses fs-5 px-4 py-2 mb-3">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        {{ $belumLulus }} Belum Lulus
                    </div>
                    <p class="text-muted small mb-0">
                        Masih ada <strong>{{ $belumLulus }}</strong> mata pelajaran yang perlu ditingkatkan.
                        Terus semangat belajar!
                    </p>
                @endif

                {{-- Progress bar: lulus / total --}}
                @if($jumlahNilai > 0)
                    <div class="w-100 mt-4">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Progress Lulus</small>
                            <small class="fw-semibold">{{ $jumlahLulus }}/{{ $jumlahNilai }}</small>
                        </div>
                        <div class="progress" style="height:10px; border-radius:99px;">
                            @php $pct = $jumlahNilai > 0 ? round(($jumlahLulus / $jumlahNilai) * 100) : 0; @endphp
                            <div class="progress-bar {{ $lulusSemua ? 'bg-success' : 'bg-warning' }}"
                                 role="progressbar"
                                 style="width: {{ $pct }}%; border-radius:99px;"
                                 aria-valuenow="{{ $pct }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted d-block mt-1">{{ $pct }}% lulus</small>
                    </div>
                @endif

                {{-- CTA to nilai detail --}}
                <a href="{{ route('siswa.nilai-saya') }}" class="btn btn-primary w-100 mt-4">
                    <i class="bi bi-journal-text me-1"></i> Lihat Nilai Saya
                </a>

            </div>
        </div>
    </div>

    {{-- RIGHT: Recent nilai table --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between">
                <h6 class="fw-semibold text-dark mb-0">
                    <i class="bi bi-table me-2 text-primary"></i>Rekap Nilai
                </h6>
                <a href="{{ route('siswa.nilai-saya') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="card-body p-0">
                @if($siswa->nilais->isEmpty())
                    {{-- Empty state --}}
                    <div class="empty-state py-5">
                        <div class="empty-state-icon">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <h6 class="empty-state-title">Belum Ada Nilai</h6>
                        <p class="empty-state-desc">Nilai kamu akan muncul di sini setelah guru memasukkan data.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3 text-uppercase fs-xs text-muted fw-semibold">Mata Pelajaran</th>
                                    <th class="text-center py-3 text-uppercase fs-xs text-muted fw-semibold">Tugas</th>
                                    <th class="text-center py-3 text-uppercase fs-xs text-muted fw-semibold">UTS</th>
                                    <th class="text-center py-3 text-uppercase fs-xs text-muted fw-semibold">UAS</th>
                                    <th class="text-center py-3 text-uppercase fs-xs text-muted fw-semibold">Nilai Akhir</th>
                                    <th class="text-center py-3 text-uppercase fs-xs text-muted fw-semibold">Grade</th>
                                    <th class="text-center pe-4 py-3 text-uppercase fs-xs text-muted fw-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswa->nilais as $nilai)
                                    @php
                                        $na       = $nilai->nilai_akhir;
                                        $grade    = konversiNilaiKeHuruf($na);
                                        $lulus    = $na >= 75;

                                        // Color class for nilai_akhir cell
                                        if ($na >= 85)      $naColor = 'text-success fw-bold';
                                        elseif ($na >= 75)  $naColor = 'text-primary fw-bold';
                                        elseif ($na >= 60)  $naColor = 'text-warning fw-bold';
                                        else                $naColor = 'text-danger fw-bold';
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="mapel-dot" style="background: {{ $lulus ? 'var(--clr-success)' : 'var(--clr-danger)' }};"></div>
                                                <span class="fw-medium text-dark">{{ $nilai->mataPelajaran->nama }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-muted">{{ $nilai->nilai_tugas ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-muted">{{ $nilai->nilai_uts ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-muted">{{ $nilai->nilai_uas ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="{{ $naColor }} fs-6">{{ number_format($na, 1) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge-grade badge-grade--{{ strtolower($grade) }}">{{ $grade }}</span>
                                        </td>
                                        <td class="text-center pe-4">
                                            @if($lulus)
                                                <span class="badge-lulus">Lulus</span>
                                            @else
                                                <span class="badge-tidak-lulus">Tidak Lulus</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if(!$siswa->nilais->isEmpty())
                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center px-4 py-3">
                    <span class="text-muted small">
                        Menampilkan {{ $siswa->nilais->count() }} mata pelajaran
                    </span>
                    <a href="{{ route('siswa.nilai-saya') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-journal-text me-1"></i> Detail Nilai Saya
                    </a>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
/* =============================================
   SISWA DASHBOARD — LOCAL STYLES
   (Extend global design system)
============================================= */

/* ---- Hero Banner ---- */
.siswa-hero-banner {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 40%, #06b6d4 100%);
    border-radius: 1.25rem;
    padding: 2rem 2rem;
    color: #fff;
    box-shadow: 0 8px 32px rgba(99,102,241,0.25);
}

.hero-blob {
    position: absolute;
    border-radius: 50%;
    opacity: 0.15;
    pointer-events: none;
}
.hero-blob-1 {
    width: 280px; height: 280px;
    background: #fff;
    top: -80px; right: -60px;
}
.hero-blob-2 {
    width: 160px; height: 160px;
    background: #06b6d4;
    bottom: -60px; left: 30%;
}

.hero-inner {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.25rem;
}

/* Avatar circle */
.hero-avatar {
    flex-shrink: 0;
    width: 72px; height: 72px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    border: 3px solid rgba(255,255,255,0.5);
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(4px);
}
.hero-avatar-initials {
    font-size: 1.6rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: 0.02em;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Text */
.hero-greeting {
    font-size: 0.85rem;
    opacity: 0.85;
    font-weight: 400;
}
.hero-name {
    font-size: 1.55rem;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    letter-spacing: -0.01em;
}
.hero-meta-item {
    font-size: 0.875rem;
    background: rgba(255,255,255,0.15);
    border-radius: 99px;
    padding: 3px 12px;
    backdrop-filter: blur(4px);
}

/* Status pill */
.hero-status-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1.25rem;
    border-radius: 99px;
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 0.02em;
}
.hero-status-pill--lulus {
    background: rgba(16,185,129,0.25);
    border: 1.5px solid rgba(16,185,129,0.5);
    color: #d1fae5;
}
.hero-status-pill--proses {
    background: rgba(245,158,11,0.25);
    border: 1.5px solid rgba(245,158,11,0.5);
    color: #fef3c7;
}

/* ---- Stat Cards ---- */
.stat-card {
    position: relative;
    overflow: hidden;
    border-radius: 1rem;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    color: #fff;
    box-shadow: 0 4px 18px rgba(0,0,0,0.10);
    transition: transform 0.18s ease, box-shadow 0.18s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.15);
}

.stat-card--indigo  { background: linear-gradient(135deg, #6366f1, #4f46e5); }
.stat-card--emerald { background: linear-gradient(135deg, #10b981, #059669); }
.stat-card--rose    { background: linear-gradient(135deg, #ef4444, #dc2626); }
.stat-card--cyan    { background: linear-gradient(135deg, #06b6d4, #0891b2); }
.stat-card--amber   { background: linear-gradient(135deg, #f59e0b, #d97706); }

.stat-card-icon-wrap {
    flex-shrink: 0;
    width: 48px; height: 48px;
    border-radius: 0.75rem;
    background: rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
}
.stat-card-body {
    display: flex;
    flex-direction: column;
    z-index: 1;
}
.stat-card-label {
    font-size: 0.78rem;
    font-weight: 500;
    opacity: 0.85;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.stat-card-value {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1.1;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.stat-card-bg-icon {
    position: absolute;
    right: -10px; bottom: -10px;
    font-size: 5rem;
    opacity: 0.08;
    pointer-events: none;
}

/* ---- Status Big Icon ---- */
.status-big-icon {
    width: 96px; height: 96px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 3.5rem;
}
.status-big-icon--lulus {
    background: rgba(16,185,129,0.1);
    color: var(--clr-success);
    animation: pulse-lulus 2.5s ease-in-out infinite;
}
.status-big-icon--proses {
    background: rgba(245,158,11,0.1);
    color: var(--clr-warning);
}

@keyframes pulse-lulus {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.25); }
    50%       { box-shadow: 0 0 0 14px rgba(16,185,129,0); }
}

/* Badge proses (complement to .badge-lulus) */
.badge-proses {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: rgba(245,158,11,0.12);
    color: #d97706;
    border: 1.5px solid rgba(245,158,11,0.3);
    border-radius: 99px;
    font-weight: 600;
    font-size: 0.78rem;
    letter-spacing: 0.05em;
    padding: 0.3rem 0.9rem;
}

/* ---- Table helpers ---- */
.fs-xs { font-size: 0.72rem; }
.mapel-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ---- Empty state ---- */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}
.empty-state-icon {
    font-size: 3rem;
    color: #d1d5db;
    margin-bottom: 0.75rem;
}
.empty-state-title {
    font-size: 1rem;
    font-weight: 600;
    color: #6b7280;
}
.empty-state-desc {
    font-size: 0.85rem;
    color: #9ca3af;
    max-width: 280px;
    margin: 0 auto;
}
</style>
@endpush
