<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Dashboard') — SIMPEL</title>
    <meta name="description" content="SIMPEL - Sistem Informasi Manajemen Pelajar. Kelola nilai siswa secara digital, cepat, dan terstruktur.">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts: Inter + Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* =========================================================
         * SIMPEL — Design System
         * Custom CSS that overrides Bootstrap defaults
         * Author: SIMPEL Development
         * ========================================================= */

        :root {
            /* Core Palette */
            --clr-primary:       #6366f1;   /* Indigo */
            --clr-primary-dark:  #4f46e5;
            --clr-primary-light: #818cf8;
            --clr-accent:        #06b6d4;   /* Cyan accent */
            --clr-success:       #10b981;
            --clr-danger:        #ef4444;
            --clr-warning:       #f59e0b;
            --clr-info:          #3b82f6;

            /* Sidebar */
            --sidebar-w:         260px;
            --sidebar-bg:        #0d0f1a;
            --sidebar-border:    rgba(255,255,255,0.06);
            --sidebar-text:      #8b92a9;
            --sidebar-hover-bg:  rgba(99,102,241,0.12);
            --sidebar-active-bg: rgba(99,102,241,0.18);
            --sidebar-active-cl: #a5b4fc;

            /* Surface */
            --bg-body:           #f0f2f8;
            --bg-card:           #ffffff;
            --bg-topbar:         rgba(255,255,255,0.85);

            /* Text */
            --text-primary:      #111827;
            --text-secondary:    #6b7280;
            --text-muted:        #9ca3af;

            /* Borders & Shadows */
            --border-color:      #e5e7eb;
            --shadow-sm:         0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:         0 4px 16px rgba(0,0,0,0.08);
            --shadow-lg:         0 10px 40px rgba(0,0,0,0.12);
            --radius-md:         12px;
            --radius-lg:         16px;
            --radius-xl:         20px;

            /* Transition */
            --transition:        all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            font-size: 0.9rem;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* =========================================================
         * SIDEBAR
         * ========================================================= */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-right: 1px solid var(--sidebar-border);
            transition: var(--transition);
        }

        /* Subtle animated gradient orb behind sidebar */
        .sidebar::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
            top: -60px; left: -80px;
            border-radius: 50%;
            pointer-events: none;
        }
        .sidebar::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(6,182,212,0.08) 0%, transparent 70%);
            bottom: 80px; right: -60px;
            border-radius: 50%;
            pointer-events: none;
        }

        /* --- Brand --- */
        .sidebar-brand {
            padding: 1.4rem 1.5rem 1.2rem;
            border-bottom: 1px solid var(--sidebar-border);
            position: relative; z-index: 1;
            flex-shrink: 0;
        }
        .brand-logo {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--clr-primary), var(--clr-accent));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff;
            box-shadow: 0 4px 12px rgba(99,102,241,0.4);
            flex-shrink: 0;
        }
        .brand-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            color: #fff;
            letter-spacing: -0.02em;
            line-height: 1;
        }
        .brand-sub {
            font-size: 0.65rem;
            color: var(--sidebar-text);
            font-weight: 400;
            letter-spacing: 0.02em;
        }

        /* --- Nav --- */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0 0.5rem;
            overflow-y: auto;
            position: relative; z-index: 1;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }

        .nav-section-label {
            padding: 0.8rem 1.5rem 0.3rem;
            font-size: 0.6rem;
            font-weight: 700;
            color: rgba(139,146,169,0.5);
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.855rem;
            font-weight: 500;
            border-radius: 0;
            transition: var(--transition);
            position: relative;
            margin: 0.05rem 0.75rem;
            border-radius: 10px;
        }
        .sidebar-link .link-icon {
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px;
            font-size: 0.95rem;
            background: rgba(255,255,255,0.04);
            transition: var(--transition);
            flex-shrink: 0;
        }
        .sidebar-link:hover {
            color: #e0e4f5;
            background: var(--sidebar-hover-bg);
        }
        .sidebar-link:hover .link-icon {
            background: rgba(99,102,241,0.2);
            color: var(--clr-primary-light);
        }
        .sidebar-link.active {
            color: #c7d2fe;
            background: var(--sidebar-active-bg);
            font-weight: 600;
        }
        .sidebar-link.active .link-icon {
            background: linear-gradient(135deg, rgba(99,102,241,0.3), rgba(6,182,212,0.2));
            color: #a5b4fc;
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 60%;
            background: linear-gradient(180deg, var(--clr-primary), var(--clr-accent));
            border-radius: 0 4px 4px 0;
            margin-left: -0.75rem;
        }

        /* --- Sidebar Footer --- */
        .sidebar-footer {
            padding: 1rem 1rem 1.2rem;
            border-top: 1px solid var(--sidebar-border);
            position: relative; z-index: 1;
            flex-shrink: 0;
        }
        .user-card {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.6rem 0.75rem;
            background: rgba(255,255,255,0.04);
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 0.65rem;
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--clr-primary), var(--clr-accent));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .user-name {
            font-size: 0.8rem; font-weight: 600; color: #e0e4f5;
            line-height: 1.2; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
        }
        .user-role {
            font-size: 0.65rem; color: var(--sidebar-text);
        }
        .btn-logout {
            width: 100%;
            padding: 0.5rem 1rem;
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.15);
            color: #fca5a5;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-logout:hover {
            background: rgba(239,68,68,0.2);
            border-color: rgba(239,68,68,0.3);
            color: #fca5a5;
        }

        /* =========================================================
         * MAIN WRAPPER
         * ========================================================= */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
            transition: var(--transition);
        }

        /* --- Topbar --- */
        .topbar {
            position: sticky; top: 0; z-index: 900;
            padding: 0.85rem 1.75rem;
            background: var(--bg-topbar);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(229,231,235,0.8);
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }
        .topbar-meta {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 400;
        }
        .topbar-badge {
            display: flex; align-items: center; gap: 0.4rem;
            padding: 0.35rem 0.85rem;
            background: linear-gradient(135deg, rgba(99,102,241,0.08), rgba(6,182,212,0.08));
            border: 1px solid rgba(99,102,241,0.15);
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--clr-primary);
        }

        /* =========================================================
         * MAIN CONTENT
         * ========================================================= */
        .main-content {
            padding: 1.75rem;
            flex: 1;
        }

        /* =========================================================
         * COMPONENTS
         * ========================================================= */

        /* --- Page Header --- */
        .page-header {
            margin-bottom: 1.5rem;
        }
        .page-header-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            margin-bottom: 0.2rem;
        }
        .page-header-sub {
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        /* --- Cards --- */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-header-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex; align-items: center; gap: 0.5rem;
        }
        .card-body { padding: 1.25rem; }

        /* --- Stat Cards --- */
        .stat-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 1.25rem 1.4rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 50%;
            opacity: 0.06;
            transform: translate(25px, -25px);
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: rgba(99,102,241,0.2);
        }
        .stat-icon-wrap {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 0.85rem;
        }
        .stat-value {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 0.2rem;
        }
        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Stat color variants */
        .stat-indigo  .stat-icon-wrap { background: rgba(99,102,241,0.1); color: var(--clr-primary); }
        .stat-cyan    .stat-icon-wrap { background: rgba(6,182,212,0.1); color: var(--clr-accent); }
        .stat-emerald .stat-icon-wrap { background: rgba(16,185,129,0.1); color: var(--clr-success); }
        .stat-amber   .stat-icon-wrap { background: rgba(245,158,11,0.1); color: var(--clr-warning); }
        .stat-rose    .stat-icon-wrap { background: rgba(239,68,68,0.1); color: var(--clr-danger); }

        .stat-indigo  .stat-value { color: var(--clr-primary-dark); }
        .stat-cyan    .stat-value { color: #0891b2; }
        .stat-emerald .stat-value { color: #059669; }
        .stat-amber   .stat-value { color: #d97706; }
        .stat-rose    .stat-value { color: var(--clr-danger); }

        /* --- Tables --- */
        .table {
            font-size: 0.855rem;
        }
        .table thead th {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border-color);
            padding: 0.75rem 1rem;
            background: #f9fafb;
        }
        .table tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: #fafbff; }

        /* --- Badges --- */
        .badge-status {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.3em 0.8em;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.03em;
        }
        .badge-lulus {
            background: rgba(16,185,129,0.1);
            color: #065f46;
            border: 1px solid rgba(16,185,129,0.2);
        }
        .badge-tidak-lulus {
            background: rgba(239,68,68,0.08);
            color: #991b1b;
            border: 1px solid rgba(239,68,68,0.15);
        }
        .badge-grade {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 800;
        }
        .grade-a { background: rgba(16,185,129,0.12); color: #065f46; }
        .grade-b { background: rgba(59,130,246,0.12); color: #1e40af; }
        .grade-c { background: rgba(245,158,11,0.12); color: #92400e; }
        .grade-d { background: rgba(249,115,22,0.12); color: #9a3412; }
        .grade-e { background: rgba(239,68,68,0.1); color: #991b1b; }

        .badge-role-admin   { background: rgba(99,102,241,0.1); color: #4338ca; border: 1px solid rgba(99,102,241,0.15); }
        .badge-role-guru    { background: rgba(6,182,212,0.1); color: #0e7490; border: 1px solid rgba(6,182,212,0.15); }
        .badge-role-siswa   { background: rgba(16,185,129,0.1); color: #065f46; border: 1px solid rgba(16,185,129,0.15); }

        /* --- Buttons --- */
        .btn {
            font-weight: 500;
            font-size: 0.845rem;
            border-radius: 9px;
            transition: var(--transition);
            display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-dark));
            border: none;
            color: #fff;
            box-shadow: 0 2px 8px rgba(99,102,241,0.3);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(99,102,241,0.4);
            background: linear-gradient(135deg, #818cf8, var(--clr-primary));
            color: #fff;
        }
        .btn-outline-primary {
            border-color: rgba(99,102,241,0.3);
            color: var(--clr-primary);
        }
        .btn-outline-primary:hover {
            background: rgba(99,102,241,0.08);
            border-color: var(--clr-primary);
            color: var(--clr-primary-dark);
        }
        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            color: #fff;
        }
        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            color: #fff;
        }
        .btn-secondary {
            background: #f3f4f6;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
        }
        .btn-secondary:hover { background: #e5e7eb; color: var(--text-primary); }

        /* --- Forms --- */
        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.4rem;
        }
        .form-control, .form-select {
            border: 1.5px solid #e5e7eb;
            border-radius: 9px;
            padding: 0.6rem 0.9rem;
            font-size: 0.875rem;
            color: var(--text-primary);
            background: #fff;
            transition: var(--transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
            outline: none;
        }
        .form-control.is-invalid {
            border-color: var(--clr-danger);
        }

        /* --- Alerts --- */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            font-size: 0.855rem;
            font-weight: 500;
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.85rem 1rem;
        }
        .alert-success {
            background: rgba(16,185,129,0.08);
            color: #065f46;
            border-left: 4px solid var(--clr-success);
        }
        .alert-danger {
            background: rgba(239,68,68,0.06);
            color: #991b1b;
            border-left: 4px solid var(--clr-danger);
        }
        .alert-warning {
            background: rgba(245,158,11,0.08);
            color: #92400e;
            border-left: 4px solid var(--clr-warning);
        }
        .alert-info {
            background: rgba(59,130,246,0.06);
            color: #1e40af;
            border-left: 4px solid var(--clr-info);
        }

        /* --- Pagination --- */
        .pagination {
            gap: 0.25rem;
        }
        .page-link {
            border-radius: 8px !important;
            border-color: var(--border-color);
            color: var(--text-secondary);
            font-size: 0.82rem;
            font-weight: 500;
            padding: 0.45rem 0.8rem;
            transition: var(--transition);
        }
        .page-link:hover { background: rgba(99,102,241,0.08); color: var(--clr-primary); border-color: rgba(99,102,241,0.2); }
        .page-item.active .page-link { background: var(--clr-primary); border-color: var(--clr-primary); }

        /* --- Search Input --- */
        .search-wrap { position: relative; }
        .search-wrap .search-icon {
            position: absolute; left: 0.85rem; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted); font-size: 0.9rem;
            pointer-events: none;
        }
        .search-wrap .form-control { padding-left: 2.4rem; }

        /* --- Avatar Initials --- */
        .avatar-initials {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; font-weight: 800;
            flex-shrink: 0;
            letter-spacing: 0.05em;
        }

        /* --- Progress Bar --- */
        .progress {
            background: #f1f5f9;
            border-radius: 20px;
            overflow: hidden;
        }
        .progress-bar {
            border-radius: 20px;
            transition: width 0.8s ease;
        }

        /* --- Section Divider --- */
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-color), transparent);
            margin: 1.5rem 0;
        }

        /* =========================================================
         * MOBILE / RESPONSIVE
         * ========================================================= */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }
        .btn-menu-toggle {
            display: none;
            background: none; border: none; padding: 0.4rem;
            color: var(--text-secondary); font-size: 1.2rem; cursor: pointer;
        }

        @media (max-width: 991.98px) {
            .main-wrapper { margin-left: 0; }
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.is-open {
                transform: translateX(0);
                box-shadow: var(--shadow-lg);
            }
            .sidebar-overlay.is-open { display: block; }
            .btn-menu-toggle { display: flex; align-items: center; }
        }

        /* =========================================================
         * UTILITY
         * ========================================================= */
        .text-gradient {
            background: linear-gradient(135deg, var(--clr-primary), var(--clr-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .fw-800 { font-weight: 800; }
        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>

    @stack('styles')
</head>
<body>

{{-- Overlay for mobile sidebar --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ============================================================
     SIDEBAR
     ============================================================ --}}
<div class="sidebar" id="mainSidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div class="brand-logo">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
                <div class="brand-name">SIMPEL</div>
                <div class="brand-sub">Manajemen Pelajar</div>
            </div>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <nav class="sidebar-nav">
        @yield('sidebar-menu')
    </nav>

    {{-- Footer: User info + Logout --}}
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="overflow-hidden">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                Keluar dari Sistem
            </button>
        </form>
    </div>
</div>

{{-- ============================================================
     MAIN WRAPPER
     ============================================================ --}}
<div class="main-wrapper">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn-menu-toggle" onclick="toggleSidebar()" id="menuToggleBtn" aria-label="Toggle Menu">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-meta">@yield('page-breadcrumb', 'SIMPEL · Sistem Informasi Manajemen Pelajar')</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="topbar-badge">
                <i class="bi bi-calendar3"></i>
                {{ \Carbon\Carbon::now()->isoFormat('D MMM YYYY') }}
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success mb-4" role="alert">
                <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // --- Sidebar Toggle (Mobile) ---
    function toggleSidebar() {
        const sidebar = document.getElementById('mainSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('is-open');
        overlay.classList.toggle('is-open');
    }
    function closeSidebar() {
        document.getElementById('mainSidebar').classList.remove('is-open');
        document.getElementById('sidebarOverlay').classList.remove('is-open');
    }

    // --- Auto-hide alerts after 5s ---
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => el.remove(), 400);
        }, 5000);
    });
</script>

@stack('scripts')
</body>
</html>
