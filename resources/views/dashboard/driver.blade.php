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
            @if($driver)
                Welcome, {{ $driver->full_name }}!
            @else
                Welcome!
            @endif
        </div>
    </div>

    {{-- 🔹 Main dashboard layout --}}
    <div class="dashboard-layout">

        {{-- Left column: driver details + vertical cards --}}
        <div class="dashboard-left">
            <div class="driver-info-card">
                <h2 style="color: #2563eb;"><u>{{ $driver->full_name ?? 'N/A' }}</u></h2>
                <p><strong>Username:</strong> {{ $driver->user->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $driver->user->email ?? 'N/A' }}</p>
                <p><strong>Mobile:</strong> {{ $driver->mobile ?? 'N/A' }}</p>
                <p><strong>ID Number:</strong> {{ $driver->id_number ?? 'N/A' }}</p>
            </div>

            {{-- Vertical dashboard cards --}}
            <div class="dashboard-cards-vertical">
                <a href="{{ route('driver.requests.create') }}" class="dashboard-card blue">Request Tire</a>
                <a href="{{ route('driver.requests.index') }}" class="dashboard-card purple">View Requests</a>
                <a href="#" class="dashboard-card green">View Receipts</a>
                <a href="{{ route('driver.profile.edit') }}" class="dashboard-card yellow">Manage Account</a>
            </div>
        </div>

        {{-- Right column: image with text overlay --}}
        <div class="dashboard-right">
            <div class="right-photo-card">
{{-- Right column: creative photo with overlay text --}}
<div class="dashboard-right">
    <div class="right-photo-card">
        <div class="right-text-overlay">
            <h2>SLTMOBITEL Contact</h2>
            <p>For any problem, please contact:</p>
            <ul>
                <li><strong>Section Manager:</strong> sectionmanager123@gmail.com</li>
                <li><strong>Mechanic Officer:</strong> mechanicofficer123@gmail.com</li>
                <li><strong>Transport Officer:</strong> transportofficer123@gmail.com</li>
            </ul>
        </div>
    </div>
</div>

            </div>
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

/* 🔹 Dashboard layout */
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
.dashboard-card.purple { background: #2563eb; }
.dashboard-card.green { background: #2563eb; }
.dashboard-card.yellow { background: #2563eb; }

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
    position: relative; /* for overlay positioning */
    background: url('{{ asset('assets/images/driver-right.jpg') }}') center/cover no-repeat;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    transition: transform 0.3s, box-shadow 0.3s;
}

.right-text-overlay {
    position: absolute; /* top overlay */
    top: 0;
    left: 0;
    width: 100%;
    padding: 1.5rem 2rem;
    background: rgba(0,0,0,0.55); /* slightly darker for better readability */
    border-radius: 1rem 1rem 0 0; /* rounded top corners */
    color: #ffffff;
    text-align: left;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.right-text-overlay h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #facc15; /* highlight title in yellow */
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
    color: #38bdf8; /* make roles highlighted */
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
