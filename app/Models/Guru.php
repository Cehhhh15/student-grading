<?php

/**
 * ============================================================
 * FILE: Guru.php (Model)
 * LOKASI: app/Models/Guru.php
 * ============================================================
 * CLASS OOP: Guru
 *
 * Model ini merepresentasikan entitas Guru dalam sistem.
 * Guru bertanggung jawab untuk menginput nilai siswa
 * pada mata pelajaran yang ia ampu.
 *
 * ATRIBUT UTAMA:
 *   - id                : ID unik (Primary Key)
 *   - user_id           : Relasi ke tabel users (Foreign Key)
 *   - nip               : Nomor Induk Pegawai (unik)
 *   - nama              : Nama lengkap guru
 *   - mata_pelajaran_id : Mata pelajaran yang diampu (Foreign Key)
 *
 * RELASI:
 *   - belongsTo User         : Guru memiliki satu akun user
 *   - belongsTo MataPelajaran: Guru mengampu satu mata pelajaran
 *   - hasMany Nilai          : Guru telah menginput banyak nilai
 *
 * METHOD BISNIS:
 *   - getJumlahSiswaInput(): Hitung berapa siswa yang sudah diinput nilainya
 *
 * PARADIGMA: Pemrograman Berorientasi Objek (OOP)
 * ============================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
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
    protected $table = 'gurus';

    /**
     * Kolom yang diizinkan untuk mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',           // Foreign key ke tabel users
        'nip',               // Nomor Induk Pegawai (unik)
        'nama',              // Nama lengkap guru
        'mata_pelajaran_id', // Foreign key ke tabel mata_pelajarans
    ];

    // ============================================================
    // METHOD - Relasi
    // ============================================================

    /**
     * Relasi Many-to-One: Guru ke User.
     *
     * Setiap guru dimiliki oleh satu akun user.
     * Data login (email, password) tersimpan di tabel users.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Many-to-One: Guru ke MataPelajaran.
     *
     * Setiap guru mengampu SATU mata pelajaran.
     *
     * Contoh penggunaan:
     *   $guru->mataPelajaran->nama  => Nama mapel yang diampu
     *   $guru->mataPelajaran->kode  => Kode mapel (contoh: MTK)
     *
     * @return BelongsTo<MataPelajaran, $this>
     */
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    /**
     * Relasi One-to-Many: Guru ke Nilai.
     *
     * Satu guru bisa menginput BANYAK nilai untuk banyak siswa.
     *
     * Contoh penggunaan:
     *   $guru->nilais           => Semua nilai yang pernah diinput guru ini
     *   $guru->nilais()->count()=> Jumlah total nilai yang sudah diinput
     *
     * @return HasMany<Nilai, $this>
     */
    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    /**
     * Relasi One-to-Many: Guru ke GuruKelas.
     *
     * @return HasMany<GuruKelas, $this>
     */
    public function kelasMengajar(): HasMany
    {
        return $this->hasMany(GuruKelas::class);
    }

    // ============================================================
    // METHOD - Logika Bisnis
    // ============================================================

    /**
     * Menghitung jumlah siswa unik yang sudah diinput nilainya oleh guru ini.
     *
     * Menggunakan distinct() untuk memastikan setiap siswa dihitung sekali
     * meskipun memiliki beberapa baris nilai.
     *
     * Contoh penggunaan:
     *   echo $guru->getJumlahSiswaInput(); // Output: 30 (siswa)
     *
     * @return int  Jumlah siswa yang sudah diinput nilainya
     */
    public function getJumlahSiswaInput(): int
    {
        return $this->nilais()
            ->distinct('siswa_id')
            ->count('siswa_id');
    }
}
