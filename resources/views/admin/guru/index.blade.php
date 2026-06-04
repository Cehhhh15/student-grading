@extends('layouts.app')

@section('page-title', 'Data Guru')
@section('page-breadcrumb', 'Admin · Data Guru')

{{-- ============================================================
     SIDEBAR MENU
     ============================================================ --}}
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

{{-- ============================================================
     PAGE CONTENT
     ============================================================ --}}
@section('content')

{{-- ── PAGE HEADER ─────────────────────────────────────────────── --}}
<div class="page-header mb-4">
    <div>
        <h1 class="page-header-title">
            <i class="bi bi-person-badge-fill me-2" style="color:var(--clr-primary)"></i>Data Guru
        </h1>
        <p class="page-header-sub">Kelola seluruh data guru beserta mata pelajaran yang diampu.</p>
    </div>
    <a href="{{ route('admin.guru.create') }}" class="btn-primary d-flex align-items-center gap-2 px-3 py-2"
       style="border:none;border-radius:.75rem;cursor:pointer;font-weight:600;text-decoration:none;">
        <i class="bi bi-person-plus-fill"></i> Tambah Guru
    </a>
</div>

{{-- ── FLASH MESSAGES ───────────────────────────────────────────── --}}
@if(session('success'))
    <div class="alert d-flex align-items-center gap-2 mb-4 py-3 px-4"
         style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);border-radius:.875rem;color:#065f46;"
         role="alert">
        <i class="bi bi-check-circle-fill" style="color:var(--clr-success);font-size:1.15rem;flex-shrink:0;"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert d-flex align-items-center gap-2 mb-4 py-3 px-4"
         style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:.875rem;color:#7f1d1d;"
         role="alert">
        <i class="bi bi-exclamation-triangle-fill" style="color:var(--clr-danger);font-size:1.15rem;flex-shrink:0;"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ── MAIN CARD ─────────────────────────────────────────────────── --}}
