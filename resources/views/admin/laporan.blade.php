@extends('layouts.app')

@section('page-title', 'Laporan Nilai')
@section('page-breadcrumb', 'Admin · Laporan')

{{-- ─────────────────────────────────────────────────────
     SIDEBAR MENU
────────────────────────────────────────────────────── --}}
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

{{-- ─────────────────────────────────────────────────────
     MAIN CONTENT
────────────────────────────────────────────────────── --}}
@section('content')

{{-- ── Page Header ──────────────────────────────────── --}}
<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-header-title">
            <span class="me-2" style="color:var(--clr-primary);"><i class="bi bi-file-earmark-bar-graph-fill"></i></span>
            Laporan Nilai
        </h1>
        <p class="page-header-sub mb-0">Rekap hasil belajar siswa – filter, analisis, dan ekspor ke PDF.</p>
    </div>

    {{-- PDF Download — carries current filter params --}}
    <a href="{{ route('admin.laporan.pdf', array_filter(['kelas' => request('kelas'), 'mapel_id' => request('mapel_id')])) }}"
       class="btn d-inline-flex align-items-center gap-2 rounded-3 px-4 py-2 shadow-sm text-white fw-semibold"
       style="background: linear-gradient(135deg,#ef4444,#f87171);"
       target="_blank"
       title="Unduh laporan sebagai PDF">
        <i class="bi bi-file-earmark-pdf-fill fs-5"></i>
        <span>Unduh PDF</span>
    </a>
</div>


{{-- ════════════════════════════════════════════════════
     FILTER CARD
═══════════════════════════════════════════════════════ --}}
<div class="card border-0 shadow-sm rounded-4 mb-4"
     style="background: linear-gradient(135deg,rgba(99,102,241,0.05) 0%,rgba(6,182,212,0.04) 100%);">
    <div class="card-body px-4 py-3">
        <form method="GET" action="{{ route('admin.laporan') }}" class="row g-3 align-items-end" id="formFilter">

            {{-- Filter: Kelas --}}
            <div class="col-12 col-sm-6 col-lg-4">
                <label for="filter_kelas" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing:.5px;">
                    <i class="bi bi-mortarboard me-1"></i> Kelas
                </label>
                <select id="filter_kelas" name="kelas" class="form-control form-select rounded-3">
                    <option value="">— Semua Kelas —</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                            {{ $kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter: Mata Pelajaran --}}
            <div class="col-12 col-sm-6 col-lg-4">
                <label for="filter_mapel" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing:.5px;">
                    <i class="bi bi-book me-1"></i> Mata Pelajaran
                </label>
                <select id="filter_mapel" name="mapel_id" class="form-control form-select rounded-3">
                    <option value="">— Semua Mapel —</option>
                    @foreach ($mapelList as $mapel)
                        <option value="{{ $mapel->id }}" {{ request('mapel_id') == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="col-12 col-lg-4 d-flex gap-2">
                <button type="submit"
                        class="btn btn-primary rounded-3 px-4 d-inline-flex align-items-center gap-2 flex-grow-1">
                    <i class="bi bi-funnel-fill"></i>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('admin.laporan') }}"
                   class="btn btn-light rounded-3 px-3 d-inline-flex align-items-center gap-1"
                   title="Reset filter">
                    <i class="bi bi-x-circle"></i>
                    <span class="d-none d-sm-inline">Reset</span>
                </a>
            </div>

        </form>
    </div>
</div>


{{-- ════════════════════════════════════════════════════
     SUMMARY STAT CHIPS
═══════════════════════════════════════════════════════ --}}
@php
    $tidakLulus      = $totalData - $jumlahLulus;
    $persentaseLulus = $totalData > 0 ? round(($jumlahLulus / $totalData) * 100, 1) : 0;
@endphp

