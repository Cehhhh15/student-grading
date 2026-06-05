<?php

/**
 * Migration: Buat tabel guru_kelas
 * Relasi many-to-many antara guru dan kelas yang mereka ajar.
 * Sudah digabung ke dalam migration awal (tidak perlu migration terpisah).
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel guru_kelas.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('guru_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->string('kelas', 20);
            $table->timestamps();

            // Satu guru tidak boleh mengajar kelas yang sama dua kali
            $table->unique(['guru_id', 'kelas'], 'unique_guru_kelas');
        });
    }

    /**
     * Batalkan migration: hapus tabel guru_kelas.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_kelas');
    }
};
