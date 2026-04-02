<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Body Repair Admin</title>
    <meta name="description" content="Sistem Informasi Administrasi Keuangan Jasa Body Repair Kendaraan">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #818cf8;
            --sidebar-width: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --header-height: 60px;
            --body-bg: #f1f5f9;
            --card-radius: 12px;
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            color: #1e293b;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1040;
            overflow-y: auto;
            transition: transform .3s ease;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .sidebar-brand .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-name {
            color: #fff;
            font-weight: 700;
            font-size: .9rem;
            line-height: 1.3;
        }
        .sidebar-brand .brand-sub { color: #94a3b8; font-size: .7rem; font-weight: 400; }

        .sidebar-nav { flex: 1; padding: 1rem 0; }
        .nav-section-title {
            color: #475569;
            font-size: .65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: .75rem 1.5rem .25rem;
        }
        .sidebar-nav .nav-item .nav-link {
            color: #94a3b8;
            padding: .6rem 1.5rem;
            font-size: .85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .75rem;
            border-radius: 0;
            transition: all .2s;
            text-decoration: none;
        }
        .sidebar-nav .nav-item .nav-link:hover,
        .sidebar-nav .nav-item .nav-link.active {
            color: #fff;
            background: var(--sidebar-hover);
        }
        .sidebar-nav .nav-item .nav-link.active {
            border-left: 3px solid var(--primary-light);
            color: var(--primary-light);
        }
        .sidebar-nav .nav-link i { font-size: 1rem; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: #94a3b8;
            font-size: .8rem;
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: .85rem;
            flex-shrink: 0;
        }
        .user-name { color: #e2e8f0; font-weight: 600; font-size: .82rem; }
        .user-role {
            font-size: .68rem;
            padding: .1rem .45rem;
            border-radius: 100px;
            font-weight: 600;
        }
        .role-admin { background: rgba(79,70,229,.25); color: #818cf8; }
        .role-owner { background: rgba(16,185,129,.25); color: #34d399; }

        /* ===== MAIN CONTENT ===== */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar .page-title { font-weight: 700; font-size: 1rem; color: #1e293b; }
        .topbar .breadcrumb { margin: 0; font-size: .78rem; }
        .topbar .breadcrumb-item.active { color: #64748b; }

        .content-area { flex: 1; padding: 1.5rem; }

        /* ===== CARDS ===== */
        .stat-card {
            background: #fff;
            border-radius: var(--card-radius);
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,.08); }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            margin-bottom: .75rem;
        }
        .stat-card .stat-value { font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1; }
        .stat-card .stat-label { font-size: .78rem; color: #64748b; margin-top: .25rem; }
        .stat-card .stat-trend { font-size: .75rem; font-weight: 600; }

        .card-custom {
            background: #fff;
            border-radius: var(--card-radius);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .card-custom .card-header-custom {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            font-size: .9rem;
        }

        /* ===== TABLE ===== */
        .table-custom { font-size: .83rem; }
        .table-custom thead th {
            background: #f8fafc;
            font-weight: 600;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            padding: .75rem 1rem;
        }
        .table-custom tbody td { padding: .75rem 1rem; vertical-align: middle; }
        .table-custom tbody tr:hover { background: #f8fafc; }

        /* ===== FORMS ===== */
        .form-label { font-size: .83rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
        .form-control, .form-select {
            font-size: .85rem;
            border-color: #e2e8f0;
            border-radius: 8px;
            padding: .5rem .75rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        /* ===== BADGES ===== */
        .badge { font-size: .7rem; font-weight: 600; padding: .3em .6em; border-radius: 6px; }

        /* ===== ALERTS ===== */
        .alert { border-radius: 10px; border: none; font-size: .85rem; }

        /* Toggle Sidebar Mobile */
        .sidebar-toggle { display: none; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggle { display: block; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-car-front-fill"></i></div>
        <div>
            <div class="brand-name">Body Repair Admin</div>
            <div class="brand-sub">Sistem Keuangan Bengkel</div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-section-title">Utama</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
        </ul>

        @if(auth()->user()->isAdmin())
        <div class="nav-section-title">Administrasi</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('pelanggan.index') }}" class="nav-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Data Pelanggan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('layanan.index') }}" class="nav-link {{ request()->routeIs('layanan.*') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i> Data Layanan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transaksi.index') }}" class="nav-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i> Transaksi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('transaksi.create') }}" class="nav-link {{ request()->routeIs('transaksi.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle-fill"></i> Input Transaksi
                </a>
            </li>
        </ul>
        @endif

        <div class="nav-section-title">Laporan</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i> Laporan Keuangan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('laporan.rekap') }}" class="nav-link {{ request()->routeIs('laporan.rekap') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Rekap Transaksi
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->nama }}</div>
                <span class="user-role {{ auth()->user()->role === 'admin' ? 'role-admin' : 'role-owner' }}">
                    {{ strtoupper(auth()->user()->role) }}
                </span>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="font-size:.78rem;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</nav>

{{-- MAIN WRAPPER --}}
<div class="main-wrapper">
    {{-- TOPBAR --}}
    <div class="topbar">
        <button class="btn btn-sm btn-light sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('show')">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none" style="color:#4f46e5">Home</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="badge bg-light text-dark border" style="font-size:.73rem;">
                <i class="bi bi-calendar3"></i> {{ now()->translatedFormat('d M Y') }}
            </span>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="content-area">
        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    /* ============================================================
     * JWT Security Guard
     * - Token diterima dari server via session flash (sekali pakai)
     * - Disimpan di sessionStorage → otomatis hilang saat tab/browser tutup
     * - Setiap AJAX dikirim dengan header Authorization: Bearer <token>
     * - Jika sessionStorage kosong (tab baru / window baru) → logout
     * ============================================================ */

    (function () {
        const TOKEN_KEY = 'jwt_token';

        // 1. Tangkap token baru dari server (hanya muncul tepat setelah login)
        @if(session('jwt_token'))
        sessionStorage.setItem(TOKEN_KEY, '{{ session('jwt_token') }}');
        @endif

        // 2. Validasi: jika tidak ada token di sessionStorage → redirect login
        const storedToken = sessionStorage.getItem(TOKEN_KEY);
        if (!storedToken) {
            // Paksa logout dan redirect ke login
            fetch('{{ route('logout') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Content-Type': 'application/json'
                }
            }).finally(() => {
                window.location.href = '{{ route('login') }}';
            });
        }

        // 3. Sertakan JWT di semua AJAX request (jQuery)
        $(document).ajaxSend(function (event, jqXHR) {
            const t = sessionStorage.getItem(TOKEN_KEY);
            if (t) {
                jqXHR.setRequestHeader('Authorization', 'Bearer ' + t);
            }
        });

        // 4. Handle 401 dari server → redirect ke login
        $(document).ajaxError(function (event, jqXHR) {
            if (jqXHR.status === 401) {
                sessionStorage.removeItem(TOKEN_KEY);
                try {
                    const res = JSON.parse(jqXHR.responseText);
                    if (res.redirect) { window.location.href = res.redirect; return; }
                } catch (e) {}
                window.location.href = '{{ route('login') }}';
            }
        });

        // 5. Auto-dismiss success alerts
        setTimeout(() => {
            document.querySelectorAll('.alert.alert-success').forEach(el => {
                new bootstrap.Alert(el).close();
            });
        }, 4000);
    })();
</script>
@stack('scripts')
</body>
</html>
