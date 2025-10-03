@extends('layouts.app')

@section('title', 'Driver Dashboard')

@section('content')
<div class="container mx-auto p-6" style="margin-bottom: 40px;">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flash-msg flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-msg flash-error">{{ session('error') }}</div>
    @endif

    @php
        $driver = \App\Models\Driver::with('user')->where('user_id', auth()->id())->first();
        $profilePhoto = $driver && $driver->profile_photo
                        ? asset('storage/'.$driver->profile_photo)
                        : asset('assets/images/default-profile.jpg');
    @endphp

    {{-- 🔹 Welcome section --}}
    <div class="welcome-section">
        <img src="{{ $profilePhoto }}" alt="Profile Photo" class="avatar-large">
        <div class="welcome-text">
            Welcome, {{ $driver->full_name ?? 'Driver' }}!
        </div>
    </div>

    {{-- 🔹 First Row: 3 cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-2.5 gap-y-2.5 mb-6 mt-6">
        <div class="card interactive-card">
            <h2 class="card-title">Request Tire</h2>
            <p class="card-text">Submit a new tire request quickly and easily.</p>
            <a href="{{ route('driver.requests.create') }}" class="btn btn-blue">Request Now</a>
        </div>

        <div class="card interactive-card">
            <h2 class="card-title">View Your Requests</h2>
            <p class="card-text">Track the status of your tire requests.</p>
            <a href="{{ route('driver.requests.index') }}" class="btn btn-purple">View Requests</a>
        </div>

        <div class="card interactive-card">
            <h2 class="card-title">View Receipts</h2>
            <p class="card-text">Check all your tire request receipts.</p>
            <a href="#" class="btn btn-green">View Receipts</a>
        </div>
    </div>

    {{-- 🔹 Second Row: 1 card centered --}}
    <div class="flex flex-wrap justify-center gap-x-2.5 gap-y-2.5">
        <div class="card interactive-card w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
            <h2 class="card-title">Manage Account</h2>
            <p class="card-text">Update your profile and account details.</p>
            <a href="{{ route('driver.profile.edit') }}" class="btn btn-yellow">Manage Account</a>
        </div>

    </div>
</div>

{{-- Modern CSS --}}
<style>
/* Flash messages */
.flash-msg {
    padding: 1rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 1rem;
    position: fixed;
    top: 5rem;
    right: 2rem;
    z-index: 9999;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    opacity: 1;
    animation: slideFade 4s forwards;
}
.flash-success { background: linear-gradient(135deg,#22c55e,#16a34a); }
.flash-error { background: linear-gradient(135deg,#ef4444,#b91c1c); }
@keyframes slideFade { 0% {opacity:1; transform:translateY(0);} 100%{opacity:0; transform:translateY(-20px);} }

/* Welcome section */
.welcome-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}
.avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid #2563eb;
    object-fit: cover;
    transition: transform 0.3s, box-shadow 0.3s;
}
.avatar-large:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 15px rgba(0,0,0,0.25);
}
.welcome-text {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2563eb;
}

/* Dashboard layout */
.dashboard-layout {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

/* Left column */
.dashboard-left {
    flex: 1 1 350px;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Driver info card */
.driver-info-card {
    background: #f3f4f6;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    text-align: left;
    transition: transform 0.3s, box-shadow 0.3s;
}
.driver-info-card:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* Vertical dashboard cards */
.dashboard-cards-vertical {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.dashboard-card {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 2rem;
    border-radius: 1rem;
    font-weight: 600;
    color: white;
    text-decoration: none;
    transition: transform 0.3s, box-shadow 0.3s;
    text-align: center;
}
.dashboard-card:hover {
    transform: translateY(-5px) scale(1.03);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}
.dashboard-card.blue { background: #2563eb; }
.dashboard-card.purple { background: #7e22ce; }
.dashboard-card.green { background: #16a34a; }
.dashboard-card.yellow { background: #ca8a04; }

/* Right column: image with overlay text */
.dashboard-right {
    flex: 1 1 350px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.right-photo-card {
    width: 100%;
    min-height: 625px;
    border-radius: 1rem;
    overflow: hidden;
    position: relative;
    background: url('{{ asset('assets/images/driver-right.jpg') }}') center/cover no-repeat;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    transition: transform 0.3s, box-shadow 0.3s;
}

/* Overlay Text */
.right-text-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    padding: 1.5rem 2rem;
    background: rgba(0,0,0,0.55);
    border-radius: 1rem 1rem 0 0;
    color: #ffffff;
    text-align: left;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.right-text-overlay h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #facc15;
}
.right-text-overlay p {
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}
.right-text-overlay ul {
    list-style: none;
    padding-left: 0;
}
.right-text-overlay ul li {
    font-size: 0.95rem;
    margin-bottom: 0.3rem;
}
.right-text-overlay ul li strong {
    color: #38bdf8;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-layout {
        flex-direction: column;
    }
    .dashboard-right {
        min-height: 200px;
    }
}
</style>

{{-- JS: auto-hide flash messages --}}
<script>
setTimeout(() => {
    document.querySelectorAll('.flash-msg').forEach(msg => msg.remove());
}, 4000);
</script>

@endsection
