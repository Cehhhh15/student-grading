<?php

/**
 * ============================================================
 * FILE: DatabaseSeeder.php
 * LOKASI: database/seeders/DatabaseSeeder.php
 * ============================================================
 * Seeder untuk mengisi data awal (dummy data) ke database.
 * Jalankan dengan: php artisan db:seed
 *
 * Data yang dibuat:
 * - 1 Admin
 * - 3 Guru (masing-masing mengampu 1 mapel)
 * - 5 Siswa dengan akun login
 * - 3 Mata Pelajaran
 * - Nilai untuk semua siswa di semua mapel
 * ============================================================
 */

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Mengisi database dengan data awal untuk pengujian sistem.
     *
     * @return void
     */
    public function run(): void
    {
        // ============================================================
        // 1. BUAT AKUN ADMIN
        // ============================================================
        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@SIMPEL.com',
            'password' => Hash::make('password123'), // Hash password dengan bcrypt
            'role'     => 'admin',
        ]);

        // ============================================================
        // 2. BUAT MATA PELAJARAN
        // ============================================================
        $mapelMatematika = MataPelajaran::create(['kode' => 'MTK', 'nama' => 'Matematika']);
        $mapelBahasaIndo = MataPelajaran::create(['kode' => 'BID', 'nama' => 'Bahasa Indonesia']);
        $mapelBahasaIng  = MataPelajaran::create(['kode' => 'BIG', 'nama' => 'Bahasa Inggris']);

        // ============================================================
        // 3. BUAT AKUN GURU + PROFIL GURU
        // ============================================================

        // Guru 1: Mengampu Matematika
        $userGuru1 = User::create([
            'name'     => 'Bapak Ahmad Fauzi',
            'email'    => 'ahmad@SIMPEL.com',
            'password' => Hash::make('password123'),
            'role'     => 'guru',
        ]);
        $guru1 = Guru::create([
            'user_id'           => $userGuru1->id,
            'nip'               => '198501012010011001',
            'nama'              => 'Ahmad Fauzi',
            'mata_pelajaran_id' => $mapelMatematika->id,
        ]);

        // Guru 2: Mengampu Bahasa Indonesia
        $userGuru2 = User::create([
            'name'     => 'Ibu Sari Dewi',
            'email'    => 'sari@SIMPEL.com',
            'password' => Hash::make('password123'),
            'role'     => 'guru',
        ]);
        $guru2 = Guru::create([
            'user_id'           => $userGuru2->id,
            'nip'               => '197803152005012002',
            'nama'              => 'Sari Dewi',
            'mata_pelajaran_id' => $mapelBahasaIndo->id,
        ]);

        // Guru 3: Mengampu Bahasa Inggris
        $userGuru3 = User::create([
            'name'     => 'Bapak Rizky Pratama',
            'email'    => 'rizky@SIMPEL.com',
            'password' => Hash::make('password123'),
            'role'     => 'guru',
        ]);
        $guru3 = Guru::create([
            'user_id'           => $userGuru3->id,
            'nip'               => '199002202015031003',
            'nama'              => 'Rizky Pratama',
            'mata_pelajaran_id' => $mapelBahasaIng->id,
        ]);

        // ============================================================
        // 4. BUAT AKUN SISWA + PROFIL SISWA
        // ============================================================
        $dataSiswa = [
            ['nis' => '2024001', 'nama' => 'Budi Santoso',    'kelas' => 'X-A', 'email' => 'budi@SIMPEL.com'],
            ['nis' => '2024002', 'nama' => 'Siti Aminah',     'kelas' => 'X-A', 'email' => 'siti@SIMPEL.com'],
            ['nis' => '2024003', 'nama' => 'Andi Pratama',    'kelas' => 'X-B', 'email' => 'andi@SIMPEL.com'],
            ['nis' => '2024004', 'nama' => 'Dewi Lestari',    'kelas' => 'X-B', 'email' => 'dewi@SIMPEL.com'],
            ['nis' => '2024005', 'nama' => 'Fajar Nugroho',   'kelas' => 'X-A', 'email' => 'fajar@SIMPEL.com'],
        ];

        $siswas = [];
        foreach ($dataSiswa as $data) {
            $userSiswa = User::create([
                'name'     => $data['nama'],
                'email'    => $data['email'],
                'password' => Hash::make('password123'),
                'role'     => 'siswa',
            ]);
            $siswas[] = Siswa::create([
                'user_id' => $userSiswa->id,
                'nis'     => $data['nis'],
                'nama'    => $data['nama'],
                'kelas'   => $data['kelas'],
            ]);
        }

        // ============================================================
        // 5. BUAT DATA NILAI
        // Nilai akan otomatis dihitung oleh Model::boot() event 'saving'
        // ============================================================
        $dataNilai = [
            // [siswa_index, guru, mapel, tugas, uts, uas]
            // Budi Santoso
            [$siswas[0], $guru1, $mapelMatematika, 85, 78, 90],
            [$siswas[0], $guru2, $mapelBahasaIndo, 80, 75, 85],
            [$siswas[0], $guru3, $mapelBahasaIng,  70, 65, 75],
            // Siti Aminah
            [$siswas[1], $guru1, $mapelMatematika, 90, 88, 92],
            [$siswas[1], $guru2, $mapelBahasaIndo, 88, 85, 90],
            [$siswas[1], $guru3, $mapelBahasaIng,  75, 80, 78],
            // Andi Pratama (ada yang tidak lulus)
            [$siswas[2], $guru1, $mapelMatematika, 60, 55, 65],
            [$siswas[2], $guru2, $mapelBahasaIndo, 70, 68, 72],
            [$siswas[2], $guru3, $mapelBahasaIng,  50, 45, 55],
            // Dewi Lestari
            [$siswas[3], $guru1, $mapelMatematika, 78, 82, 80],
            [$siswas[3], $guru2, $mapelBahasaIndo, 85, 88, 90],
            [$siswas[3], $guru3, $mapelBahasaIng,  80, 75, 85],
            // Fajar Nugroho (ada yang tidak lulus)
            [$siswas[4], $guru1, $mapelMatematika, 55, 60, 65],
            [$siswas[4], $guru2, $mapelBahasaIndo, 72, 70, 75],
            [$siswas[4], $guru3, $mapelBahasaIng,  68, 65, 70],
        ];

        foreach ($dataNilai as [$siswa, $guru, $mapel, $tugas, $uts, $uas]) {
            // nilai_akhir dan status dihitung otomatis oleh Model Nilai
            Nilai::create([
                'siswa_id'          => $siswa->id,
                'mata_pelajaran_id' => $mapel->id,
                'guru_id'           => $guru->id,
                'nilai_tugas'       => $tugas,
                'nilai_uts'         => $uts,
                'nilai_uas'         => $uas,
            ]);
        }
    }
}
