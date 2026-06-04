@extends('layouts.app')

@section('page-title', 'Input Nilai')
@section('page-breadcrumb', 'Guru · Input Nilai')

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
{{-- MATA PELAJARAN HEADER BANNER                                         --}}
{{-- ------------------------------------------------------------------ --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden"
             style="background:linear-gradient(135deg,var(--clr-primary) 0%,#4f46e5 55%,var(--clr-accent) 100%);">
            <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:56px;height:56px;background:rgba(255,255,255,.18);border:2px solid rgba(255,255,255,.35);">
                    <i class="bi bi-pencil-square text-white" style="font-size:1.5rem;"></i>
                </div>
                <div class="text-white flex-grow-1">
                    <p class="mb-0 small opacity-75 fw-semibold text-uppercase">Mata Pelajaran</p>
                    <h4 class="fw-bold mb-1" style="font-family:'Plus Jakarta Sans',sans-serif;">
                        {{ $guru->mataPelajaran->nama ?? 'Belum Ada Mapel' }}
                    </h4>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge rounded-pill px-3 py-1"
                              style="background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);font-size:.78rem;">
                            <i class="bi bi-person-fill me-1"></i>{{ $guru->nama }}
                        </span>
                        @if($kelasDipilih)
                            <span class="badge rounded-pill px-3 py-1"
                                  style="background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);font-size:.78rem;">
                                <i class="bi bi-buildings me-1"></i>Kelas: {{ $kelasDipilih }}
                            </span>
                        @endif
                    </div>
                </div>
                {{-- Step indicator --}}
                <div class="d-flex gap-2 flex-shrink-0">
                    <div class="d-flex flex-column align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                             style="width:36px;height:36px;background:{{ !$kelasDipilih ? '#fff' : 'rgba(255,255,255,.3)' }};color:{{ !$kelasDipilih ? 'var(--clr-primary)' : 'rgba(255,255,255,.7)' }};font-size:.85rem;">
                            1
                        </div>
                        <span class="text-white opacity-75 mt-1" style="font-size:.7rem;">Pilih Kelas</span>
                    </div>
                    <div class="d-flex align-items-center pb-3">
                        <div style="width:24px;height:2px;background:rgba(255,255,255,.3);"></div>
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                             style="width:36px;height:36px;background:{{ $kelasDipilih ? '#fff' : 'rgba(255,255,255,.2)' }};color:{{ $kelasDipilih ? 'var(--clr-primary)' : 'rgba(255,255,255,.5)' }};font-size:.85rem;">
                            2
                        </div>
                        <span class="text-white opacity-75 mt-1" style="font-size:.7rem;">Input Nilai</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ------------------------------------------------------------------ --}}
{{-- SUCCESS / ERROR ALERTS                                               --}}
{{-- ------------------------------------------------------------------ --}}
@if(session('success'))
    <div class="alert border-0 shadow-sm d-flex align-items-center gap-3 mb-4"
         style="background:rgba(16,185,129,.1);border-left:4px solid var(--clr-success)!important;border-radius:12px;"
         role="alert">
        <i class="bi bi-check-circle-fill" style="color:var(--clr-success);font-size:1.2rem;"></i>
        <span class="fw-semibold" style="color:var(--clr-success);">{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert border-0 shadow-sm d-flex align-items-center gap-3 mb-4"
         style="background:rgba(239,68,68,.1);border-left:4px solid var(--clr-danger)!important;border-radius:12px;"
         role="alert">
        <i class="bi bi-exclamation-circle-fill" style="color:var(--clr-danger);font-size:1.2rem;"></i>
        <span class="fw-semibold" style="color:var(--clr-danger);">{{ session('error') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ------------------------------------------------------------------ --}}
{{-- STEP 1: PILIH KELAS                                                  --}}
{{-- ------------------------------------------------------------------ --}}
<div class="row g-4 mb-4">
    <div class="col-12 col-md-5 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="rounded-3 d-flex align-items-center justify-content-center"
                          style="width:34px;height:34px;background:linear-gradient(135deg,var(--clr-primary),#818cf8);">
                        <i class="bi bi-buildings text-white" style="font-size:.9rem;"></i>
                    </span>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-family:'Plus Jakarta Sans',sans-serif;">Langkah 1</h6>
                        <p class="text-muted mb-0" style="font-size:.78rem;">Pilih kelas yang ingin diisi nilainya</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('guru.input-nilai') }}" id="form-pilih-kelas">
                    <div class="mb-3">
                        <label for="kelas" class="form-label fw-semibold">Pilih Kelas</label>
                        <select name="kelas" id="kelas" class="form-control form-select"
                                onchange="this.form.submit()">
                            <option value="">— Pilih Kelas —</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas }}" {{ $kelasDipilih == $kelas ? 'selected' : '' }}>
                                    {{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="bi bi-arrow-right-circle me-2"></i>Tampilkan Siswa
                    </button>
                </form>

                @if($kelasDipilih)
                    <hr style="border-color:rgba(0,0,0,.08);">
                    <div class="rounded-3 p-3" style="background:rgba(99,102,241,.07);">
                        <p class="mb-1 fw-semibold small" style="color:var(--clr-primary);">
                            <i class="bi bi-buildings-fill me-1"></i>Kelas Aktif
                        </p>
                        <h5 class="fw-bold mb-0">{{ $kelasDipilih }}</h5>
                        <small class="text-muted">{{ $siswas->count() }} siswa ditemukan</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Formula Info Card --}}
    <div class="col-12 col-md-7 col-xl-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 pb-0" style="background:transparent;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="rounded-3 d-flex align-items-center justify-content-center"
                          style="width:34px;height:34px;background:linear-gradient(135deg,var(--clr-accent),#38bdf8);">
                        <i class="bi bi-calculator-fill text-white" style="font-size:.9rem;"></i>
                    </span>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-family:'Plus Jakarta Sans',sans-serif;">Formula Nilai Akhir</h6>
                        <p class="text-muted mb-0" style="font-size:.78rem;">Perhitungan otomatis berdasarkan bobot komponen</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="rounded-3 p-3 mb-3 text-center"
                     style="background:linear-gradient(135deg,rgba(6,182,212,.07),rgba(99,102,241,.07));border:1.5px dashed rgba(99,102,241,.25);">
                    <p class="mb-0 fw-bold" style="font-size:1.05rem;color:var(--clr-primary);font-family:'Plus Jakarta Sans',sans-serif;">
                        NA = (0.3 × Tugas) + (0.3 × UTS) + (0.4 × UAS)
                    </p>
                </div>
                <div class="row g-3">
                    <div class="col-4">
                        <div class="rounded-3 p-3 text-center h-100"
                             style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);">
                            <div class="fw-bold" style="font-size:1.5rem;color:var(--clr-warning);">30%</div>
                            <div class="small fw-semibold text-muted mt-1">Nilai Tugas</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="rounded-3 p-3 text-center h-100"
                             style="background:rgba(6,182,212,.08);border:1px solid rgba(6,182,212,.2);">
                            <div class="fw-bold" style="font-size:1.5rem;color:var(--clr-accent);">30%</div>
                            <div class="small fw-semibold text-muted mt-1">Nilai UTS</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="rounded-3 p-3 text-center h-100"
                             style="background:rgba(99,102,241,.08);border:1px solid rgba(99,102,241,.2);">
                            <div class="fw-bold" style="font-size:1.5rem;color:var(--clr-primary);">40%</div>
                            <div class="small fw-semibold text-muted mt-1">Nilai UAS</div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 mt-3 p-3 rounded-3"
                     style="background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.2);">
                    <i class="bi bi-info-circle-fill" style="color:var(--clr-success);font-size:1.1rem;flex-shrink:0;"></i>
                    <p class="mb-0 small fw-semibold" style="color:var(--clr-success);">
                        Siswa dinyatakan <strong>LULUS</strong> apabila Nilai Akhir &geq; 70.
                        Preview nilai akan dihitung otomatis saat Anda mengisi form.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ------------------------------------------------------------------ --}}
{{-- STEP 2: INPUT TABLE (only shown when $kelasDipilih is set)           --}}
{{-- ------------------------------------------------------------------ --}}
@if($kelasDipilih)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-0" style="background:transparent;">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="rounded-3 d-flex align-items-center justify-content-center"
                          style="width:34px;height:34px;background:linear-gradient(135deg,var(--clr-primary),#818cf8);">
                        <i class="bi bi-table text-white" style="font-size:.9rem;"></i>
                    </span>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-family:'Plus Jakarta Sans',sans-serif;">
                            Langkah 2 — Input Nilai Kelas {{ $kelasDipilih }}
                        </h6>
                        <p class="text-muted mb-0" style="font-size:.78rem;">
                            Mata Pelajaran: <strong>{{ $guru->mataPelajaran->nama ?? '-' }}</strong>
                            &bull; {{ $siswas->count() }} siswa
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm fw-semibold px-3"
                            style="background:rgba(16,185,129,.1);color:var(--clr-success);border-radius:8px;"
                            onclick="fillAllMax()">
                        <i class="bi bi-lightning-fill me-1"></i>Isi Semua 100
                    </button>
                    <button type="button" class="btn btn-sm fw-semibold px-3"
                            style="background:rgba(239,68,68,.1);color:var(--clr-danger);border-radius:8px;"
                            onclick="clearAll()">
                        <i class="bi bi-trash me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('guru.simpan-nilai') }}" id="form-input-nilai">
            @csrf
            {{-- Hidden kelas field --}}
            <input type="hidden" name="kelas" value="{{ $kelasDipilih }}">

            <div class="card-body p-0">
                @if($siswas->isEmpty())
                    <div class="text-center py-5">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width:64px;height:64px;background:rgba(99,102,241,.08);">
                            <i class="bi bi-person-x text-primary" style="font-size:1.6rem;"></i>
                        </div>
                        <h6 class="fw-bold text-muted">Tidak ada siswa di kelas ini</h6>
                        <p class="text-muted small">Pastikan data siswa sudah terdaftar di kelas {{ $kelasDipilih }}.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tabel-nilai" style="font-size:.875rem;">
                            <thead>
                                <tr style="background:rgba(99,102,241,.05);">
                                    <th class="ps-4 py-3 fw-semibold text-muted border-0" style="width:3%;">#</th>
                                    <th class="py-3 fw-semibold text-muted border-0" style="min-width:180px;">Nama Siswa</th>
                                    <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:60px;">NIS</th>
                                    <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:120px;">
                                        Nilai Tugas <span class="badge ms-1" style="background:rgba(245,158,11,.15);color:var(--clr-warning);font-size:.7rem;">30%</span>
                                    </th>
                                    <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:120px;">
                                        Nilai UTS <span class="badge ms-1" style="background:rgba(6,182,212,.15);color:var(--clr-accent);font-size:.7rem;">30%</span>
                                    </th>
                                    <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:120px;">
                                        Nilai UAS <span class="badge ms-1" style="background:rgba(99,102,241,.15);color:var(--clr-primary);font-size:.7rem;">40%</span>
                                    </th>
                                    <th class="py-3 fw-semibold text-muted border-0 text-center" style="width:110px;">Preview NA</th>
                                    <th class="pe-4 py-3 fw-semibold text-muted border-0 text-center" style="width:130px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswas as $index => $siswa)
                                    @php
                                        // Pre-fill from existing nilai if available
                                        $existingNilai = $siswa->nilais->first();
                                        $prefillTugas  = $existingNilai ? $existingNilai->nilai_tugas : '';
                                        $prefillUts    = $existingNilai ? $existingNilai->nilai_uts   : '';
                                        $prefillUas    = $existingNilai ? $existingNilai->nilai_uas   : '';
                                    @endphp
                                    <tr class="nilai-row" data-index="{{ $index }}">

                                        {{-- Row number --}}
                                        <td class="ps-4 py-3 border-0 text-muted small">{{ $index + 1 }}</td>

                                        {{-- Nama Siswa --}}
                                        <td class="py-3 border-0">
                                            {{-- Hidden siswa_id --}}
                                            <input type="hidden"
                                                   name="nilai[{{ $index }}][siswa_id]"
                                                   value="{{ $siswa->id }}">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                     style="width:34px;height:34px;background:rgba(99,102,241,.1);">
                                                    <span class="fw-bold text-primary" style="font-size:.7rem;">
                                                        {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-semibold" style="line-height:1.2;">{{ $siswa->nama }}</p>
                                                    @if($existingNilai)
                                                        <small class="text-success">
                                                            <i class="bi bi-check-circle-fill me-1"></i>Sudah ada nilai
                                                        </small>
                                                    @else
                                                        <small class="text-muted">Belum diisi</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- NIS --}}
                                        <td class="py-3 border-0 text-center">
                                            <span class="badge rounded-pill px-3 py-1 fw-semibold"
                                                  style="background:rgba(99,102,241,.08);color:var(--clr-primary);font-size:.75rem;">
                                                {{ $siswa->nis ?? '-' }}
                                            </span>
                                        </td>

                                        {{-- Nilai Tugas --}}
                                        <td class="py-3 border-0 text-center">
                                            <input type="number"
                                                   name="nilai[{{ $index }}][nilai_tugas]"
                                                   class="form-control text-center nilai-tugas fw-semibold"
                                                   style="border-radius:10px;border-color:rgba(245,158,11,.4);width:90px;margin:0 auto;"
                                                   min="0" max="100" step="0.01"
                                                   placeholder="0-100"
                                                   value="{{ $prefillTugas }}"
                                                   data-row="{{ $index }}">
                                        </td>

                                        {{-- Nilai UTS --}}
                                        <td class="py-3 border-0 text-center">
                                            <input type="number"
                                                   name="nilai[{{ $index }}][nilai_uts]"
                                                   class="form-control text-center nilai-uts fw-semibold"
                                                   style="border-radius:10px;border-color:rgba(6,182,212,.4);width:90px;margin:0 auto;"
                                                   min="0" max="100" step="0.01"
                                                   placeholder="0-100"
                                                   value="{{ $prefillUts }}"
                                                   data-row="{{ $index }}">
                                        </td>

                                        {{-- Nilai UAS --}}
                                        <td class="py-3 border-0 text-center">
                                            <input type="number"
                                                   name="nilai[{{ $index }}][nilai_uas]"
                                                   class="form-control text-center nilai-uas fw-semibold"
                                                   style="border-radius:10px;border-color:rgba(99,102,241,.4);width:90px;margin:0 auto;"
                                                   min="0" max="100" step="0.01"
                                                   placeholder="0-100"
                                                   value="{{ $prefillUas }}"
                                                   data-row="{{ $index }}">
                                        </td>

                                        {{-- Preview Nilai Akhir --}}
                                        <td class="py-3 border-0 text-center">
                                            <span class="preview-na fw-bold"
                                                  data-row="{{ $index }}"
                                                  style="font-size:1rem;color:var(--clr-primary);">
                                                @if($existingNilai)
                                                    @php
                                                        $na = (0.3*$prefillTugas) + (0.3*$prefillUts) + (0.4*$prefillUas);
                                                    @endphp
                                                    {{ number_format($na, 1) }}
                                                @else
                                                    —
                                                @endif
                                            </span>
                                        </td>

                                        {{-- Preview Status --}}
                                        <td class="pe-4 py-3 border-0 text-center">
                                            @if($existingNilai)
                                                @php $naExist = (0.3*$prefillTugas)+(0.3*$prefillUts)+(0.4*$prefillUas); @endphp
                                                @if($naExist >= 70)
                                                    <span class="preview-status badge-lulus" data-row="{{ $index }}">Lulus</span>
                                                @else
                                                    <span class="preview-status badge-tidak-lulus" data-row="{{ $index }}">Tidak Lulus</span>
                                                @endif
                                            @else
                                                <span class="preview-status badge rounded-pill px-3 py-1 fw-semibold"
                                                      data-row="{{ $index }}"
                                                      style="background:rgba(0,0,0,.07);color:#9ca3af;font-size:.75rem;">—</span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Form Footer --}}
            @if($siswas->isNotEmpty())
                <div class="card-footer border-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 p-4"
                     style="background:rgba(99,102,241,.03);">
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="bi bi-shield-check-fill text-success"></i>
                        <span>Data yang sudah ada akan <strong>diperbarui</strong>. Data baru akan <strong>disimpan</strong>.</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('guru.input-nilai') }}"
                           class="btn fw-semibold px-4"
                           style="background:rgba(0,0,0,.06);color:#6b7280;border-radius:10px;">
                            <i class="bi bi-arrow-left me-2"></i>Ganti Kelas
                        </a>
                        <button type="submit" class="btn btn-primary fw-semibold px-5">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i>Simpan Semua Nilai
                        </button>
                    </div>
                </div>
            @endif

        </form>
    </div>
