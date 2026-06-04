<?php

/**
 * Migration: Buat tabel nilais
 * Tabel inti untuk menyimpan nilai siswa per mata pelajaran.
 * nilai_akhir dan status dihitung otomatis oleh Model (lihat Model/Nilai.php).
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration: buat tabel nilais.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();

            // Relasi ke siswa yang dinilai
            $table->foreignId('siswa_id')
                  ->constrained('siswas')
                  ->onDelete('cascade');

            // Relasi ke mata pelajaran yang dinilai
            $table->foreignId('mata_pelajaran_id')
                  ->constrained('mata_pelajarans')
                  ->onDelete('cascade');

            // Relasi ke guru yang menginput nilai
            $table->foreignId('guru_id')
                  ->constrained('gurus')
                  ->onDelete('cascade');

            // Komponen nilai (input manual oleh guru)
            $table->decimal('nilai_tugas', 5, 2);  // Nilai tugas (0.00 - 100.00)
            $table->decimal('nilai_uts', 5, 2);     // Nilai UTS
            $table->decimal('nilai_uas', 5, 2);     // Nilai UAS

            // Hasil kalkulasi otomatis (dihitung oleh Model::boot())
            $table->decimal('nilai_akhir', 5, 2);   // Nilai akhir berbobot
            $table->enum('status', ['LULUS', 'TIDAK LULUS']); // Status kelulusan

            $table->timestamps();

            // UNIQUE CONSTRAINT: Satu siswa hanya boleh punya SATU nilai per mata pelajaran
            // Mencegah duplikasi data nilai yang sama
            $table->unique(['siswa_id', 'mata_pelajaran_id'], 'unique_nilai_siswa_mapel');
        });
    }

    /**
     * Batalkan migration: hapus tabel nilais.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
