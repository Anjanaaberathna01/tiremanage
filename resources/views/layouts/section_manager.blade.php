<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Section Manager Vehicles')</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    @include('partials.theme')
    <style>
      /* Section Manager dark navbar with distinct purple/violet gradient */
      .section-manager-navbar.navbar { background: linear-gradient(180deg, #4c1d95, #5b21b6) !important; border-bottom: 1px solid rgba(196,181,253,.16); box-shadow: 0 10px 28px rgba(76,29,149,.35); }
      .section-manager-navbar .navbar-brand { color: #e9d5ff !important; }
      .section-manager-navbar .navbar-brand img { filter: brightness(1.15) contrast(1.08); }
      .section-manager-navbar .nav-link { color: #ddd6fe !important; font-weight: 600; }
      .section-manager-navbar .nav-link:hover, .section-manager-navbar .nav-link.active { background: rgba(168,85,247,.15); color: #ffffff !important; }
      .section-manager-navbar .btn-link.nav-link { color: #fca5a5 !important; }
      .section-manager-navbar .navbar-toggler { border-color: rgba(221,214,254,.35); }
      .section-manager-navbar .navbar-toggler:focus { box-shadow: 0 0 0 .15rem rgba(168,85,247,.35); }
      .section-manager-navbar .navbar-toggler-icon { filter: invert(1) brightness(1.2); }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark section-manager-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('section_manager.dashboard') }}">
                <img src="{{ asset('assets/images/logo3.png') }}" alt="logo" style="height:36px; width:auto; margin-right:20px; margin-bottom: 4px;" />
                <span>Section Manager</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#smNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="smNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('section_manager.dashboard') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('section_manager.requests.approved_list') }}">Approved</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('section_manager.requests.rejected_list') }}">Rejected</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('section_manager.vehicles.index') }}">Vehicles</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('section_manager.drivers.index') }}">Drivers</a></li>
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

    <div class="container mt-4">
        @yield('content')
    </div>

    @include('partials.footer')

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