<div class="row g-3 mb-4">

    {{-- Total Data --}}
    <div class="col-6 col-md-3">
        <div class="stat-card h-100 rounded-4 shadow-sm p-3 d-flex align-items-center gap-3"
             style="background:#fff; border-left: 4px solid var(--clr-primary);">
            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:46px; height:46px; background:rgba(99,102,241,0.12);">
                <i class="bi bi-collection-fill fs-5" style="color:var(--clr-primary);"></i>
            </div>
            <div class="overflow-hidden">
                <div class="stat-value fw-bold fs-4 lh-1" style="color:var(--clr-primary);">{{ $totalData }}</div>
                <div class="stat-label text-muted small mt-1">Total Data</div>
            </div>
        </div>
    </div>

    {{-- Lulus --}}
    <div class="col-6 col-md-3">
        <div class="stat-card h-100 rounded-4 shadow-sm p-3 d-flex align-items-center gap-3"
             style="background:#fff; border-left: 4px solid var(--clr-success);">
            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:46px; height:46px; background:rgba(16,185,129,0.12);">
                <i class="bi bi-patch-check-fill fs-5" style="color:var(--clr-success);"></i>
            </div>
            <div class="overflow-hidden">
                <div class="stat-value fw-bold fs-4 lh-1" style="color:var(--clr-success);">{{ $jumlahLulus }}</div>
                <div class="stat-label text-muted small mt-1">Lulus</div>
            </div>
        </div>
    </div>

    {{-- Tidak Lulus --}}
    <div class="col-6 col-md-3">
        <div class="stat-card h-100 rounded-4 shadow-sm p-3 d-flex align-items-center gap-3"
             style="background:#fff; border-left: 4px solid var(--clr-danger);">
            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:46px; height:46px; background:rgba(239,68,68,0.12);">
                <i class="bi bi-x-circle-fill fs-5" style="color:var(--clr-danger);"></i>
            </div>
            <div class="overflow-hidden">
                <div class="stat-value fw-bold fs-4 lh-1" style="color:var(--clr-danger);">{{ $tidakLulus }}</div>
                <div class="stat-label text-muted small mt-1">Tidak Lulus</div>
            </div>
        </div>
    </div>

    {{-- Persentase Lulus --}}
    <div class="col-6 col-md-3">
        <div class="stat-card h-100 rounded-4 shadow-sm p-3 d-flex align-items-center gap-3"
             style="background:#fff; border-left: 4px solid var(--clr-accent);">
            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:46px; height:46px; background:rgba(6,182,212,0.12);">
                <i class="bi bi-bar-chart-fill fs-5" style="color:var(--clr-accent);"></i>
            </div>
            <div class="overflow-hidden">
                <div class="stat-value fw-bold fs-4 lh-1" style="color:var(--clr-accent);">{{ $persentaseLulus }}%</div>
                <div class="stat-label text-muted small mt-1">% Lulus</div>
            </div>
        </div>
    </div>

</div>