<div class="card" style="border:none;box-shadow:0 4px 24px rgba(99,102,241,.08);border-radius:1rem;overflow:hidden;">

    {{-- Card Header: search + count --}}
    <div class="card-header py-3 px-4"
         style="background:linear-gradient(135deg,rgba(99,102,241,.06) 0%,rgba(6,182,212,.04) 100%);
                border-bottom:1px solid rgba(99,102,241,.1);">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
            {{-- Total badge --}}
            <div class="d-flex align-items-center gap-2">
                <span class="fw-700" style="color:var(--clr-primary);font-size:1rem;">
                    Total Guru
                </span>
                <span class="badge rounded-pill px-3 py-1"
                      style="background:linear-gradient(135deg,var(--clr-primary),var(--clr-accent));color:#fff;font-weight:700;font-size:.8rem;">
                    {{ $gurus->total() }}
                </span>
            </div>

            {{-- Search form --}}
            <form method="GET" action="{{ route('admin.guru') }}" class="d-flex gap-2">
                <div class="input-group" style="width:280px;">
                    <span class="input-group-text" style="background:rgba(99,102,241,.07);border-color:rgba(99,102,241,.2);color:var(--clr-primary);">
                        <i class="bi bi-search"></i>
                    </span>
                    <input
                        type="text"
                        class="form-control"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama / NIP / email…"
                        style="border-color:rgba(99,102,241,.2);"
                    >
                    @if(request('search'))
                        <a href="{{ route('admin.guru') }}" class="btn btn-outline-secondary" title="Hapus pencarian">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="btn px-3"
                        style="background:linear-gradient(135deg,var(--clr-primary),var(--clr-accent));color:#fff;border:none;border-radius:.65rem;font-weight:600;">
                    Cari
                </button>
            </form>
        </div>
    </div>

    {{-- Card Body: table --}}
    <div class="card-body p-0">
        @if($gurus->isEmpty())
            {{-- Empty state --}}
            <div class="text-center py-5 px-4">
                <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,rgba(99,102,241,.1),rgba(6,182,212,.1));
                            display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="bi bi-person-badge" style="font-size:2rem;color:var(--clr-primary);opacity:.6;"></i>
                </div>
                <h5 class="fw-700 mb-1" style="color:var(--clr-primary);">Belum Ada Data Guru</h5>
                <p class="text-muted mb-3">
                    @if(request('search'))
                        Tidak ada guru yang cocok dengan pencarian "<strong>{{ request('search') }}</strong>".
                    @else
                        Mulai tambahkan guru untuk memulai pengelolaan data.
                    @endif
                </p>
                @if(request('search'))
                    <a href="{{ route('admin.guru') }}" class="btn btn-outline-secondary me-2">Lihat Semua</a>
                @endif
                <a href="{{ route('admin.guru.create') }}" class="btn-primary px-4 py-2"
                   style="border:none;border-radius:.75rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem;">
                    <i class="bi bi-person-plus-fill"></i> Tambah Guru
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:.9rem;">
                    <thead>
                        <tr style="background:rgba(99,102,241,.04);border-bottom:2px solid rgba(99,102,241,.1);">
                            <th class="ps-4 py-3 fw-700 text-muted" style="font-size:.75rem;letter-spacing:.06em;text-transform:uppercase;width:50px;">#</th>
                            <th class="py-3 fw-700 text-muted" style="font-size:.75rem;letter-spacing:.06em;text-transform:uppercase;">Guru</th>
                            <th class="py-3 fw-700 text-muted" style="font-size:.75rem;letter-spacing:.06em;text-transform:uppercase;">NIP</th>
                            <th class="py-3 fw-700 text-muted" style="font-size:.75rem;letter-spacing:.06em;text-transform:uppercase;">Mata Pelajaran</th>
                            <th class="py-3 fw-700 text-muted" style="font-size:.75rem;letter-spacing:.06em;text-transform:uppercase;">Email</th>
                            <th class="py-3 pe-4 fw-700 text-muted text-end" style="font-size:.75rem;letter-spacing:.06em;text-transform:uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gurus as $index => $guru)
                        @php
                            // Build 2-letter avatar initials from nama
                            $words    = explode(' ', trim($guru->nama));
                            $initials = strtoupper(
                                count($words) >= 2
                                    ? substr($words[0], 0, 1) . substr($words[1], 0, 1)
                                    : substr($words[0], 0, 2)
                            );

                            // Cycle through accent colours for the avatar
                            $avatarColors = [
                                ['bg'=>'rgba(99,102,241,.15)',  'color'=>'var(--clr-primary)'],
                                ['bg'=>'rgba(6,182,212,.15)',   'color'=>'var(--clr-accent)'],
                                ['bg'=>'rgba(16,185,129,.15)',  'color'=>'var(--clr-success)'],
                                ['bg'=>'rgba(245,158,11,.15)',  'color'=>'var(--clr-warning)'],
                                ['bg'=>'rgba(239,68,68,.13)',   'color'=>'var(--clr-danger)'],
                            ];
                            $ac = $avatarColors[$index % count($avatarColors)];
                        @endphp
                        <tr style="border-bottom:1px solid rgba(99,102,241,.06); transition:background .15s;">
                            {{-- Row number --}}
                            <td class="ps-4 text-muted fw-600">
                                {{ $gurus->firstItem() + $index }}
                            </td>

                            {{-- Avatar + Nama --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width:42px;height:42px;border-radius:50%;background:{{ $ac['bg'] }};
                                                display:flex;align-items:center;justify-content:center;flex-shrink:0;
                                                font-weight:700;font-size:.9rem;color:{{ $ac['color'] }};letter-spacing:.02em;">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-700" style="color:var(--clr-dark,#1e293b);">{{ $guru->nama }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- NIP --}}
                            <td>
                                <code style="background:rgba(99,102,241,.07);color:var(--clr-primary);padding:.2rem .5rem;border-radius:.4rem;font-size:.82rem;font-weight:600;">
                                    {{ $guru->nip ?? '-' }}
                                </code>
                            </td>

                            {{-- Mata Pelajaran --}}
                            <td>
                                @if($guru->mataPelajaran)
                                    <span class="badge px-3 py-1"
                                          style="background:rgba(16,185,129,.12);color:var(--clr-success);font-weight:600;border-radius:.5rem;font-size:.8rem;">
                                        <i class="bi bi-book-fill me-1"></i>{{ $guru->mataPelajaran->nama }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic" style="font-size:.82rem;">— belum ditentukan —</span>
                                @endif
                            </td>

                            {{-- Email --}}
                            <td>
                                <span class="text-muted" style="font-size:.85rem;">
                                    <i class="bi bi-envelope me-1"></i>{{ $guru->user->email ?? '-' }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="pe-4 text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.guru.edit', $guru) }}"
                                       class="btn btn-sm d-flex align-items-center gap-1"
                                       style="background:rgba(99,102,241,.1);color:var(--clr-primary);border:none;border-radius:.6rem;font-weight:600;padding:.35rem .8rem;">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </a>
                                    {{-- Delete trigger --}}
                                    <button
                                        class="btn btn-sm d-flex align-items-center gap-1"
                                        style="background:rgba(239,68,68,.1);color:var(--clr-danger);border:none;border-radius:.6rem;font-weight:600;padding:.35rem .8rem;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapusGuru"
                                        data-id="{{ $guru->id }}"
                                        data-nama="{{ $guru->nama }}"
                                    >
                                        <i class="bi bi-trash3-fill"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($gurus->hasPages())
            <div class="d-flex align-items-center justify-content-between px-4 py-3"
                 style="border-top:1px solid rgba(99,102,241,.08);">
                <small class="text-muted">
                    Menampilkan {{ $gurus->firstItem() }}–{{ $gurus->lastItem() }} dari {{ $gurus->total() }} guru
                </small>
                <div class="pagination-wrapper">
                    {{ $gurus->appends(request()->query())->links() }}
                </div>
            </div>
            @endif

        @endif
    </div>{{-- /card-body --}}
</div>{{-- /card --}}

{{-- ================================================================
     MODAL: Konfirmasi Hapus Guru
     ================================================================ --}}
<div class="modal fade" id="modalHapusGuru" tabindex="-1" aria-labelledby="modalHapusGuruLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content" style="border:none;border-radius:1.25rem;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.18);">

            {{-- Modal Header --}}
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:50%;background:rgba(239,68,68,.12);
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:1.4rem;color:var(--clr-danger);"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-700 mb-0" id="modalHapusGuruLabel" style="color:var(--clr-danger);">
                            Hapus Data Guru
                        </h5>
                        <small class="text-muted">Tindakan ini tidak dapat diurungkan</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body px-4 py-3">
                <p class="mb-0 text-muted" style="font-size:.95rem;line-height:1.6;">
                    Anda akan menghapus data guru
                    <strong id="namaGuruModal" style="color:var(--clr-dark,#1e293b);"></strong>.
                    Seluruh data yang terkait dengan guru ini juga akan ikut terhapus.
                </p>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-1 gap-2">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    Batal
                </button>
                <form id="formHapusGuru" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn px-4 d-flex align-items-center gap-2"
                            style="background:var(--clr-danger);color:#fff;border:none;border-radius:.75rem;font-weight:600;">
                        <i class="bi bi-trash3-fill"></i> Ya, Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Populate modal with guru data before it shows
    const modalHapusGuru = document.getElementById('modalHapusGuru');
    modalHapusGuru?.addEventListener('show.bs.modal', function (event) {
        const trigger     = event.relatedTarget;
        const guruId      = trigger.getAttribute('data-id');
        const guruNama    = trigger.getAttribute('data-nama');
        const form        = document.getElementById('formHapusGuru');
        const namaSpan    = document.getElementById('namaGuruModal');

        // Set the form action dynamically
        form.action       = `/admin/guru/${guruId}`;
        namaSpan.textContent = guruNama;
    });
</script>
@endpush
