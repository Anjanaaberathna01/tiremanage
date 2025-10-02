<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Driver Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    {{-- 🔹 Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('driver.dashboard') }}">SLTMOBITEL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#driverNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="driverNavbar">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('driver.requests.create') }}">
                            <i class="bi bi-plus-circle"></i> Request Tire
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('driver.requests.index') }}">
                            <i class="bi bi-list-check"></i> View Requests
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-receipt"></i> View Receipts
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('driver.profile.edit') }}">
                            <i class="bi bi-person-circle"></i> Manage Account
                        </a>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    {{-- 🔹 Page Content --}}
    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- ✅ Make sure pushed scripts (e.g., lookupPlate in tireRequestCreate.blade.php) load --}}
    @stack('scripts')
</body>
</html>
