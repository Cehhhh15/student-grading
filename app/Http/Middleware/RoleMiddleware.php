<?php

/**
 * ============================================================
 * FILE: RoleMiddleware.php
 * LOKASI: app/Http/Middleware/RoleMiddleware.php
 * ============================================================
 * Middleware untuk membatasi akses berdasarkan role pengguna.
 *
 * Middleware ini berjalan SEBELUM request masuk ke controller.
 * Jika pengguna tidak memiliki role yang sesuai, mereka akan
 * diarahkan kembali ke dashboard atau halaman login.
 *
 * CARA PENGGUNAAN DI ROUTES:
 *   Route::middleware(['auth', 'role:admin'])->group(...)
 *   Route::middleware(['auth', 'role:guru'])->group(...)
 *   Route::middleware(['auth', 'role:admin,guru'])->group(...)
 *
 * PARADIGMA: OOP (Class Middleware)
 * ============================================================
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Menangani request masuk dan memeriksa role pengguna.
     *
     * Method ini dipanggil setiap kali ada HTTP request ke route
     * yang dilindungi oleh middleware ini.
     *
     * Alur pemeriksaan:
     * 1. Cek apakah pengguna sudah login
     * 2. Ambil daftar role yang diizinkan dari parameter
     * 3. Cek apakah role pengguna ada dalam daftar yang diizinkan
     * 4. Jika tidak sesuai, redirect ke dashboard (403 Forbidden behavior)
     *
     * @param  Request  $request     Request HTTP yang masuk
     * @param  Closure  $next        Fungsi untuk melanjutkan request ke handler berikutnya
     * @param  string   ...$roles    Satu atau lebih role yang diizinkan akses
     * @return Response              Response HTTP
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Pastikan pengguna sudah login
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Ambil role pengguna yang sedang login
        $userRole = $request->user()->role;

        // Periksa apakah role pengguna ada dalam daftar role yang diizinkan
        if (! in_array($userRole, $roles)) {
            // Role tidak sesuai: redirect ke dashboard dengan pesan error
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        // Role sesuai: lanjutkan request ke controller
        return $next($request);
    }
}
