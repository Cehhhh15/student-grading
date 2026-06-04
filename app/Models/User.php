<?php

/**
 * ============================================================
 * FILE: User.php (Model)
 * LOKASI: app/Models/User.php
 * ============================================================
 * CLASS OOP: User
 *
 * Model ini merepresentasikan entitas Pengguna (User) dalam sistem.
 * Setiap pengguna memiliki satu dari tiga role: admin, guru, atau siswa.
 *
 * ATRIBUT UTAMA:
 *   - id            : ID unik pengguna (Primary Key)
 *   - name          : Nama lengkap pengguna
 *   - email         : Email untuk login (unik)
 *   - password      : Password yang sudah di-hash (bcrypt)
 *   - role          : Hak akses (admin / guru / siswa)
 *
 * RELASI:
 *   - hasOne Siswa  : Jika role = 'siswa', memiliki profil siswa
 *   - hasOne Guru   : Jika role = 'guru', memiliki profil guru
 *
 * PARADIGMA: Pemrograman Berorientasi Objek (OOP)
 * ============================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // ============================================================
    // TRAITS - Fungsionalitas tambahan dari Laravel
    // ============================================================

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // ============================================================
    // ATRIBUT - Mendefinisikan properti model
    // ============================================================

    /**
     * Kolom yang diizinkan untuk mass assignment (pengisian massal).
     * Ini adalah fitur keamanan Laravel untuk mencegah mass assignment vulnerability.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Kolom yang disembunyikan dari serialisasi (JSON/array).
     * Password tidak boleh terekspos ke luar sistem.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data kolom secara otomatis.
     * Laravel akan mengonversi tipe data sesuai definisi ini.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed', // Password otomatis di-hash saat disimpan
        ];
    }

    // ============================================================
    // METHOD - Mendefinisikan perilaku dan relasi model
    // ============================================================

    /**
     * Relasi One-to-One: User ke Siswa.
     *
     * Setiap user dengan role 'siswa' memiliki SATU profil siswa
     * yang menyimpan data seperti NIS dan kelas.
     *
     * Contoh penggunaan:
     *   $user->siswa->nis    => Mendapatkan NIS siswa
     *   $user->siswa->kelas  => Mendapatkan kelas siswa
     *
     * @return HasOne<Siswa, $this>
     */
    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class);
    }

    /**
     * Relasi One-to-One: User ke Guru.
     *
     * Setiap user dengan role 'guru' memiliki SATU profil guru
     * yang menyimpan data seperti NIP dan mata pelajaran yang diampu.
     *
     * Contoh penggunaan:
     *   $user->guru->nip              => Mendapatkan NIP guru
     *   $user->guru->mataPelajaran->nama => Mendapatkan nama mapel
     *
     * @return HasOne<Guru, $this>
     */
    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class);
    }

    /**
     * Mengecek apakah pengguna ini adalah Admin.
     *
     * Digunakan untuk otorisasi: Admin memiliki akses penuh
     * termasuk CRUD semua data dan melihat laporan.
     *
     * Contoh penggunaan:
     *   if ($user->isAdmin()) { // tampilkan menu admin }
     *
     * @return bool  true jika role adalah 'admin'
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Mengecek apakah pengguna ini adalah Guru.
     *
     * Guru memiliki akses untuk menginput nilai siswa
     * dan melihat rekap nilai pada mata pelajaran yang diampu.
     *
     * @return bool  true jika role adalah 'guru'
     */
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Mengecek apakah pengguna ini adalah Siswa.
     *
     * Siswa hanya dapat melihat nilai pribadi dan status kelulusannya sendiri.
     *
     * @return bool  true jika role adalah 'siswa'
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }
}
