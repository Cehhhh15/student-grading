@extends('layouts.app')

@section('page-title', 'Mata Pelajaran')
@section('page-breadcrumb', 'Admin · Master Data')

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
<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-header-title">
            <span class="me-2" style="color:var(--clr-primary);"><i class="bi bi-book-fill"></i></span>
            Mata Pelajaran
        </h1>
        <p class="page-header-sub mb-0">Kelola daftar mata pelajaran yang tersedia di sistem.</p>
    </div>
    <button type="button"
            class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle-fill"></i>
        <span>Tambah Mapel</span>
    </button>
</div>

{{-- ── Flash Messages ───────────────────────────────── --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 rounded-3 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 rounded-3 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ── Data Table Card ──────────────────────────────── --}}
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 py-3 px-4 rounded-top-4">
        <div class="card-header-title d-flex align-items-center gap-2">
            <i class="bi bi-table" style="color:var(--clr-primary);"></i>
            <span>Daftar Mata Pelajaran</span>
        </div>
        <span class="badge rounded-pill" style="background:var(--clr-primary);">
            {{ $mapels->total() }} Mapel
        </span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabelMapel">
                <thead>
                    <tr style="background: linear-gradient(90deg,#f0f0ff 0%,#f5f5ff 100%);">
                        <th class="ps-4 py-3" style="width:56px;">#</th>
                        <th class="py-3">Kode</th>
                        <th class="py-3">Nama Mata Pelajaran</th>
                        <th class="py-3 text-center" style="width:140px;">Jumlah Nilai</th>
                        <th class="py-3 text-center pe-4" style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mapels as $index => $mapel)
                        <tr class="mapel-row">
                            {{-- Row number accounts for pagination offset --}}
                            <td class="ps-4 text-muted small">
                                {{ $mapels->firstItem() + $index }}
                            </td>

                            {{-- Kode Mapel styled as a code badge --}}
                            <td>
                                <span class="badge rounded-3 fw-semibold px-2 py-1"
                                      style="background:rgba(99,102,241,0.12); color:var(--clr-primary); font-size:0.78rem; letter-spacing:.5px; font-family:monospace;">
                                    {{ strtoupper($mapel->kode) }}
                                </span>
                            </td>

                            {{-- Nama --}}
                            <td class="fw-medium">{{ $mapel->nama }}</td>

                            {{-- Jumlah Nilai --}}
                            <td class="text-center">
                                @if ($mapel->nilais_count > 0)
                                    <span class="badge rounded-pill"
                                          style="background:rgba(6,182,212,0.12); color:var(--clr-accent); font-size:0.8rem;">
                                        <i class="bi bi-journal-text me-1"></i>{{ $mapel->nilais_count }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center pe-4">
                                <div class="d-inline-flex gap-1">
                                    {{-- Edit button – passes data via data-* attributes, JS fills modal --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary rounded-3 btn-edit-mapel"
                                            title="Edit"
                                            data-id="{{ $mapel->id }}"
                                            data-kode="{{ $mapel->kode }}"
                                            data-nama="{{ $mapel->nama }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEdit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>

                                    {{-- Delete button --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger rounded-3 btn-hapus-mapel"
                                            title="Hapus"
                                            data-id="{{ $mapel->id }}"
                                            data-nama="{{ $mapel->nama }}"
                                            data-nilais-count="{{ $mapel->nilais_count }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center gap-2 text-muted">
                                    <i class="bi bi-inbox fs-1" style="opacity:.35;"></i>
                                    <span class="fw-medium">Belum ada mata pelajaran.</span>
                                    <button type="button"
                                            class="btn btn-sm btn-primary mt-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalTambah">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Sekarang
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($mapels->hasPages())
        <div class="card-footer bg-transparent border-0 px-4 py-3">
            {{ $mapels->links() }}
        </div>
    @endif
</div>


{{-- ════════════════════════════════════════════════════
     MODAL: TAMBAH MATA PELAJARAN
═══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0 pb-0"
                 style="background:linear-gradient(135deg,var(--clr-primary) 0%,#818cf8 100%);">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2" id="modalTambahLabel">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Mata Pelajaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.mapels.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body px-4 pt-4 pb-2">

                    {{-- Kode --}}
                    <div class="mb-3">
                        <label for="tambah_kode" class="form-label fw-semibold">
                            Kode Mapel <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="tambah_kode"
                               name="kode"
                               class="form-control rounded-3 @error('kode') is-invalid @enderror"
                               placeholder="mis. MTK, IPA, BIN"
                               value="{{ old('kode') }}"
                               maxlength="20"
                               required>
                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Kode unik singkat (maks. 20 karakter).</div>
                    </div>

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="tambah_nama" class="form-label fw-semibold">
                            Nama Mata Pelajaran <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="tambah_nama"
                               name="nama"
                               class="form-control rounded-3 @error('nama') is-invalid @enderror"
                               placeholder="mis. Matematika, IPA, Bahasa Indonesia"
                               value="{{ old('nama') }}"
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4 gap-2">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle-fill"></i> Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════════════
     MODAL: EDIT MATA PELAJARAN
     – JS fills #edit_id, #edit_kode, #edit_nama and
       updates the form action dynamically.
═══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0 pb-0"
                 style="background:linear-gradient(135deg,#0ea5e9 0%,var(--clr-accent) 100%);">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2" id="modalEditLabel">
                    <i class="bi bi-pencil-square"></i> Edit Mata Pelajaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Action is set dynamically by JS --}}
            <form id="formEdit" action="" method="POST" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 pt-4 pb-2">

                    {{-- Hidden ID (for reference; action URL carries the real ID) --}}
                    <input type="hidden" id="edit_id" name="id">

                    {{-- Kode --}}
                    <div class="mb-3">
                        <label for="edit_kode" class="form-label fw-semibold">
                            Kode Mapel <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="edit_kode"
                               name="kode"
                               class="form-control rounded-3"
                               placeholder="mis. MTK"
                               maxlength="20"
                               required>
                        <div class="form-text">Kode unik singkat (maks. 20 karakter).</div>
                    </div>

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label fw-semibold">
                            Nama Mata Pelajaran <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="edit_nama"
                               name="nama"
                               class="form-control rounded-3"
                               placeholder="mis. Matematika"
                               required>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4 gap-2">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn d-inline-flex align-items-center gap-2 rounded-3 px-4 text-white fw-semibold"
                            style="background:linear-gradient(135deg,#0ea5e9,var(--clr-accent));">
                        <i class="bi bi-save2-fill"></i> Perbarui
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════════════
     MODAL: HAPUS MATA PELAJARAN
     – JS fills the form action and the displayed name.
═══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header border-0 pb-0"
                 style="background:linear-gradient(135deg,#ef4444 0%,#f87171 100%);">
                <h5 class="modal-title text-white fw-bold d-flex align-items-center gap-2" id="modalHapusLabel">
                    <i class="bi bi-trash3-fill"></i> Hapus Mapel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 pt-4 text-center">
                <div class="mb-3" style="font-size:3rem; line-height:1;">
                    <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                </div>
                <p class="mb-1">Yakin ingin menghapus mata pelajaran</p>
                <p class="fw-bold fs-6 mb-1" id="hapus_nama_display" style="color:var(--clr-danger, #ef4444);">—</p>
                <p id="hapus_warning_nilais" class="text-danger small d-none">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    Mapel ini memiliki data nilai terkait yang juga akan terhapus!
                </p>
                <p class="text-muted small mb-0">Tindakan ini <strong>tidak dapat</strong> dibatalkan.</p>
            </div>

            <form id="formHapus" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer border-0 px-4 pb-4 gap-2 justify-content-center">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-trash3-fill"></i> Hapus
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection


{{-- ─────────────────────────────────────────────────────
     SCRIPTS  –  Populate Edit & Hapus modals via JS
────────────────────────────────────────────────────── --}}
@push('scripts')
<script>
(function () {
    'use strict';

    // Base route templates injected from PHP (avoids hardcoding URLs in JS)
    const UPDATE_ROUTE = "{{ url('admin/mapels') }}/";   // + {id}
    const DESTROY_ROUTE = "{{ url('admin/mapels') }}/";  // + {id}

    // ── Edit Modal ─────────────────────────────────────────────────────────
    document.querySelectorAll('.btn-edit-mapel').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id   = this.dataset.id;
            const kode = this.dataset.kode;
            const nama = this.dataset.nama;

            // Populate fields
            document.getElementById('edit_id').value   = id;
            document.getElementById('edit_kode').value = kode;
            document.getElementById('edit_nama').value = nama;

            // Set form action dynamically
            document.getElementById('formEdit').action = UPDATE_ROUTE + id;
        });
    });

    // ── Hapus Modal ────────────────────────────────────────────────────────
    document.querySelectorAll('.btn-hapus-mapel').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id          = this.dataset.id;
            const nama        = this.dataset.nama;
            const nilaisCount = parseInt(this.dataset.nilaisCount, 10) || 0;

            // Display mapel name in confirmation text
            document.getElementById('hapus_nama_display').textContent = '"' + nama + '"?';

            // Warn if this mapel has linked nilai records
            const warningEl = document.getElementById('hapus_warning_nilais');
            if (nilaisCount > 0) {
                warningEl.classList.remove('d-none');
            } else {
                warningEl.classList.add('d-none');
            }

            // Set destroy form action
            document.getElementById('formHapus').action = DESTROY_ROUTE + id;
        });
    });

    // ── Auto-open Tambah modal if there were validation errors
    //    (session carries old input, implying failed store attempt)
    @if ($errors->any() && !old('_method'))
        var modalTambah = new bootstrap.Modal(document.getElementById('modalTambah'));
        modalTambah.show();
    @endif

})();
</script>
@endpush
