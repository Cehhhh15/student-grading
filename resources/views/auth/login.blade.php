<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIMPEL</title>
    <meta name="description" content="Masuk ke SIMPEL — Sistem Informasi Manajemen Pelajar untuk mengelola data nilai siswa.">
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
            min-height: 100vh;
            margin: 0;
            display: flex;
            background: #0a0d1a;
            overflow: hidden;
        }

        /* ── Animated Background ── */
        .bg-scene {
            position: fixed; inset: 0; z-index: 0;
            background: linear-gradient(135deg, #0a0d1a 0%, #111729 50%, #0d0f20 100%);
        }
        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: floatOrb 8s ease-in-out infinite;
        }
        .orb1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(99,102,241,0.25) 0%, transparent 70%);
            top: -150px; left: -150px;
            animation-delay: 0s;
        }
        .orb2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(6,182,212,0.18) 0%, transparent 70%);
            bottom: -100px; right: -100px;
            animation-delay: -4s;
        }
        .orb3 {
            width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(139,92,246,0.15) 0%, transparent 70%);
            top: 40%; left: 30%;
            animation-delay: -2s;
        }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(20px, -30px) scale(1.05); }
            66%       { transform: translate(-15px, 20px) scale(0.95); }
        }

        /* Grid overlay */
        .bg-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(99,102,241,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,0.04) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* ── Split Layout ── */
        .login-container {
            position: relative; z-index: 1;
            display: flex;
            width: 100%; min-height: 100vh;
        }

        /* Left — Branding Panel */
        .brand-panel {
            flex: 1;
            display: flex; flex-direction: column;
            justify-content: center;
            padding: 3rem 3.5rem;
            position: relative;
        }
        .brand-logo-box {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, var(--clr-primary), var(--clr-accent));
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem; color: #fff;
            margin-bottom: 2rem;
            box-shadow: 0 8px 24px rgba(99,102,241,0.4);
        }
        .brand-headline {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.04em;
            line-height: 1.1;
            margin-bottom: 1rem;
        }
        .brand-headline span {
            background: linear-gradient(135deg, #a5b4fc, #67e8f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .brand-desc {
            color: #8b92a9;
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 380px;
            margin-bottom: 2.5rem;
        }

        /* Feature chips */
        .feature-chips {
            display: flex; flex-wrap: wrap; gap: 0.6rem;
        }
        .chip {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.4rem 0.9rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            font-size: 0.78rem;
            color: #94a3b8;
            transition: all 0.2s;
        }
        .chip i { color: var(--clr-primary-light, #818cf8); }

        /* Floating stats cards */
        .stats-float {
            display: flex; gap: 0.75rem; margin-top: 2.5rem;
        }
        .stat-float-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 0.8rem 1.1rem;
            flex: 1;
        }
        .stat-float-num {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.4rem; font-weight: 800;
            background: linear-gradient(135deg, #a5b4fc, #67e8f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .stat-float-lbl { font-size: 0.7rem; color: #64748b; font-weight: 500; }

        /* ── Right — Form Panel ── */
        .form-panel {
            width: 440px;
            min-height: 100vh;
            display: flex; flex-direction: column;
            justify-content: center;
            padding: 2.5rem;
            background: rgba(255,255,255,0.02);
            border-left: 1px solid rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            position: relative;
        }

        .login-card {
            background: rgba(255,255,255,0.97);
            border-radius: 20px;
            padding: 2.25rem;
            box-shadow: 0 30px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.06);
        }

        .login-card-header {
            text-align: center;
            margin-bottom: 1.75rem;
        }
        .login-logo-sm {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, var(--clr-primary), var(--clr-accent));
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: #fff;
            margin: 0 auto 0.85rem;
            box-shadow: 0 6px 18px rgba(99,102,241,0.35);
        }
        .login-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.02em;
            margin-bottom: 0.2rem;
        }
        .login-subtitle {
            font-size: 0.8rem;
            color: #9ca3af;
        }

        .form-label-custom {
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.4rem;
        }
        .input-group-custom { position: relative; }
        .input-icon {
            position: absolute; left: 0.9rem; top: 50%;
            transform: translateY(-50%);
            color: #9ca3af; font-size: 0.9rem; pointer-events: none;
        }
        .input-field {
            width: 100%;
            padding: 0.7rem 1rem 0.7rem 2.5rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.875rem;
            color: #111827;
            background: #f9fafb;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .input-field:focus {
            outline: none;
            border-color: var(--clr-primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
        }
        .input-field.is-invalid { border-color: #ef4444; }

        .btn-signin {
            width: 100%;
            padding: 0.78rem;
            background: linear-gradient(135deg, var(--clr-primary), #4f46e5);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.22s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.01em;
            box-shadow: 0 4px 14px rgba(99,102,241,0.4);
        }
        .btn-signin:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(99,102,241,0.5);
            background: linear-gradient(135deg, #818cf8, var(--clr-primary));
        }
        .btn-signin:active { transform: translateY(0); }

        /* Demo accounts */
        .demo-box {
            margin-top: 1.25rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.85rem 1rem;
        }
        .demo-box-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.6rem;
            text-align: center;
        }
        .demo-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.3rem 0;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            border-radius: 6px;
            padding: 0.35rem 0.5rem;
            transition: background 0.15s;
        }
        .demo-row:last-child { border-bottom: none; }
        .demo-row:hover { background: #f0f4ff; }
        .demo-role {
            font-size: 0.75rem; font-weight: 600; color: #475569;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .demo-email { font-size: 0.72rem; color: #94a3b8; font-family: monospace; }
        .demo-badge {
            font-size: 0.62rem; font-weight: 700; padding: 0.15em 0.5em;
            border-radius: 4px; text-transform: uppercase;
        }

        /* Error alert */
        .login-alert {
            background: rgba(239,68,68,0.06);
            border: 1px solid rgba(239,68,68,0.15);
            border-left: 3px solid #ef4444;
            border-radius: 8px;
            padding: 0.65rem 0.85rem;
            margin-bottom: 1rem;
            font-size: 0.82rem;
            color: #991b1b;
            display: flex; align-items: center; gap: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .brand-panel { display: none; }
            .form-panel {
                width: 100%; border-left: none;
                background: transparent;
                padding: 1.5rem;
            }
            body { background: #0a0d1a; }
        }
    </style>
</head>
<body>

<div class="bg-scene">
    <div class="bg-orb orb1"></div>
    <div class="bg-orb orb2"></div>
    <div class="bg-orb orb3"></div>
    <div class="bg-grid"></div>
</div>

<div class="login-container">

    {{-- ── Left: Branding ── --}}
    <div class="brand-panel">
        <div>
            <div class="brand-logo-box">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="brand-headline">
                Kelola Nilai<br>
                Siswa Lebih<br>
                <span>Cerdas.</span>
            </div>
            <p class="brand-desc">
                SIMPEL menghadirkan platform manajemen penilaian akademik yang
                terintegrasi untuk Admin, Guru, dan Siswa dalam satu sistem yang
                mudah digunakan.
            </p>

            <div class="feature-chips">
                <div class="chip"><i class="bi bi-shield-check"></i> Role-Based Access</div>
                <div class="chip"><i class="bi bi-calculator"></i> Kalkulasi Otomatis</div>
                <div class="chip"><i class="bi bi-file-earmark-pdf"></i> Ekspor PDF</div>
                <div class="chip"><i class="bi bi-graph-up"></i> Laporan Real-time</div>
            </div>

            <div class="stats-float">
                <div class="stat-float-card">
                    <div class="stat-float-num">3</div>
                    <div class="stat-float-lbl">Role Pengguna</div>
                </div>
                <div class="stat-float-card">
                    <div class="stat-float-num">100%</div>
                    <div class="stat-float-lbl">Otomatis</div>
                </div>
                <div class="stat-float-card">
                    <div class="stat-float-num">A–E</div>
                    <div class="stat-float-lbl">Grade System</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Right: Form ── --}}
    <div class="form-panel">
        <div class="login-card">
            <div class="login-card-header">
                <div class="login-logo-sm">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="login-title">Masuk ke SIMPEL SMA Grogu</div>
                <div class="login-subtitle">Sistem Informasi Manajemen Pelajar</div>
            </div>

            {{-- Error --}}
            @if($errors->any())
                <div class="login-alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ $errors->first() }}
                </div>
            @endif
            @if(session('success'))
                <div style="background:rgba(16,185,129,0.06);border:1px solid rgba(16,185,129,0.15);border-left:3px solid #10b981;border-radius:8px;padding:0.65rem 0.85rem;margin-bottom:1rem;font-size:0.82rem;color:#065f46;display:flex;align-items:center;gap:0.5rem;">
                    <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf

                <div class="mb-3">
                    <label class="form-label-custom" for="email">Email</label>
                    <div class="input-group-custom">
                        <i class="bi bi-envelope input-icon"></i>
                        <input
                            type="email" name="email" id="email"
                            class="input-field {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            placeholder="nama@simpel.com"
                            value="{{ old('email') }}"
                            required autocomplete="email" autofocus
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label-custom" for="password">Password</label>
                    <div class="input-group-custom">
                        <i class="bi bi-lock input-icon"></i>
                        <input
                            type="password" name="password" id="password"
                            class="input-field {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="••••••••"
                            required autocomplete="current-password"
                        >
                    </div>
                </div>

                <button type="submit" class="btn-signin" id="btnSignin">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Masuk ke Sistem
                </button>
            </form>

            {{-- Demo Accounts --}}
            <div class="demo-box">
                <div class="demo-box-title">✦ Akun Demo — klik untuk isi otomatis</div>

                <div class="demo-row" onclick="fillDemo('admin@SIMPEL.com')">
                    <span class="demo-role">
                        <span class="demo-badge" style="background:rgba(99,102,241,0.1);color:#4338ca;">Admin</span>
                        Administrator
                    </span>
                    <span class="demo-email">admin@SIMPEL.com</span>
                </div>

                <div class="demo-row" onclick="fillDemo('guru1@SIMPEL.com')">
                    <span class="demo-role">
                        <span class="demo-badge" style="background:rgba(6,182,212,0.1);color:#0e7490;"> Gantar Guru</span>
                        Guru
                    </span>
                    <span class="demo-email">guru11@SIMPEL.com</span>
                </div>

                <div class="demo-row" onclick="fillDemo('siswa1@SIMPEL.com')">
                    <span class="demo-role">
                        <span class="demo-badge" style="background:rgba(16,185,129,0.1);color:#047857;">Andi Siswa</span>
                        Siswa
                    </span>
                    <span class="demo-email">siswa1@SIMPEL.com</span>
                </div>

                <div style="text-align:center;margin-top:0.5rem;font-size:0.69rem;color:#cbd5e1;">
                    Password: <code style="color:#6366f1;font-size:0.72rem;">password123</code>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function fillDemo(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password123';
        document.getElementById('email').focus();
        // brief visual feedback
        const btn = document.getElementById('btnSignin');
        btn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        setTimeout(() => {
            btn.style.background = '';
        }, 600);
    }

    // Loading state on submit
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSignin');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Memproses...';
        btn.disabled = true;
    });
</script>
</body>
</html>
