@extends('layouts.app')

@section('page-title', 'Dashboard Guru')
@section('page-breadcrumb', 'Guru · ' . $guru->nama)

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
{{-- WELCOME / PROFILE CARD                                              --}}
{{-- ------------------------------------------------------------------ --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, var(--clr-primary) 0%, #4f46e5 60%, var(--clr-accent) 100%);">
            <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center gap-4">

                {{-- Avatar --}}
                <div class="flex-shrink-0">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow"
                         style="width:72px;height:72px;background:rgba(255,255,255,.18);border:3px solid rgba(255,255,255,.4);">
                        <i class="bi bi-person-badge-fill text-white" style="font-size:2rem;"></i>
                    </div>
                </div>

                {{-- Info --}}
                <div class="flex-grow-1 text-white">
                    <p class="mb-1 small opacity-75 fw-semibold text-uppercase letter-spacing-1">Selamat Datang</p>
                    <h4 class="fw-bold mb-1" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        {{ $guru->nama }}
                    </h4>
                    <div class="d-flex flex-wrap gap-2 align-items-center mt-2">
                        <span class="badge rounded-pill px-3 py-1 fw-semibold"
                              style="background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.35);font-size:.78rem;">
                            <i class="bi bi-book-fill me-1"></i>
                            {{ $guru->mataPelajaran->nama ?? 'Belum Ada Mapel' }}
                        </span>
                        <span class="badge rounded-pill px-3 py-1 fw-semibold"
                              style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);font-size:.78rem;">
                            <i class="bi bi-card-text me-1"></i>
                            NIP: {{ $guru->nip ?? '-' }}
                        </span>
                    </div>
                </div>

                {{-- Quick action button --}}
                <div class="flex-shrink-0">
                    <a href="{{ route('guru.input-nilai') }}"
                       class="btn btn-light fw-semibold shadow-sm px-4"
                       style="border-radius:10px;color:var(--clr-primary);">
                        <i class="bi bi-pencil-square me-2"></i>Input Nilai
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ------------------------------------------------------------------ --}}
{{-- STAT CARDS ROW                                                       --}}
{{-- ------------------------------------------------------------------ --}}
<div class="row g-4 mb-4">

    {{-- Sudah Diinput --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="stat-card-icon" style="background:linear-gradient(135deg,#6366f1,#818cf8);">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div class="stat-card-body">
                <p class="stat-card-label">Sudah Diinput</p>
                <h3 class="stat-card-value">{{ $jumlahSudahDiinput }}</h3>
                <p class="stat-card-sub">Siswa dengan nilai</p>
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
                <p class="stat-card-sub">Nilai akhir ≥ 70</p>
            </div>
        </div>
    </div>

    {{-- Belum Lulus --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="stat-card-icon" style="background:linear-gradient(135deg,#ef4444,#f87171);">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div class="stat-card-body">
                <p class="stat-card-label">Belum Lulus</p>
                <h3 class="stat-card-value">{{ $jumlahSudahDiinput - $jumlahLulus }}</h3>
                <p class="stat-card-sub">Nilai akhir &lt; 70</p>
            </div>
        </div>
    </div>

    {{-- Persentase Lulus --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="stat-card-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);">
                <i class="bi bi-bar-chart-fill"></i>
            </div>
            <div class="stat-card-body">
                <p class="stat-card-label">% Kelulusan</p>
                <h3 class="stat-card-value">{{ number_format($persenLulus, 1) }}%</h3>
                {{-- Progress bar --}}
                <div class="progress mt-2" style="height:6px;border-radius:99px;background:rgba(0,0,0,.08);">
                    <div class="progress-bar"
                         role="progressbar"
                         style="width:{{ min($persenLulus, 100) }}%;background:linear-gradient(90deg,#f59e0b,#fbbf24);border-radius:99px;"
                         aria-valuenow="{{ $persenLulus }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ------------------------------------------------------------------ --}}
{{-- KELAS SUDAH DIINPUT + RECENT NILAI (two-column layout)              --}}
{{-- ------------------------------------------------------------------ --}}
<div class="row g-4 mb-4">

    {{-- Kelas yang Sudah Diinput --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="rounded-3 d-flex align-items-center justify-content-center"
                          style="width:34px;height:34px;background:linear-gradient(135deg,var(--clr-accent),#38bdf8);">
                        <i class="bi bi-mortarboard-fill text-white" style="font-size:.9rem;"></i>
                    </span>
                    <h6 class="fw-bold mb-0" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        Kelas Sudah Diinput
                    </h6>
                </div>
                <p class="text-muted small mb-3">Kelas yang telah memiliki catatan nilai</p>
            </div>
            <div class="card-body pt-0">
                @if($kelasYangSudahDiinput->isEmpty())
                    {{-- Empty state --}}
                    <div class="text-center py-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width:56px;height:56px;background:rgba(99,102,241,.08);">
                            <i class="bi bi-inbox text-primary" style="font-size:1.4rem;"></i>
                        </div>
                        <p class="text-muted small mb-0">Belum ada kelas yang diinput.</p>
                        <a href="{{ route('guru.input-nilai') }}" class="btn btn-sm btn-primary mt-3 px-4 fw-semibold">
                            Mulai Input
                        </a>
                    </div>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($kelasYangSudahDiinput as $kelas)
                            <a href="{{ route('guru.rekap-nilai', ['kelas' => $kelas]) }}"
                               class="badge rounded-pill text-decoration-none px-3 py-2 fw-semibold"
                               style="background:rgba(99,102,241,.12);color:var(--clr-primary);border:1.5px solid rgba(99,102,241,.25);font-size:.8rem;transition:all .2s;"
                               onmouseover="this.style.background='var(--clr-primary)';this.style.color='#fff';"
                               onmouseout="this.style.background='rgba(99,102,241,.12)';this.style.color='var(--clr-primary)';">
                                <i class="bi bi-buildings me-1"></i>{{ $kelas }}
                            </a>
                        @endforeach
                    </div>
                    <p class="text-muted small mt-3 mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Klik kelas untuk melihat rekap nilai.
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent Nilai Table --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div class="d-flex align-items-center gap-2">
                        <span class="rounded-3 d-flex align-items-center justify-content-center"
                              style="width:34px;height:34px;background:linear-gradient(135deg,var(--clr-primary),#818cf8);">
                            <i class="bi bi-clock-history text-white" style="font-size:.9rem;"></i>
                        </span>
                        <h6 class="fw-bold mb-0" style="font-family:'Plus Jakarta Sans',sans-serif;">
                            Nilai Terbaru Diinput
                        </h6>
                    </div>
                    <a href="{{ route('guru.rekap-nilai') }}" class="btn btn-sm px-3 fw-semibold"
                       style="background:rgba(99,102,241,.1);color:var(--clr-primary);border-radius:8px;">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <p class="text-muted small mb-3">10 entri nilai yang paling baru diperbarui</p>
            </div>
            <div class="card-body pt-0 px-0">
                @if($nilaiTerbaru->isEmpty())
                    <div class="text-center py-5">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width:56px;height:56px;background:rgba(99,102,241,.08);">
                            <i class="bi bi-journal-x text-primary" style="font-size:1.4rem;"></i>
                        </div>
                        <p class="text-muted mb-0">Belum ada nilai yang diinput.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size:.875rem;">
                            <thead>
                                <tr style="background:rgba(99,102,241,.04);">
                                    <th class="ps-4 py-3 fw-semibold text-muted border-0">Nama Siswa</th>
                                    <th class="py-3 fw-semibold text-muted border-0 text-center">Tugas</th>
                                    <th class="py-3 fw-semibold text-muted border-0 text-center">UTS</th>
                                    <th class="py-3 fw-semibold text-muted border-0 text-center">UAS</th>
                                    <th class="py-3 fw-semibold text-muted border-0 text-center">Nilai Akhir</th>
                                    <th class="pe-4 py-3 fw-semibold text-muted border-0 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nilaiTerbaru as $nilai)
                                    @php
                                        $na = (0.3 * $nilai->nilai_tugas) + (0.3 * $nilai->nilai_uts) + (0.4 * $nilai->nilai_uas);
                                        $lulus = $na >= 70;

                                        // Determine grade
                                        if ($na >= 90)      $grade = 'A';
                                        elseif ($na >= 80)  $grade = 'B';
                                        elseif ($na >= 70)  $grade = 'C';
                                        elseif ($na >= 60)  $grade = 'D';
                                        else                $grade = 'E';
                                    @endphp
                                    <tr>
                                        <td class="ps-4 py-3 border-0">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                     style="width:32px;height:32px;background:rgba(99,102,241,.1);">
                                                    <span class="fw-bold text-primary" style="font-size:.7rem;">
                                                        {{ strtoupper(substr($nilai->siswa->nama ?? '?', 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-semibold" style="line-height:1.2;">
                                                        {{ $nilai->siswa->nama ?? 'N/A' }}
                                                    </p>
                                                    <small class="text-muted">{{ $nilai->siswa->kelas ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 border-0 text-center fw-semibold">{{ $nilai->nilai_tugas }}</td>
                                        <td class="py-3 border-0 text-center fw-semibold">{{ $nilai->nilai_uts }}</td>
                                        <td class="py-3 border-0 text-center fw-semibold">{{ $nilai->nilai_uas }}</td>
                                        <td class="py-3 border-0 text-center">
                                            <span class="fw-bold" style="font-size:.95rem;color:{{ $lulus ? 'var(--clr-success)' : 'var(--clr-danger)' }};">
                                                {{ number_format($na, 1) }}
                                            </span>
                                        </td>
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
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
