<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Bootstrap (local) -->
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Custom Admin Theme (Light, neutral palette with brand navbar) -->
    <style>
        :root {
            --nav-h: 52px;        /* compact navbar height */
            /* Light neutral admin palette */
            --bg: #f5f7fb;
            --surface: #f9fafb;
            --muted: #6b7280;     /* gray-500 */
            --text: #111827;      /* gray-900 */
            --border: #e5e7eb;    /* gray-200 */
            --shadow: 0 8px 24px rgba(17, 24, 39, 0.06);

            /* Brand accents */
            --primary: #0057A8;       /* SLT blue */
            --primary-600: #004C95;   /* darker SLT blue */
            --brand-green: #39B54A;   /* MOBITEL green */
            --success: var(--brand-green);
            --success-600: #2E9E3E;
            --primary-focus: rgba(0, 87, 168, .15);
            --warning: #f59e0b;   /* amber-500 */
            --warning-600: #d97706;
            --danger: #ef4444;    /* red-500 */
            --danger-600: #dc2626;
        }

        /* Body and typography */
        body {
            background: var(--bg);
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            line-height: 1.5;
        }

        /* Top navbar */
        .navbar {
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-600) 100%) !important;
            border-bottom: none;
            box-shadow: 0 6px 18px rgba(0,0,0,.18);
            padding-top: .15rem;
            padding-bottom: .15rem;
            min-height: var(--nav-h);
        }
        .navbar .navbar-brand { color: #ffffff !important; font-weight: 700; letter-spacing: .2px; font-size: 1.05rem; line-height: 1.2; }
        .navbar .navbar-brand .brand-logo { height: 30px; width: auto; display: block; }
        .navbar .navbar-brand .brand-logo { filter: drop-shadow(0 1px 1.5px rgba(0,0,0,.4)); }
        .navbar .nav-link { color: rgba(255,255,255,.85) !important; font-weight: 500; border-radius: .4rem; padding: .35rem .6rem; line-height: 1.2; transition: background-color .2s ease, color .2s ease, transform .08s ease; }
        .navbar .nav-link:hover { background: rgba(255,255,255,.08); color: #ffffff !important; }
        .navbar .nav-link.active, .navbar .nav-link[aria-current="page"] { background: rgba(255,255,255,.18); color: #ffffff !important; }
        .navbar .nav-link:active {
            transform: translateY(1px);
        }
        .btn-link.nav-link { color: var(--danger) !important; }
        .navbar .navbar-toggler { border-color: rgba(255,255,255,.55); padding: .15rem .45rem; }
        .navbar .navbar-toggler:focus { box-shadow: 0 0 0 .15rem rgba(255,255,255,.35); }

        /* Main container spacing */
        .container { padding-top: .5rem; padding-bottom: 2rem; }

        /* Ensure space for fixed navbar */
        body { padding-top: calc(var(--nav-h) + 8px); }

        /* Cards */
        .card {
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease;
            background: var(--surface);
            color: var(--text);
        }
        .card:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(17,24,39,.08); }
        .card-header { background: #f9fafb; border-bottom-color: var(--border); color: var(--text); font-weight: 600; }

        /* Buttons */
        .btn { border-radius: 10px; font-weight: 600; letter-spacing: .2px; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-600); border-color: var(--primary-600); }
        .btn-success { background: var(--success); border-color: var(--success); }
        .btn-success:hover { background: var(--success-600); border-color: var(--success-600); }
        .btn-warning { background: var(--warning); border-color: var(--warning); color: #111827; }
        .btn-warning:hover { background: var(--warning-600); border-color: var(--warning-600); color: #111827; }
        .btn-danger { background: var(--danger); border-color: var(--danger); }
        .btn-danger:hover { background: var(--danger-600); border-color: var(--danger-600); }
        .btn-elevated { box-shadow: var(--shadow); transition: transform .15s ease, box-shadow .15s ease; }
        .btn-elevated:hover { transform: translateY(-1px); box-shadow: 0 10px 20px rgba(0,0,0,.22); }
        .btn:focus { box-shadow: 0 0 0 .16rem var(--primary-focus); }
        .btn .bi { margin-right: .4rem; position: relative; top: -1px; }

        /* Modern icon action buttons */
        .btn-icon { width: 34px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; }
        .btn-sm.btn-icon { width: 30px; height: 30px; }
        .btn-icon .bi { margin: 0; top: 0; }
        .action-buttons { display: inline-flex; gap: .35rem; }

        /* Outline buttons for dark theme */
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { color: #fff; background-color: var(--primary); border-color: var(--primary); }
        .btn-outline-success { color: var(--success); border-color: var(--success); }
        .btn-outline-success:hover { color: #fff; background-color: var(--success); border-color: var(--success); }
        .btn-outline-warning { color: var(--warning); border-color: var(--warning); }
        .btn-outline-warning:hover { color: #111827; background-color: var(--warning); border-color: var(--warning); }
        .btn-outline-danger { color: var(--danger); border-color: var(--danger); }
        .btn-outline-danger:hover { color: #fff; background-color: var(--danger); border-color: var(--danger); }
        .btn-outline-dark { color: #e5e7eb; border-color: #4b5563; }
        .btn-outline-dark:hover { color: #fff; background-color: #374151; border-color: #374151; }

        /* Tables */
        table { background: var(--surface); color: var(--text); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        thead th { background: #f3f4f6; color: #111827; font-weight: 700; }
        th, td { vertical-align: middle; }
        tbody tr { transition: background-color .15s ease; }
        tbody tr:hover { background: rgba(255,255,255,0.03); }
        .table > :not(caption) > * > * { padding: .85rem 1rem; }

        /* Links */
        a { color: var(--primary); }
        a:hover { color: var(--primary-600); }

        /* Forms */
        .form-control { border-radius: 10px; border-color: var(--border); background: #ffffff; color: var(--text); }
        .form-control::placeholder { color: #9ca3af; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 .2rem var(--primary-focus); background: #ffffff; color: var(--text); }

        /* Footer intentionally removed from admin layout */

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-brand { font-size: 1rem; }
            .navbar .nav-link { font-size: .9rem; padding: .3rem .55rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/images/logo2.png') }}" alt="SLT-MOBITEL" class="brand-logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto">
                    <!-- Home tab: navigates to admin dashboard -->
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Home</a></li>
                    
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}" href="{{ route('admin.vehicles.index') }}">Vehicles</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.tires.*') ? 'active' : '' }}" href="{{ route('admin.tires.index') }}">Tyres</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.drivers.*') || request()->routeIs('section_manager.drivers.*') ? 'active' : '' }}" href="{{ route('section_manager.drivers.index') }}">Drivers</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}">Suppliers</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.request.*') ? 'active' : '' }}" href="{{ route('admin.request.pending') }}">Requests</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container mt-4">
        @yield('content')
    </div>

    

    <!-- Bootstrap JS (local) -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
