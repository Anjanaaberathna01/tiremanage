@extends('layouts.app')

@section('title', 'Driver Dashboard')

@section('content')
<div class="container mx-auto p-6 relative">

    {{-- 🔹 Welcome message under navbar, top-left --}}
    @php
        $driver = \App\Models\Driver::where('user_id', auth()->id())->first();
    @endphp
    <div class="welcome-msg">
        @if($driver)
            Welcome, {{ $driver->full_name }}!
        @else
            Welcome!
        @endif
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

{{-- 🔹 Modern Creative CSS --}}
<style>
    /* Welcome message top-left under navbar */
    .welcome-msg {
        margin-top: 10px;
        margin-left: -40px;
        margin-bottom: 20px;
        font-size: 1.4rem;
        font-weight: 600;
        color: #2563eb;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        display: inline-block;
        transition: transform 0.3s;
    }
    .welcome-msg:hover {
        transform: scale(1.02);
    }

    /* Cards */
    .card {
        background: linear-gradient(145deg, #ffffff, #e8f0ff);
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid #e2e8f0;
    }
    .card:hover {
        transform: translateY(-5px) scale(1.03);
        box-shadow: 0 12px 20px rgba(0,0,0,0.15);
    }

    /* Card title & text */
    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1e293b;
    }
    .card-text {
        color: #4b5563;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }

    /* Buttons */
    .btn {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        border-radius: 0.75rem;
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-blue { background: #2563eb; }
    .btn-blue:hover { background: #1d4ed8; transform: scale(1.05); }
    .btn-purple { background: #7e22ce; }
    .btn-purple:hover { background: #6b21a8; transform: scale(1.05); }
    .btn-green { background: #16a34a; }
    .btn-green:hover { background: #15803d; transform: scale(1.05); }
    .btn-yellow { background: #ca8a04; }
    .btn-yellow:hover { background: #a16207; transform: scale(1.05); }
</style>

{{-- 🔹 Simple JS for interactive tilt effect --}}
<script>
    const cards = document.querySelectorAll('.interactive-card');
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const cx = rect.width/2;
            const cy = rect.height/2;
            const dx = (x - cx) / cx;
            const dy = (y - cy) / cy;
            card.style.transform = `rotateX(${-dy*5}deg) rotateY(${dx*5}deg) scale(1.03)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(-5px) scale(1.03)';
        });
    });
</script>
@endsection