@endif

@endsection

{{-- =====================================================================
     JAVASCRIPT — LIVE PREVIEW CALCULATION
====================================================================== --}}
@push('scripts')
<script>
/**
 * SIMPEL — Input Nilai Live Preview
 * Formula: NA = (0.3 × Tugas) + (0.3 × UTS) + (0.4 × UAS)
 * Status : LULUS if NA >= 70, else TIDAK LULUS
 */

/**
 * Calculate and update preview for a single row.
 * @param {number} rowIndex - The data-row index of the row being updated.
 */
function updatePreview(rowIndex) {
    const tugasInput  = document.querySelector(`.nilai-tugas[data-row="${rowIndex}"]`);
    const utsInput    = document.querySelector(`.nilai-uts[data-row="${rowIndex}"]`);
    const uasInput    = document.querySelector(`.nilai-uas[data-row="${rowIndex}"]`);
    const naDisplay   = document.querySelector(`.preview-na[data-row="${rowIndex}"]`);
    const stDisplay   = document.querySelector(`.preview-status[data-row="${rowIndex}"]`);

    if (!tugasInput || !utsInput || !uasInput) return;

    const tugas = parseFloat(tugasInput.value) || 0;
    const uts   = parseFloat(utsInput.value)   || 0;
    const uas   = parseFloat(uasInput.value)   || 0;

    // Check if at least one value is entered
    const hasInput = tugasInput.value !== '' || utsInput.value !== '' || uasInput.value !== '';

    if (!hasInput) {
        naDisplay.textContent = '—';
        naDisplay.style.color = '#9ca3af';
        stDisplay.className = 'preview-status badge rounded-pill px-3 py-1 fw-semibold';
        stDisplay.style.cssText = 'background:rgba(0,0,0,.07);color:#9ca3af;font-size:.75rem;';
        stDisplay.textContent = '—';
        return;
    }

    // NA formula
    const na = (0.3 * tugas) + (0.3 * uts) + (0.4 * uas);
    const lulus = na >= 70;

    // Update Nilai Akhir display
    naDisplay.textContent = na.toFixed(1);
    naDisplay.style.color = lulus ? 'var(--clr-success)' : 'var(--clr-danger)';

    // Update Status badge
    stDisplay.style.cssText = ''; // reset inline styles
    if (lulus) {
        stDisplay.className = 'preview-status badge-lulus';
        stDisplay.textContent = 'Lulus';
    } else {
        stDisplay.className = 'preview-status badge-tidak-lulus';
        stDisplay.textContent = 'Tidak Lulus';
    }

    // Highlight row
    const row = tugasInput.closest('tr');
    if (row) {
        row.style.transition = 'background .3s';
        row.style.background = lulus
            ? 'rgba(16,185,129,.04)'
            : 'rgba(239,68,68,.04)';
    }
}

