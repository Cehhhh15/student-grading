<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPEL — Sistem Informasi Manajemen Pelajar</title>
    <meta name="description" content="SIMPEL: Platform manajemen nilai siswa yang modern, cepat, dan terintegrasi untuk institusi pendidikan Indonesia.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --clr-primary: #6366f1;
            --clr-accent:  #06b6d4;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0d1a;
            color: #e2e8f0;
            margin: 0;
            overflow-x: hidden;
        }

        /* ── Background ── */
        .bg-scene {
            position: fixed; inset: 0; z-index: 0;
            background: linear-gradient(135deg, #0a0d1a 0%, #0f1629 60%, #0a0d1a 100%);
        }
        .bg-orb {
            position: absolute; border-radius: 50%;
            filter: blur(100px); pointer-events: none;
        }
        .orb1 { width: 600px; height: 600px; background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%); top:-200px; left:-200px; animation: orbFloat 10s ease-in-out infinite; }
        .orb2 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(6,182,212,0.12) 0%, transparent 70%); bottom:-150px; right:-100px; animation: orbFloat 12s ease-in-out infinite reverse; }
        .orb3 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(139,92,246,0.1) 0%, transparent 70%); top:50%; left:45%; animation: orbFloat 8s ease-in-out infinite; }
        @keyframes orbFloat {
            0%,100% { transform: translate(0,0); }
            50% { transform: translate(30px,-40px); }
        }
        .bg-dots {
            position: absolute; inset: 0;
            background-image: radial-gradient(rgba(99,102,241,0.08) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        /* ── Navbar ── */
        .navbar-custom {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 1rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            backdrop-filter: blur(20px);
            background: rgba(10,13,26,0.7);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .nav-brand {
            display: flex; align-items: center; gap: 0.65rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800; font-size: 1.1rem; color: #fff;
            text-decoration: none; letter-spacing: -0.01em;
        }
        .nav-logo {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--clr-primary), var(--clr-accent));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem; color: #fff;
        }
        .btn-nav-login {
            padding: 0.45rem 1.25rem;
            background: rgba(99,102,241,0.15);
            border: 1px solid rgba(99,102,241,0.3);
            color: #a5b4fc;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-nav-login:hover {
            background: rgba(99,102,241,0.25);
            color: #c7d2fe;
        }

        /* ── Hero Section ── */
        .hero {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            text-align: center;
            padding: 8rem 2rem 5rem;
        }

        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.35rem 1rem;
            background: rgba(99,102,241,0.1);
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #a5b4fc;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
        }
        .hero-eyebrow::before {
            content: '';
            width: 6px; height: 6px;
            background: #a5b4fc;
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        .hero-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1.05;
            color: #fff;
            margin-bottom: 1.25rem;
            max-width: 750px;
        }
        .hero-title .gradient-text {
            background: linear-gradient(135deg, #a5b4fc 0%, #67e8f9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 1.05rem;
            color: #94a3b8;
            line-height: 1.7;
            max-width: 520px;
            margin-bottom: 2.5rem;
        }

        .hero-cta {
            display: flex; gap: 0.85rem; flex-wrap: wrap;
            justify-content: center; margin-bottom: 4rem;
        }
        .btn-cta-primary {
            padding: 0.85rem 2rem;
            background: linear-gradient(135deg, var(--clr-primary), #4f46e5);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(99,102,241,0.4);
            transition: all 0.22s;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .btn-cta-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(99,102,241,0.5);
            color: #fff;
        }
        .btn-cta-secondary {
            padding: 0.85rem 1.75rem;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px;
            color: #cbd5e1;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.22s;
            display: flex; align-items: center; gap: 0.5rem;
            backdrop-filter: blur(10px);
        }
        .btn-cta-secondary:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        /* ── Scroll indicator ── */
        .scroll-indicator {
            display: flex; flex-direction: column; align-items: center; gap: 0.4rem;
            color: rgba(255,255,255,0.2);
            font-size: 0.7rem; font-weight: 500;
            letter-spacing: 0.1em; text-transform: uppercase;
        }
        .scroll-line {
            width: 1px; height: 40px;
            background: linear-gradient(180deg, rgba(99,102,241,0.6), transparent);
            animation: scrollAnim 2s ease-in-out infinite;
        }
        @keyframes scrollAnim {
            0% { transform: scaleY(0); transform-origin: top; }
            50% { transform: scaleY(1); transform-origin: top; }
            51% { transform: scaleY(1); transform-origin: bottom; }
            100% { transform: scaleY(0); transform-origin: bottom; }
        }

        /* ── Features Section ── */
        .section {
            position: relative; z-index: 1;
            padding: 5rem 1.5rem;
        }
        .section-label {
            display: inline-flex; align-items: center; gap: 0.5rem;
            font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.12em; color: #6366f1;
            margin-bottom: 0.75rem;
        }
        .section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(1.6rem, 3vw, 2.5rem);
            font-weight: 800; letter-spacing: -0.03em;
            color: #fff; margin-bottom: 1rem;
        }
        .section-desc {
            color: #64748b; font-size: 0.95rem; max-width: 480px;
        }

        /* Role cards */
        .role-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; }
        .role-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            padding: 1.75rem;
            transition: all 0.25s;
            position: relative; overflow: hidden;
        }
        .role-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
            border-radius: 16px 16px 0 0;
        }
        .role-card.admin::before { background: linear-gradient(90deg, #6366f1, #818cf8); }
        .role-card.guru::before  { background: linear-gradient(90deg, #06b6d4, #67e8f9); }
        .role-card.siswa::before { background: linear-gradient(90deg, #10b981, #6ee7b7); }
        .role-card:hover {
            background: rgba(255,255,255,0.05);
            transform: translateY(-4px);
            border-color: rgba(255,255,255,0.12);
        }
        .role-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin-bottom: 1rem;
        }
        .role-card.admin .role-icon { background: rgba(99,102,241,0.15); color: #818cf8; }
        .role-card.guru  .role-icon { background: rgba(6,182,212,0.15); color: #67e8f9; }
        .role-card.siswa .role-icon { background: rgba(16,185,129,0.15); color: #6ee7b7; }
        .role-name { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1rem; color: #e2e8f0; margin-bottom: 0.4rem; }
        .role-desc { font-size: 0.82rem; color: #64748b; line-height: 1.6; margin-bottom: 0.85rem; }
        .role-features { list-style: none; padding: 0; margin: 0; }
        .role-features li { font-size: 0.78rem; color: #94a3b8; padding: 0.2rem 0; display: flex; align-items: center; gap: 0.5rem; }
        .role-features li::before { content: '✓'; color: #6366f1; font-weight: 700; font-size: 0.7rem; }

        /* Stats strip */
        .stats-strip {
            background: rgba(255,255,255,0.03);
            border-top: 1px solid rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 2.5rem 1.5rem;
        }
        .stats-strip-inner {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(150px,1fr));
            gap: 2rem; max-width: 900px; margin: 0 auto; text-align: center;
        }
        .strip-num {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2.2rem; font-weight: 900;
            background: linear-gradient(135deg, #a5b4fc, #67e8f9);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .strip-lbl { font-size: 0.78rem; color: #64748b; font-weight: 500; }

        /* ── Footer ── */
        .footer {
            position: relative; z-index: 1;
            text-align: center;
            padding: 2.5rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            color: #334155;
            font-size: 0.8rem;
        }
        .footer a { color: #6366f1; text-decoration: none; }
    </style>
</head>
<body>

<div class="bg-scene">
    <div class="bg-orb orb1"></div>
    <div class="bg-orb orb2"></div>
    <div class="bg-orb orb3"></div>
    <div class="bg-dots"></div>
</div>

{{-- Navbar --}}
<nav class="navbar-custom">
    <a href="/" class="nav-brand">
        <div class="nav-logo"><i class="bi bi-mortarboard-fill"></i></div>
        SIMPEL
    </a>
    @auth
        <a href="{{ url('/dashboard') }}" class="btn-nav-login">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
    @else
        <a href="{{ route('login') }}" class="btn-nav-login">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
        </a>
    @endauth
</nav>

{{-- Hero --}}
<section class="hero">
    <div class="hero-eyebrow">Sistem Informasi Manajemen Pelajar</div>

    <h1 class="hero-title">
        Manajemen Nilai Siswa<br>
        yang <span class="gradient-text">Modern</span> &amp; Efisien
    </h1>

    <p class="hero-desc">
        SIMPEL menghadirkan platform terpadu untuk mengelola data siswa,
        input nilai, perhitungan otomatis, dan laporan akademik — cepat,
        akurat, dan mudah digunakan.
    </p>

    <div class="hero-cta">
        <a href="{{ route('login') }}" class="btn-cta-primary">
            <i class="bi bi-rocket-takeoff-fill"></i> Mulai Sekarang
        </a>
        <a href="#fitur" class="btn-cta-secondary">
            <i class="bi bi-info-circle"></i> Pelajari Fitur
        </a>
    </div>

    <div class="scroll-indicator">
        <div class="scroll-line"></div>
        <span>Scroll</span>
    </div>
</section>

{{-- Stats Strip --}}
<div class="stats-strip">
    <div class="stats-strip-inner">
        <div>
            <div class="strip-num">3</div>
            <div class="strip-lbl">Peran Pengguna</div>
        </div>
        <div>
            <div class="strip-num">100%</div>
            <div class="strip-lbl">Kalkulasi Otomatis</div>
        </div>
        <div>
            <div class="strip-num">A–E</div>
            <div class="strip-lbl">Sistem Grade</div>
        </div>
        <div>
            <div class="strip-num">KKM 70</div>
            <div class="strip-lbl">Standar Kelulusan</div>
        </div>
        <div>
            <div class="strip-num">PDF</div>
            <div class="strip-lbl">Ekspor Laporan</div>
        </div>
    </div>
</div>

{{-- Features Section --}}
<section class="section" id="fitur">
    <div style="max-width:1100px; margin: 0 auto;">
        <div class="text-center mb-5">
            <div class="section-label"><i class="bi bi-stars"></i> Fitur Utama</div>
            <h2 class="section-title">Satu Platform, Tiga Peran</h2>
            <p class="section-desc mx-auto">
                Setiap pengguna mendapatkan tampilan dan fitur yang disesuaikan
                dengan kebutuhan dan tanggung jawabnya.
            </p>
        </div>

        <div class="role-cards">

            {{-- Admin --}}
            <div class="role-card admin">
                <div class="role-icon"><i class="bi bi-shield-check-fill"></i></div>
                <div class="role-name">🛡️ Administrator</div>
                <div class="role-desc">Kendali penuh atas seluruh sistem — data siswa, guru, mata pelajaran, dan laporan.</div>
                <ul class="role-features">
                    <li>CRUD data siswa & guru</li>
                    <li>Manajemen mata pelajaran</li>
                    <li>Lihat laporan seluruh kelas</li>
                    <li>Ekspor laporan ke PDF</li>
                    <li>Statistik sistem real-time</li>
                </ul>
            </div>

            {{-- Guru --}}
            <div class="role-card guru">
                <div class="role-icon"><i class="bi bi-person-badge-fill"></i></div>
                <div class="role-name">👨‍🏫 Guru</div>
                <div class="role-desc">Fokus pada mata pelajaran yang diampu — input nilai, rekap, dan pantau perkembangan siswa.</div>
                <ul class="role-features">
                    <li>Input nilai Tugas, UTS, UAS</li>
                    <li>Kalkulasi otomatis nilai akhir</li>
                    <li>Rekap nilai per kelas</li>
                    <li>Preview status kelulusan</li>
                    <li>Filter berdasarkan kelas</li>
                </ul>
            </div>

            {{-- Siswa --}}
            <div class="role-card siswa">
                <div class="role-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <div class="role-name">🎓 Siswa</div>
                <div class="role-desc">Akses transparan nilai akademik pribadi dan status kelulusan secara mandiri.</div>
                <ul class="role-features">
                    <li>Lihat nilai per mata pelajaran</li>
                    <li>Nilai Tugas, UTS, UAS, & Akhir</li>
                    <li>Grade huruf (A–E)</li>
                    <li>Status LULUS / TIDAK LULUS</li>
                    <li>Rata-rata seluruh mapel</li>
                </ul>
            </div>

        </div>
    </div>
</section>

{{-- Formula Section --}}
<section class="section" style="padding-top:2rem; padding-bottom:5rem;">
    <div style="max-width:700px; margin: 0 auto; text-align:center;">
        <div class="section-label"><i class="bi bi-calculator"></i> Perhitungan Nilai</div>
        <h2 class="section-title">Rumus Nilai Akhir</h2>
        <div style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.15);border-radius:16px;padding:2rem;margin:2rem 0;">
            <div style="font-family:'Plus Jakarta Sans',sans-serif;font-size:1.1rem;font-weight:700;color:#c7d2fe;letter-spacing:-0.01em;">
                Nilai Akhir = (30% × Tugas) + (30% × UTS) + (40% × UAS)
            </div>
            <div style="margin-top:1rem;display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap;">
                <div style="text-align:center;">
                    <div style="font-size:1.5rem;font-weight:800;color:#818cf8;">30%</div>
                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Tugas</div>
                </div>
                <div style="font-size:1.5rem;color:#334155;align-self:center;">+</div>
                <div style="text-align:center;">
                    <div style="font-size:1.5rem;font-weight:800;color:#67e8f9;">30%</div>
                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">UTS</div>
                </div>
                <div style="font-size:1.5rem;color:#334155;align-self:center;">+</div>
                <div style="text-align:center;">
                    <div style="font-size:1.5rem;font-weight:800;color:#6ee7b7;">40%</div>
                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">UAS</div>
                </div>
                <div style="font-size:1.5rem;color:#334155;align-self:center;">→</div>
                <div style="text-align:center;">
                    <div style="font-size:1.5rem;font-weight:800;color:#fbbf24;">≥70</div>
                    <div style="font-size:0.72rem;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">LULUS</div>
                </div>
            </div>
        </div>
        <a href="{{ route('login') }}" class="btn-cta-primary" style="display:inline-flex;margin:0 auto;">
            <i class="bi bi-box-arrow-in-right"></i> Masuk ke SIMPEL
        </a>
    </div>
</section>

{{-- Footer --}}
<footer class="footer">
    <p>
        SIMPEL &copy; {{ date('Y') }} ·
        Sistem Informasi Manajemen Pelajar ·
        Dikembangkan oleh <strong style="color:#a5b4fc;">Christian Daniel Wijaya</strong>
    </p>
    <p style="margin-top:0.25rem;">PHP 8.3 + Laravel 13 + MySQL + Bootstrap 5</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
