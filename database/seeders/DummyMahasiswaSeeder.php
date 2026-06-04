<?php

/**
 * ============================================================
 * FILE  : DummyMahasiswaSeeder.php
 * LOKASI: database/seeders/DummyMahasiswaSeeder.php
 * ============================================================
 *
 * DESKRIPSI:
 *   Seeder untuk mengisi data dummy siswa dalam jumlah besar.
 *   Berguna untuk pengujian pagination, performa, dan laporan.
 *
 * KONFIGURASI (dapat diubah):
 *   - 3 Angkatan  : 2022, 2023, 2024
 *   - 3 Kelas     : A, B, C (per angkatan)
 *   - 35 Siswa    : per kelas
 *   Total         : 3 × 3 × 35 = 315 siswa
 *
 * CARA MENJALANKAN (migrate):
 *   php artisan db:seed --class=DummyMahasiswaSeeder
 *
 * CARA MEMBATALKAN (rollback / hapus data dummy):
 *   php artisan db:seed --class=DummyMahasiswaSeeder --rollback
 *   -- ATAU --
 *   php artisan dummy:rollback
 *
 * CATATAN:
 *   Data dummy dapat diidentifikasi melalui tag "@dummy.simpel"
 *   yang tertanam di kolom email setiap akun siswa buatan.
 *   Saat rollback, hanya baris ber-tag ini yang dihapus.
 *
 * ============================================================
 */

namespace Database\Seeders;