{{-- ════════════════════════════════════════════════════
     DATA TABLE CARD
═══════════════════════════════════════════════════════ --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header d-flex align-items-center justify-content-between py-3 px-4 rounded-top-4 flex-wrap gap-2">
        <div class="card-header-title d-flex align-items-center gap-2">
            <i class="bi bi-table" style="color:var(--clr-primary);"></i>
            <span>Rekap Nilai Siswa</span>
            {{-- Active filter indicators --}}
            @if (request('kelas'))
                <span class="badge rounded-pill ms-1"
                      style="background:rgba(99,102,241,0.15); color:var(--clr-primary); font-size:.75rem;">
                    Kelas {{ request('kelas') }}
                </span>
            @endif
            @if (request('mapel_id'))
                <span class="badge rounded-pill"
                      style="background:rgba(6,182,212,0.15); color:var(--clr-accent); font-size:.75rem;">
                    {{ $mapelList->firstWhere('id', request('mapel_id'))->nama ?? 'Mapel' }}
                </span>
            @endif
        </div>
        <span class="badge rounded-pill"
              style="background:var(--clr-primary); font-size:.8rem;">
            {{ $nilais->total() }} record
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabelLaporan">
                <thead>
                    <tr style="background: linear-gradient(90deg,#f0f0ff 0%,#f5f5ff 100%);">
                        <th class="ps-4 py-3" style="width:50px;">#</th>
                        <th class="py-3" style="min-width:180px;">Siswa</th>
                        <th class="py-3" style="width:80px;">Kelas</th>
                        <th class="py-3" style="min-width:160px;">Mata Pelajaran</th>
                        <th class="py-3 text-center" style="width:75px;">Tugas</th>
                        <th class="py-3 text-center" style="width:75px;">UTS</th>
                        <th class="py-3 text-center" style="width:75px;">UAS</th>
                        <th class="py-3 text-center" style="width:100px;">Nilai Akhir</th>
                        <th class="py-3 text-center" style="width:80px;">Grade</th>
                        <th class="py-3 text-center pe-4" style="width:120px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($nilais as $index => $nilai)
                        @php
                            // Compute color class for Nilai Akhir
                            $na = $nilai->nilai_akhir;
                            if ($na >= 90)      { $naColor = 'var(--clr-success)'; }
                            elseif ($na >= 75)  { $naColor = 'var(--clr-accent)'; }
                            elseif ($na >= 60)  { $naColor = 'var(--clr-warning)'; }
                            else                { $naColor = 'var(--clr-danger)'; }

                            $grade = konversiNilaiKeHuruf($na);
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted small">
                                {{ $nilais->firstItem() + $index }}
                            </td>

                            {{-- Siswa: nama + NIS chip --}}
                            <td>
                                <div class="fw-medium lh-sm">{{ $nilai->siswa->nama }}</div>
                                <div class="text-muted" style="font-size:.75rem;">
                                    <i class="bi bi-person-badge me-1"></i>{{ $nilai->siswa->nis }}
                                </div>
                            </td>

                            {{-- Kelas --}}
                            <td>
                                <span class="badge rounded-3 fw-semibold px-2"
                                      style="background:rgba(99,102,241,0.1); color:var(--clr-primary); font-size:.78rem;">
                                    {{ $nilai->siswa->kelas }}
                                </span>
                            </td>

                            {{-- Mata Pelajaran --}}
                            <td class="fw-medium">{{ $nilai->mataPelajaran->nama }}</td>

                            {{-- Komponen Nilai --}}
                            <td class="text-center text-muted">{{ $nilai->nilai_tugas }}</td>
                            <td class="text-center text-muted">{{ $nilai->nilai_uts }}</td>
                            <td class="text-center text-muted">{{ $nilai->nilai_uas }}</td>

                            {{-- Nilai Akhir color-coded --}}
                            <td class="text-center">
                                <span class="fw-bold fs-6" style="color:{{ $naColor }};">
                                    {{ $na }}
                                </span>
                            </td>

                            {{-- Grade Badge --}}
                            <td class="text-center">
                                <span class="badge-grade badge-grade-{{ strtolower($grade) }}">
                                    {{ $grade }}
                                </span>
                            </td>

                            {{-- Status Badge --}}
                            <td class="text-center pe-4">
                                @if (strtoupper($nilai->status) === 'LULUS')
                                    <span class="badge-lulus">
                                        <i class="bi bi-check-circle-fill me-1"></i> Lulus
                                    </span>
                                @else
                                    <span class="badge-tidak-lulus">
                                        <i class="bi bi-x-circle-fill me-1"></i> Tidak Lulus
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center gap-2 text-muted">
                                    <i class="bi bi-inbox fs-1" style="opacity:.35;"></i>
                                    <span class="fw-medium">Tidak ada data nilai untuk filter yang dipilih.</span>
                                    <a href="{{ route('admin.laporan') }}" class="btn btn-sm btn-light mt-1">
                                        <i class="bi bi-x-circle me-1"></i> Reset Filter
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination – appends current filter query string --}}
    @if ($nilais->hasPages())
        <div class="card-footer bg-transparent border-0 px-4 py-3">
            {{ $nilais->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@endsection
