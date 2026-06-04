<?php

/**
 * ============================================================
 * FILE: Siswa.php (Model)
 * LOKASI: app/Models/Siswa.php
 * ============================================================
 * CLASS OOP: Siswa
 *
 * Model ini merepresentasikan entitas Siswa dalam sistem.
 * Menyimpan profil lengkap siswa yang terhubung dengan akun user.
 *
 * ATRIBUT UTAMA:
 *   - id       : ID unik (Primary Key)
 *   - user_id  : Relasi ke tabel users (Foreign Key)
 *   - nis      : Nomor Induk Siswa (unik)
 *   - nama     : Nama lengkap siswa
 *   - kelas    : Kelas siswa (contoh: X-A, XI-B)
 *
 * RELASI:
 *   - belongsTo User     : Siswa memiliki satu akun user
 *   - hasMany Nilai      : Siswa memiliki banyak data nilai (per mapel)
 *
 * METHOD BISNIS:
 *   - getNilaiByMapel()      : Ambil nilai siswa untuk mapel tertentu
 *   - getRataRataNilaiAkhir(): Hitung rata-rata semua nilai akhir
 *   - isLulusSemua()         : Cek apakah siswa lulus semua mapel
 *
 * PARADIGMA: Pemrograman Berorientasi Objek (OOP)
 * ============================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    // ============================================================
    // ATRIBUT
    // ============================================================

    /**
     * Nama tabel database yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'siswas';

    /**
     * Kolom yang diizinkan untuk mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // Foreign key ke tabel users
        'nis',     // Nomor Induk Siswa (unik)
        'nama',    // Nama lengkap siswa
        'kelas',   // Kelas siswa (contoh: X-A, XI-B, XII-C)
        'angkatan',// Angkatan masuk (contoh: 2024)
    ];

    // ============================================================
    // METHOD - Relasi
    // ============================================================

    /**
     * Relasi Many-to-One: Siswa ke User.
     *
     * Setiap siswa DIMILIKI oleh satu akun user.
     * Data login (email, password) disimpan di tabel users.
     *
     * Contoh penggunaan:
     *   $siswa->user->email  => Email akun siswa tersebut
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi One-to-Many: Siswa ke Nilai.
     *
     * Satu siswa memiliki BANYAK data nilai
     * (satu nilai untuk setiap mata pelajaran yang diikuti).
     *
     * Contoh penggunaan:
     *   $siswa->nilais              => Semua nilai siswa (Collection)
     *   $siswa->nilais()->count()   => Jumlah nilai yang ada
     *
     * @return HasMany<Nilai, $this>
     */
    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    // ============================================================
    // METHOD - Logika Bisnis (Business Logic)
    // ============================================================

    /**
     * Mengambil data nilai siswa untuk mata pelajaran tertentu.
     *
     * Digunakan untuk mengecek apakah siswa sudah diinput nilainya
     * untuk suatu mata pelajaran, atau untuk mengambil nilainya.
     *
     * Contoh penggunaan:
     *   $nilai = $siswa->getNilaiByMapel(1); // Ambil nilai mapel dengan ID 1
     *   if ($nilai) { echo $nilai->nilai_akhir; }
     *
     * @param  int        $mapelId  ID mata pelajaran yang dicari
     * @return Nilai|null           Object Nilai jika ditemukan, null jika belum ada
     */
    public function getNilaiByMapel(int $mapelId): ?Nilai
    {
        return $this->nilais()
            ->where('mata_pelajaran_id', $mapelId)
            ->first();
    }

    /**
     * Menghitung rata-rata nilai akhir dari semua mata pelajaran yang diikuti.
     *
     * Berguna untuk menampilkan ringkasan performa siswa secara keseluruhan.
     * Mengembalikan 0.00 jika siswa belum memiliki nilai sama sekali.
     *
     * Contoh penggunaan:
     *   echo $siswa->getRataRataNilaiAkhir(); // Output: 78.50
     *
     * @return float  Rata-rata nilai akhir (dibulatkan 2 desimal)
     */
    public function getRataRataNilaiAkhir(): float
    {
        // avg() mengembalikan null jika tidak ada data, ?? 0 untuk default
        return round((float) ($this->nilais()->avg('nilai_akhir') ?? 0), 2);
    }

    /**
     * Mengecek apakah siswa telah LULUS semua mata pelajaran.
     *
     * Kondisi lulus semua:
     * 1. Siswa harus sudah memiliki minimal satu nilai
     * 2. Tidak ada satu pun mata pelajaran yang berstatus 'TIDAK LULUS'
     *
     * Contoh penggunaan:
     *   if ($siswa->isLulusSemua()) { echo "Siswa lulus semua!"; }
     *
     * @return bool  true jika lulus semua mapel, false jika ada yang tidak lulus atau belum ada nilai
     */
    public function isLulusSemua(): bool
    {
        // Jika belum ada nilai sama sekali, belum bisa ditentukan
        if ($this->nilais()->count() === 0) {
            return false;
        }

        // Cek apakah ada nilai dengan status 'TIDAK LULUS'
        return $this->nilais()
            ->where('status', 'TIDAK LULUS')
            ->count() === 0;
    }
}
