<?php

/**
 * Migration: Buat tabel gurus
 * Menyimpan profil guru, terhubung ke users dan mata_pelajarans.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel gurus.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel users
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('nip', 30)->unique();  // Nomor Induk Pegawai

            $table->string('nama');               // Nama lengkap guru

            // Foreign key ke mata pelajaran yang diampu
            $table->foreignId('mata_pelajaran_id')
                  ->constrained('mata_pelajarans')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Batalkan migration: hapus tabel gurus.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
