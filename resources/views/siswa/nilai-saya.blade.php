@extends('layouts.app')

@section('page-title', 'Nilai Saya')
@section('page-breadcrumb', 'Siswa · ' . $siswa->nama . ' · Nilai Saya')

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
     STUDENT INFO HEADER
================================================================ --}}
<div class="nilai-info-header mb-4">
    <div class="nilai-info-inner">
        {{-- Student avatar --}}
        <div class="ns-avatar">
            <span class="ns-avatar-initials">
                {{ strtoupper(substr($siswa->nama, 0, 1)) }}{{ strtoupper(substr(strstr($siswa->nama, ' ') ?: '_', 1, 1)) }}
            </span>
        </div>

        <div class="ns-identity">
            <h4 class="ns-name">{{ $siswa->nama }}</h4>
            <div class="ns-chips">
                <span class="ns-chip"><i class="bi bi-person-badge me-1"></i>NIS: {{ $siswa->nis }}</span>
                <span class="ns-chip"><i class="bi bi-building me-1"></i>Kelas: {{ $siswa->kelas }}</span>
            </div>
        </div>

        {{-- Print button --}}
        <div class="ms-auto d-print-none">
            <button onclick="window.print()" class="btn btn-outline-primary">
                <i class="bi bi-printer me-1"></i> Cetak Nilai
            </button>
        </div>
    </div>
</div>

{{-- ================================================================
     SUMMARY BANNER  (Rata-rata / Total Mapel / Lulus)
================================================================ --}}
<div class="nilai-summary-banner mb-4">
    <div class="nsb-item">
        <div class="nsb-icon nsb-icon--indigo">
            <i class="bi bi-graph-up-arrow"></i>
        </div>
        <div>
            <div class="nsb-label">Rata-rata Nilai</div>
            <div class="nsb-value">
                @php
                    $rrColor = $rataRata >= 75 ? '#10b981' : ($rataRata >= 60 ? '#f59e0b' : '#ef4444');
                @endphp
                <span style="color: {{ $rrColor }}">{{ number_format($rataRata, 1) }}</span>
            </div>
        </div>
    </div>

    <div class="nsb-divider d-none d-md-block"></div>

    <div class="nsb-item">
        <div class="nsb-icon nsb-icon--cyan">
            <i class="bi bi-book-half"></i>
        </div>
        <div>
            <div class="nsb-label">Total Mata Pelajaran</div>
            <div class="nsb-value">{{ $jumlahTotal }}</div>
        </div>
    </div>

    <div class="nsb-divider d-none d-md-block"></div>

    <div class="nsb-item">
        <div class="nsb-icon nsb-icon--emerald">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="nsb-label">Lulus</div>
            <div class="nsb-value" style="color: var(--clr-success)">{{ $jumlahLulus }}</div>
        </div>
    </div>

    <div class="nsb-divider d-none d-md-block"></div>

    <div class="nsb-item">
        <div class="nsb-icon nsb-icon--rose">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <div>
            <div class="nsb-label">Belum Lulus</div>
            <div class="nsb-value" style="color: var(--clr-danger)">{{ $jumlahTotal - $jumlahLulus }}</div>
        </div>
    </div>
</div>

{{-- ================================================================
     PAGE HEADER for the cards section
================================================================ --}}
<div class="d-flex align-items-center justify-content-between mb-3 d-print-none">
    <div>
        <h5 class="fw-bold text-dark mb-0">Detail Nilai Per Mata Pelajaran</h5>
        <p class="text-muted small mb-0">Komponen Tugas, UTS, dan UAS ditampilkan per mapel</p>
    </div>
    @if($nilais->isNotEmpty())
        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold">
            {{ $nilais->count() }} Mata Pelajaran
        </span>
    @endif
</div>