/**
 * Attach input event listeners to all nilai inputs.
 */
function attachListeners() {
    document.querySelectorAll('.nilai-tugas, .nilai-uts, .nilai-uas').forEach(function(input) {
        input.addEventListener('input', function() {
            updatePreview(parseInt(this.dataset.row));
        });
    });
}

/**
 * Helper: fill all inputs with 100 (for quick testing).
 */
function fillAllMax() {
    document.querySelectorAll('.nilai-tugas, .nilai-uts, .nilai-uas').forEach(function(input) {
        input.value = 100;
    });
    // Trigger update for all rows
    const rows = document.querySelectorAll('.nilai-row');
    rows.forEach(function(row) {
        updatePreview(parseInt(row.dataset.index));
    });
}

/**
 * Helper: clear all inputs.
 */
function clearAll() {
    if (!confirm('Reset semua nilai yang belum disimpan?')) return;
    document.querySelectorAll('.nilai-tugas, .nilai-uts, .nilai-uas').forEach(function(input) {
        input.value = '';
    });
    const rows = document.querySelectorAll('.nilai-row');
    rows.forEach(function(row) {
        updatePreview(parseInt(row.dataset.index));
    });
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    attachListeners();

    // Run initial calculation for pre-filled rows (existing nilai)
    document.querySelectorAll('.nilai-row').forEach(function(row) {
        const idx = parseInt(row.dataset.index);
        const tugasInput = document.querySelector(`.nilai-tugas[data-row="${idx}"]`);
        if (tugasInput && tugasInput.value !== '') {
            updatePreview(idx);
        }
    });
});
</script>
@endpush
