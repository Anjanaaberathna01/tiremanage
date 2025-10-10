@extends('layouts.app')

@section('title', 'Driver Dashboard')

@section('content')
<div class="dashboard-container">

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
            ? asset('storage/' . $driver->profile_photo)
            : asset('assets/images/default-profile.jpg');
    @endphp

    {{-- Welcome --}}
    <div class="welcome-section">
        <img src="{{ $profilePhoto }}" alt="Profile Photo" class="avatar-large">
        <h1 class="welcome-text">
            Welcome, {{ $driver->full_name ?? 'Driver' }}!
        </h1>
    </div>

    {{-- NEW DRIVER INFO CARD --}}
@if($driver)
<div class="driver-info-card fade-in">
    <div class="card-overlay"></div>
    <h2 class="info-title">👤 Driver Information</h2>
    <div class="info-grid">
        <div><strong>Full Name:</strong> {{ $driver->full_name ?? 'N/A' }}</div>
        <div><strong>Email:</strong> {{ $driver->user->email ?? 'N/A' }}</div>
        <div><strong>Mobile:</strong> {{ $driver->mobile ?? 'N/A' }}</div>
        <div><strong>ID Number:</strong> {{ $driver->id_number ?? 'N/A' }}</div>
    </div>
</div>
@endif


    {{-- Two Column Layout --}}
    <div class="dashboard-two-col">

        {{-- LEFT COLUMN - Cards --}}
        <div class="left-col">
            <div class="cards-stack">

                <div class="card clickable" data-href="{{ route('driver.requests.create') }}">
                    <h2 class="card-title">Request Tyre</h2>
                    <p class="card-text">Submit a new tyre request quickly and easily.</p>
                </div>

                <div class="card clickable" data-href="{{ route('driver.requests.index') }}">
                    <h2 class="card-title">View Your Requests</h2>
                    <p class="card-text">Track the status of your tyre requests.</p>
                </div>

                    @php
                        $unreadReceipts = \App\Models\Receipt::whereHas('tireRequest', function ($query) {
                            $query->where('user_id', auth()->id());
                        })->where('is_read', false)->count();
                    @endphp

                    <div class="card clickable" data-href="{{ route('driver.receipts') }}" style="position: relative;">
                        {{-- Notification badge --}}
                        @if($unreadReceipts > 0)
                            <div class="notif-badge">{{ $unreadReceipts }}</div>
                        @endif

                        <h2 class="card-title">View Receipts</h2>
                        <p class="card-text">Check all your tyre request receipts.</p>
                    </div>



                <div class="card clickable" data-href="{{ route('driver.profile.edit') }}">
                    <h2 class="card-title">Manage Account</h2>
                    <p class="card-text">Update your profile and account details.</p>
                </div>

            </div>
        </div>

        {{-- RIGHT COLUMN - Image --}}
        <div class="right-col">
            <div class="image-panel">
                <div class="image-bg" style="background-image: url('{{ asset('assets/images/driver-right.jpg') }}');"></div>
                <div class="image-overlay"></div>
                <div class="image-content">
                    <h2 class="image-title">Smooth Rides Start Here</h2>
                    <p class="image-desc">
                        Manage your tyre requests — request, track approvals, and view receipts all in one place.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
/* ===== Layout ===== */
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
    margin-bottom: 100px;
}

/* ===== Welcome Section ===== */
.welcome-section {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 30px;
}
.welcome-text {
    font-size: 26px;
    font-weight: bold;
    color: #2563eb;
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
    box-shadow: 0 8px 20px rgba(37,99,235,0.2);
}

