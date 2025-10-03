@extends('layouts.mechanic_officer')

@section('title', 'Mechanic Officer Dashboard')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="dashboard-title">Mechanic Officer — Pending Requests</h2>

    <ul class="requests-list">
        @forelse($requests as $req)
            <li class="request-card">
                <div class="request-content">
                    <div class="request-info">
                        <div class="request-header">
                            <strong>Request:</strong> User: {{ $req->user->name }}
                        </div>
                        <div class="request-vehicle">
                            Vehicle: {{ $req->vehicle->plate_no ?? 'N/A' }}<br>
                            Branch: {{ $req->vehicle->branch ?? 'N/A' }}<br>
                            Tire: {{ $req->tire->size ?? 'N/A' }}
                        </div>
                        <div class="request-damage">
                            <strong>Damage Description:</strong>
                            <p>{{ $req->damage_description ?? 'No description provided' }}</p>
                        </div>

                        {{-- Tire Images --}}
                        @php
                            $images = [];
                            if (isset($req->tire_images)) {
                                if (is_array($req->tire_images)) {
                                    $images = $req->tire_images;
                                } elseif (is_string($req->tire_images) && trim($req->tire_images) !== '') {
                                    $decoded = json_decode($req->tire_images, true);
                                    if (is_array($decoded)) {
                                        $images = $decoded;
                                    } else {
                                        $images = array_map('trim', explode(',', $req->tire_images));
                                    }
                                }
                            }
                        @endphp

                        @if(count($images) > 0)
                            <div class="request-images">
                                <strong>Images:</strong>
                                <div class="images-container">
                                    @foreach($images as $img)
                                        <a href="{{ asset('storage/' . $img) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $img) }}"
                                                 alt="image-{{ $req->id }}"
                                                 class="request-img"/>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="no-images"><em>No images provided</em></div>
                        @endif
                    </div>

                    {{-- Actions Section --}}
                    <div class="request-actions">
                        <form action="{{ route('mechanic_officer.requests.approve', $req->id) }}" method="POST" class="action-form">
                            @csrf
                            <button type="submit" class="btn btn-approve">Approve</button>
                        </form>
                        <form action="{{ route('mechanic_officer.requests.reject', $req->id) }}" method="POST" class="action-form">
                            @csrf
                            <button type="submit" class="btn btn-reject">Reject</button>
                        </form>
                    </div>
                </div>
            </li>
        @empty
            <li class="request-card text-center text-gray-500 italic">
                No manager-approved requests available for mechanic.
            </li>
        @endforelse
    </ul>
</div>

{{-- --- STYLES (copied/adapted from Section Manager) --- --}}
<style>
.dashboard-title {
    font-size:1.75rem;
    font-weight:700;
    text-align:center;
    margin-bottom:1.5rem;
    color:#0f766e;
}

/* Requests List + Cards */
.requests-list { display:flex; flex-direction:column; gap:1.5rem; }
.request-card {
    background: linear-gradient(145deg, #ffffff, #f0f4ff);
    padding: 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}
.request-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
}
.request-content { display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
.request-info { flex:1; }
.request-header { font-weight:600; font-size:1.1rem; margin-bottom:0.25rem; }
.request-vehicle { font-size:1rem; margin-bottom:0.5rem; color:#374151; }
.request-damage p { margin-top:0.25rem; color:#4b5563; }
.request-images { margin-top:0.75rem; }
.images-container { display:flex; flex-wrap:wrap; gap:0.5rem; margin-top:0.5rem; }
.request-img { width:100px; border-radius:0.5rem; border:1px solid #d1d5db; transition: transform 0.2s; }
.request-img:hover { transform:scale(1.05); }
.no-images { margin-top:0.5rem; color:#6b7280; font-style:italic; }

.request-actions {
    min-width:140px;
    display:flex;
    flex-direction:column;
    gap:0.5rem;
}
.btn {
    padding:0.5rem 1rem;
    border-radius:0.75rem;
    font-weight:600;
    color:white;
    font-size:0.95rem;
    border:none;
    cursor:pointer;
    transition:all 0.3s ease;
}
.btn-approve { background:#16a34a; }
.btn-approve:hover { background:#15803d; transform:scale(1.05); }
.btn-reject { background:#dc2626; }
.btn-reject:hover { background:#b91c1c; transform:scale(1.05); }

@media (max-width:768px) {
    .request-content { flex-direction:column; }
    .request-actions { flex-direction:row; justify-content:space-between; min-width:100%; }
}
</style>

{{-- --- SCRIPTS (Image Preview like Section Manager) --- --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.request-img').forEach(img => {
        img.addEventListener('click', (e) => {
            e.preventDefault();
            const lightbox = document.createElement('div');
            lightbox.className = 'lightbox';
            lightbox.innerHTML = '<span class="close-btn">&times;</span><img src="'+img.src+'" alt="preview"/>';
            document.body.appendChild(lightbox);

            const closeBtn = lightbox.querySelector('.close-btn');
            closeBtn.addEventListener('click', () => lightbox.remove());
            lightbox.addEventListener('click', (ev) => { if(ev.target===lightbox) lightbox.remove(); });
        });
    });
});
</script>
@endsection
