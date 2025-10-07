<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Custom Creative CSS -->
    <style>
        /* Body background and font */
        body {
            background: linear-gradient(to right, #e0f7fa, #f1f8e9);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 56px; /* Default Bootstrap navbar height */
            color: #333;
        }

        /* Navbar customization */
        .navbar {
            background: #456882;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 0.5rem 1rem; /* Default Bootstrap padding */
        }
        .navbar-brand {
            font-size: 1.25rem; /* Bootstrap default */
            color: #fff !important;
        }
        .nav-link {
            color: #e3f2fd !important;
            transition: color 0.2s;
        }
        .nav-link:hover {
            color: #31e3d7 !important;
        }
        .btn-link.nav-link {
            color: #ff5252 !important;
        }

        /* Container styling */
        .container {
            padding-top: 20px;
        }

        /* Cards styling */
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background-color: #3949ab;
            border: none;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #283593;
        }
        .btn-success {
            background-color: #43a047;
            border: none;
            color: #fff;
        }
        .btn-success:hover {
            background-color: #2e7d32;
        }
        .btn-warning {
            background-color: #fbc02d;
            border: none;
            color: #000;
        }
        .btn-warning:hover {
            background-color: #f9a825;
        }
        .btn-danger {
            background-color: #e53935;
            border: none;
            color: #fff;
        }
        .btn-danger:hover {
            background-color: #b71c1c;
        }

        /* Tables */
        table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background: #fff;
        }
        th {
            background: #1e88e5;
            color: #fff;
            text-align: center;
            padding: 12px;
        }
        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }
        tr:hover {
            background: #f1f8e9;
            transition: 0.2s;
        }

        /* Footer styling */
        footer {
            margin-top: 40px;
            padding: 20px 0;
            text-align: center;
            background: #1e88e5;
            color: #fff;
            border-radius: 12px 12px 0 0;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.1rem;
            }
            .nav-link {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.vehicles.index') }}">Vehicles</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.tires.index') }}">Tyres</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('section_manager.drivers.index') }}">Drivers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.suppliers.index') }}">Suppliers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.request.pending') }}">Requests</a></li>
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

    <!-- Footer -->
    <footer>
        &copy; {{ date('Y') }} Admin Dashboard. All rights reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