/* ===== Flash Messages ===== */
.flash-msg {
    position: fixed;
    top: 70px;
    right: 25px;
    padding: 12px 18px;
    border-radius: 6px;
    font-weight: bold;
    color: white;
    z-index: 1000;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    animation: fadeOut 4s forwards;
}
.flash-success { background: linear-gradient(135deg, #16a34a, #22c55e); }
.flash-error { background: linear-gradient(135deg, #dc2626, #b91c1c); }
@keyframes fadeOut {
    0% { opacity: 1; }
    80% { opacity: 1; }
    100% { opacity: 0; transform: translateY(-20px); }
}

/* ===== Driver Info Card ===== */
.driver-info-card {
    position: relative;
    background: url("{{ asset('assets/images/driver-information.png') }}") no-repeat center center/cover;
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 40px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    color: #fff;
    backdrop-filter: blur(5px);
    transform: translateY(0);
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}

/* Overlay to make text readable */
.driver-info-card .card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(31, 31, 31, 0.65), rgba(164, 164, 165, 0.65));
    z-index: 1;
    border-radius: inherit;
}

.driver-info-card * {
    position: relative;
    z-index: 2;
}

/* Hover effect */
.driver-info-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(13, 110, 253, 0.25);
}

/* Title */
.info-title {
    font-size: 24px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 18px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

/* Info grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 14px;
    font-size: 16px;
    color: #f1f5f9;
}

/* Fade-in animation */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInCard 0.8s ease forwards;
}

@keyframes fadeInCard {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}


/* ===== Two Column Layout ===== */
.dashboard-two-col {
    display: flex;
    flex-direction: column;
    gap: 40px;
}
@media (min-width: 1024px) {
    .dashboard-two-col {
        flex-direction: row;
        gap: 40px;
        align-items: stretch;
    }
}
.left-col, .right-col { flex: 1; }

/* ===== Cards ===== */
.cards-stack {
    display: flex;
    flex-direction: column;
    gap: 30px;
}
.card {
    background: #2563eb;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.04);
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateX(-25px);
}
.card.in-view { opacity: 1; transform: translateX(0); }
.card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: #2563eb
    opacity: 0;
    transition: opacity 0.4s;
    z-index: 0;
}
.card:hover::before { opacity: 0.15; }
.card:hover {
    transform: translateY(-4px);

}
.card-title {
    font-size: 20px;
    font-weight: 700;
    color: #ead024;
    margin-bottom: 6px;
    position: relative;
    z-index: 1;
}
.card-text {
    font-size: 15px;
    color: #dbba15;
    position: relative;
    z-index: 1;
}

/* ===== Image Panel ===== */
.image-panel {
    position: relative;
    height: 500px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.image-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transition: transform 0.6s ease;
}
.image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.35), rgba(0,0,0,0.55));
}
.image-content {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    padding: 30px;
    color: #fff;
}
.image-title {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 10px;
}
.image-desc {
    max-width: 480px;
    color: rgba(255,255,255,0.9);
}
.image-panel:hover .image-bg { transform: scale(1.05); }

/* Responsive */
@media (max-width: 768px) {
    .dashboard-two-col { flex-direction: column; }
    .image-panel { height: 320px; }
}
/* === Creative Notification Badge === */
.notif-badge {
    position: absolute;
    top: 14px;
    right: 16px;
    background: linear-gradient(145deg, #ef4444, #b91c1c);
    color: #fff;
    font-size: 13px;
    font-weight: bold;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 8px rgba(0,0,0,0.3);
    animation: pulseBadge 1.5s infinite;
    z-index: 10;
}

/* Pulse animation */
@keyframes pulseBadge {
    0%   { transform: scale(1); box-shadow: 0 0 0 0 rgba(239,68,68,0.6); }
    70%  { transform: scale(1.15); box-shadow: 0 0 0 8px rgba(239,68,68,0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239,68,68,0); }
}


</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Fade-out flash messages
    setTimeout(() => {
        document.querySelectorAll('.flash-msg').forEach(msg => {
            msg.style.transition = 'opacity 0.6s, transform 0.6s';
            msg.style.opacity = '0';
            msg.style.transform = 'translateY(-12px)';
            setTimeout(() => msg.remove(), 600);
        });
    }, 4000);

    // Animate cards
    const cards = document.querySelectorAll('.card');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        cards.forEach(card => observer.observe(card));
    } else {
        cards.forEach(card => card.classList.add('in-view'));
    }

    // Card click navigation
    document.querySelectorAll('.card.clickable').forEach(card => {
        card.addEventListener('click', () => {
            const link = card.getAttribute('data-href');
            if (link) window.location.href = link;
        });
    });
});
</script>
@endpush
