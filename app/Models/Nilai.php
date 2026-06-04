<?php

/**
 * ============================================================
 * FILE: Nilai.php (Model)
 * LOKASI: app/Models/Nilai.php
 * ============================================================
 * CLASS OOP: Nilai
 *
 * Model ini merepresentasikan entitas Nilai dalam sistem.
 * Ini adalah model INTI dari SIMPEL — menyimpan data nilai
 * setiap siswa untuk setiap mata pelajaran.
 *
 * ATRIBUT UTAMA:
 *   - id                : ID unik (Primary Key)
 *   - siswa_id          : Siswa yang dinilai (Foreign Key)
 *   - mata_pelajaran_id : Mata pelajaran yang dinilai (Foreign Key)
 *   - guru_id           : Guru yang menginput nilai (Foreign Key)
 *   - nilai_tugas       : Nilai tugas (0-100)
 *   - nilai_uts         : Nilai UTS (0-100)
 *   - nilai_uas         : Nilai UAS (0-100)
 *   - nilai_akhir       : Nilai akhir (dihitung otomatis)
 *   - status            : LULUS / TIDAK LULUS (ditentukan otomatis)
 *
 * RELASI:
 *   - belongsTo Siswa         : Nilai dimiliki oleh satu siswa
 *   - belongsTo Guru          : Nilai diinput oleh satu guru
 *   - belongsTo MataPelajaran : Nilai untuk satu mata pelajaran
 *
 * FITUR OTOMATIS (via boot):
 *   - nilai_akhir dihitung OTOMATIS saat data disimpan
 *   - status LULUS/TIDAK LULUS ditentukan OTOMATIS
 *   - Menggunakan fungsi prosedural dari NilaiHelper.php
 *     (Integrasi OOP + Pemrograman Terstruktur)
 *
 * PARADIGMA: Pemrograman Berorientasi Objek (OOP)
 *            + Integrasi Pemrograman Terstruktur
 * ============================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    // ============================================================
    // ATRIBUT
    // ============================================================

    /**
     * Nama tabel database untuk model ini.
     *
     * @var string
     */
    protected $table = 'nilais';

    /**
     * Kolom yang diizinkan untuk mass assignment.
     * Catatan: nilai_akhir dan status TIDAK perlu diisi manual
     * karena akan dihitung otomatis oleh event 'saving' di boot().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'siswa_id',           // ID siswa yang dinilai
        'mata_pelajaran_id',  // ID mata pelajaran
        'guru_id',            // ID guru yang menginput
        'nilai_tugas',        // Nilai tugas (0-100)
        'nilai_uts',          // Nilai Ujian Tengah Semester (0-100)
        'nilai_uas',          // Nilai Ujian Akhir Semester (0-100)
        'nilai_akhir',        // Dihitung otomatis
        'status',             // Ditentukan otomatis
    ];

    /**
     * Casting tipe data untuk kolom-kolom numerik.
     * Memastikan nilai selalu bertipe float saat diakses.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'nilai_tugas'  => 'float',
        'nilai_uts'    => 'float',
        'nilai_uas'    => 'float',
        'nilai_akhir'  => 'float',
    ];

    // ============================================================
    // BOOT - Otomatisasi saat Model Event terjadi
    // ============================================================

    /**
     * Inisialisasi event listener untuk model Nilai.
     *
     * Method boot() dijalankan sekali saat model pertama kali digunakan.
     * Di sini kita mendaftarkan event 'saving' yang akan berjalan
     * SETIAP KALI data nilai akan disimpan (create atau update).
     *
     * INTEGRASI PROSEDURAL + OOP:
     * Method boot() dalam class OOP ini memanggil fungsi-fungsi
     * prosedural dari NilaiHelper.php untuk melakukan perhitungan.
     * Ini adalah contoh nyata integrasi kedua paradigma pemrograman.
     *
     * @return void
     */
    protected static function boot(): void
    {
        // Panggil boot() dari parent class (Model) terlebih dahulu
        parent::boot();

        /**
         * Event 'saving': Dijalankan SEBELUM data disimpan ke database.
         *
         * Setiap kali Nilai::create() atau $nilai->save() dipanggil,
         * kode di dalam closure ini akan berjalan terlebih dahulu.
         *
         * Ini memastikan nilai_akhir dan status SELALU dihitung ulang
         * secara otomatis — tidak perlu dihitung manual di controller.
         */
        static::saving(function (Nilai $nilai): void {

            // -------------------------------------------------------
            // LANGKAH 1: Hitung Nilai Akhir
            // Memanggil fungsi prosedural hitungNilaiAkhir() dari NilaiHelper.php
            // Rumus: NA = (30% × Tugas) + (30% × UTS) + (40% × UAS)
            // -------------------------------------------------------
            $nilai->nilai_akhir = hitungNilaiAkhir(
                (float) $nilai->nilai_tugas,
                (float) $nilai->nilai_uts,
                (float) $nilai->nilai_uas
            );

            // -------------------------------------------------------
            // LANGKAH 2: Tentukan Status Kelulusan
            // Memanggil fungsi prosedural tentukanKelulusan() dari NilaiHelper.php
            // LULUS jika nilai_akhir >= 70, TIDAK LULUS jika < 70
            // -------------------------------------------------------
            $nilai->status = tentukanKelulusan($nilai->nilai_akhir);
        });
    }

    // ============================================================
    // METHOD - Relasi
    // ============================================================

    /**
     * Relasi Many-to-One: Nilai ke Siswa.
     *
     * Setiap nilai dimiliki oleh SATU siswa.
     *
     * Contoh penggunaan:
     *   $nilai->siswa->nama  => Nama siswa pemilik nilai ini
     *   $nilai->siswa->nis   => NIS siswa pemilik nilai ini
     *
     * @return BelongsTo<Siswa, $this>
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Relasi Many-to-One: Nilai ke Guru.
     *
     * Setiap nilai diinput oleh SATU guru.
     *
     * Contoh penggunaan:
     *   $nilai->guru->nama  => Nama guru yang menginput nilai ini
     *
     * @return BelongsTo<Guru, $this>
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Relasi Many-to-One: Nilai ke MataPelajaran.
     *
     * Setiap nilai merupakan nilai untuk SATU mata pelajaran.
     *
     * Contoh penggunaan:
     *   $nilai->mataPelajaran->nama  => Nama mata pelajaran
     *   $nilai->mataPelajaran->kode  => Kode mata pelajaran (MTK, BIG, dll)
     *
     * @return BelongsTo<MataPelajaran, $this>
     */
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    // ============================================================
    // METHOD - Logika Bisnis
    // ============================================================

    /**
     * Menghitung ulang nilai akhir berdasarkan data saat ini.
     *
     * Method ini memanggil fungsi prosedural hitungNilaiAkhir()
     * dari NilaiHelper.php — contoh integrasi OOP + Prosedural.
     *
     * @return float  Nilai akhir yang dihitung ulang
     */
    public function hitungNilaiAkhir(): float
    {
        // Memanggil fungsi prosedural dari NilaiHelper.php
        return hitungNilaiAkhir(
            (float) $this->nilai_tugas,
            (float) $this->nilai_uts,
            (float) $this->nilai_uas
        );
    }

    /**
     * Menentukan status kelulusan berdasarkan nilai akhir saat ini.
     *
     * Method ini memanggil fungsi prosedural tentukanKelulusan()
     * dari NilaiHelper.php — contoh integrasi OOP + Prosedural.
     *
     * @return string  'LULUS' atau 'TIDAK LULUS'
     */
    public function tentukanKelulusan(): string
    {
        // Memanggil fungsi prosedural dari NilaiHelper.php
        return tentukanKelulusan((float) $this->nilai_akhir);
    }

    /**
     * Mendapatkan grade huruf dari nilai akhir.
     *
     * @return string  Grade huruf (A/B/C/D/E)
     */
    public function getGrade(): string
    {
        // Memanggil fungsi prosedural dari NilaiHelper.php
        return konversiNilaiKeHuruf((float) $this->nilai_akhir);
    }
}
