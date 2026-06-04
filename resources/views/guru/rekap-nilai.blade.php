@extends('layouts.app')

@section('page-title', 'Rekap Nilai')
@section('page-breadcrumb', 'Guru · Rekap Nilai')

{{-- =====================================================================
     GURU SIDEBAR MENU
====================================================================== --}}
@section('sidebar-menu')
    <span class="nav-section-label">Menu Guru</span>
    <a href="{{ route('guru.dashboard') }}" class="sidebar-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
        <span class="link-icon"><i class="bi bi-speedometer2"></i></span> Dashboard
    </a>
    <a href="{{ route('guru.input-nilai') }}" class="sidebar-link {{ request()->routeIs('guru.input-nilai') ? 'active' : '' }}">
        <span class="link-icon"><i class="bi bi-pencil-square"></i></span> Input Nilai
    </a>
    <a href="{{ route('guru.rekap-nilai') }}" class="sidebar-link {{ request()->routeIs('guru.rekap-nilai') ? 'active' : '' }}">
        <span class="link-icon"><i class="bi bi-table"></i></span> Rekap Nilai
    </a>
@endsection

{{-- =====================================================================
     PAGE CONTENT
====================================================================== --}}
@section('content')

{{-- ------------------------------------------------------------------ --}}
{{-- PAGE HEADER BANNER                                                   --}}
{{-- ------------------------------------------------------------------ --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden"
             style="background:linear-gradient(135deg,#4f46e5 0%,var(--clr-primary) 50%,var(--clr-accent) 100%);">
            <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:56px;height:56px;background:rgba(255,255,255,.18);border:2px solid rgba(255,255,255,.35);">
                    <i class="bi bi-table text-white" style="font-size:1.5rem;"></i>
                </div>
                <div class="text-white flex-grow-1">
                    <p class="mb-0 small opacity-75 fw-semibold text-uppercase">Rekap Nilai — Mata Pelajaran</p>
                    <h4 class="fw-bold mb-1" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        {{ $guru->mataPelajaran->nama ?? 'Belum Ada Mapel' }}
                    </h4>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge rounded-pill px-3 py-1"
                              style="background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);font-size:.78rem;">
                            <i class="bi bi-person-fill me-1"></i>{{ $guru->nama }}
                        </span>
                        @if(request('kelas'))
                            <span class="badge rounded-pill px-3 py-1"
                                  style="background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);font-size:.78rem;">
                                <i class="bi bi-buildings me-1"></i>Kelas: {{ request('kelas') }}
                            </span>
                        @endif
                        <span class="badge rounded-pill px-3 py-1"
                              style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);font-size:.78rem;">
                            <i class="bi bi-list-ol me-1"></i>{{ $total }} entri
                        </span>
                    </div>
                </div>
                <a href="{{ route('guru.input-nilai') }}"
                   class="btn btn-light fw-semibold shadow-sm px-4 flex-shrink-0"
                   style="border-radius:10px;color:var(--clr-primary);">
                    <i class="bi bi-pencil-square me-2"></i>Input Nilai
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ------------------------------------------------------------------ --}}
{{-- SUMMARY STAT CARDS                                                   --}}
{{-- ------------------------------------------------------------------ --}}
<div class="row g-4 mb-4">

    {{-- Total Diinput --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="stat-card-icon" style="background:linear-gradient(135deg,#6366f1,#818cf8);">
                <i class="bi bi-journal-check"></i>
            </div>
            <div class="stat-card-body">
                <p class="stat-card-label">Total Diinput</p>
                <h3 class="stat-card-value">{{ $total }}</h3>
                <p class="stat-card-sub">
                    @if(request('kelas'))
                        Kelas {{ request('kelas') }}
                    @else
                        Semua kelas
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Lulus --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="stat-card-icon" style="background:linear-gradient(135deg,#10b981,#34d399);">
                <i class="bi bi-award-fill"></i>
            </div>
            <div class="stat-card-body">
                <p class="stat-card-label">Lulus</p>
                <h3 class="stat-card-value">{{ $jumlahLulus }}</h3>
                <p class="stat-card-sub">Nilai akhir &geq; 70</p>
            </div>
        </div>
    </div>

    {{-- Tidak Lulus --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="stat-card-icon" style="background:linear-gradient(135deg,#ef4444,#f87171);">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div class="stat-card-body">
                <p class="stat-card-label">Tidak Lulus</p>
                <h3 class="stat-card-value">{{ $total - $jumlahLulus }}</h3>
                <p class="stat-card-sub">Nilai akhir &lt; 70</p>
            </div>
        </div>
    </div>

    {{-- Rata-rata --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="stat-card-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="stat-card-body">
                <p class="stat-card-label">Rata-rata NA</p>
                <h3 class="stat-card-value">{{ number_format($rataRata, 1) }}</h3>
                @php
                    // Mini progress for rata-rata
                    $pctRataRata = min($rataRata, 100);
                    $colorRata   = $rataRata >= 70 ? 'var(--clr-success)' : ($rataRata >= 50 ? 'var(--clr-warning)' : 'var(--clr-danger)');
                @endphp
                <div class="progress mt-2" style="height:6px;border-radius:99px;background:rgba(0,0,0,.08);">
                    <div class="progress-bar" role="progressbar"
                         style="width:{{ $pctRataRata }}%;background:{{ $colorRata }};border-radius:99px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ------------------------------------------------------------------ --}}
{{-- FILTER KELAS + TABLE                                                  --}}
{{-- ------------------------------------------------------------------ --}}
<div class="card border-0 shadow-sm mb-4">

    {{-- Card Header: Filter --}}
    <div class="card-header border-0 p-4" style="background:transparent;">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">

            <div class="d-flex align-items-center gap-2">
                <span class="rounded-3 d-flex align-items-center justify-content-center"
                      style="width:34px;height:34px;background:linear-gradient(135deg,var(--clr-primary),#818cf8);">
                    <i class="bi bi-funnel-fill text-white" style="font-size:.9rem;"></i>
                </span>
                <div>
                    <h6 class="fw-bold mb-0" style="font-family:'Plus Jakarta Sans',sans-serif;">Daftar Nilai Siswa</h6>
                    <p class="text-muted mb-0" style="font-size:.78rem;">
                        Menampilkan nilai untuk: <strong>{{ $guru->mataPelajaran->nama ?? '-' }}</strong>
                    </p>
                </div>
            </div>

            {{-- Filter form --}}
            <form method="GET" action="{{ route('guru.rekap-nilai') }}"
                  class="d-flex gap-2 align-items-center flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    <label for="filter-kelas" class="form-label mb-0 fw-semibold small text-nowrap">Filter Kelas:</label>
                    <select name="kelas" id="filter-kelas" class="form-control form-select"
                            style="min-width:160px;"
                            onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if(request('kelas'))
                    <a href="{{ route('guru.rekap-nilai') }}"
                       class="btn btn-sm fw-semibold px-3"
                       style="background:rgba(239,68,68,.1);color:var(--clr-danger);border-radius:8px;">
                        <i class="bi bi-x-circle me-1"></i>Reset
                    </a>
                @endif
            </form>

        </div>
    </div>

    {{-- Table --}}
    <div class="card-body p-0">
        @if($nilais->isEmpty())
            {{-- Empty state --}}
            <div class="text-center py-5">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:72px;height:72px;background:rgba(99,102,241,.08);">
                    <i class="bi bi-inbox text-primary" style="font-size:1.8rem;"></i>
                </div>
                <h6 class="fw-bold text-muted">Belum ada data nilai</h6>
                <p class="text-muted small mb-4">
                    @if(request('kelas'))
                        Tidak ada nilai untuk kelas <strong>{{ request('kelas') }}</strong>.
                    @else
                        Anda belum menginput nilai untuk mata pelajaran ini.
                    @endif
                </p>
                <a href="{{ route('guru.input-nilai') }}" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-pencil-square me-2"></i>Input Nilai Sekarang
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
                    <thead>
                        <tr style="background:rgba(99,102,241,.05);">
                            <th class="ps-4 py-3 fw-semibold text-muted border-0" style="width:3%;">#</th>
                            <th class="py-3 fw-semibold text-muted border-0" style="min-width:180px;">Nama Siswa</th>
                            <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:100px;">Kelas</th>
                            <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:80px;">Angkatan</th>
                            <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:90px;">
                                Tugas <span class="d-block" style="font-size:.68rem;color:var(--clr-warning);">30%</span>
                            </th>
                            <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:90px;">
                                UTS <span class="d-block" style="font-size:.68rem;color:var(--clr-accent);">30%</span>
                            </th>
                            <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:90px;">
                                UAS <span class="d-block" style="font-size:.68rem;color:var(--clr-primary);">40%</span>
                            </th>
                            <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:110px;">Nilai Akhir</th>
                            <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:80px;">Grade</th>
                            <th class="pe-4 py-3 fw-semibold text-muted border-0 text-center" style="width:120px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nilais as $i => $nilai)
                            @php
                                // Calculate Nilai Akhir
                                $na    = (0.3 * $nilai->nilai_tugas) + (0.3 * $nilai->nilai_uts) + (0.4 * $nilai->nilai_uas);
                                $lulus = $na >= 70;

                                // Grade logic
                                if      ($na >= 90) { $grade = 'A'; $gradeColor = 'var(--clr-success)';  $gradeBg = 'rgba(16,185,129,.12)';  }
                                elseif  ($na >= 80) { $grade = 'B'; $gradeColor = 'var(--clr-accent)';   $gradeBg = 'rgba(6,182,212,.12)';   }
                                elseif  ($na >= 70) { $grade = 'C'; $gradeColor = 'var(--clr-primary)';  $gradeBg = 'rgba(99,102,241,.12)';  }
                                elseif  ($na >= 60) { $grade = 'D'; $gradeColor = 'var(--clr-warning)';  $gradeBg = 'rgba(245,158,11,.12)';  }
                                else                { $grade = 'E'; $gradeColor = 'var(--clr-danger)';   $gradeBg = 'rgba(239,68,68,.12)';   }

                                // Color for Nilai Akhir cell
                                if      ($na >= 80) $naColor = 'var(--clr-success)';
                                elseif  ($na >= 70) $naColor = '#059669';
                                elseif  ($na >= 60) $naColor = 'var(--clr-warning)';
                                else                $naColor = 'var(--clr-danger)';

                                // Row offset for pagination
                                $rowNum = ($nilais->currentPage() - 1) * $nilais->perPage() + $i + 1;
                            @endphp
                            <tr>
                                {{-- # --}}
                                <td class="ps-4 py-3 border-0 text-muted small">{{ $rowNum }}</td>

                                {{-- Nama Siswa --}}
                                <td class="py-3 border-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width:34px;height:34px;background:rgba(99,102,241,.1);">
                                            <span class="fw-bold text-primary" style="font-size:.7rem;">
                                                {{ strtoupper(substr($nilai->siswa->nama ?? '?', 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-semibold" style="line-height:1.2;">
                                                {{ $nilai->siswa->nama ?? 'N/A' }}
                                            </p>
                                            <small class="text-muted">NIS: {{ $nilai->siswa->nis ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kelas --}}
                                <td class="py-3 border-0 text-center">
                                    <span class="badge rounded-pill px-3 py-1 fw-semibold"
                                          style="background:rgba(6,182,212,.1);color:var(--clr-accent);font-size:.75rem;">
                                        {{ $nilai->siswa->kelas ?? '-' }}
                                    </span>
                                </td>

                                {{-- Angkatan --}}
                                <td class="py-3 border-0 text-center text-muted small fw-semibold">
                                    {{ $nilai->siswa->angkatan ?? '-' }}
                                </td>

                                {{-- Tugas --}}
                                <td class="py-3 border-0 text-center fw-semibold">
                                    <span style="color:var(--clr-warning);">{{ $nilai->nilai_tugas }}</span>
                                </td>

                                {{-- UTS --}}
                                <td class="py-3 border-0 text-center fw-semibold">
                                    <span style="color:var(--clr-accent);">{{ $nilai->nilai_uts }}</span>
                                </td>

                                {{-- UAS --}}
                                <td class="py-3 border-0 text-center fw-semibold">
                                    <span style="color:var(--clr-primary);">{{ $nilai->nilai_uas }}</span>
                                </td>

                                {{-- Nilai Akhir (color-coded) --}}
                                <td class="py-3 border-0 text-center">
                                    <span class="fw-bold" style="font-size:1.05rem;color:{{ $naColor }};">
                                        {{ number_format($na, 1) }}
                                    </span>
                                </td>

                                {{-- Grade badge --}}
                                <td class="py-3 border-0 text-center">
                                    <span class="badge-grade fw-bold d-inline-flex align-items-center justify-content-center"
                                          style="width:32px;height:32px;border-radius:8px;background:{{ $gradeBg }};color:{{ $gradeColor }};font-size:.9rem;">
                                        {{ $grade }}
                                    </span>
                                </td>

                                {{-- Status badge --}}
                                <td class="pe-4 py-3 border-0 text-center">
                                    @if($lulus)
                                        <span class="badge-lulus">Lulus</span>
                                    @else
                                        <span class="badge-tidak-lulus">Tidak Lulus</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    {{-- Table footer summary --}}
                    <tfoot>
                        <tr style="background:rgba(99,102,241,.04);border-top:2px solid rgba(99,102,241,.12);">
                            <td colspan="7" class="ps-4 py-3 fw-bold text-muted border-0">
                                Rata-rata (halaman ini)
                            </td>
                            <td class="py-3 border-0 text-center fw-bold" style="color:{{ $rataRata >= 70 ? 'var(--clr-success)' : 'var(--clr-danger)' }};">
                                {{ number_format($rataRata, 1) }}
                            </td>
                            <td class="py-3 border-0 text-center">
                                @php
                                    if      ($rataRata >= 90) { $rg='A'; $rgc='var(--clr-success)'; $rgb='rgba(16,185,129,.12)'; }
                                    elseif  ($rataRata >= 80) { $rg='B'; $rgc='var(--clr-accent)';  $rgb='rgba(6,182,212,.12)'; }
                                    elseif  ($rataRata >= 70) { $rg='C'; $rgc='var(--clr-primary)'; $rgb='rgba(99,102,241,.12)'; }
                                    elseif  ($rataRata >= 60) { $rg='D'; $rgc='var(--clr-warning)'; $rgb='rgba(245,158,11,.12)'; }
                                    else                      { $rg='E'; $rgc='var(--clr-danger)';  $rgb='rgba(239,68,68,.12)'; }
                                @endphp
                                <span class="badge-grade fw-bold d-inline-flex align-items-center justify-content-center"
                                      style="width:32px;height:32px;border-radius:8px;background:{{ $rgb }};color:{{ $rgc }};font-size:.9rem;">
                                    {{ $rg }}
                                </span>
                            </td>
                            <td class="pe-4 py-3 border-0 text-center">
                                @if($rataRata >= 70)
                                    <span class="badge-lulus">Lulus</span>
                                @else
                                    <span class="badge-tidak-lulus">Tidak Lulus</span>
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if($nilais->hasPages())
        <div class="card-footer border-0 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 p-4"
             style="background:rgba(99,102,241,.03);">
            <p class="text-muted small mb-0">
                Menampilkan
                <strong>{{ $nilais->firstItem() }}</strong>–<strong>{{ $nilais->lastItem() }}</strong>
                dari <strong>{{ $nilais->total() }}</strong> entri
            </p>
            {{-- Custom Bootstrap 5 pagination styling --}}
            <nav aria-label="Navigasi halaman">
                {{ $nilais->appends(request()->query())->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    @else
        @if($nilais->isNotEmpty())
            <div class="card-footer border-0 p-4" style="background:rgba(99,102,241,.03);">
                <p class="text-muted small mb-0">
                    Menampilkan semua <strong>{{ $nilais->total() }}</strong> entri
                </p>
            </div>
        @endif
    @endif

</div>

{{-- ------------------------------------------------------------------ --}}
{{-- INFO NOTE                                                            --}}
{{-- ------------------------------------------------------------------ --}}
<div class="d-flex align-items-start gap-3 rounded-3 p-3 mb-4"
     style="background:rgba(6,182,212,.07);border:1px solid rgba(6,182,212,.2);">
    <i class="bi bi-info-circle-fill mt-1 flex-shrink-0" style="color:var(--clr-accent);font-size:1rem;"></i>
    <div>
        <p class="mb-1 fw-semibold small" style="color:var(--clr-accent);">Catatan</p>
        <p class="mb-0 small text-muted">
            Rekap ini menampilkan nilai untuk mata pelajaran
            <strong>{{ $guru->mataPelajaran->nama ?? '-' }}</strong>.
            Formula nilai akhir: <strong>NA = (0.3 × Tugas) + (0.3 × UTS) + (0.4 × UAS)</strong>.
            Siswa dinyatakan <strong>LULUS</strong> apabila Nilai Akhir &geq; 70.
            Untuk mengubah nilai, gunakan menu <a href="{{ route('guru.input-nilai') }}" class="fw-semibold" style="color:var(--clr-primary);">Input Nilai</a>.
        </p>
    </div>
</div>

@endsection