{{-- ================================================================
     CARDS GRID  — One card per Mata Pelajaran
================================================================ --}}
@if($nilais->isEmpty())

    {{-- Empty State --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body py-5">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-journal-x"></i>
                </div>
                <h5 class="empty-state-title">Belum Ada Nilai</h5>
                <p class="empty-state-desc">
                    Nilai kamu belum dimasukkan oleh guru. Silakan hubungi wali kelas
                    atau tunggu hingga nilai diperbarui.
                </p>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

@else

    <div class="row g-4 mb-5 d-print-none" id="nilai-cards">
        @foreach($nilais as $nilai)
            @php
                $na       = $nilai->nilai_akhir;
                $grade    = konversiNilaiKeHuruf($na);
                $lulus    = $na >= 75;

                // Nilai color for large number
                if ($na >= 85)      { $naColor = '#10b981'; $naLabel = 'Sangat Baik'; }
                elseif ($na >= 75)  { $naColor = '#6366f1'; $naLabel = 'Baik'; }
                elseif ($na >= 60)  { $naColor = '#f59e0b'; $naLabel = 'Cukup'; }
                else                { $naColor = '#ef4444'; $naLabel = 'Perlu Ditingkatkan'; }

                // Grade badge variant
                $gradeVariant = match(true) {
                    $grade === 'A' => 'grade-a',
                    $grade === 'B' => 'grade-b',
                    $grade === 'C' => 'grade-c',
                    $grade === 'D' => 'grade-d',
                    default        => 'grade-e',
                };

                // Card accent color (left border)
                $accentColor = $lulus ? 'var(--clr-success)' : 'var(--clr-danger)';
            @endphp

            <div class="col-sm-6 col-xl-4">
                <div class="nilai-card" style="--accent: {{ $accentColor }};">
                    {{-- Card Header: Mapel name + Grade badge --}}
                    <div class="nilai-card-header">
                        <div class="nilai-card-mapel-icon">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="nilai-card-mapel-info">
                            <span class="nilai-card-mapel-name">{{ $nilai->mataPelajaran->nama }}</span>
                        </div>
                        <div class="nilai-card-grade">
                            <span class="badge-grade badge-grade--{{ strtolower($grade) }}">{{ $grade }}</span>
                        </div>
                    </div>

                    {{-- Three component scores --}}
                    <div class="nilai-card-components">
                        <div class="nilai-component">
                            <span class="nilai-component-label">Tugas</span>
                            <span class="nilai-component-val">{{ $nilai->nilai_tugas ?? '—' }}</span>
                        </div>
                        <div class="nilai-component-sep"></div>
                        <div class="nilai-component">
                            <span class="nilai-component-label">UTS</span>
                            <span class="nilai-component-val">{{ $nilai->nilai_uts ?? '—' }}</span>
                        </div>
                        <div class="nilai-component-sep"></div>
                        <div class="nilai-component">
                            <span class="nilai-component-label">UAS</span>
                            <span class="nilai-component-val">{{ $nilai->nilai_uas ?? '—' }}</span>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="nilai-card-hr"></div>

                    {{-- Footer: Nilai Akhir + Status --}}
                    <div class="nilai-card-footer">
                        <div class="nilai-akhir-block">
                            <span class="nilai-akhir-label">Nilai Akhir</span>
                            <span class="nilai-akhir-number" style="color: {{ $naColor }}">
                                {{ number_format($na, 1) }}
                            </span>
                            <span class="nilai-akhir-sublabel" style="color: {{ $naColor }}">{{ $naLabel }}</span>
                        </div>
                        <div>
                            @if($lulus)
                                <span class="badge-lulus fs-7">
                                    <i class="bi bi-check2-circle me-1"></i>Lulus
                                </span>
                            @else
                                <span class="badge-tidak-lulus fs-7">
                                    <i class="bi bi-x-circle me-1"></i>Belum Lulus
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Progress bar for nilai akhir --}}
                    <div class="nilai-card-progress">
                        <div class="nilai-card-progress-bar" style="width: {{ min($na, 100) }}%; background: {{ $naColor }};"></div>
                    </div>
                </div>
            </div>

        @endforeach
    </div>

    {{-- =============================================================
         PRINT-FRIENDLY SUMMARY TABLE
         Visible on screen too (below cards), but highlighted on print
    ============================================================= --}}
    <div class="card border-0 shadow-sm" id="print-table">
        <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
            <h6 class="fw-semibold text-dark mb-0">
                <i class="bi bi-table me-2 text-primary"></i>Rekap Nilai (Tabel Ringkas)
            </h6>
            <span class="text-muted small d-print-none">Tampilan ini cocok untuk dicetak</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 print-table">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3" style="width:40px">#</th>
                            <th class="py-3">Mata Pelajaran</th>
                            <th class="text-center py-3">Tugas</th>
                            <th class="text-center py-3">UTS</th>
                            <th class="text-center py-3">UAS</th>
                            <th class="text-center py-3">Nilai Akhir</th>
                            <th class="text-center py-3">Grade</th>
                            <th class="text-center pe-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nilais as $i => $nilai)
                            @php
                                $na    = $nilai->nilai_akhir;
                                $grade = konversiNilaiKeHuruf($na);
                                $lulus = $na >= 75;

                                if ($na >= 85)     $naColor = 'text-success fw-bold';
                                elseif ($na >= 75) $naColor = 'text-primary fw-bold';
                                elseif ($na >= 60) $naColor = 'text-warning fw-bold';
                                else               $naColor = 'text-danger fw-bold';
                            @endphp
                            <tr>
                                <td class="ps-4 text-muted small">{{ $i + 1 }}</td>
                                <td>
                                    <span class="fw-medium text-dark">{{ $nilai->mataPelajaran->nama }}</span>
                                </td>
                                <td class="text-center text-muted">{{ $nilai->nilai_tugas ?? '—' }}</td>
                                <td class="text-center text-muted">{{ $nilai->nilai_uts ?? '—' }}</td>
                                <td class="text-center text-muted">{{ $nilai->nilai_uas ?? '—' }}</td>
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
                    {{-- Summary footer row --}}
                    <tfoot>
                        <tr class="table-light fw-semibold">
                            <td class="ps-4 py-3" colspan="5">
                                <span>Rata-rata Nilai</span>
                            </td>
                            <td class="text-center py-3">
                                @php
                                    $fColor = $rataRata >= 75 ? 'text-success' : ($rataRata >= 60 ? 'text-warning' : 'text-danger');
                                @endphp
                                <span class="{{ $fColor }} fw-bold fs-6">{{ number_format($rataRata, 1) }}</span>
                            </td>
                            <td class="text-center py-3">
                                @php $avgGrade = konversiNilaiKeHuruf($rataRata); @endphp
                                <span class="badge-grade badge-grade--{{ strtolower($avgGrade) }}">{{ $avgGrade }}</span>
                            </td>
                            <td class="text-center pe-4 py-3">
                                @if($jumlahLulus === $jumlahTotal && $jumlahTotal > 0)
                                    <span class="badge-lulus">Semua Lulus</span>
                                @else
                                    <span class="badge-proses">{{ $jumlahLulus }}/{{ $jumlahTotal }} Lulus</span>
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@endif

