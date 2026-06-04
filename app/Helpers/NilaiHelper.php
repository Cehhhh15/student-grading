<?php

/**
 * ============================================================
 * FILE: NilaiHelper.php
 * LOKASI: app/Helpers/NilaiHelper.php
 * ============================================================
 * DESKRIPSI:
 *   File ini berisi kumpulan FUNGSI PROSEDURAL (bukan method OOP)
 *   untuk menangani logika pengolahan nilai siswa.
 *
 *   Fungsi-fungsi ini merepresentasikan implementasi
 *   PEMROGRAMAN TERSTRUKTUR dalam sistem SIMPEL.
 *
 *   File ini dimuat otomatis melalui autoload di composer.json.
 *   Fungsi-fungsi ini dapat dipanggil dari mana saja dalam
 *   aplikasi — termasuk dari dalam class/model (OOP),
 *   sehingga menunjukkan integrasi kedua paradigma.
 *
 * DAFTAR FUNGSI:
 *   1. validasiNilai()          - Validasi rentang nilai 0-100
 *   2. hitungNilaiAkhir()       - Hitung nilai akhir berdasarkan bobot
 *   3. tentukanKelulusan()      - Tentukan status LULUS/TIDAK LULUS
 *   4. konversiNilaiKeHuruf()   - Konversi nilai numerik ke grade huruf
 *   5. formatLaporan()          - Format data untuk laporan PDF
 *   6. getWarnaBadge()          - Ambil kelas CSS warna badge status
 *
 * PARADIGMA: Pemrograman Terstruktur (Procedural Programming)
 * AUTHOR: SIMPEL Development Team
 * VERSI: 1.0.0
 * ============================================================
 */

// ============================================================
// FUNGSI 1: VALIDASI NILAI
// ============================================================

/**
 * Memvalidasi apakah sebuah nilai berada dalam rentang yang valid (0-100).
 *
 * Fungsi ini mengecek dua kondisi:
 * 1. Apakah input merupakan angka (numerik)?
 * 2. Apakah nilai berada dalam rentang 0 sampai 100?
 *
 * Contoh penggunaan:
 *   validasiNilai(85)    => true  (valid)
 *   validasiNilai(105)   => false (melebihi batas atas)
 *   validasiNilai(-5)    => false (di bawah batas bawah)
 *   validasiNilai('abc') => false (bukan angka)
 *
 * @param  mixed $nilai  Nilai yang akan divalidasi (bisa berupa angka atau string)
 * @return bool          true jika nilai valid (0-100), false jika tidak valid
 */
function validasiNilai(mixed $nilai): bool
{
    // Cek apakah input merupakan angka (integer atau float)
    if (! is_numeric($nilai)) {
        return false;
    }

    // Konversi ke float untuk perbandingan yang akurat
    $nilaiFloat = (float) $nilai;

    // Cek apakah nilai berada dalam rentang 0 hingga 100 (inklusif)
    return $nilaiFloat >= 0 && $nilaiFloat <= 100;
}

// ============================================================
// FUNGSI 2: HITUNG NILAI AKHIR
// ============================================================

/**
 * Menghitung nilai akhir siswa berdasarkan bobot yang telah ditentukan.
 *
 * RUMUS PERHITUNGAN:
 *   Nilai Akhir = (30% × Nilai Tugas) + (30% × Nilai UTS) + (40% × Nilai UAS)
 *
 * Bobot penilaian:
 *   - Tugas : 30% (0.30)
 *   - UTS   : 30% (0.30)
 *   - UAS   : 40% (0.40)
 *
 * Contoh penggunaan:
 *   hitungNilaiAkhir(80, 75, 90)
 *   = (0.30 × 80) + (0.30 × 75) + (0.40 × 90)
 *   = 24 + 22.5 + 36
 *   = 82.50
 *
 * @param  float $tugas  Nilai tugas siswa (rentang 0-100)
 * @param  float $uts    Nilai Ujian Tengah Semester (rentang 0-100)
 * @param  float $uas    Nilai Ujian Akhir Semester (rentang 0-100)
 * @return float         Nilai akhir yang sudah dibulatkan ke 2 desimal
 */
function hitungNilaiAkhir(float $tugas, float $uts, float $uas): float
{
    // Bobot masing-masing komponen nilai (sesuai ketentuan sistem)
    $bobotTugas = 0.30; // 30%
    $bobotUts   = 0.30; // 30%
    $bobotUas   = 0.40; // 40%

    // Hitung nilai akhir dengan rumus berbobot
    $nilaiAkhir = ($bobotTugas * $tugas)
                + ($bobotUts * $uts)
                + ($bobotUas * $uas);

    // Bulatkan ke 2 angka desimal untuk tampilan yang rapi
    return round($nilaiAkhir, 2);
}

// ============================================================
// FUNGSI 3: TENTUKAN STATUS KELULUSAN
// ============================================================

/**
 * Menentukan status kelulusan siswa berdasarkan nilai akhir.
 *
 * KETENTUAN KELULUSAN:
 *   - Nilai Akhir >= 70 : LULUS
 *   - Nilai Akhir <  70 : TIDAK LULUS
 *
 * Batas kelulusan (Kriteria Ketuntasan Minimum/KKM) adalah 70.
 *
 * Contoh penggunaan:
 *   tentukanKelulusan(85)  => 'LULUS'
 *   tentukanKelulusan(70)  => 'LULUS'  (batas tepat = lulus)
 *   tentukanKelulusan(69)  => 'TIDAK LULUS'
 *   tentukanKelulusan(45)  => 'TIDAK LULUS'
 *
 * @param  float  $nilaiAkhir  Nilai akhir siswa (hasil dari hitungNilaiAkhir())
 * @return string              'LULUS' jika >= 70, 'TIDAK LULUS' jika < 70
 */
