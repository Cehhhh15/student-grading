<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\GuruKelas;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Admin
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@SIMPEL.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // 2. Mata Pelajaran (10 Mapel)
        $mapelsData = [
            'MTK' => 'Matematika', 
            'BID' => 'Bahasa Indonesia', 
            'BIG' => 'Bahasa Inggris', 
            'IPA' => 'Ilmu Pengetahuan Alam', 
            'IPS' => 'Ilmu Pengetahuan Sosial', 
            'PJK' => 'Pendidikan Jasmani', 
            'PKN' => 'Pendidikan Kewarganegaraan', 
            'SBD' => 'Seni Budaya', 
            'AGM' => 'Pendidikan Agama', 
            'TIK' => 'Teknologi Informasi'
        ];
        
        $mapels = [];
        foreach($mapelsData as $kode => $nama) {
            $mapels[] = MataPelajaran::create(['kode' => $kode, 'nama' => $nama]);
        }

        // 3. Guru (15 Guru)
        $gurus = [];
        for ($i = 1; $i <= 15; $i++) {
            $user = User::create([
                'name'     => $faker->name,
                'email'    => "guru{$i}@SIMPEL.com",
                'password' => Hash::make('password123'),
                'role'     => 'guru',
            ]);
            
            // Randomly assign one of the mapels
            $mapel = $faker->randomElement($mapels); 
            
            $guru = Guru::create([
                'user_id'           => $user->id,
                'nip'               => $faker->numerify('##################'), // 18 digit NIP
                'nama'              => $user->name,
                'mata_pelajaran_id' => $mapel->id,
            ]);
            
            $gurus[] = $guru;
        }

        // 4. Assign Classes to Gurus
        $kelasList = ['X-A', 'X-B', 'X-C', 'XI-A', 'XI-B', 'XI-C', 'XII-A', 'XII-B', 'XII-C'];
        $gurusByMapel = collect($gurus)->groupBy('mata_pelajaran_id');
        
        foreach ($mapels as $mapel) {
            $gurusOfMapel = $gurusByMapel->get($mapel->id) ?? [];
            if (count($gurusOfMapel) == 0) continue;
            
            // Shuffle classes to distribute randomly but evenly
            $shuffledKelas = collect($kelasList)->shuffle();
            $idx = 0;
            
            while ($idx < count($shuffledKelas)) {
                foreach ($gurusOfMapel as $g) {
                    if ($idx >= count($shuffledKelas)) break;
                    GuruKelas::create([
                        'guru_id' => $g->id,
                        'kelas'   => $shuffledKelas[$idx]
                    ]);
                    $idx++;
                }
            }
        }

        // 5. Siswa (20 per class, 9 classes = 180 total)
        $siswas = [];
        $nisCounter = 2024001;
        foreach ($kelasList as $kelas) {
            // Tentukan angkatan berdasarkan tingkat kelas
            $angkatan = str_starts_with($kelas, 'XII') ? 2022 : (str_starts_with($kelas, 'XI') ? 2023 : 2024);
            
            for ($i = 1; $i <= 20; $i++) {
                $name = $faker->name;
                $user = User::create([
                    'name'     => $name,
                    'email'    => "siswa{$nisCounter}@SIMPEL.com",
                    'password' => Hash::make('password123'),
                    'role'     => 'siswa',
                ]);
                
                $siswas[] = Siswa::create([
                    'user_id'  => $user->id,
                    'nis'      => (string)$nisCounter,
                    'nama'     => $name,
                    'kelas'    => $kelas,
                    'angkatan' => $angkatan,
                ]);
                
                $nisCounter++;
            }
        }

        // 6. Nilai (Insert for each student, for each mapel taught in their class)
        // Build a lookup table in memory to avoid 1800+ queries
        $guruLookup = [];
        $semuaGuru = Guru::with('kelasMengajar')->get();
        foreach ($semuaGuru as $g) {
            foreach ($g->kelasMengajar as $gk) {
                $guruLookup[$g->mata_pelajaran_id][$gk->kelas] = $g->id;
            }
        }

        foreach ($siswas as $siswa) {
            foreach ($mapels as $mapel) {
                // If there's a teacher assigned to this mapel for this class
                if (isset($guruLookup[$mapel->id][$siswa->kelas])) {
                    $guru_id = $guruLookup[$mapel->id][$siswa->kelas];
                    
                    // Create nilai (the saving event in Nilai model calculates akhir & status)
                    Nilai::create([
                        'siswa_id'          => $siswa->id,
                        'mata_pelajaran_id' => $mapel->id,
                        'guru_id'           => $guru_id,
                        'nilai_tugas'       => $faker->numberBetween(50, 100),
                        'nilai_uts'         => $faker->numberBetween(50, 100),
                        'nilai_uas'         => $faker->numberBetween(50, 100),
                    ]);
                }
            }
        }
    }
}
