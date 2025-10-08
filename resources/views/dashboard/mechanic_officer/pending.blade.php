@extends('layouts.mechanicofficer')

@section('title', 'Pending Tyre Requests')

@section('content')
<div class="container mx-auto p-6">

    {{-- Search Bar --}}
    <div class="toolbar mb-6">
        <form id="driver-search-form" action="{{ route('mechanic_officer.pending') }}" method="GET" class="search-bar">
            <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                placeholder="Search by Driver or Vehicle..." />
            <button type="submit">🔍 Search</button>
        </form>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 mb-4 rounded-lg shadow">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-4 mb-4 rounded-lg shadow">
            {{ session('error') }}
        </div>
    @endif

    {{--  Pending Requests --}}
    <ul class="requests-list">
        @forelse($pendingRequests as $req)
            <li class="request-card">
                <div class="request-content">
                    <div class="request-info">
                        <div class="request-header">
                            <strong>Driver:</strong> {{ $req->user->name ?? 'N/A' }}
                        </div>
                        <div class="request-vehicle">
                            Vehicle: {{ $req->vehicle->plate_no ?? 'N/A' }}<br>
                            Branch: {{ $req->vehicle->branch ?? 'N/A' }}<br>
                            Tyre Count: {{ $req->tire_count ?? 'N/A' }}
                        </div>
                        <div class="request-damage">
                            <strong>Damage Description:</strong>
                            <p>{{ $req->damage_description ?? 'No description provided' }}</p>
                        </div>

                        {{-- Tire Images --}}
                        @php
                            $images = [];
                            if(!empty($req->tire_images)) {
                                if(is_array($req->tire_images)) {
                                    $images = $req->tire_images;
                                } elseif(is_string($req->tire_images)) {
                                    $decoded = json_decode($req->tire_images, true);
                                    if(is_array($decoded)) $images = $decoded;
                                }
                            }
                        @endphp

                        @if(count($images) > 0)
                        <div class="request-images">
                            <strong>Images:</strong>
                            <div class="images-container">
                                @foreach($images as $img)
                                    <img src="{{ asset('storage/' . $img) }}"
                                         alt="image-{{ $req->id }}"
                                         class="request-img"
                                         data-full="{{ asset('storage/' . $img) }}" />
                                @endforeach
                            </div>
                        </div>
                        @else
                            <div class="no-images"><em>No images provided</em></div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="request-actions">
                        <form action="{{ route('mechanic_officer.approve', $req->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="approve-btn">✅ Approve</button>
                        </form>
                        <form action="{{ route('mechanic_officer.reject', $req->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="reject-btn">❌ Reject</button>
                        </form>
                    </div>
                </div>
            </li>
        @empty
            <li class="request-card empty-card">🚫 No pending requests found.</li>
        @endforelse
    </ul>
</div>
@endsection

@push('styles')
<style>
/* --- Search Bar --- */
.search-bar {
    display: flex;
    max-width: 400px;
    margin-bottom: 20px;
    border-radius: 30px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.search-bar input[type="text"] {
    flex: 1;
    padding: 10px 20px;
    border: none;
    font-size: 1rem;
    outline: none;
    transition: background 0.3s;
}
.search-bar input[type="text"]:focus {
    background: #e6f7ff;
}
.search-bar button {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 0 20px;
    cursor: pointer;
    transition: background 0.3s;
}
.search-bar button:hover {
    background: #2563eb;
}

/* --- Requests List --- */
.requests-list { display:flex; flex-direction:column; gap:1.5rem; }
.request-card { background: linear-gradient(135deg,#e0f2fe,#f0f9ff); border:1px solid rgba(3,105,161,0.2); border-radius:1rem; padding:1.5rem; box-shadow:0 6px 14px rgba(0,0,0,0.06); transition:transform .3s, box-shadow .3s; }
.request-card:hover { transform:translateY(-4px); box-shadow:0 12px 20px rgba(0,0,0,0.1); }
.request-header { font-weight:600; color:#0f172a; margin-bottom:5px; font-size:1.1rem; }
.request-vehicle, .request-damage p { color:#374151; margin-bottom:5px; }
.images-container { display:flex; flex-wrap:wrap; gap:0.5rem; margin-top:0.5rem; }
.request-img { width:100px; height:70px; object-fit:cover; border-radius:6px; border:1px solid #ccc; cursor:pointer; transition:transform 0.25s, box-shadow 0.25s; }
.request-img:hover { transform:scale(1.05); box-shadow:0 6px 15px rgba(0,0,0,0.15); }
.no-images { font-style:italic; color:#6b7280; margin-top:5px; }

/* --- Buttons --- */
.request-actions { margin-top:10px; display:flex; gap:10px; }
.approve-btn { background:#10b981; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; font-weight:600; transition:background 0.25s, transform 0.25s; }
.approve-btn:hover { background:#059669; transform:translateY(-2px); }
.reject-btn { background:#ef4444; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; font-weight:600; transition:background 0.25s, transform 0.25s; }
.reject-btn:hover { background:#b91c1c; transform:translateY(-2px); }
.empty-card { text-align:center; color:#6b7280; font-style:italic; }

/* --- Lightbox --- */
.lightbox { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,0.7); z-index:10000; }
.lightbox.active { display:flex; }
.lightbox img { max-width:90%; max-height:85%; border-radius:12px; }
.lightbox .close-btn { position:absolute; top:20px; right:30px; font-size:2rem; color:#fff; cursor:pointer; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Lightbox
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = '<span class="close-btn">&times;</span><img src="" alt="preview"/>';
    document.body.appendChild(lightbox);
    const imgEl = lightbox.querySelector('img');
    const closeBtn = lightbox.querySelector('.close-btn');

    document.querySelectorAll('.request-img').forEach(img => {
        img.addEventListener('click', () => {
            imgEl.src = img.dataset.full || img.src;
            lightbox.classList.add('active');
        });
    });
    closeBtn.addEventListener('click', () => {
        lightbox.classList.remove('active');
        imgEl.src = '';
    });
    lightbox.addEventListener('click', (e) => {
        if(e.target === lightbox){
            lightbox.classList.remove('active');
            imgEl.src = '';
        }
    });

    // Live Search Filter
    const searchInput = document.getElementById('search-input');
    searchInput.addEventListener('input', function(){
        const term = searchInput.value.toLowerCase();
        document.querySelectorAll('.request-card').forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(term) ? '' : 'none';
        });
    });
});
</script>
@endpush
