<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mechanic Officer')</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    @include('partials.theme')
    <style>
      /* Mechanic Officer dark navbar with distinct teal/emerald gradient */
      .mechanic-navbar.navbar { background: linear-gradient(180deg, #064e3b, #065f46) !important; border-bottom: 1px solid rgba(134,239,172,.16); box-shadow: 0 10px 28px rgba(6,78,59,.35); }
      .mechanic-navbar .navbar-brand { color: #d1fae5 !important; }
      .mechanic-navbar .navbar-brand img { filter: brightness(1.15) contrast(1.08); }
      .mechanic-navbar .nav-link { color: #a7f3d0 !important; font-weight: 600; }
      .mechanic-navbar .nav-link:hover, .mechanic-navbar .nav-link.active { background: rgba(16,185,129,.15); color: #ffffff !important; }
      .mechanic-navbar .btn-link.nav-link { color: #fca5a5 !important; }
      .mechanic-navbar .navbar-toggler { border-color: rgba(167,243,208,.35); }
      .mechanic-navbar .navbar-toggler:focus { box-shadow: 0 0 0 .15rem rgba(16,185,129,.35); }
      .mechanic-navbar .navbar-toggler-icon { filter: invert(1) brightness(1.2); }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Mechanic Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark mechanic-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('mechanic_officer.pending') }}">
                <img src="{{ asset('assets/images/logo2.png') }}" alt="logo" style="height:36px; width:auto; margin-right:20px; margin-bottom: 4px;" />
                <span>Mechanic Officer</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#moNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="moNavbar">
                <ul class="navbar-nav ms-auto">
                    {{-- Dashboard / Pending Requests --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mechanic_officer.pending') }}">Home</a>
                    </li>
                    {{-- Approved Requests --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mechanic_officer.approved') }}">Approved Requests</a>
                    </li>
                    {{-- Rejected Requests --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('mechanic_officer.rejected') }}">Rejected Requests</a>
                    </li>
                    {{-- Logout --}}
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

    <div class="container mt-5 pt-4">
        @yield('content')
    </div>

    @include('partials.footer')

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
