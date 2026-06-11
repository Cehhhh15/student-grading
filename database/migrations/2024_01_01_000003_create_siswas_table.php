<?php

/**
 * Migration: Buat tabel siswas
 * Menyimpan profil lengkap siswa, terhubung ke tabel users.
 * Kolom 'angkatan' sudah digabung langsung di sini (tidak perlu migration terpisah).
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel siswas.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel users — cascade delete: jika user dihapus, profil siswa ikut terhapus
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('nis', 20)->unique();  // Nomor Induk Siswa (wajib unik)
            $table->string('nama');               // Nama lengkap siswa
            $table->string('kelas', 20);          // Kelas siswa (contoh: X-A, XI-B)
            $table->integer('angkatan')->nullable(); // Tahun angkatan (contoh: 2022, 2023, 2024)
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration: hapus tabel siswas.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
