@extends('layouts.app')

@section('page-title', 'Edit Guru')
@section('page-breadcrumb', 'Admin · Data Guru · Edit')

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
            <i class="bi bi-pencil-square me-2" style="color:var(--clr-primary)"></i>Edit Guru
        </h1>
        <p class="page-header-sub">
            Mengubah data untuk guru
            <strong style="color:var(--clr-primary);">{{ $guru->nama }}</strong>
            &mdash; NIP {{ $guru->nip ?? '-' }}
        </p>
    </div>
    <a href="{{ route('admin.guru') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form action="{{ route('admin.guru.update', $guru) }}" method="POST" id="formEditGuru" novalidate>
    @csrf
    @method('PUT')

    {{-- ── ROW: two cards side by side on lg+ ─────────────────── --}}
    <div class="row g-4">

        {{-- ── CARD 1 · DATA GURU ───────────────────────────────── --}}
        <div class="col-12 col-lg-6">
            <div class="card h-100" style="border:none; box-shadow:0 4px 24px rgba(99,102,241,.08); border-radius:1rem; overflow:hidden;">
                {{-- Card Header --}}
                <div class="card-header d-flex align-items-center gap-3 py-3 px-4"
                     style="background:linear-gradient(135deg,rgba(99,102,241,.12) 0%,rgba(6,182,212,.08) 100%);
                            border-bottom:1px solid rgba(99,102,241,.12);">
                    <div style="width:38px;height:38px;border-radius:.75rem;background:linear-gradient(135deg,var(--clr-primary),var(--clr-accent));
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;
                                box-shadow:0 4px 12px rgba(99,102,241,.35);">
                        <i class="bi bi-person-badge-fill text-white" style="font-size:1rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-700" style="color:var(--clr-primary);letter-spacing:.01em;">Data Guru</h6>
                        <small class="text-muted">Identitas dan mata pelajaran guru</small>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-4">

                    {{-- NIP --}}
                    <div class="mb-4">
                        <label for="nip" class="form-label fw-600">
                            NIP <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:rgba(99,102,241,.07);border-color:rgba(99,102,241,.2);color:var(--clr-primary);">
                                <i class="bi bi-hash"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control @error('nip') is-invalid @enderror"
                                id="nip"
                                name="nip"
                                value="{{ old('nip', $guru->nip) }}"
                                placeholder="Nomor Induk Pegawai"
                                autocomplete="off"
                                style="border-color:rgba(99,102,241,.2);"
                            >
                            @error('nip')
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
                                value="{{ old('nama', $guru->nama) }}"
                                placeholder="Nama lengkap guru"
                                style="border-color:rgba(99,102,241,.2);"
                            >
                            @error('nama')
                                <div class="invalid-feedback d-flex align-items-center gap-1">
                                    <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Mata Pelajaran --}}
                    <div class="mb-2">
                        <label for="mata_pelajaran_id" class="form-label fw-600">
                            Mata Pelajaran <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="background:rgba(99,102,241,.07);border-color:rgba(99,102,241,.2);color:var(--clr-primary);">
                                <i class="bi bi-book-fill"></i>
                            </span>
                            <select
                                class="form-select @error('mata_pelajaran_id') is-invalid @enderror"
                                id="mata_pelajaran_id"
                                name="mata_pelajaran_id"
                                style="border-color:rgba(99,102,241,.2);"
                            >
                                <option value="" disabled>— Pilih Mata Pelajaran —</option>
                                @foreach($mapels as $mapel)
                                    <option value="{{ $mapel->id }}"
                                        {{ old('mata_pelajaran_id', $guru->mata_pelajaran_id) == $mapel->id ? 'selected' : '' }}>
                                        {{ $mapel->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mata_pelajaran_id')
                                <div class="invalid-feedback d-flex align-items-center gap-1">
                                    <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @if($mapels->isEmpty())
                            <div class="mt-2 d-flex align-items-center gap-1" style="color:var(--clr-warning);font-size:.82rem;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Belum ada mata pelajaran.
                                <a href="{{ route('admin.mapels.create') }}" class="fw-600" style="color:var(--clr-primary);">Tambah sekarang</a>
                            </div>
                        @endif
                    </div>

                    {{-- Kelas Mengajar --}}
                    <div class="mt-4">
                        <label class="form-label fw-600">
                            Kelas Mengajar <span class="text-danger">*</span>
                        </label>
                        <div class="card card-body p-3 @error('kelas_mengajar') border-danger @enderror" style="background:rgba(99,102,241,.03);border-color:rgba(99,102,241,.2);">
                            <div class="row g-2">
                                @foreach($kelasList as $kelas)
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="kelas_mengajar[]" value="{{ $kelas }}" id="kelas_{{ $kelas }}" {{ in_array($kelas, old('kelas_mengajar', $guruKelas)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="kelas_{{ $kelas }}">
                                            {{ $kelas }} <small class="text-muted" style="font-size:0.75em;">(Angkatan {{ $angkatanPerKelas[$kelas] ?? 'Kosong' }})</small>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @error('kelas_mengajar')
                            <div class="text-danger mt-1" style="font-size:.875em;">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                            </div>
                        @enderror
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
                                value="{{ old('email', $guru->user->email ?? '') }}"
                                placeholder="email@sekolah.sch.id"
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

                    {{-- Password Baru --}}
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
        <a href="{{ route('admin.guru') }}" class="btn btn-outline-secondary px-4">
            <i class="bi bi-x-lg me-1"></i> Batal
        </a>
        <button type="submit" class="btn-primary d-flex align-items-center gap-2 px-4 py-2"
                style="border:none;border-radius:.75rem;cursor:pointer;font-weight:600;">
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

    // Check taken classes logic
    const takenClassesList = @json($takenClassesList);
    const angkatanMapping = @json($angkatanPerKelas);
    const mapelSelect = document.getElementById('mata_pelajaran_id');
    const checkboxes = document.querySelectorAll('input[name="kelas_mengajar[]"]');

    function updateCheckboxes() {
        const selectedMapel = mapelSelect.value;
        if (!selectedMapel) return;

        // Get classes already taken for the selected mapel
        const takenClasses = takenClassesList
            .filter(item => item.mapel_id == selectedMapel)
            .map(item => item.kelas);

        checkboxes.forEach(cb => {
            const isTaken = takenClasses.includes(cb.value);
            cb.disabled = isTaken;
            const label = document.querySelector(`label[for="${cb.id}"]`);
            let angkatan = angkatanMapping[cb.value] || 'Kosong';
            let angkatanHtml = `<small class="text-muted" style="font-size:0.75em;">(Angkatan ${angkatan})</small>`;
            
            if (isTaken) {
                // If it is taken, we shouldn't uncheck it ONLY IF it was somehow checked initially for editing, 
                // but since takenClassesList excludes the current guru's classes in gurusEdit, it won't mark the current guru's classes as disabled.
                cb.checked = false;
                label.innerHTML = `${cb.value} ${angkatanHtml} <span class="badge bg-danger ms-1" style="font-size:0.6rem">Penuh</span>`;
                label.style.opacity = '0.5';
            } else {
                label.innerHTML = `${cb.value} ${angkatanHtml}`;
                label.style.opacity = '1';
            }
        });
    }

    mapelSelect.addEventListener('change', updateCheckboxes);
    
    // Run on initial load
    if (mapelSelect.value) {
        updateCheckboxes();
    }
</script>
@endpush
