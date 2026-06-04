<?php

/**
 * Migration: Buat tabel mata_pelajarans
 * Tabel ini menyimpan data mata pelajaran yang ada di sekolah.
 * Dibuat lebih awal karena tabel gurus dan nilais membutuhkan foreign key ke sini.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel mata_pelajarans.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('mata_pelajarans', function (Blueprint $table) {
            $table->id();                            // Primary Key
            $table->string('kode', 10)->unique();    // Kode mapel unik (contoh: MTK, BIG, IPA)
            $table->string('nama');                  // Nama mata pelajaran
            $table->timestamps();                    // created_at, updated_at
        });
    }

    /**
     * Batalkan migration: hapus tabel mata_pelajarans.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_pelajarans');
    }
};