@endsection

@push('styles')
<style>
/* =============================================
   SISWA NILAI-SAYA — LOCAL STYLES
============================================= */

/* ---- Student info header ---- */
.nilai-info-header {
    background: #fff;
    border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    border: 1px solid rgba(99,102,241,0.08);
}
.nilai-info-inner {
    display: flex;
    align-items: center;
    gap: 1.1rem;
    flex-wrap: wrap;
}
.ns-avatar {
    flex-shrink: 0;
    width: 56px; height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--clr-primary), #4f46e5);
    display: flex; align-items: center; justify-content: center;
}
.ns-avatar-initials {
    font-size: 1.3rem;
    font-weight: 700;
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.ns-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.25rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.ns-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.ns-chip {
    font-size: 0.8rem;
    background: #f3f4f6;
    color: #6b7280;
    border-radius: 99px;
    padding: 3px 12px;
}

/* ---- Summary Banner ---- */
.nilai-summary-banner {
    background: #fff;
    border-radius: 1rem;
    padding: 1.25rem 1.75rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}
.nsb-item {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    flex: 1;
    min-width: 140px;
}
.nsb-icon {
    width: 44px; height: 44px;
    border-radius: 0.75rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.nsb-icon--indigo  { background: rgba(99,102,241,0.12); color: #6366f1; }
.nsb-icon--cyan    { background: rgba(6,182,212,0.12);  color: #06b6d4; }
.nsb-icon--emerald { background: rgba(16,185,129,0.12); color: #10b981; }
.nsb-icon--rose    { background: rgba(239,68,68,0.12);  color: #ef4444; }

.nsb-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #9ca3af;
    font-weight: 500;
}
.nsb-value {
    font-size: 1.6rem;
    font-weight: 700;
    color: #111827;
    line-height: 1;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.nsb-divider {
    width: 1px;
    height: 48px;
    background: #e5e7eb;
}

/* ---- Nilai Card ---- */
.nilai-card {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 2px 14px rgba(0,0,0,0.07);
    border: 1px solid #f0f0f0;
    border-left: 4px solid var(--accent, var(--clr-primary));
    overflow: hidden;
    transition: transform 0.18s ease, box-shadow 0.18s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.nilai-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.12);
}

/* Card header */
.nilai-card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.1rem 0.75rem;
}
.nilai-card-mapel-icon {
    width: 38px; height: 38px;
    border-radius: 0.6rem;
    background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(6,182,212,0.08));
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    color: var(--clr-primary);
    flex-shrink: 0;
}
.nilai-card-mapel-info {
    flex: 1;
    min-width: 0;
}
.nilai-card-mapel-name {
    font-size: 0.92rem;
    font-weight: 600;
    color: #111827;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Component scores */
.nilai-card-components {
    display: flex;
    align-items: stretch;
    padding: 0.5rem 1.1rem;
    background: #fafafa;
    gap: 0;
}
.nilai-component {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.5rem 0.25rem;
}
.nilai-component-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #9ca3af;
    font-weight: 600;
    margin-bottom: 0.25rem;
}
.nilai-component-val {
    font-size: 1.15rem;
    font-weight: 700;
    color: #374151;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.nilai-component-sep {
    width: 1px;
    background: #e5e7eb;
    margin: 0.5rem 0;
}

/* Divider */
.nilai-card-hr {
    height: 1px;
    background: #f0f0f0;
    margin: 0 1.1rem;
}

/* Card footer */
.nilai-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.85rem 1.1rem 0.7rem;
    flex: 1;
    align-items: flex-end;
}
.nilai-akhir-block {
    display: flex;
    flex-direction: column;
}
.nilai-akhir-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #9ca3af;
    font-weight: 600;
}
.nilai-akhir-number {
    font-size: 2.2rem;
    font-weight: 800;
    line-height: 1;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.nilai-akhir-sublabel {
    font-size: 0.72rem;
    font-weight: 500;
    opacity: 0.8;
}
.fs-7 { font-size: 0.8rem !important; }

/* Card progress bar (bottom strip) */
.nilai-card-progress {
    height: 4px;
    background: #f0f0f0;
    margin-top: 0.5rem;
}
.nilai-card-progress-bar {
    height: 100%;
    border-radius: 0 2px 2px 0;
    transition: width 0.8s ease;
}

/* Badge proses (for tfoot) */
.badge-proses {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: rgba(245,158,11,0.12);
    color: #d97706;
    border: 1.5px solid rgba(245,158,11,0.3);
    border-radius: 99px;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.04em;
    padding: 0.25rem 0.75rem;
}

/* ---- Empty state ---- */
.empty-state {
    text-align: center;
    padding: 4rem 1rem;
}
.empty-state-icon {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}
.empty-state-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #6b7280;
    margin-bottom: 0.5rem;
}
.empty-state-desc {
    font-size: 0.875rem;
    color: #9ca3af;
    max-width: 320px;
    margin: 0 auto;
}

/* ---- Print styles ---- */
@media print {
    /* Hide non-essential elements */
    .d-print-none,
    .sidebar,
    .topbar,
    #nilai-cards {
        display: none !important;
    }

    /* Show print-friendly table */
    #print-table {
        display: block !important;
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    /* Info header in print */
    .nilai-info-header,
    .nilai-summary-banner {
        box-shadow: none;
        border: 1px solid #ddd !important;
        border-radius: 0 !important;
    }

    body {
        font-size: 12px;
    }

    .print-table th, .print-table td {
        padding: 8px 10px !important;
        font-size: 12px;
    }

    /* Print page title */
    .nilai-info-header::before {
        content: "SIMPEL — Laporan Nilai Siswa";
        display: block;
        text-align: center;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #111;
    }
}
</style>
@endpush