use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DummyMahasiswaSeeder extends Seeder
{
    // ============================================================
    // KONFIGURASI — Ubah di sini jika ingin jumlah berbeda
    // ============================================================

    /** Daftar angkatan yang akan dibuat */
    private array $angkatan = ['2022', '2023', '2024'];

    /** Daftar kelas per angkatan */
    private array $kelas = ['A', 'B', 'C'];

    /** Jumlah siswa per kelas */
    private int $siswaPerkelas = 35;

    /**
     * Tag penanda unik untuk data dummy.
     * Semua email siswa dummy mengandung string ini.
     * Digunakan saat rollback untuk memastikan hanya data dummy yang dihapus.
     */
    private string $dummyTag = '@dummy.simpel';

    // ============================================================
    // KUMPULAN NAMA DUMMY
    // ============================================================

    /** Daftar nama depan (dapat diperbanyak) */
    private array $namaDepan = [
        'Budi', 'Siti', 'Andi', 'Dewi', 'Fajar', 'Rina', 'Agus', 'Lina',
        'Rizky', 'Nita', 'Hendra', 'Wulan', 'Dian', 'Yoga', 'Maya', 'Bagas',
        'Novia', 'Ilham', 'Putri', 'Raka', 'Sari', 'Farel', 'Laila', 'Wahyu',
        'Cindy', 'Erik', 'Vina', 'Hafiz', 'Salma', 'Rendi', 'Ayu', 'Deni',
        'Fitri', 'Galih', 'Hani', 'Iwan', 'Joko', 'Kartika', 'Leo', 'Mira',
        'Nanda', 'Okta', 'Panji', 'Qori', 'Rama', 'Susan', 'Tono', 'Umi',
        'Vega', 'Wahid', 'Xena', 'Yusuf', 'Zahra', 'Aris', 'Bella', 'Candra',
    ];

    /** Daftar nama belakang */
    private array $namaBelakang = [
        'Santoso', 'Wijaya', 'Pratama', 'Lestari', 'Nugroho', 'Rahayu',
        'Susanto', 'Handayani', 'Setiawan', 'Kurniawan', 'Putra', 'Purnama',
        'Saputra', 'Hidayat', 'Permata', 'Firmansyah', 'Kusuma', 'Maulana',
        'Irawan', 'Halim', 'Budiman', 'Lubis', 'Nasution', 'Siregar',
        'Tampubolon', 'Sihombing', 'Harahap', 'Daulay', 'Sibarani', 'Sitorus',
        'Panjaitan', 'Hutabarat', 'Aritonang', 'Silalahi', 'Simanjuntak',
    ];

    // ============================================================
    // METHOD UTAMA: run()
    // ============================================================

    /**
     * Jalankan seeder.
     * Gunakan --rollback untuk menghapus data dummy yang pernah dibuat.
     *
     * @return void
     */
    public function run(): void
    {
        // Cek argumen rollback (php artisan db:seed --class=... tidak mendukung args,
        // jadi kita cek environment variable yang bisa diset sebelum memanggil)
        if (app()->environment('rollback_dummy')) {
            $this->rollback();
            return;
        }

        // OTOMATIS BERSIHKAN DATA LAMA: 
        // Mencegah error "Integrity constraint violation (UNIQUE constraint failed)" 
        // jika seeder ini dijalankan berulang kali tanpa di-rollback dulu.
        $this->rollback();

        $this->command->info('');
        $this->command->info('====================================================');
        $this->command->info('  SIMPEL — Dummy Mahasiswa Seeder');
        $this->command->info('====================================================');
        $this->command->info("  Angkatan : " . implode(', ', $this->angkatan));
        $this->command->info("  Kelas    : " . implode(', ', $this->kelas));
        $this->command->info("  Per Kelas: {$this->siswaPerkelas} siswa");
        $total = count($this->angkatan) * count($this->kelas) * $this->siswaPerkelas;
        $this->command->info("  TOTAL    : {$total} siswa");
        $this->command->info('====================================================');
        $this->command->info('');

        // Ambil semua mata pelajaran yang ada di database untuk assign nilai
        $mapels = MataPelajaran::all();
        if ($mapels->isEmpty()) {
            $this->command->warn('⚠ Tidak ada mata pelajaran di database.');
            $this->command->warn('  Jalankan DatabaseSeeder terlebih dahulu agar nilai dummy bisa dibuat.');
        }

        // Ambil semua guru untuk assign sebagai penginput nilai
        $guruList = \App\Models\Guru::with('mataPelajaran')->get();

        $urutan     = 1;    // Penomoran NIS global
        $totalBuat  = 0;

        // Wrap dalam transaksi database agar jika gagal di tengah, semua di-rollback
        DB::transaction(function () use ($mapels, $guruList, &$urutan, &$totalBuat) {

            foreach ($this->angkatan as $thn) {
                foreach ($this->kelas as $kls) {
                    $namaKelas = "X{$thn}-{$kls}"; // Contoh: X2022-A

                    $this->command->line("  → Membuat Kelas <fg=cyan>{$namaKelas}</> ({$this->siswaPerkelas} siswa)...");

                    for ($i = 1; $i <= $this->siswaPerkelas; $i++) {

                        // Buat nama acak yang tidak duplikat menggunakan kombinasi array
                        $namaD = $this->namaDepan[($urutan - 1) % count($this->namaDepan)];
                        $namaB = $this->namaBelakang[($i - 1) % count($this->namaBelakang)];
                        $nama  = "{$namaD} {$namaB}";

                        // NIS: angkatan + kelas (A=1, B=2, C=3) + nomor urut 3 digit
                        $kelasNum = array_search($kls, $this->kelas) + 1;
                        $nis      = $thn . $kelasNum . str_pad($i, 3, '0', STR_PAD_LEFT);

                        // Email dengan dummy tag agar bisa diidentifikasi saat rollback
                        $emailSlug = strtolower(str_replace(' ', '.', $nama)) . '.' . $nis;
                        $email     = "{$emailSlug}{$this->dummyTag}";

                        // Buat akun User
                        $user = User::create([
                            'name'     => $nama,
                            'email'    => $email,
                            'password' => Hash::make('dummy123'),  // Password default semua siswa dummy
                            'role'     => 'siswa',
                        ]);

                        // Buat profil Siswa
                        $siswa = Siswa::create([
                            'user_id' => $user->id,
                            'nis'     => $nis,
                            'nama'    => $nama,
                            'kelas'   => $namaKelas,
                        ]);

                        // Buat nilai dummy untuk setiap mata pelajaran (jika ada)
                        foreach ($mapels as $mapel) {
                            // Cari guru yang mengampu mapel ini
                            $guru = $guruList->where('mata_pelajaran_id', $mapel->id)->first();
                            if (! $guru) continue;

                            // Generate nilai acak dengan distribusi realistis
                            // (sengaja ada beberapa yang tidak lulus untuk variasi data)
                            $nilaiTugas = $this->nilaiAcak($urutan, 'tugas');
                            $nilaiUts   = $this->nilaiAcak($urutan, 'uts');
                            $nilaiUas   = $this->nilaiAcak($urutan, 'uas');

                            Nilai::create([
                                'siswa_id'          => $siswa->id,
                                'mata_pelajaran_id' => $mapel->id,
                                'guru_id'           => $guru->id,
                                'nilai_tugas'       => $nilaiTugas,
                                'nilai_uts'         => $nilaiUts,
                                'nilai_uas'         => $nilaiUas,
                                // nilai_akhir & status dihitung otomatis oleh Model::boot()
                            ]);
                        }

                        $urutan++;
                        $totalBuat++;
                    }

                    $this->command->line("    <fg=green>✓ Kelas {$namaKelas} selesai.</>");
                }
            }
        });

        $this->command->info('');
        $this->command->info("====================================================");
        $this->command->info("  ✅ Selesai! Total {$totalBuat} siswa dummy berhasil dibuat.");
        $this->command->info("  💡 Password semua siswa dummy: dummy123");
        $this->command->info("  🏷  Tag dummy   : {$this->dummyTag}");
        $this->command->info("  🗑  Untuk hapus : php artisan dummy:rollback");
        $this->command->info("====================================================");
        $this->command->info('');
    }

    // ============================================================
    // METHOD ROLLBACK: Hapus semua data dummy
    // ============================================================

    /**
     * Menghapus semua data dummy yang dibuat oleh seeder ini.
     * Hanya menghapus akun yang emailnya mengandung tag dummy.
     *
     * @return void
     */
    public function rollback(): void
    {
        $this->command->info('');
        $this->command->warn('====================================================');
        $this->command->warn('  ROLLBACK — Menghapus Data Dummy Siswa');
        $this->command->warn('====================================================');

        // Cari semua user dummy berdasarkan tag email
        $dummyUsers = User::where('email', 'like', '%' . $this->dummyTag . '%')->get();

        if ($dummyUsers->isEmpty()) {
            $this->command->warn('  Tidak ada data dummy yang ditemukan.');
            $this->command->warn('  (Pastikan seeder sudah pernah dijalankan)');
            $this->command->info('====================================================');
            return;
        }

        $jumlah = $dummyUsers->count();
        $this->command->line("  Ditemukan {$jumlah} akun dummy...");

        // Hapus dalam transaksi untuk keamanan
        DB::transaction(function () use ($dummyUsers) {
            foreach ($dummyUsers as $user) {
                // Cascade delete akan otomatis menghapus Siswa dan Nilai terkait
                // (karena migration menggunakan onDelete('cascade'))
                $user->delete();
            }
        });

        $this->command->info('');
        $this->command->info("  ✅ {$jumlah} data dummy berhasil dihapus.");
        $this->command->info("  Data asli (DatabaseSeeder) tidak terpengaruh.");
        $this->command->info('====================================================');
        $this->command->info('');
    }

    // ============================================================
    // METHOD HELPER: Generate nilai acak realistis
    // ============================================================

    /**
     * Menghasilkan nilai acak dengan distribusi yang realistis.
     *
     * Distribusi:
     *   - 70% siswa → nilai "lulus" (65 - 100)
     *   - 30% siswa → nilai "risiko tidak lulus" (45 - 74)
     *
     * Nilai dipengaruhi oleh nomor urut siswa dan jenis nilai
     * agar hasilnya bervariasi antar siswa.
     *
     * @param  int    $urutan  Nomor urut siswa (untuk variasi)
     * @param  string $jenis   Jenis nilai: 'tugas', 'uts', atau 'uas'
     * @return int             Nilai bulat antara 45-100
     */
    private function nilaiAcak(int $urutan, string $jenis): int
    {
        // Seed deterministik berdasarkan urutan agar nilai konsisten tiap run
        $seed = ($urutan * 7) + match ($jenis) {
            'tugas' => 3,
            'uts'   => 11,
            'uas'   => 17,
            default => 5,
        };

        // 30% siswa mendapat nilai "risiko" (tidak lulus / hampir lulus)
        if ($seed % 10 < 3) {
            // Rentang 45–72 (kemungkinan tidak lulus)
            return 45 + ($seed % 28);
        }

        // 70% siswa mendapat nilai di atas KKM (lulus)
        return 65 + ($seed % 36);
    }
}
