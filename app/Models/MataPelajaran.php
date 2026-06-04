<?php

/**
 * ============================================================
 * FILE: MataPelajaran.php (Model)
 * LOKASI: app/Models/MataPelajaran.php
 * ============================================================
 * CLASS OOP: MataPelajaran
 *
 * Model ini merepresentasikan entitas Mata Pelajaran.
 * Setiap mata pelajaran memiliki kode unik dan nama.
 *
 * ATRIBUT UTAMA:
 *   - id    : ID unik (Primary Key)
 *   - kode  : Kode mata pelajaran (unik, contoh: MTK, BIG, IPA)
 *   - nama  : Nama lengkap mata pelajaran
 *
 * RELASI:
 *   - hasMany Nilai  : Satu mapel memiliki banyak data nilai
 *   - hasMany Guru   : Satu mapel bisa diampu oleh banyak guru
 *
 * PARADIGMA: Pemrograman Berorientasi Objek (OOP)
 * ============================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    use HasFactory;

    // ============================================================
    // ATRIBUT
    // ============================================================

    /**
     * Nama tabel di database yang digunakan model ini.
     * Laravel secara default akan mencari tabel 'mata_pelajarans'.
     *
     * @var string
     */
    protected $table = 'mata_pelajarans';

    /**
     * Kolom yang diizinkan untuk mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode', // Kode unik mata pelajaran (contoh: MTK, BIG)
        'nama', // Nama lengkap mata pelajaran (contoh: Matematika)
    ];

    // ============================================================
    // METHOD - Relasi
    // ============================================================

    /**
     * Relasi One-to-Many: MataPelajaran ke Nilai.
     *
     * Satu mata pelajaran memiliki BANYAK data nilai
     * (dari banyak siswa yang mengikuti mapel tersebut).
     *
     * Contoh penggunaan:
     *   $mapel->nilais->count()  => Jumlah nilai yang ada untuk mapel ini
     *
     * @return HasMany<Nilai, $this>
     */
    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class, 'mata_pelajaran_id');
    }

    /**
     * Relasi One-to-Many: MataPelajaran ke Guru.
     *
     * Satu mata pelajaran bisa diampu oleh beberapa guru.
     *
     * @return HasMany<Guru, $this>
     */
    public function gurus(): HasMany
    {
        return $this->hasMany(Guru::class, 'mata_pelajaran_id');
    }
}
