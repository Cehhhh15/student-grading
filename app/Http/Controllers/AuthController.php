<?php

/**
 * ============================================================
 * FILE: AuthController.php
 * LOKASI: app/Http/Controllers/AuthController.php
 * ============================================================
 * Controller untuk menangani proses autentikasi pengguna.
 *
 * FUNGSI UTAMA:
 *   - Menampilkan form login
 *   - Memproses login dan redirect berdasarkan role
 *   - Menangani logout
 *
 * ALUR LOGIN BERDASARKAN ROLE:
 *   Admin  → /admin/dashboard
 *   Guru   → /guru/dashboard
 *   Siswa  → /siswa/dashboard
 *
 * PARADIGMA: OOP (Class Controller)
 * ============================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login.
     *
     * Jika pengguna sudah login, arahkan langsung ke dashboard
     * sesuai role-nya (tidak perlu login lagi).
     *
     * @return View|RedirectResponse
     */
    public function showLogin(): View|RedirectResponse
    {
        // Jika sudah login, redirect ke dashboard yang sesuai
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        // Tampilkan halaman login
        return view('auth.login');
    }

    /**
     * Memproses data login dari form.
     *
     * Langkah-langkah proses:
     * 1. Validasi input (email dan password wajib diisi)
     * 2. Coba autentikasi dengan Auth::attempt()
     * 3. Jika berhasil, buat session dan regenerate token CSRF
     * 4. Redirect ke dashboard sesuai role pengguna
     * 5. Jika gagal, kembalikan ke form login dengan pesan error
     *
     * @param  Request            $request  Data dari form login
     * @return RedirectResponse             Redirect ke dashboard atau kembali ke login
     */
    public function login(Request $request): RedirectResponse
    {
        // -------------------------------------------------------
        // LANGKAH 1: Validasi input form
        // Laravel akan otomatis mengembalikan error jika validasi gagal
        // -------------------------------------------------------
        $request->validate([
            'email'    => 'required|email',         // Email wajib diisi dan format harus valid
            'password' => 'required|min:6',          // Password wajib diisi, minimal 6 karakter
        ], [
            // Pesan error dalam Bahasa Indonesia
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        // -------------------------------------------------------
        // LANGKAH 2: Ambil data credentials dari request
        // -------------------------------------------------------
        $credentials = $request->only('email', 'password');

        // -------------------------------------------------------
        // LANGKAH 3: Coba autentikasi pengguna
        // Auth::attempt() akan mencocokkan email + password (bcrypt)
        // -------------------------------------------------------
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerate session ID untuk mencegah Session Fixation Attack
            $request->session()->regenerate();

            // Redirect ke dashboard sesuai role
            return $this->redirectByRole()
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        // -------------------------------------------------------
        // LANGKAH 4: Login gagal — kembalikan ke form dengan error
        // withInput() mengisi kembali field email (bukan password)
        // -------------------------------------------------------
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
    }

    /**
     * Menangani proses logout pengguna.
     *
     * Menghapus session dan mengarahkan ke halaman login.
     *
     * @param  Request            $request  HTTP Request
     * @return RedirectResponse             Redirect ke halaman login
     */
    public function logout(Request $request): RedirectResponse
    {
        // Logout dari sistem (hapus session autentikasi)
        Auth::logout();

        // Invalidate session untuk mencegah session reuse
        $request->session()->invalidate();

        // Regenerate CSRF token untuk keamanan
        $request->session()->regenerateToken();

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    /**
     * Redirect pengguna ke dashboard yang sesuai berdasarkan role-nya.
     *
     * Method ini dipanggil setelah login berhasil untuk menentukan
     * ke mana pengguna harus diarahkan.
     *
     * @return RedirectResponse  Redirect ke route dashboard yang sesuai
     */
    private function redirectByRole(): RedirectResponse
    {
        $user = Auth::user();

        // Tentukan route tujuan berdasarkan role
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru'  => redirect()->route('guru.dashboard'),
            'siswa' => redirect()->route('siswa.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
