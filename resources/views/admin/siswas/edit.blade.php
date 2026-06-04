@extends('layouts.app')

@section('page-title', 'Edit Siswa')
@section('page-breadcrumb', 'Admin · Data Siswa · Edit')

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
<div class="page-header mb-4">
    <div>
        <h1 class="page-header-title">
            <i class="bi bi-pencil-square me-2" style="color:var(--clr-primary)"></i>Edit Siswa
        </h1>
        <p class="page-header-sub">
            Mengubah data untuk siswa
            <strong style="color:var(--clr-primary);">{{ $siswa->nama }}</strong>
            &mdash; NIS {{ $siswa->nis }}
        </p>
    </div>
    <a href="{{ route('admin.siswas') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form action="{{ route('admin.siswas.update', $siswa) }}" method="POST" id="formEditSiswa" novalidate>
    @csrf
    @method('PUT')

    {{-- ── ROW: two cards side by side on lg+ ─────────────────── --}}
    <div class="row g-4">

        {{-- ── CARD 1 · DATA SISWA ──────────────────────────────── --}}
        <div class="col-12 col-lg-6">
            <div class="card h-100" style="border:none; box-shadow:0 4px 24px rgba(99,102,241,.08); border-radius:1rem; overflow:hidden;">
                {{-- Card Header --}}
                <div class="card-header d-flex align-items-center gap-3 py-3 px-4"
                     style="background:linear-gradient(135deg,rgba(99,102,241,.12) 0%,rgba(6,182,212,.08) 100%);
                            border-bottom:1px solid rgba(99,102,241,.12);">
                    <div style="width:38px;height:38px;border-radius:.75rem;background:linear-gradient(135deg,var(--clr-primary),var(--clr-accent));
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;
                                box-shadow:0 4px 12px rgba(99,102,241,.35);">
                        <i class="bi bi-person-vcard-fill text-white" style="font-size:1rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-700" style="color:var(--clr-primary);letter-spacing:.01em;">Data Siswa</h6>
                        <small class="text-muted">Identitas akademik siswa</small>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-4">

                    {{-- NIS --}}
                    <div class="mb-4">
                        <label for="nis" class="form-label fw-600">
                            NIS <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:rgba(99,102,241,.07);border-color:rgba(99,102,241,.2);color:var(--clr-primary);">
                                <i class="bi bi-hash"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control @error('nis') is-invalid @enderror"
                                id="nis"
                                name="nis"
                                value="{{ old('nis', $siswa->nis) }}"
                                placeholder="Contoh: 2425001"
                                autocomplete="off"
                                style="border-color:rgba(99,102,241,.2);"
                            >
                            @error('nis')
                                <div class="invalid-feedback d-flex align-items-center gap-1">
                                    <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label for="nama" class="form-label fw-600">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:rgba(99,102,241,.07);border-color:rgba(99,102,241,.2);color:var(--clr-primary);">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control @error('nama') is-invalid @enderror"
                                id="nama"
                                name="nama"
                                value="{{ old('nama', $siswa->nama) }}"
                                placeholder="Nama siswa sesuai rapor"
                                style="border-color:rgba(99,102,241,.2);"
                            >
                            @error('nama')
                                <div class="invalid-feedback d-flex align-items-center gap-1">
                                    <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Kelas --}}
                    <div class="mb-2">
                        <label for="kelas" class="form-label fw-600">
                            Kelas <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:rgba(99,102,241,.07);border-color:rgba(99,102,241,.2);color:var(--clr-primary);">
                                <i class="bi bi-building-fill"></i>
                            </span>
                            <select
                                class="form-select @error('kelas') is-invalid @enderror"
                                id="kelas"
                                name="kelas"
                                style="border-color:rgba(99,102,241,.2);"
                            >
                                <option value="" disabled>— Pilih Kelas —</option>
                                @foreach(['X-A','X-B','X-C','XI-A','XI-B','XI-C','XII-A','XII-B','XII-C'] as $k)
                                    <option value="{{ $k }}" {{ old('kelas', $siswa->kelas) === $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                            @error('kelas')
                                <div class="invalid-feedback d-flex align-items-center gap-1">
                                    <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- ---- Field Angkatan ---- --}}
                        <div class="mt-4">
                            <label for="angkatan" class="form-label fw-medium">
                                Angkatan <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   id="angkatan"
                                   name="angkatan"
                                   class="form-control @error('angkatan') is-invalid @enderror"
                                   placeholder="Contoh: 2024"
                                   value="{{ old('angkatan', $siswa->angkatan) }}"
                                   min="2000"
                                   max="2100"
                                   required>
                            @error('angkatan')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>{{-- /card-body --}}
            </div>{{-- /card --}}
        </div>{{-- /col --}}

        {{-- ── CARD 2 · AKUN LOGIN ───────────────────────────────── --}}
        <div class="col-12 col-lg-6">
            <div class="card h-100" style="border:none; box-shadow:0 4px 24px rgba(6,182,212,.08); border-radius:1rem; overflow:hidden;">
                {{-- Card Header --}}
                <div class="card-header d-flex align-items-center gap-3 py-3 px-4"
                     style="background:linear-gradient(135deg,rgba(6,182,212,.10) 0%,rgba(99,102,241,.07) 100%);
                            border-bottom:1px solid rgba(6,182,212,.15);">
                    <div style="width:38px;height:38px;border-radius:.75rem;background:linear-gradient(135deg,var(--clr-accent),var(--clr-primary));
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;
                                box-shadow:0 4px 12px rgba(6,182,212,.35);">
                        <i class="bi bi-shield-lock-fill text-white" style="font-size:1rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-700" style="color:var(--clr-accent);letter-spacing:.01em;">Akun Login</h6>
                        <small class="text-muted">Kredensial untuk masuk ke sistem</small>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-4">

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="form-label fw-600">
                            Email <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:rgba(6,182,212,.07);border-color:rgba(6,182,212,.25);color:var(--clr-accent);">
                                <i class="bi bi-envelope-fill"></i>
                            </span>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email', $siswa->user->email ?? '') }}"
                                placeholder="contoh@sekolah.sch.id"
                                style="border-color:rgba(6,182,212,.25);"
                            >
                            @error('email')
                                <div class="invalid-feedback d-flex align-items-center gap-1">
                                    <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Password Note --}}
                    <div class="alert d-flex align-items-start gap-2 mb-4 py-2 px-3"
                         style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);border-radius:.75rem;color:#92400e;">
                        <i class="bi bi-info-circle-fill mt-1" style="color:var(--clr-warning);flex-shrink:0;"></i>
                        <small>
                            <strong>Password bersifat opsional.</strong>
                            Kosongkan jika tidak ingin mengubah password saat ini.
                        </small>
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="form-label fw-600">
                            Password Baru
                            <span class="badge ms-1" style="background:rgba(6,182,212,.12);color:var(--clr-accent);font-size:.65rem;font-weight:600;">Opsional</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:rgba(6,182,212,.07);border-color:rgba(6,182,212,.25);color:var(--clr-accent);">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                placeholder="Isi untuk mengganti password"
                                style="border-color:rgba(6,182,212,.25);"
                            >
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                                    style="border-color:rgba(6,182,212,.25);" title="Tampilkan/sembunyikan password">
                                <i class="bi bi-eye-fill" id="eyeIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback d-flex align-items-center gap-1">
                                    <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-2">
                        <label for="password_confirmation" class="form-label fw-600">
                            Konfirmasi Password Baru
                            <span class="badge ms-1" style="background:rgba(6,182,212,.12);color:var(--clr-accent);font-size:.65rem;font-weight:600;">Opsional</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:rgba(6,182,212,.07);border-color:rgba(6,182,212,.25);color:var(--clr-accent);">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Ulangi password baru"
                                style="border-color:rgba(6,182,212,.25);"
                            >
                        </div>
                    </div>

                </div>{{-- /card-body --}}
            </div>{{-- /card --}}
        </div>{{-- /col --}}

    </div>{{-- /row --}}

    {{-- ── ACTION BUTTONS ───────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-end gap-3 mt-4">
        <a href="{{ route('admin.siswas') }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-x-lg me-1"></i> Batal
        </a>
        <button type="submit" class="btn-primary d-flex align-items-center gap-2 px-4 py-2" style="border:none;border-radius:.75rem;cursor:pointer;font-weight:600;">
            <i class="bi bi-check2-circle"></i> Simpan Perubahan
        </button>
    </div>

</form>
@endsection

@push('scripts')
<script>
    // Toggle show/hide password
    document.getElementById('togglePassword')?.addEventListener('click', function () {
        const pwdInput = document.getElementById('password');
        const eyeIcon  = document.getElementById('eyeIcon');
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            eyeIcon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
        } else {
            pwdInput.type = 'password';
            eyeIcon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
        }
    });
</script>
@endpush
