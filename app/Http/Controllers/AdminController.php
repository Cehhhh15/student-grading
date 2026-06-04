<?php

/**
 * ============================================================
 * FILE: AdminController.php
 * LOKASI: app/Http/Controllers/AdminController.php
 * ============================================================
 * Controller untuk menangani semua fitur yang dapat diakses Admin.
 *
 * FITUR ADMIN:
 *   - Dashboard dengan statistik sistem
 *   - CRUD Data Siswa
 *   - CRUD Data Guru
 *   - CRUD Mata Pelajaran
 *   - Kelola User (akun pengguna)
 *   - Laporan Nilai & Cetak PDF
 *
 * HANYA DAPAT DIAKSES oleh pengguna dengan role = 'admin'
 * (dilindungi oleh RoleMiddleware)
 *
 * PARADIGMA: OOP (Class Controller)
 * ============================================================
 */

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    // ============================================================
    // DASHBOARD
    // ============================================================

    /**
     * Menampilkan dashboard admin dengan statistik sistem.
     *
     * Statistik yang ditampilkan:
     * - Total siswa terdaftar
     * - Total guru terdaftar
     * - Total mata pelajaran
     * - Persentase kelulusan
     * - Daftar nilai terbaru yang diinput
     *
     * @return View
     */
    public function dashboard(): View
    {
        // Hitung statistik untuk kartu ringkasan di dashboard
        $totalSiswa    = Siswa::count();
        $totalGuru     = Guru::count();
        $totalMapel    = MataPelajaran::count();
        $totalNilai    = Nilai::count();

        // Hitung persentase kelulusan
        $nilaiLulus    = Nilai::where('status', 'LULUS')->count();
        $persenLulus   = $totalNilai > 0
            ? round(($nilaiLulus / $totalNilai) * 100, 1)
            : 0;

        // Ambil 10 nilai terbaru yang diinput (dengan eager loading untuk efisiensi)
        $nilaiTerbaru = Nilai::with(['siswa', 'mataPelajaran', 'guru'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalMapel',
            'totalNilai',
            'persenLulus',
            'nilaiTerbaru'
        ));
    }

    // ============================================================
    // MANAJEMEN DATA SISWA
    // ============================================================

    /**
     * Menampilkan daftar semua siswa dengan fitur pencarian dan pagination.
     *
     * @param  Request $request  Request untuk filter/pencarian
     * @return View
     */
    public function siswas(Request $request): View
    {
        // Query siswa dengan fitur pencarian berdasarkan nama, NIS, atau kelas
        $query = Siswa::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan kelas lalu nama, tampilkan 10 per halaman
        $siswas = $query->orderBy('kelas')->orderBy('nama')->paginate(10);

        return view('admin.siswas.index', compact('siswas'));
    }

    /**
     * Menampilkan form untuk menambah siswa baru.
     *
     * @return View
     */
    public function siswasCreate(): View
    {
        return view('admin.siswas.create');
    }

    /**
     * Menyimpan data siswa baru ke database.
     *
     * Proses:
     * 1. Validasi data input
     * 2. Buat akun User baru dengan role 'siswa'
     * 3. Buat profil Siswa terhubung ke User
     *
     * @param  Request           $request  Data dari form tambah siswa
     * @return RedirectResponse
     */
    public function siswasStore(Request $request): RedirectResponse
    {
        // Validasi input dari form
        $validated = $request->validate([
            'nis'      => 'required|string|max:20|unique:siswas,nis',
            'nama'     => 'required|string|max:255',
            'kelas'    => 'required|string|max:20',
            'angkatan' => 'required|integer|min:2000|max:2100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nis.unique'       => 'NIS sudah terdaftar dalam sistem.',
            'email.unique'     => 'Email sudah digunakan oleh pengguna lain.',
            'password.min'     => 'Password minimal 6 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        // Buat akun User untuk siswa ini
        $user = User::create([
            'name'     => $validated['nama'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']), // Hash password
            'role'     => 'siswa',
        ]);

        // Buat profil Siswa yang terhubung ke User
        Siswa::create([
            'user_id' => $user->id,
            'nis'     => $validated['nis'],
            'nama'    => $validated['nama'],
            'kelas'   => $validated['kelas'],
            'angkatan'=> $validated['angkatan'],
        ]);

        return redirect()->route('admin.siswas')
            ->with('success', "Siswa {$validated['nama']} berhasil ditambahkan.");
    }

    /**
     * Menampilkan form edit data siswa.
     *
     * @param  Siswa $siswa  Model Siswa yang akan diedit (Route Model Binding)
     * @return View
     */
    public function siswasEdit(Siswa $siswa): View
    {
        return view('admin.siswas.edit', compact('siswa'));
    }

    /**
     * Mengupdate data siswa di database.
     *
     * @param  Request           $request  Data baru dari form edit
     * @param  Siswa             $siswa    Model Siswa yang akan diupdate
     * @return RedirectResponse
     */
    public function siswasUpdate(Request $request, Siswa $siswa): RedirectResponse
    {
        // Validasi (ignore unique rule untuk data milik siswa ini sendiri)
        $validated = $request->validate([
            'nis'   => "required|string|max:20|unique:siswas,nis,{$siswa->id}",
            'nama'  => 'required|string|max:255',
            'kelas' => 'required|string|max:20',
            'angkatan' => 'required|integer|min:2000|max:2100',
            'email' => "required|email|unique:users,email,{$siswa->user_id}",
        ]);

        // Update profil siswa
        $siswa->update([
            'nis'   => $validated['nis'],
            'nama'  => $validated['nama'],
            'kelas' => $validated['kelas'],
            'angkatan' => $validated['angkatan'],
        ]);

        // Update nama dan email di tabel users
        $siswa->user->update([
            'name'  => $validated['nama'],
            'email' => $validated['email'],
        ]);

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $siswa->user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.siswas')
            ->with('success', "Data siswa {$validated['nama']} berhasil diperbarui.");
    }

    /**
     * Menghapus data siswa dari database.
     *
     * Karena ada cascade delete di migration, menghapus User
     * akan otomatis menghapus profil Siswa dan semua Nilai-nya.
     *
     * @param  Siswa             $siswa  Model Siswa yang akan dihapus
     * @return RedirectResponse
     */
    public function siswasDestroy(Siswa $siswa): RedirectResponse
    {
        $nama = $siswa->nama;

        // Hapus user — cascade delete akan menghapus siswa dan nilai terkait
        $siswa->user->delete();

        return redirect()->route('admin.siswas')
            ->with('success', "Data siswa {$nama} berhasil dihapus.");
    }

    // ============================================================
    // MANAJEMEN DATA GURU
    // ============================================================

    /**
     * Menampilkan daftar semua guru.
     *
     * @param  Request $request
     * @return View
     */
    public function gurus(Request $request): View
    {
        $query = Guru::with(['user', 'mataPelajaran']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $gurus  = $query->orderBy('nama')->paginate(10);
        $mapels = MataPelajaran::orderBy('nama')->get();

        return view('admin.guru.index', compact('gurus', 'mapels'));
    }

    /**
     * Menampilkan form tambah guru.
     *
     * @return View
     */
    public function gurusCreate(): View
    {
        $mapels = MataPelajaran::orderBy('nama')->get();
        $kelasList = ['X-A', 'X-B', 'X-C', 'XI-A', 'XI-B', 'XI-C', 'XII-A', 'XII-B', 'XII-C'];
        
        $takenClassesList = \Illuminate\Support\Facades\DB::table('guru_kelas')
            ->join('gurus', 'guru_kelas.guru_id', '=', 'gurus.id')
            ->select('gurus.mata_pelajaran_id as mapel_id', 'guru_kelas.kelas')
            ->get();
            
        // Get dynamic angkatan mapping based on actual student data
        $angkatanPerKelas = \App\Models\Siswa::select('kelas', 'angkatan')
            ->groupBy('kelas', 'angkatan')
            ->pluck('angkatan', 'kelas')
            ->toArray();
            
        return view('admin.guru.create', compact('mapels', 'kelasList', 'takenClassesList', 'angkatanPerKelas'));
    }

    /**
     * Menyimpan data guru baru.
     *
     * @param  Request           $request
     * @return RedirectResponse
     */
    public function gurusStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nip'               => 'required|string|max:30|unique:gurus,nip',
            'nama'              => 'required|string|max:255',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'kelas_mengajar'    => 'required|array|min:1',
            'kelas_mengajar.*'  => 'string',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:6|confirmed',
        ]);

        $takenClasses = \App\Models\GuruKelas::whereHas('guru', function($q) use ($validated) {
            $q->where('mata_pelajaran_id', $validated['mata_pelajaran_id']);
        })->pluck('kelas')->toArray();

        $intersect = array_intersect($validated['kelas_mengajar'], $takenClasses);
        if (!empty($intersect)) {
            return back()->withInput()->withErrors(['kelas_mengajar' => 'Kelas ' . implode(', ', $intersect) . ' sudah diajar oleh guru lain untuk mata pelajaran ini.']);
        }

        $user = User::create([
            'name'     => $validated['nama'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'guru',
        ]);

        $guru = Guru::create([
            'user_id'           => $user->id,
            'nip'               => $validated['nip'],
            'nama'              => $validated['nama'],
            'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
        ]);

        foreach ($validated['kelas_mengajar'] as $kelas) {
            $guru->kelasMengajar()->create(['kelas' => $kelas]);
        }

        return redirect()->route('admin.guru')
            ->with('success', "Guru {$validated['nama']} berhasil ditambahkan.");
    }

    /**
     * Menampilkan form edit guru.
     *
     * @param  Guru $guru
     * @return View
     */
    public function gurusEdit(Guru $guru): View
    {
        $mapels = MataPelajaran::orderBy('nama')->get();
        $kelasList = ['X-A', 'X-B', 'X-C', 'XI-A', 'XI-B', 'XI-C', 'XII-A', 'XII-B', 'XII-C'];
        $guruKelas = $guru->kelasMengajar->pluck('kelas')->toArray();

        $takenClassesList = \Illuminate\Support\Facades\DB::table('guru_kelas')
            ->join('gurus', 'guru_kelas.guru_id', '=', 'gurus.id')
            ->where('gurus.id', '!=', $guru->id)
            ->select('gurus.mata_pelajaran_id as mapel_id', 'guru_kelas.kelas')
            ->get();

        // Get dynamic angkatan mapping based on actual student data
        $angkatanPerKelas = \App\Models\Siswa::select('kelas', 'angkatan')
            ->groupBy('kelas', 'angkatan')
            ->pluck('angkatan', 'kelas')
            ->toArray();

        return view('admin.guru.edit', compact('guru', 'mapels', 'kelasList', 'guruKelas', 'takenClassesList', 'angkatanPerKelas'));
    }

    /**
     * Mengupdate data guru.
     *
     * @param  Request           $request
     * @param  Guru              $guru
     * @return RedirectResponse
     */
    public function gurusUpdate(Request $request, Guru $guru): RedirectResponse
    {
        $validated = $request->validate([
            'nip'               => "required|string|max:30|unique:gurus,nip,{$guru->id}",
            'nama'              => 'required|string|max:255',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'kelas_mengajar'    => 'required|array|min:1',
            'kelas_mengajar.*'  => 'string',
            'email'             => "required|email|unique:users,email,{$guru->user_id}",
        ]);

        $takenClasses = \App\Models\GuruKelas::whereHas('guru', function($q) use ($validated, $guru) {
            $q->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
              ->where('id', '!=', $guru->id);
        })->pluck('kelas')->toArray();

        $intersect = array_intersect($validated['kelas_mengajar'], $takenClasses);
        if (!empty($intersect)) {
            return back()->withInput()->withErrors(['kelas_mengajar' => 'Kelas ' . implode(', ', $intersect) . ' sudah diajar oleh guru lain untuk mata pelajaran ini.']);
        }

        $guru->update([
            'nip'               => $validated['nip'],
            'nama'              => $validated['nama'],
            'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
        ]);

        $guru->kelasMengajar()->delete();
        foreach ($validated['kelas_mengajar'] as $kelas) {
            $guru->kelasMengajar()->create(['kelas' => $kelas]);
        }

        $guru->user->update([
            'name'  => $validated['nama'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $guru->user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.guru')
            ->with('success', "Data guru {$validated['nama']} berhasil diperbarui.");
    }

    /**
     * Menghapus data guru.
     *
     * @param  Guru              $guru
     * @return RedirectResponse
     */
    public function gurusDestroy(Guru $guru): RedirectResponse
    {
        $nama = $guru->nama;
        $guru->user->delete();

        return redirect()->route('admin.guru')
            ->with('success', "Data guru {$nama} berhasil dihapus.");
    }

    // ============================================================
    // MANAJEMEN MATA PELAJARAN
    // ============================================================

    /**
     * Menampilkan daftar mata pelajaran.
     *
     * @return View
     */
    public function mapels(): View
    {
        $mapels = MataPelajaran::withCount('nilais')->orderBy('nama')->paginate(10);
        return view('admin.mapels.index', compact('mapels'));
    }

    /**
     * Menyimpan mata pelajaran baru.
     *
     * @param  Request           $request
     * @return RedirectResponse
     */
    public function mapelsStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:mata_pelajarans,kode',
            'nama' => 'required|string|max:255',
        ], [
            'kode.unique' => 'Kode mata pelajaran sudah digunakan.',
        ]);

        MataPelajaran::create($validated);

        return redirect()->route('admin.mapels')
            ->with('success', "Mata pelajaran {$validated['nama']} berhasil ditambahkan.");
    }

    /**
     * Mengupdate mata pelajaran.
     *
     * @param  Request           $request
     * @param  MataPelajaran     $mapel
     * @return RedirectResponse
     */
    public function mapelsUpdate(Request $request, MataPelajaran $mapel): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => "required|string|max:10|unique:mata_pelajarans,kode,{$mapel->id}",
            'nama' => 'required|string|max:255',
        ]);

        $mapel->update($validated);

        return redirect()->route('admin.mapels')
            ->with('success', "Mata pelajaran {$validated['nama']} berhasil diperbarui.");
    }

    /**
     * Menghapus mata pelajaran.
     *
     * @param  MataPelajaran     $mapel
     * @return RedirectResponse
     */
    public function mapelsDestroy(MataPelajaran $mapel): RedirectResponse
    {
        // Cek apakah mata pelajaran masih digunakan
        if ($mapel->nilais()->count() > 0) {
            return redirect()->route('admin.mapels')
                ->with('error', 'Mata pelajaran tidak dapat dihapus karena masih memiliki data nilai.');
        }

        $nama = $mapel->nama;
        $mapel->delete();

        return redirect()->route('admin.mapels')
            ->with('success', "Mata pelajaran {$nama} berhasil dihapus.");
    }

    // ============================================================
    // LAPORAN NILAI
    // ============================================================

    /**
     * Menampilkan halaman laporan nilai dengan filter.
     *
     * @param  Request $request  Filter kelas dan mapel
     * @return View
     */
    public function laporan(Request $request): View
    {
        // Ambil semua kelas unik yang ada dari tabel siswas
        $kelasList = Siswa::distinct()->orderBy('kelas')->pluck('kelas');
        $mapelList = MataPelajaran::orderBy('nama')->get();

        // Query dengan eager loading, filter berdasarkan input
        $query = Nilai::with(['siswa', 'mataPelajaran', 'guru'])
            ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
            ->join('mata_pelajarans', 'nilais.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->select('nilais.*');

        // Terapkan filter kelas jika ada
        if ($request->filled('kelas')) {
            $query->where('siswas.kelas', $request->kelas);
        }

        // Terapkan filter mata pelajaran jika ada
        if ($request->filled('mapel_id')) {
            $query->where('nilais.mata_pelajaran_id', $request->mapel_id);
        }

        $nilais = $query->orderBy('siswas.kelas')
            ->orderBy('siswas.nama')
            ->paginate(20);

        // Statistik ringkasan
        $totalData    = $query->count();
        // Clone query agar jumlah lulus dihitung dari data yang terfilter, bukan seluruh isi database
        $jumlahLulus  = (clone $query)->where('status', 'LULUS')->count();

        return view('admin.laporan', compact(
            'nilais',
            'kelasList',
            'mapelList',
            'totalData',
            'jumlahLulus'
        ));
    }

    /**
     * Mencetak laporan nilai dalam format PDF.
     *
     * Menggunakan library DomPDF (barryvdh/laravel-dompdf)
     * untuk mengonversi tampilan HTML menjadi file PDF.
     *
     * @param  Request $request  Filter kelas dan mapel untuk PDF
     * @return \Illuminate\Http\Response
     */
    public function laporanPdf(Request $request)
    {
        // Ambil data nilai berdasarkan filter
        $query = Nilai::with(['siswa', 'mataPelajaran', 'guru'])
            ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
            ->join('mata_pelajarans', 'nilais.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->select('nilais.*');

        if ($request->filled('kelas')) {
            $query->where('siswas.kelas', $request->kelas);
        }

        if ($request->filled('mapel_id')) {
            $query->where('nilais.mata_pelajaran_id', $request->mapel_id);
        }

        $nilais = $query->orderBy('siswas.kelas')
            ->orderBy('siswas.nama')
            ->get();

        // Gunakan fungsi prosedural formatLaporan() dari NilaiHelper.php
        // untuk memformat data sebelum dikirim ke template PDF
        $dataForPdf = formatLaporan($nilais->map(fn($n) => [
            'nis'            => $n->siswa->nis,
            'nama'           => $n->siswa->nama,
            'kelas'          => $n->siswa->kelas,
            'mata_pelajaran' => $n->mataPelajaran->nama,
            'nilai_tugas'    => $n->nilai_tugas,
            'nilai_uts'      => $n->nilai_uts,
            'nilai_uas'      => $n->nilai_uas,
            'nilai_akhir'    => $n->nilai_akhir,
            'status'         => $n->status,
        ])->toArray());

        // Generate PDF dari view template
        $pdf = Pdf::loadView('admin.laporan_pdf', [
            'nilais'    => $dataForPdf,
            'kelas'     => $request->kelas ?? 'Semua Kelas',
            'tanggal'   => now()->format('d F Y'),
        ])->setPaper('a4', 'landscape');

        // Return sebagai download PDF
        return $pdf->download('laporan-nilai-SIMPEL.pdf');
    }
}
