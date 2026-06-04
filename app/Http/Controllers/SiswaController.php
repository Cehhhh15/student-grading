<?php

/**
 * ============================================================
 * FILE: SiswaController.php
 * LOKASI: app/Http/Controllers/SiswaController.php
 * ============================================================
 * Controller untuk fitur yang dapat diakses oleh Siswa.
 *
 * FITUR SISWA:
 *   - Dashboard dengan ringkasan nilai
 *   - Lihat nilai pribadi per mata pelajaran
 *   - Lihat status kelulusan
 *
 * BATASAN AKSES:
 *   - Siswa HANYA dapat melihat nilai DIRINYA SENDIRI
 *   - Tidak ada akses ke data siswa lain
 *   - Tidak ada akses ke fitur input atau manajemen data
 *
 * PARADIGMA: OOP (Class Controller)
 * ============================================================
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SiswaController extends Controller
{
    // ============================================================
    // DASHBOARD SISWA
    // ============================================================

    /**
     * Menampilkan dashboard siswa dengan ringkasan nilai.
     *
     * @return View
     */
    public function dashboard(): View
    {
        // Ambil profil siswa dari user yang sedang login
        $siswa = Auth::user()->siswa;

        // Eager load nilai beserta mata pelajaran untuk efisiensi query
        $siswa->load('nilais.mataPelajaran');

        // Hitung rata-rata nilai akhir semua mapel
        // Memanggil method OOP dari Model Siswa
        $rataRata = $siswa->getRataRataNilaiAkhir();

        // Cek apakah siswa lulus semua mata pelajaran
        // Memanggil method OOP dari Model Siswa
        $lulusSemua = $siswa->isLulusSemua();

        // Hitung berapa mapel yang sudah ada nilainya
        $jumlahNilai = $siswa->nilais()->count();
        $jumlahLulus = $siswa->nilais()->where('status', 'LULUS')->count();

        return view('siswa.dashboard', compact(
            'siswa',
            'rataRata',
            'lulusSemua',
            'jumlahNilai',
            'jumlahLulus'
        ));
    }

    // ============================================================
    // NILAI PRIBADI
    // ============================================================

    /**
     * Menampilkan seluruh nilai pribadi siswa yang login.
     *
     * Nilai ditampilkan per mata pelajaran dengan informasi lengkap:
     * - Nilai Tugas, UTS, UAS
     * - Nilai Akhir (sudah dihitung otomatis)
     * - Grade Huruf (A/B/C/D/E)
     * - Status Kelulusan (LULUS / TIDAK LULUS)
     *
     * Fungsi prosedural konversiNilaiKeHuruf() dipanggil dari view
     * melalui helper global untuk menampilkan grade.
     *
     * @return View
     */
    public function nilaiSaya(): View
    {
        // Ambil profil siswa
        $siswa = Auth::user()->siswa;

        // Ambil semua nilai siswa ini beserta relasi mata pelajaran
        $nilais = $siswa->nilais()
            ->with('mataPelajaran')
            ->orderBy('mata_pelajaran_id')
            ->get();

        // Hitung statistik ringkasan
        $rataRata    = $siswa->getRataRataNilaiAkhir();
        $jumlahLulus = $nilais->where('status', 'LULUS')->count();
        $jumlahTotal = $nilais->count();

        return view('siswa.nilai-saya', compact(
            'siswa',
            'nilais',
            'rataRata',
            'jumlahLulus',
            'jumlahTotal'
        ));
    }
}