function tentukanKelulusan(float $nilaiAkhir): string
{
    // Kriteria Ketuntasan Minimum (KKM) yang berlaku di sistem ini
    $kkm = 70;

    // Bandingkan nilai akhir dengan KKM
    if ($nilaiAkhir >= $kkm) {
        return 'LULUS';
    }

    return 'TIDAK LULUS';
}

// ============================================================
// FUNGSI 4: KONVERSI NILAI KE HURUF (GRADE)
// ============================================================

/**
 * Mengonversi nilai akhir numerik menjadi grade huruf (A, B, C, D, E).
 *
 * SKALA KONVERSI:
 *   A : 90 - 100  (Sangat Baik)
 *   B : 80 - 89   (Baik)
 *   C : 70 - 79   (Cukup)
 *   D : 60 - 69   (Kurang)
 *   E : 0  - 59   (Sangat Kurang / Tidak Lulus)
 *
 * Contoh penggunaan:
 *   konversiNilaiKeHuruf(95)  => 'A'
 *   konversiNilaiKeHuruf(82)  => 'B'
 *   konversiNilaiKeHuruf(73)  => 'C'
 *   konversiNilaiKeHuruf(65)  => 'D'
 *   konversiNilaiKeHuruf(50)  => 'E'
 *
 * @param  float  $nilaiAkhir  Nilai akhir siswa (0-100)
 * @return string              Grade huruf (A/B/C/D/E)
 */
function konversiNilaiKeHuruf(float $nilaiAkhir): string
{
    // Evaluasi nilai dari yang tertinggi ke terendah
    if ($nilaiAkhir >= 90) {
        return 'A'; // Sangat Baik
    } elseif ($nilaiAkhir >= 80) {
        return 'B'; // Baik
    } elseif ($nilaiAkhir >= 70) {
        return 'C'; // Cukup (batas lulus)
    } elseif ($nilaiAkhir >= 60) {
        return 'D'; // Kurang
    } else {
        return 'E'; // Sangat Kurang / Tidak Lulus
    }
}

// ============================================================
// FUNGSI 5: FORMAT DATA LAPORAN
// ============================================================

/**
 * Memformat array data nilai siswa mentah dari database menjadi
 * struktur yang siap digunakan untuk laporan / cetak PDF.
 *
 * Fungsi ini melakukan:
 * 1. Iterasi setiap record nilai siswa
 * 2. Menambahkan kolom 'grade' (huruf) menggunakan konversiNilaiKeHuruf()
 * 3. Mengembalikan array yang sudah diformat dan siap ditampilkan
 *
 * Contoh input:
 *   [
 *     ['nis' => '2024001', 'nama' => 'Budi', 'nilai_akhir' => 85, 'status' => 'LULUS', ...]
 *   ]
 *
 * Contoh output:
 *   [
 *     ['nis' => '2024001', 'nama' => 'Budi', 'nilai_akhir' => 85, 'grade' => 'B', 'status' => 'LULUS', ...]
 *   ]
 *
 * @param  array $dataNilai  Array asosiatif berisi data nilai dari database
 * @return array             Array yang sudah diformat dan siap digunakan untuk laporan
 */
function formatLaporan(array $dataNilai): array
{
    // Array untuk menyimpan hasil format
    $laporan = [];

    // Iterasi setiap data nilai siswa
    foreach ($dataNilai as $nilai) {
        // Tambahkan grade huruf dari nilai akhir yang ada
        $laporan[] = [
            'nis'            => $nilai['nis']            ?? '-',
            'nama'           => $nilai['nama']           ?? '-',
            'kelas'          => $nilai['kelas']          ?? '-',
            'mata_pelajaran' => $nilai['mata_pelajaran'] ?? '-',
            'nilai_tugas'    => $nilai['nilai_tugas']    ?? 0,
            'nilai_uts'      => $nilai['nilai_uts']      ?? 0,
            'nilai_uas'      => $nilai['nilai_uas']      ?? 0,
            'nilai_akhir'    => $nilai['nilai_akhir']    ?? 0,
            'grade'          => konversiNilaiKeHuruf((float) ($nilai['nilai_akhir'] ?? 0)),
            'status'         => $nilai['status']         ?? 'TIDAK LULUS',
        ];
    }

    return $laporan;
}

// ============================================================
// FUNGSI 6: WARNA BADGE STATUS
// ============================================================

/**
 * Mengembalikan kelas CSS Bootstrap yang sesuai untuk badge status kelulusan.
 *
 * Fungsi ini digunakan di view Blade untuk menampilkan badge
 * dengan warna yang berbeda tergantung status kelulusan siswa.
 *
 * Contoh penggunaan di Blade:
 *   <span class="badge bg-{{ getWarnaBadge($status) }}">{{ $status }}</span>
 *
 * Contoh output:
 *   getWarnaBadge('LULUS')       => 'success'  (hijau)
 *   getWarnaBadge('TIDAK LULUS') => 'danger'   (merah)
 *
 * @param  string $status  Status kelulusan ('LULUS' atau 'TIDAK LULUS')
 * @return string          Nama kelas warna Bootstrap (success/danger)
 */
function getWarnaBadge(string $status): string
{
    // Kembalikan warna sesuai status
    return $status === 'LULUS' ? 'success' : 'danger';
}
