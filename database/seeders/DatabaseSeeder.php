<?php

/**
 * ============================================================
 * DatabaseSeeder.php — Data Esensial (Skala Kecil)
 * ============================================================
 *
 * Dijalankan saat:
 *   php artisan migrate:fresh --seed
 *   php artisan db:seed
 *
 * ISI:
 *   - 1  Admin
 *   - 2  Mata Pelajaran (Matematika, Bahasa Indonesia)
 *   - 2  Guru (masing-masing 1 mapel, mengajar kelas X-A)
 *   - 5  Siswa (kelas X-A, angkatan 2024)
 *   - Nilai sudah diisi untuk setiap siswa (2 mapel × 5 siswa)
 *
 * Cocok untuk testing fitur secara cepat dengan data minimal.
 * Data ini TIDAK bertag dummy → AMAN dari dummy:rollback.
 *
 * ============================================================
 * WORKFLOW:
 * ============================================================
 *
 *  [Testing skala kecil — hanya data ini]
 *    php artisan migrate:fresh --seed
 *
 *  [Tambah 180 data dummy di atasnya]
 *    php artisan db:seed --class=DummySeeder
 *
 *  [Hapus data dummy, data kecil tetap utuh]
 *    php artisan dummy:rollback
 *
 * ============================================================
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\GuruKelas;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --------------------------------------------------------
        // 1. ADMIN
        // --------------------------------------------------------
        User::firstOrCreate(
            ['email' => 'admin@SIMPEL.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        $this->command->info('✅ Admin: admin@SIMPEL.com / password123');

        // --------------------------------------------------------
        // 2. MATA PELAJARAN (hanya 2 untuk skala kecil)
        // --------------------------------------------------------
        $mtk = MataPelajaran::firstOrCreate(
            ['kode' => 'MTK'],
            ['nama' => 'Matematika']
        );

        $bid = MataPelajaran::firstOrCreate(
            ['kode' => 'BID'],
            ['nama' => 'Bahasa Indonesia']
        );

        $this->command->info('✅ 2 Mata Pelajaran: Matematika, Bahasa Indonesia');

        // --------------------------------------------------------
        // 3. GURU (2 guru, email tetap bukan dummy tag)
        // --------------------------------------------------------
        $userGuru1 = User::firstOrCreate(
            ['email' => 'guru1@SIMPEL.com'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role'     => 'guru',
            ]
        );

        $guru1 = Guru::firstOrCreate(
            ['user_id' => $userGuru1->id],
            [
                'nip'               => '198501012010011001',
                'nama'              => 'Budi Santoso',
                'mata_pelajaran_id' => $mtk->id,
            ]
        );

        $userGuru2 = User::firstOrCreate(
            ['email' => 'guru2@SIMPEL.com'],
            [
                'name'     => 'Sari Rahayu',
                'password' => Hash::make('password123'),
                'role'     => 'guru',
            ]
        );

        $guru2 = Guru::firstOrCreate(
            ['user_id' => $userGuru2->id],
            [
                'nip'               => '198603052012012002',
                'nama'              => 'Sari Rahayu',
                'mata_pelajaran_id' => $bid->id,
            ]
        );

        // Assign kedua guru ke kelas X-A
        GuruKelas::firstOrCreate(['guru_id' => $guru1->id, 'kelas' => 'X-A']);
        GuruKelas::firstOrCreate(['guru_id' => $guru2->id, 'kelas' => 'X-A']);

        $this->command->info('✅ 2 Guru: guru1@SIMPEL.com (MTK), guru2@SIMPEL.com (BID) — kelas X-A');

        // --------------------------------------------------------
        // 4. SISWA (5 siswa, kelas X-A)
        // --------------------------------------------------------
        $siswaData = [
            ['nama' => 'Andi Pratama',    'email' => 'siswa1@SIMPEL.com', 'nis' => '2024001'],
            ['nama' => 'Dewi Lestari',    'email' => 'siswa2@SIMPEL.com', 'nis' => '2024002'],
            ['nama' => 'Fajar Nugroho',   'email' => 'siswa3@SIMPEL.com', 'nis' => '2024003'],
            ['nama' => 'Rina Handayani',  'email' => 'siswa4@SIMPEL.com', 'nis' => '2024004'],
            ['nama' => 'Rizky Setiawan',  'email' => 'siswa5@SIMPEL.com', 'nis' => '2024005'],
        ];

        // Nilai yang sudah ditentukan (bervariasi agar tidak monoton)
        $nilaiPreset = [
            // [tugas, uts, uas]
            [85, 78, 90],
            [72, 65, 70],
            [90, 88, 95],
            [60, 55, 62],
            [78, 82, 80],
        ];

        foreach ($siswaData as $idx => $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['nama'],
                    'password' => Hash::make('password123'),
                    'role'     => 'siswa',
                ]
            );

            $siswa = Siswa::firstOrCreate(
                ['nis' => $data['nis']],
                [
                    'user_id'  => $user->id,
                    'nama'     => $data['nama'],
                    'kelas'    => 'X-A',
                    'angkatan' => 2024,
                ]
            );

            // Nilai Matematika (dari Guru 1)
            Nilai::firstOrCreate(
                ['siswa_id' => $siswa->id, 'mata_pelajaran_id' => $mtk->id],
                [
                    'guru_id'     => $guru1->id,
                    'nilai_tugas' => $nilaiPreset[$idx][0],
                    'nilai_uts'   => $nilaiPreset[$idx][1],
                    'nilai_uas'   => $nilaiPreset[$idx][2],
                ]
            );

            // Nilai Bahasa Indonesia (dari Guru 2)
            Nilai::firstOrCreate(
                ['siswa_id' => $siswa->id, 'mata_pelajaran_id' => $bid->id],
                [
                    'guru_id'     => $guru2->id,
                    'nilai_tugas' => $nilaiPreset[$idx][2],       // variasi berbeda
                    'nilai_uts'   => $nilaiPreset[$idx][0] - 5,
                    'nilai_uas'   => $nilaiPreset[$idx][1] + 8,
                ]
            );
        }

        $this->command->info('✅ 5 Siswa kelas X-A + nilai MTK & BID sudah diisi');

        // --------------------------------------------------------
        // RINGKASAN
        // --------------------------------------------------------
        $this->command->newLine();
        $this->command->line('  <fg=cyan>┌─────────────────────────────────────────────┐</>');
        $this->command->line('  <fg=cyan>│       SIMPEL — Data Siap (Skala Kecil)      │</>');
        $this->command->line('  <fg=cyan>├─────────────────────────────────────────────┤</>');
        $this->command->line('  <fg=cyan>│  Admin : admin@SIMPEL.com / password123     │</>');
        $this->command->line('  <fg=cyan>│  Guru  : guru1@SIMPEL.com (MTK) /pw123      │</>');
        $this->command->line('  <fg=cyan>│  Guru  : guru2@SIMPEL.com (BID) /pw123      │</>');
        $this->command->line('  <fg=cyan>│  Siswa : siswa1~5@SIMPEL.com / password123  │</>');
        $this->command->line('  <fg=cyan>│  Kelas : X-A | Mapel: MTK, BID (+ nilai)   │</>');
        $this->command->line('  <fg=cyan>├─────────────────────────────────────────────┤</>');
        $this->command->line('  <fg=yellow>│  [+] Tambah 180 dummy:                      │</>');
        $this->command->line('  <fg=yellow>│  php artisan db:seed --class=DummySeeder    │</>');
        $this->command->line('  <fg=cyan>└─────────────────────────────────────────────┘</>');
        $this->command->newLine();
    }
}
