<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Nilai Siswa - SIMPEL</title>
    <style>
        /* PDF-friendly inline styles for DomPDF */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            background: #fff;
        }

        /* ── Header ── */
        .doc-header {
            border-bottom: 3px solid #6366f1;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-box {
            width: 36px; height: 36px;
            background: #6366f1;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 16pt; font-weight: bold;
        }
        .logo-text-wrap {}
        .logo-name {
            font-size: 16pt;
            font-weight: 900;
            color: #4338ca;
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .logo-sub {
            font-size: 7pt;
            color: #94a3b8;
            margin-top: 2px;
        }
        .doc-meta {
            text-align: right;
            font-size: 7.5pt;
            color: #64748b;
            line-height: 1.6;
        }
        .doc-title {
            margin-top: 10px;
        }
        .doc-title h1 {
            font-size: 13pt;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.3px;
        }
        .doc-title .filter-info {
            font-size: 8pt;
            color: #64748b;
            margin-top: 3px;
        }

        /* ── Summary Strip ── */
        .summary-strip {
            display: flex;
            gap: 10px;
            margin-bottom: 14px;
        }
        .summary-box {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            text-align: center;
        }
        .summary-box .s-num {
            font-size: 14pt;
            font-weight: 900;
            color: #4338ca;
        }
        .summary-box .s-lbl {
            font-size: 6.5pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .summary-box.lulus .s-num { color: #059669; }
        .summary-box.tidak-lulus .s-num { color: #dc2626; }
        .summary-box.persen .s-num { color: #0891b2; }

        /* ── Table ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }
        .data-table thead tr {
            background: #f8faff;
            border-bottom: 2px solid #6366f1;
        }
        .data-table thead th {
            padding: 7px 8px;
            text-align: left;
            font-weight: 700;
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #4338ca;
        }
        .data-table thead th.center { text-align: center; }
        .data-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
        }
        .data-table tbody tr:nth-child(even) {
            background: #fafbff;
        }
        .data-table tbody tr:hover {
            background: #eff6ff;
        }
        .data-table tbody td {
            padding: 6px 8px;
            vertical-align: middle;
        }
        .data-table tbody td.center { text-align: center; }
        .data-table tbody td.num { text-align: center; font-weight: 600; }

        /* Status badges */
        .badge-lulus {
            display: inline-block;
            padding: 2px 8px;
            background: #d1fae5;
            color: #065f46;
            border-radius: 20px;
            font-size: 7pt;
            font-weight: 700;
        }
        .badge-tidak-lulus {
            display: inline-block;
            padding: 2px 8px;
            background: #fee2e2;
            color: #991b1b;
            border-radius: 20px;
            font-size: 7pt;
            font-weight: 700;
        }

        /* Grade badges */
        .grade {
            display: inline-block;
            width: 20px; height: 20px;
            border-radius: 5px;
            text-align: center;
            line-height: 20px;
            font-weight: 800;
            font-size: 8pt;
        }
        .grade-a { background: #d1fae5; color: #065f46; }
        .grade-b { background: #dbeafe; color: #1e40af; }
        .grade-c { background: #fef3c7; color: #92400e; }
        .grade-d { background: #fed7aa; color: #9a3412; }
        .grade-e { background: #fee2e2; color: #991b1b; }

        /* Nilai akhir coloring */
        .na-lulus { color: #059669; font-weight: 800; }
        .na-tidak  { color: #dc2626; font-weight: 800; }

        /* ── Footer ── */
        .doc-footer {
            margin-top: 16px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            font-size: 7pt;
            color: #94a3b8;
        }
        .footer-sig {
            text-align: right;
        }
        .sig-line {
            width: 120px;
            border-bottom: 1px solid #1e293b;
            margin-bottom: 4px;
            margin-top: 30px;
            margin-left: auto;
        }
    </style>
</head>
<body>

{{-- ── Document Header ── --}}
<div class="doc-header">
    <div class="header-top">
        <div class="logo-area">
            <div class="logo-box">S</div>
            <div class="logo-text-wrap">
                <div class="logo-name">SIMPEL</div>
                <div class="logo-sub">Sistem Informasi Manajemen Pelajar</div>
            </div>
        </div>
        <div class="doc-meta">
            <div>Tanggal Cetak: <strong>{{ $tanggal }}</strong></div>
            <div>Kelas: <strong>{{ $kelas }}</strong></div>
            <div>Jumlah Data: <strong>{{ count($nilais) }} siswa</strong></div>
        </div>
    </div>
    <div class="doc-title">
        <h1>Laporan Hasil Belajar Siswa</h1>
        <div class="filter-info">
            KKM: 70 · Rumus: NA = (30% × Tugas) + (30% × UTS) + (40% × UAS)
        </div>
    </div>
</div>

{{-- ── Summary ── --}}
@php
    $total   = count($nilais);
    $lulus   = collect($nilais)->where('status','LULUS')->count();
    $tLulus  = $total - $lulus;
    $persen  = $total > 0 ? round(($lulus / $total) * 100, 1) : 0;
@endphp
<div class="summary-strip">
    <div class="summary-box">
        <div class="s-num">{{ $total }}</div>
        <div class="s-lbl">Total Data</div>
    </div>
    <div class="summary-box lulus">
        <div class="s-num">{{ $lulus }}</div>
        <div class="s-lbl">Lulus</div>
    </div>
    <div class="summary-box tidak-lulus">
        <div class="s-num">{{ $tLulus }}</div>
        <div class="s-lbl">Tidak Lulus</div>
    </div>
    <div class="summary-box persen">
        <div class="s-num">{{ $persen }}%</div>
        <div class="s-lbl">% Kelulusan</div>
    </div>
</div>

{{-- ── Data Table ── --}}
<table class="data-table">
    <thead>
        <tr>
            <th style="width:25px;">No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Mata Pelajaran</th>
            <th class="center" style="width:40px;">Tugas</th>
            <th class="center" style="width:35px;">UTS</th>
            <th class="center" style="width:35px;">UAS</th>
            <th class="center" style="width:50px;">N. Akhir</th>
            <th class="center" style="width:30px;">Grade</th>
            <th class="center" style="width:60px;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($nilais as $i => $nilai)
            <tr>
                <td class="center" style="color:#94a3b8;">{{ $i + 1 }}</td>
                <td style="font-family:monospace;color:#4338ca;font-size:8pt;">{{ $nilai['nis'] }}</td>
                <td style="font-weight:600;">{{ $nilai['nama'] }}</td>
                <td>{{ $nilai['kelas'] }}</td>
                <td>{{ $nilai['mata_pelajaran'] }}</td>
                <td class="num">{{ $nilai['nilai_tugas'] }}</td>
                <td class="num">{{ $nilai['nilai_uts'] }}</td>
                <td class="num">{{ $nilai['nilai_uas'] }}</td>
                <td class="center">
                    <span class="{{ $nilai['nilai_akhir'] >= 70 ? 'na-lulus' : 'na-tidak' }}">
                        {{ number_format($nilai['nilai_akhir'], 1) }}
                    </span>
                </td>
                <td class="center">
                    <span class="grade grade-{{ strtolower($nilai['grade']) }}">{{ $nilai['grade'] }}</span>
                </td>
                <td class="center">
                    @if($nilai['status'] === 'LULUS')
                        <span class="badge-lulus">LULUS</span>
                    @else
                        <span class="badge-tidak-lulus">TDK LULUS</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" style="text-align:center;padding:20px;color:#94a3b8;">
                    Tidak ada data nilai.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- ── Footer ── --}}
<div class="doc-footer">
    <div>
        <div>Dicetak dari SIMPEL — Sistem Informasi Manajemen Pelajar</div>
        <div>Tanggal: {{ $tanggal }}</div>
    </div>
    <div class="footer-sig">
        <div>Mengetahui,</div>
        <div class="sig-line"></div>
        <div>Kepala Sekolah</div>
    </div>
</div>

</body>
</html>
