<?php

/**
 * ============================================================
 * FILE: GuruController.php
 * LOKASI: app/Http/Controllers/GuruController.php
 * ============================================================
 * Controller untuk fitur yang dapat diakses oleh Guru.
 *
 * FITUR GURU:
 *   - Dashboard dengan statistik input nilai
 *   - Input nilai siswa (Tugas, UTS, UAS) per kelas
 *   - Edit nilai yang sudah diinput
 *   - Lihat rekap nilai siswa pada mapel yang diampu
 *
 * BATASAN AKSES:
 *   - Guru HANYA dapat menginput nilai untuk mata pelajaran yang ia ampu
 *   - Guru TIDAK bisa mengubah data siswa atau guru lain
 *
 * PARADIGMA: OOP (Class Controller)
 * ============================================================
 */

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GuruController extends Controller
{
    // ============================================================
    // DASHBOARD GURU
    // ============================================================

    /**
     * Menampilkan dashboard guru.
     *
     * Informasi yang ditampilkan:
     * - Profil guru dan mata pelajaran yang diampu
     * - Statistik: jumlah siswa yang sudah diinput
     * - Daftar nilai yang baru saja diinput
     *
     * @return View
     */
    public function dashboard(): View
    {
        // Ambil profil guru dari user yang sedang login
        $guru = Auth::user()->guru;

        // Eager load relasi mata pelajaran
        $guru->load('mataPelajaran');

        // Hitung statistik input nilai guru ini
        $jumlahSudahDiinput = $guru->nilais()->count();
        $jumlahLulus = $guru->nilais()->where('status', 'LULUS')->count();
        $persenLulus = $jumlahSudahDiinput > 0
            ? round(($jumlahLulus / $jumlahSudahDiinput) * 100, 1)
            : 0;

        // Ambil daftar kelas unik dari siswa yang sudah diinput nilainya oleh guru ini
        $kelasYangSudahDiinput = $guru->nilais()
            ->with('siswa')
            ->get()
            ->pluck('siswa.kelas')
            ->unique()
            ->sort()
            ->values();

        // Nilai terbaru yang diinput oleh guru ini
        $nilaiTerbaru = $guru->nilais()
            ->with('siswa')
            ->latest()
            ->take(5)
            ->get();

        return view('guru.dashboard', compact(
            'guru',
            'jumlahSudahDiinput',
            'jumlahLulus',
            'persenLulus',
            'kelasYangSudahDiinput',
            'nilaiTerbaru'
        ));
    }

    // ============================================================
    // INPUT NILAI
    // ============================================================

    /**
     * Menampilkan form input nilai.
     *
     * Guru memilih kelas terlebih dahulu, lalu sistem menampilkan
     * daftar siswa di kelas tersebut beserta form input nilai.
     *
     * @param  Request $request  Mengandung filter kelas yang dipilih
     * @return View
     */
    public function inputNilai(Request $request): View
    {
        // Ambil profil guru dari user yang login
        $guru = Auth::user()->guru()->with('mataPelajaran')->first();

        // Ambil semua kelas unik dari tabel siswa
        $kelasList = Siswa::distinct()->orderBy('kelas')->pluck('kelas');

        $siswas    = collect(); // Default: kosong
        $kelasDipilih = $request->kelas;

        // Jika kelas sudah dipilih, ambil daftar siswa di kelas tersebut
        if ($request->filled('kelas')) {
            // Ambil siswa beserta nilai untuk mapel yang diampu guru ini (jika sudah ada)
            $siswas = Siswa::where('kelas', $request->kelas)
                ->with(['nilais' => function ($q) use ($guru) {
                    // Hanya ambil nilai untuk mapel yang diampu guru ini
                    $q->where('mata_pelajaran_id', $guru->mata_pelajaran_id);
                }])
                ->orderBy('nama')
                ->get();
        }

        return view('guru.input-nilai', compact(
            'guru',
            'kelasList',
            'siswas',
            'kelasDipilih'
        ));
    }

    /**
     * Menyimpan atau mengupdate nilai siswa.
     *
     * Menggunakan updateOrCreate() untuk menangani dua kasus:
     * 1. Nilai belum ada → CREATE (tambah baru)
     * 2. Nilai sudah ada → UPDATE (perbarui)
     *
     * Nilai akhir dan status dihitung OTOMATIS oleh event 'saving'
     * di Model Nilai (lihat app/Models/Nilai.php).
     *
     * Fungsi prosedural validasiNilai() dari NilaiHelper.php
     * digunakan untuk validasi tambahan.
     *
     * @param  Request           $request  Data nilai dari form
     * @return RedirectResponse
     */
    public function simpanNilai(Request $request): RedirectResponse
    {
        // Ambil profil guru yang sedang login
        $guru = Auth::user()->guru;

        // Validasi data dari form
        $request->validate([
            'kelas'       => 'required|string',
            'nilai'       => 'required|array',          // Array nilai per siswa
            'nilai.*.siswa_id'     => 'required|exists:siswas,id',
            'nilai.*.nilai_tugas'  => 'required|numeric|min:0|max:100',
            'nilai.*.nilai_uts'    => 'required|numeric|min:0|max:100',
            'nilai.*.nilai_uas'    => 'required|numeric|min:0|max:100',
        ], [
            'nilai.*.nilai_tugas.min'  => 'Nilai tugas tidak boleh kurang dari 0.',
            'nilai.*.nilai_tugas.max'  => 'Nilai tugas tidak boleh lebih dari 100.',
            'nilai.*.nilai_uts.min'    => 'Nilai UTS tidak boleh kurang dari 0.',
            'nilai.*.nilai_uts.max'    => 'Nilai UTS tidak boleh lebih dari 100.',
            'nilai.*.nilai_uas.min'    => 'Nilai UAS tidak boleh kurang dari 0.',
            'nilai.*.nilai_uas.max'    => 'Nilai UAS tidak boleh lebih dari 100.',
        ]);

        $jumlahDisimpan = 0;

        // Iterasi setiap nilai yang disubmit dari form
        foreach ($request->nilai as $nilaiData) {
            // Validasi tambahan menggunakan fungsi prosedural dari NilaiHelper.php
            // Ini adalah contoh integrasi antara OOP (Controller) dan Pemrograman Terstruktur (Helper)
            if (
                ! validasiNilai($nilaiData['nilai_tugas']) ||
                ! validasiNilai($nilaiData['nilai_uts'])   ||
                ! validasiNilai($nilaiData['nilai_uas'])
            ) {
                // Skip jika ada nilai yang tidak valid (sudah divalidasi Laravel, ini sebagai pengaman ekstra)
                continue;
            }

            // Simpan atau update nilai menggunakan updateOrCreate
            // Nilai akhir dan status akan dihitung otomatis oleh Model::boot()
            Nilai::updateOrCreate(
                // Kondisi pencarian: nilai untuk siswa ini pada mapel ini
                [
                    'siswa_id'          => $nilaiData['siswa_id'],
                    'mata_pelajaran_id' => $guru->mata_pelajaran_id,
                ],
                // Data yang akan disimpan/diupdate
                [
                    'guru_id'      => $guru->id,
                    'nilai_tugas'  => $nilaiData['nilai_tugas'],
                    'nilai_uts'    => $nilaiData['nilai_uts'],
                    'nilai_uas'    => $nilaiData['nilai_uas'],
                    // nilai_akhir dan status dihitung otomatis oleh Model::boot() -> saving event
                ]
            );

            $jumlahDisimpan++;
        }

        return redirect()->route('guru.input-nilai', ['kelas' => $request->kelas])
            ->with('success', "{$jumlahDisimpan} nilai siswa berhasil disimpan.");
    }

    // ============================================================
    // REKAP NILAI
    // ============================================================

    /**
     * Menampilkan rekap nilai siswa untuk mata pelajaran yang diampu guru.
     *
     * Guru hanya bisa melihat nilai untuk mapel yang ia ampu.
     * Dapat difilter berdasarkan kelas.
     *
     * @param  Request $request  Filter kelas
     * @return View
     */
    public function rekapNilai(Request $request): View
    {
        // Ambil profil guru
        $guru = Auth::user()->guru()->with('mataPelajaran')->first();

        // Ambil kelas yang tersedia
        $kelasList = Siswa::distinct()->orderBy('kelas')->pluck('kelas');

        // Query nilai yang diinput oleh guru ini
        $query = $guru->nilais()->with('siswa');

        // Filter berdasarkan kelas jika dipilih
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Gunakan join ke tabel siswas untuk pengurutan berdasarkan nama
        $nilais = $query
            ->join('siswas as s_order', 'nilais.siswa_id', '=', 's_order.id')
            ->orderBy('s_order.nama')
            ->select('nilais.*')
            ->paginate(15);


        // Hitung statistik
        $rataRata    = $guru->nilais()->avg('nilai_akhir') ?? 0;
        $jumlahLulus = $guru->nilais()->where('status', 'LULUS')->count();
        $total       = $guru->nilais()->count();

        return view('guru.rekap-nilai', compact(
            'guru',
            'nilais',
            'kelasList',
            'rataRata',
            'jumlahLulus',
            'total'
        ));
    }
}
