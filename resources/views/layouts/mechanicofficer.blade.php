<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mechanic Officer')</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    @include('partials.theme')
    @stack('styles')
</head>
<body>
    {{-- Mechanic Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
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
