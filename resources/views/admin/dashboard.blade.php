{{--
    admin/dashboard.blade.php
    Dashboard utama Admin SIMPEL — statistik + nilai terbaru
--}}
@extends('layouts.app')

@section('page-title', 'Dashboard Admin')
@section('page-breadcrumb', 'Admin · Ringkasan Sistem')

@section('sidebar-menu')
    <span class="nav-section-label">Navigasi</span>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="link-icon"><i class="bi bi-speedometer2"></i></span> Dashboard
    </a>
    <a href="{{ route('admin.siswas') }}" class="sidebar-link {{ request()->routeIs('admin.siswas*') ? 'active' : '' }}">
        <span class="link-icon"><i class="bi bi-people-fill"></i></span> Data Siswa
    </a>
    <a href="{{ route('admin.guru') }}" class="sidebar-link {{ request()->routeIs('admin.guru*') ? 'active' : '' }}">
        <span class="link-icon"><i class="bi bi-person-badge-fill"></i></span> Data Guru
    </a>
    <a href="{{ route('admin.mapels') }}" class="sidebar-link {{ request()->routeIs('admin.mapels*') ? 'active' : '' }}">
        <span class="link-icon"><i class="bi bi-book-fill"></i></span> Mata Pelajaran
    </a>

    <span class="nav-section-label" style="margin-top:0.5rem;">Laporan</span>
    <a href="{{ route('admin.laporan') }}" class="sidebar-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
        <span class="link-icon"><i class="bi bi-file-earmark-bar-graph-fill"></i></span> Laporan Nilai
    </a>
@endsection

@section('content')
<div class="container-fluid px-0">

    {{-- ── Page Header ── --}}
    <div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div>
            <h1 class="page-header-title">Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
            <p class="page-header-sub">Berikut ringkasan sistem SIMPEL hari ini.</p>
        </div>
        <a href="{{ route('admin.laporan') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-file-earmark-bar-graph"></i> Lihat Laporan
        </a>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="row g-3 mb-4">

        <div class="col-xl col-md-4 col-sm-6">
            <div class="stat-card stat-indigo">
                <div class="stat-icon-wrap">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalSiswa) }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>

        <div class="col-xl col-md-4 col-sm-6">
            <div class="stat-card stat-cyan">
                <div class="stat-icon-wrap">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalGuru) }}</div>
                <div class="stat-label">Total Guru</div>
            </div>
        </div>

        <div class="col-xl col-md-4 col-sm-6">
            <div class="stat-card stat-amber">
                <div class="stat-icon-wrap">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalMapel) }}</div>
                <div class="stat-label">Mata Pelajaran</div>
            </div>
        </div>

        <div class="col-xl col-md-4 col-sm-6">
            <div class="stat-card stat-emerald">
                <div class="stat-icon-wrap">
                    <i class="bi bi-clipboard2-data-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalNilai) }}</div>
                <div class="stat-label">Data Nilai</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-8 col-sm-12">
            <div class="stat-card stat-rose">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="stat-icon-wrap mb-0">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <span class="badge rounded-pill" style="background:rgba(16,185,129,0.1);color:#065f46;font-size:0.7rem;font-weight:700;">
                        Kelulusan
                    </span>
                </div>
                <div class="stat-value" style="color:#059669;">{{ number_format($persenLulus, 1) }}%</div>
                <div class="stat-label mb-2">Tingkat Lulus</div>
                <div class="progress" style="height:6px;">
                    <div class="progress-bar"
                         style="width:{{ $persenLulus }}%;background:linear-gradient(90deg,#10b981,#06b6d4);"
                         role="progressbar"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Recent Grades Table ── --}}
    <div class="card">
        <div class="card-header">
            <div class="card-header-title">
                <i class="bi bi-clock-history text-primary"></i>
                Nilai Terbaru Diinput
            </div>
            <a href="{{ route('admin.laporan') }}" class="btn btn-outline-primary btn-sm">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table mb-0" id="tabel-nilai-terbaru">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th class="ps-4 py-3 fw-semibold text-muted border-0">Siswa</th>
                        <th class="py-3 fw-semibold text-muted border-0">Kelas</th>
                        <th class="py-3 fw-semibold text-muted border-0">Angkatan</th>
                        <th class="py-3 fw-semibold text-muted border-0">Mata Pelajaran</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">UTS</th>
                        <th class="text-center">UAS</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilaiTerbaru as $i => $nilai)
                        <tr>
                            <td class="ps-4 text-muted fw-500">{{ $i + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-initials" style="background:linear-gradient(135deg,#6366f1,#06b6d4);color:#fff;">
                                        {{ strtoupper(substr($nilai->siswa->nama ?? '-', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-600" style="font-size:0.84rem;">{{ $nilai->siswa->nama ?? '-' }}</div>
                                        <div style="font-size:0.7rem;color:#9ca3af;font-family:monospace;">{{ $nilai->siswa->nis ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill" style="background:#f3f4f6;color:#374151;font-size:0.72rem;font-weight:600;">
                                    {{ $nilai->siswa->kelas ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted small fw-semibold">
                                    {{ $nilai->siswa->angkatan ?? '-' }}
                                </span>
                            </td>
                            <td style="font-size:0.84rem;">{{ $nilai->mataPelajaran->nama ?? '-' }}</td>
                            <td class="text-center">{{ $nilai->nilai_tugas }}</td>
                            <td class="text-center">{{ $nilai->nilai_uts }}</td>
                            <td class="text-center">{{ $nilai->nilai_uas }}</td>
                            <td class="text-center">
                                <span class="fw-800 font-jakarta" style="font-size:0.95rem;color:{{ $nilai->nilai_akhir >= 70 ? '#059669' : '#dc2626' }}">
                                    {{ number_format($nilai->nilai_akhir, 1) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php $grade = konversiNilaiKeHuruf($nilai->nilai_akhir); @endphp
                                <span class="badge-grade grade-{{ strtolower($grade) }}">{{ $grade }}</span>
                            </td>
                            <td class="text-center">
                                @if($nilai->status === 'LULUS')
                                    <span class="badge-status badge-lulus">
                                        <i class="bi bi-check-circle-fill"></i> Lulus
                                    </span>
                                @else
                                    <span class="badge-status badge-tidak-lulus">
                                        <i class="bi bi-x-circle-fill"></i> Tidak Lulus
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:0.5rem;opacity:0.3;"></i>
                                Belum ada data nilai yang diinput.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
