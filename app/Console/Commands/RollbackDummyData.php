<?php

/**
 * ============================================================
 * FILE  : RollbackDummyData.php
 * LOKASI: app/Console/Commands/RollbackDummyData.php
 * ============================================================
 * Artisan Command untuk menghapus data dummy siswa.
 *
 * CARA PAKAI:
 *   php artisan dummy:rollback
 *
 * ============================================================
 */

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RollbackDummyData extends Command
{
    /**
     * Nama perintah artisan yang bisa dipanggil di terminal.
     *
     * @var string
     */
    protected $signature = 'dummy:rollback
                            {--force : Paksa hapus tanpa konfirmasi}';

    /**
     * Deskripsi singkat perintah (tampil saat php artisan list).
     *
     * @var string
     */
    protected $description = 'Hapus semua data dummy siswa yang dibuat oleh DummyMahasiswaSeeder';

    /**
     * Tag penanda email dummy — harus sama dengan yang ada di DummyMahasiswaSeeder.
     */
    private string $dummyTag = '@dummy.simpel';

    /**
     * Jalankan perintah.
     *
     * @return int  0 = sukses, 1 = gagal/dibatalkan
     */
    public function handle(): int
    {
        $this->newLine();
        $this->line('╔══════════════════════════════════════════════╗');
        $this->line('║     SIMPEL — Rollback Data Dummy Siswa       ║');
        $this->line('╚══════════════════════════════════════════════╝');
        $this->newLine();

        // Cari semua akun dummy
        $dummyUsers = User::where('email', 'like', '%' . $this->dummyTag . '%')->get();

        if ($dummyUsers->isEmpty()) {
            $this->warn('  ⚠  Tidak ada data dummy yang ditemukan di database.');
            $this->line('  Pastikan DummyMahasiswaSeeder pernah dijalankan terlebih dahulu.');
            $this->newLine();
            return 0;
        }

        $jumlah = $dummyUsers->count();

        // Tampilkan preview data yang akan dihapus
        $this->table(
            ['Keterangan', 'Jumlah'],
            [
                ['Akun dummy yang ditemukan', $jumlah],
                ['Tag penanda email',         $this->dummyTag],
            ]
        );

        $this->newLine();
        $this->warn("  ⚠  Tindakan ini akan menghapus {$jumlah} akun siswa dummy beserta");
        $this->warn("     semua nilai yang terkait secara permanen!");
        $this->line('  Data asli (admin, guru, dan siswa dari DatabaseSeeder) TIDAK akan terpengaruh.');
        $this->newLine();

        // Konfirmasi (kecuali --force)
        if (! $this->option('force')) {
            if (! $this->confirm("  Lanjutkan penghapusan {$jumlah} data dummy?", false)) {
                $this->warn('  Dibatalkan oleh pengguna.');
                $this->newLine();
                return 1;
            }
        }

        $this->newLine();
        $this->line('  Menghapus data...');

        // Hapus dalam transaksi database
        try {
            DB::transaction(function () use ($dummyUsers) {
                // Progress bar
                $bar = $this->output->createProgressBar($dummyUsers->count());
                $bar->start();

                foreach ($dummyUsers as $user) {
                    // Cascade delete otomatis menghapus Siswa dan Nilai terkait
                    $user->delete();
                    $bar->advance();
                }

                $bar->finish();
            });
        } catch (\Exception $e) {
            $this->newLine(2);
            $this->error('  ✗ Terjadi error saat menghapus: ' . $e->getMessage());
            $this->error('  Semua perubahan dibatalkan (rollback transaksi).');
            $this->newLine();
            return 1;
        }

        $this->newLine(2);
        $this->line('╔══════════════════════════════════════════════╗');
        $this->info("║  ✅ Berhasil! {$jumlah} data dummy telah dihapus.");
        $this->line('╚══════════════════════════════════════════════╝');
        $this->newLine();

        return 0;
    }
}
