{{-- admin/siswas/index.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Data Siswa')
@section('page-breadcrumb', 'Admin · Manajemen Data Siswa')

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

    {{-- Header --}}
    <div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
        <div>
            <h1 class="page-header-title">Data Siswa</h1>
            <p class="page-header-sub">Kelola seluruh data siswa yang terdaftar dalam sistem.</p>
        </div>
        <a href="{{ route('admin.siswas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill"></i> Tambah Siswa
        </a>
    </div>

    {{-- Card Table --}}
    <div class="card">
        {{-- Search Bar --}}
        <div class="card-header">
            <form action="{{ route('admin.siswas') }}" method="GET" class="d-flex gap-2 align-items-center flex-wrap">
                <div class="search-wrap" style="max-width:320px;flex:1;">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari NIS, nama, atau kelas..."
                           value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.siswas') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-x"></i> Reset
                    </a>
                @endif
                <span class="ms-auto text-muted" style="font-size:0.78rem;">
                    Total: <strong>{{ $siswas->total() }}</strong> siswa
                </span>
            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:50px">#</th>
                        <th class="ps-3">Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Angkatan</th>
                        <th class="text-center" style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $siswa)
                        <tr>
                            <td class="ps-4 text-muted">
                                {{ ($siswas->currentPage() - 1) * $siswas->perPage() + $loop->iteration }}
                            </td>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                        $initials = strtoupper(substr($siswa->nama, 0, 2));
                                        $colors = ['#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#ec4899'];
                                        $color = $colors[crc32($siswa->nama) % count($colors)];
                                    @endphp
                                    <div class="avatar-initials" style="background:{{ $color }};color:#fff;">
                                        {{ $initials }}
                                    </div>
                                    <div class="fw-medium text-dark">{{ $siswa->nama }}</div>
                                </div>
                            </td>
                            <td><code>{{ $siswa->nis }}</code></td>
                            <td>
                                <span class="badge rounded-pill" style="background:rgba(99,102,241,0.1);color:#4338ca;font-size:0.75rem;">
                                    {{ $siswa->kelas }}
                                </span>
                            </td>
                            <td>{{ $siswa->angkatan ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.siswas.edit', $siswa) }}"
                                       class="btn btn-sm"
                                       style="background:rgba(245,158,11,0.1);color:#d97706;border:1px solid rgba(245,158,11,0.2);"
                                       title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm"
                                            style="background:rgba(239,68,68,0.08);color:#dc2626;border:1px solid rgba(239,68,68,0.15);"
                                            title="Hapus"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapus"
                                            data-id="{{ $siswa->id }}"
                                            data-nama="{{ $siswa->nama }}">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x" style="font-size:2.5rem;display:block;margin-bottom:0.5rem;opacity:0.3;"></i>
                                @if(request('search'))
                                    Tidak ada siswa dengan kata kunci "<strong>{{ request('search') }}</strong>".
                                @else
                                    Belum ada data siswa.
                                    <a href="{{ route('admin.siswas.create') }}" style="color:#6366f1;">Tambah sekarang?</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($siswas->hasPages())
            <div class="card-body border-top d-flex justify-content-between align-items-center py-3" style="border-color:#f3f4f6!important;">
                <small class="text-muted">
                    Menampilkan {{ $siswas->firstItem() }}–{{ $siswas->lastItem() }}
                    dari {{ $siswas->total() }} siswa
                </small>
                {{ $siswas->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border:none;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-body p-4 text-center">
                <div style="width:60px;height:60px;background:rgba(239,68,68,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.5rem;color:#ef4444;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h5 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:1.1rem;margin-bottom:0.5rem;">Hapus Siswa?</h5>
                <p style="color:#6b7280;font-size:0.85rem;margin-bottom:0.25rem;">Anda akan menghapus:</p>
                <p class="fw-700 text-danger" id="namaSiswaDihapus" style="font-size:1rem;margin-bottom:0.75rem;">-</p>
                <small style="color:#9ca3af;font-size:0.75rem;">
                    <i class="bi bi-info-circle"></i>
                    Seluruh data nilai siswa ini juga akan ikut terhapus. Tindakan ini <strong>tidak dapat dibatalkan</strong>.
                </small>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pb-4 px-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="flex:1;">
                    Batal
                </button>
                <form id="formHapusSiswa" method="POST" action="" style="flex:1;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash-fill"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('modalHapus').addEventListener('show.bs.modal', function(e) {
        const btn = e.relatedTarget;
        document.getElementById('namaSiswaDihapus').textContent = btn.dataset.nama;
        document.getElementById('formHapusSiswa').action = `/admin/siswas/${btn.dataset.id}`;
    });
</script>
@endpush
