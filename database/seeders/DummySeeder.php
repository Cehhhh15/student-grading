<?php

/**
 * ============================================================
 * FILE  : DummySeeder.php
 * LOKASI: database/seeders/DummySeeder.php
 * ============================================================
 *
 * DESKRIPSI:
 *   Seeder untuk mengisi data dummy LENGKAP (skala besar).
 *   Dijalankan DI ATAS data DatabaseSeeder yang sudah ada.
 *
 *   Yang ditambahkan:
 *     - Sisa 8 Mata Pelajaran (dari 2 → 10 total)
 *     - 15 Guru dummy (email @dummy.simpel)
 *     - 180 Siswa dummy (20/kelas × 9 kelas, email @dummy.simpel)
 *     - Nilai lengkap untuk setiap siswa dummy
 *
 *   Data asli (admin, guru1, guru2, 5 siswa) TIDAK terpengaruh.
 *
 * ============================================================
 * CARA PENGGUNAAN:
 * ============================================================
 *
 *  [Jalankan setelah migrate:fresh --seed]
 *    php artisan db:seed --class=DummySeeder
 *
 *  [Hapus semua data dummy — admin & data kecil tetap aman]
 *    php artisan dummy:rollback
 *    -- atau --
 *    php artisan db:seed --class=DummySeeder --rollback
 *
 * ============================================================
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\GuruKelas;

class DummySeeder extends Seeder
{
    // ============================================================
    // KONFIGURASI
    // ============================================================

    /** Jumlah guru dummy yang akan dibuat */
    private int $jumlahGuru = 15;

    /** Daftar kelas yang ada */
    private array $kelasList = ['X-A', 'X-B', 'X-C', 'XI-A', 'XI-B', 'XI-C', 'XII-A', 'XII-B', 'XII-C'];

    /** Jumlah siswa per kelas */
    private int $jumlahSiswaKelas = 20;

    /**
     * Tag penanda unik untuk data dummy.
     * Semua email dummy mengandung string ini.
     * Digunakan saat rollback agar hanya data dummy yang dihapus.
     */
    private string $dummyTag = '@dummy.simpel';

    /** Semua 10 mata pelajaran (2 sudah ada, 8 sisanya ditambahkan) */
    private array $semuaMapel = [
        'MTK' => 'Matematika',
        'BID' => 'Bahasa Indonesia',
        'BIG' => 'Bahasa Inggris',
        'IPA' => 'Ilmu Pengetahuan Alam',
        'IPS' => 'Ilmu Pengetahuan Sosial',
        'PJK' => 'Pendidikan Jasmani',
        'PKN' => 'Pendidikan Kewarganegaraan',
        'SBD' => 'Seni Budaya',
        'AGM' => 'Pendidikan Agama',
        'TIK' => 'Teknologi Informasi',
    ];

    // ============================================================
    // KUMPULAN NAMA DUMMY
    // ============================================================

    private array $namaDepan = [
        'Budi', 'Siti', 'Andi', 'Dewi', 'Fajar', 'Rina', 'Agus', 'Lina',
        'Rizky', 'Nita', 'Hendra', 'Wulan', 'Dian', 'Yoga', 'Maya', 'Bagas',
        'Novia', 'Ilham', 'Putri', 'Raka', 'Sari', 'Farel', 'Laila', 'Wahyu',
        'Cindy', 'Erik', 'Vina', 'Hafiz', 'Salma', 'Rendi', 'Ayu', 'Deni',
        'Fitri', 'Galih', 'Hani', 'Iwan', 'Joko', 'Kartika', 'Leo', 'Mira',
        'Nanda', 'Okta', 'Panji', 'Qori', 'Rama', 'Susan', 'Tono', 'Umi',
        'Vega', 'Wahid', 'Xena', 'Yusuf', 'Zahra', 'Aris', 'Bella', 'Candra',
    ];

    private array $namaBelakang = [
        'Santoso', 'Wijaya', 'Pratama', 'Lestari', 'Nugroho', 'Rahayu',
        'Susanto', 'Handayani', 'Setiawan', 'Kurniawan', 'Putra', 'Purnama',
        'Saputra', 'Hidayat', 'Permata', 'Firmansyah', 'Kusuma', 'Maulana',
        'Irawan', 'Halim', 'Budiman', 'Lubis', 'Nasution', 'Siregar',
        'Tampubolon', 'Sihombing', 'Harahap', 'Daulay', 'Sibarani', 'Sitorus',
    ];

    private array $namaDepanGuru = [
        'Pak Ahmad', 'Bu Sari', 'Pak Budi', 'Bu Dewi', 'Pak Hendra',
        'Bu Ratna', 'Pak Dedi', 'Bu Yuni', 'Pak Rudi', 'Bu Erna',
        'Pak Joni', 'Bu Wati', 'Pak Tono', 'Bu Lina', 'Pak Agus',
    ];

    private array $namaBelakangGuru = [
        'Santoso', 'Wijaya', 'Pratama', 'Rahayu', 'Susanto',
        'Handayani', 'Setiawan', 'Kurniawan', 'Putra', 'Saputra',
        'Hidayat', 'Kusuma', 'Maulana', 'Irawan', 'Halim',
    ];

    // ============================================================
    // METHOD UTAMA: run()
    // ============================================================

    public function run(): void
    {
        // Bersihkan data dummy lama terlebih dahulu
        $this->rollback();

        $this->command->newLine();
        $this->command->line('  <fg=cyan>====================================================</>');
        $this->command->line('  <fg=cyan>  SIMPEL — Dummy Seeder (Skala Besar)</>');
        $this->command->line('  <fg=cyan>====================================================</>');

        $totalSiswa = count($this->kelasList) * $this->jumlahSiswaKelas;
        $this->command->line("  Guru    : {$this->jumlahGuru} guru dummy");
        $this->command->line("  Kelas   : " . implode(', ', $this->kelasList));
        $this->command->line("  Siswa   : {$this->jumlahSiswaKelas} per kelas → Total: {$totalSiswa} siswa");
        $this->command->line('  <fg=cyan>====================================================</>');
        $this->command->newLine();

        DB::transaction(function () {
            // --------------------------------------------------
            // STEP 1: Pastikan semua 10 Mapel ada (tambahkan yang belum)
            // --------------------------------------------------
            $this->command->line('  → <fg=yellow>Memastikan 10 Mata Pelajaran tersedia...</>');
            $mapels = $this->pastikanSemuaMapel();
            $this->command->line('  <fg=green>  ✓ 10 Mata Pelajaran siap.</>');

            // --------------------------------------------------
            // STEP 2: Buat 15 Guru Dummy
            // --------------------------------------------------
            $this->command->line("  → <fg=yellow>Membuat {$this->jumlahGuru} Guru dummy...</>");
            $gurus = $this->buatGuru($mapels);
            $this->command->line("  <fg=green>  ✓ {$this->jumlahGuru} Guru dummy berhasil dibuat.</>");

            // --------------------------------------------------
            // STEP 3: Assign Guru ke Kelas
            // --------------------------------------------------
            $this->command->line('  → <fg=yellow>Mendistribusikan kelas ke guru...</>');
            $guruLookup = $this->assignGuruKelas($gurus, $mapels);
            $this->command->line('  <fg=green>  ✓ Distribusi kelas selesai.</>');

            // --------------------------------------------------
            // STEP 4: Buat 180 Siswa + Nilai
            // --------------------------------------------------
            $this->command->line('  → <fg=yellow>Membuat siswa dummy dan nilai...</>');
            $totalBuat = $this->buatSiswaDanNilai($mapels, $guruLookup);
            $this->command->line("  <fg=green>  ✓ {$totalBuat} Siswa dummy berhasil dibuat dengan nilai.</>");
        });

        $this->command->newLine();
        $this->command->line('  <fg=green>====================================================</>');
        $this->command->line('  <fg=green>  ✅ Dummy Seeder selesai!</>');
        $this->command->line('  <fg=green>  💡 Password guru dummy  : password123</>');
        $this->command->line('  <fg=green>  💡 Password siswa dummy : dummy123</>');
        $this->command->line('  <fg=green>  🏷  Tag dummy            : ' . $this->dummyTag . '</>');
        $this->command->line('  <fg=green>  🗑  Untuk hapus         : php artisan dummy:rollback</>');
        $this->command->line('  <fg=green>====================================================</>');
        $this->command->newLine();
    }

    // ============================================================
    // METHOD ROLLBACK: Hapus semua data dummy
    // ============================================================

    /**
     * Menghapus semua data dummy (guru + siswa).
     * Admin, guru1, guru2, 5 siswa asli, dan Mapel TIDAK terpengaruh.
     */
    public function rollback(): void
    {
        $this->command->newLine();
        $this->command->line('  <fg=yellow>====================================================</>');
        $this->command->line('  <fg=yellow>  ROLLBACK — Menghapus Data Dummy</>');
        $this->command->line('  <fg=yellow>====================================================</>');

        $dummyUsers = User::where('email', 'like', '%' . $this->dummyTag . '%')->get();

        if ($dummyUsers->isEmpty()) {
            $this->command->line('  Tidak ada data dummy yang ditemukan.');
            $this->command->line('  <fg=yellow>====================================================</>');
            return;
        }

        $jumlah = $dummyUsers->count();
        $this->command->line("  Ditemukan {$jumlah} akun dummy, menghapus...");

        DB::transaction(function () use ($dummyUsers) {
            foreach ($dummyUsers as $user) {
                // onDelete('cascade') otomatis hapus Guru/Siswa dan Nilai terkait
                $user->delete();
            }
        });

        $this->command->line("  <fg=green>✅ {$jumlah} akun dummy dihapus. Admin & data asli aman.</>");
        $this->command->line('  <fg=yellow>====================================================</>');
        $this->command->newLine();
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    /**
     * Pastikan semua 10 mata pelajaran tersedia (firstOrCreate).
     * 2 mapel dari DatabaseSeeder tidak akan diduplikasi.
     */
    private function pastikanSemuaMapel()
    {
        foreach ($this->semuaMapel as $kode => $nama) {
            MataPelajaran::firstOrCreate(['kode' => $kode], ['nama' => $nama]);
        }

        return MataPelajaran::all();
    }

    /**
     * Buat data guru dummy beserta akun user-nya.
     * Email mengandung $dummyTag agar bisa diidentifikasi saat rollback.
     */
    private function buatGuru($mapels): array
    {
        $gurus    = [];
        $mapelArr = $mapels->values()->all();

        for ($i = 0; $i < $this->jumlahGuru; $i++) {
            $depanIdx  = $i % count($this->namaDepanGuru);
            $belakIdx  = $i % count($this->namaBelakangGuru);
            $nama      = $this->namaDepanGuru[$depanIdx] . ' ' . $this->namaBelakangGuru[$belakIdx];
            $nomorGuru = $i + 1;
            $email     = "guru{$nomorGuru}{$this->dummyTag}";

            // Assign mapel secara merata (round-robin)
            $mapel = $mapelArr[$i % count($mapelArr)];

            // NIP: 18 digit deterministik
            $nip = str_pad(($i + 100) * 19700101001, 18, '0', STR_PAD_LEFT);

            $user = User::create([
                'name'     => $nama,
                'email'    => $email,
                'password' => Hash::make('password123'),
                'role'     => 'guru',
            ]);

            $guru = Guru::create([
                'user_id'           => $user->id,
                'nip'               => $nip,
                'nama'              => $nama,
                'mata_pelajaran_id' => $mapel->id,
            ]);

            $gurus[] = $guru;
        }

        return $gurus;
    }

    /**
     * Assign guru dummy ke kelas secara merata per mata pelajaran.
     * Mengembalikan lookup array: [mata_pelajaran_id][kelas] = guru_id
     *
     * Catatan: guru asli (guru1, guru2) mungkin sudah assign ke X-A.
     * Guru dummy akan cover kelas lainnya tanpa konflik.
     */
    private function assignGuruKelas(array $gurus, $mapels): array
    {
        $guruLookup   = [];
        $gurusByMapel = collect($gurus)->groupBy('mata_pelajaran_id');

        foreach ($mapels as $mapel) {
            $gurusOfMapel = $gurusByMapel->get($mapel->id, collect())->values()->all();
            if (empty($gurusOfMapel)) continue;

            foreach ($this->kelasList as $idx => $kelas) {
                $guru = $gurusOfMapel[$idx % count($gurusOfMapel)];

                // Idempotent: skip jika kombinasi guru+kelas sudah ada
                $exists = GuruKelas::where('guru_id', $guru->id)->where('kelas', $kelas)->exists();
                if (! $exists) {
                    GuruKelas::create([
                        'guru_id' => $guru->id,
                        'kelas'   => $kelas,
                    ]);
                }

                $guruLookup[$mapel->id][$kelas] = $guru->id;
            }
        }

        // Juga masukkan guru asli ke lookup agar siswa dummy di X-A juga punya nilai
        $guruAsli = Guru::with('kelasMengajar')->whereHas('user', function ($q) {
            $q->where('email', 'not like', '%' . $this->dummyTag . '%');
        })->get();

        foreach ($guruAsli as $g) {
            foreach ($g->kelasMengajar as $gk) {
                // Hanya isi jika belum ada (guru dummy lebih prioritas)
                if (! isset($guruLookup[$g->mata_pelajaran_id][$gk->kelas])) {
                    $guruLookup[$g->mata_pelajaran_id][$gk->kelas] = $g->id;
                }
            }
        }

        return $guruLookup;
    }

    /**
     * Buat siswa dummy beserta nilai untuk setiap kelas.
     * NIS dimulai dari 9000001 agar tidak bentrok dengan siswa asli (2024001-2024005).
     */
    private function buatSiswaDanNilai($mapels, array $guruLookup): int
    {
        $nisCounter = 9000001;
        $totalBuat  = 0;
        $urutan     = 1;

        foreach ($this->kelasList as $kelas) {
            $angkatan = str_starts_with($kelas, 'XII') ? 2022
                      : (str_starts_with($kelas, 'XI')  ? 2023 : 2024);

            for ($i = 1; $i <= $this->jumlahSiswaKelas; $i++) {
                $depanIdx = ($urutan - 1) % count($this->namaDepan);
                $belakIdx = ($i - 1) % count($this->namaBelakang);
                $nama     = $this->namaDepan[$depanIdx] . ' ' . $this->namaBelakang[$belakIdx];
                $email    = strtolower(str_replace(' ', '.', $nama)) . ".{$nisCounter}{$this->dummyTag}";

                $user = User::create([
                    'name'     => $nama,
                    'email'    => $email,
                    'password' => Hash::make('dummy123'),
                    'role'     => 'siswa',
                ]);

                $siswa = Siswa::create([
                    'user_id'  => $user->id,
                    'nis'      => (string) $nisCounter,
                    'nama'     => $nama,
                    'kelas'    => $kelas,
                    'angkatan' => $angkatan,
                ]);

                // Buat nilai untuk setiap mapel yang ada gurunya di kelas ini
                foreach ($mapels as $mapel) {
                    if (! isset($guruLookup[$mapel->id][$kelas])) continue;

                    Nilai::create([
                        'siswa_id'          => $siswa->id,
                        'mata_pelajaran_id' => $mapel->id,
                        'guru_id'           => $guruLookup[$mapel->id][$kelas],
                        'nilai_tugas'       => $this->nilaiAcak($urutan, 'tugas'),
                        'nilai_uts'         => $this->nilaiAcak($urutan, 'uts'),
                        'nilai_uas'         => $this->nilaiAcak($urutan, 'uas'),
                    ]);
                }

                $nisCounter++;
                $urutan++;
                $totalBuat++;
            }
        }

        return $totalBuat;
    }

    /**
     * Nilai acak realistis: 70% lulus (65–100), 30% berisiko (45–72).
     */
    private function nilaiAcak(int $urutan, string $jenis): int
    {
        $seed = ($urutan * 7) + match ($jenis) {
            'tugas' => 3,
            'uts'   => 11,
            'uas'   => 17,
            default => 5,
        };

        if ($seed % 10 < 3) {
            return 45 + ($seed % 28);
        }

        return 65 + ($seed % 36);
    }
}
