@extends('layouts.section_manager')

@section('title', 'Search Results')

@section('content')
<div class="container mx-auto p-6">

    {{-- ✅ Search Bar --}}
    <div class="toolbar mb-6">
        <form id="driver-search-form" action="{{ route('section_manager.requests.search') }}" method="GET" class="flex">
            <input type="text" name="search" id="search-input" value="{{ old('search', $search ?? '') }}"
                placeholder="Search by Driver Name..."
                class="flex-1 border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            <button type="submit"
                class="bg-yellow-500 text-white px-4 py-2 rounded-r hover:bg-yellow-600 transition">
                🔍 Search
            </button>
        </form>
    </div>

    {{-- ✅ Results --}}
    <h2 class="dashboard-title">🔍 Search Results for "{{ $search }}"</h2>

    <ul class="requests-list">
        @forelse($pendingRequests as $req)
            <li class="request-card" style="background-color:#e5db1b;">
                <div class="request-content flex justify-between flex-wrap gap-4">

                    {{-- Request Info --}}
                    <div class="flex-1">
                        <h4 class="request-header font-semibold text-lg mb-1">
                            Request #{{ $req->id }} — {{ $req->user->name ?? 'Unknown User' }}
                        </h4>
                        <p><strong>Vehicle:</strong> {{ $req->vehicle->plate_no ?? 'N/A' }}</p>
                        <p><strong>Branch:</strong> {{ $req->vehicle->branch ?? 'N/A' }}</p>
                        <p><strong>Tire:</strong> {{ $req->tire->size ?? 'N/A' }}</p>
                        <p><strong>Damage Description:</strong> {{ $req->damage_description ?? 'No description' }}</p>

                        {{-- Tire Images --}}
                        @php
                            $images = $req->tire_images ?? [];
                            if(is_string($images)){
                                $decoded = json_decode($images, true);
                                $images = is_array($decoded) ? $decoded : explode(',', $images);
                            }
                        @endphp
                        @if(count($images) > 0)
                            <div class="request-images mt-3">
                                <strong>Images:</strong>
                                <div class="images-container flex flex-wrap gap-2 mt-2">
                                    @foreach($images as $img)
                                        <img src="{{ asset('storage/' . $img) }}"
                                             class="request-img w-24 h-24 object-cover rounded shadow"
                                             data-full="{{ asset('storage/' . $img) }}" />
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-2 request-actions">
                        <form action="{{ route('section_manager.requests.approve', $req->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-approve">✅ Approve</button>
                        </form>
                        <form action="{{ route('section_manager.requests.reject', $req->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-reject">❌ Reject</button>
                        </form>
                    </div>
                </div>
            </li>
        @empty
            <li class="request-card empty-card">No pending requests found for "{{ $search }}"</li>
        @endforelse
    </ul>
</div>
@endsection

@push('styles')
<style>
/* Dashboard Title */
.dashboard-title { font-size:2rem; font-weight:700; text-align:center; margin-bottom:1.5rem; color:#065f46; }
/* Requests List */
.requests-list { display:flex; flex-direction:column; gap:1.2rem; }
/* Cards */
.request-card { border-radius:1rem; padding:1.5rem; box-shadow:0 6px 14px rgba(0,0,0,0.06); transition: transform .3s, box-shadow .3s; }
.request-card:hover { transform: translateY(-5px) scale(1.01); box-shadow:0 12px 24px rgba(0,0,0,0.12); }
.empty-card { text-align:center; color:#6b7280; font-style:italic; }
/* Info */
.request-header { font-size:1.1rem; font-weight:600; color:#1f1f1f; margin-bottom:0.25rem; }
.request-vehicle { color:#374151; margin-bottom:0.6rem; }
.request-damage p { margin-top:0.3rem; color:#4b5563; }
/* Images */
.images-container { display:flex; flex-wrap:wrap; gap:0.6rem; margin-top:0.6rem; }
.request-img { width:110px; height:80px; object-fit:cover; border-radius:0.5rem; border:1px solid #ddd; cursor:pointer; transition:transform .25s, box-shadow .25s; }
.request-img:hover { transform:scale(1.05); box-shadow:0 10px 20px rgba(0,0,0,0.15); }
/* Buttons */
.btn { padding:0.5rem 1rem; border-radius:0.75rem; font-weight:600; color:white; font-size:0.95rem; cursor:pointer; border:none; transition:all .3s ease; }
.btn-approve { background:#16a34a; }
.btn-approve:hover { background:#15803d; transform:scale(1.05); }
.btn-reject { background:#dc2626; }
.btn-reject:hover { background:#b91c1c; transform:scale(1.05); }
/* Lightbox */
.lightbox { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,0.7); z-index:10000; }
.lightbox.active { display:flex; }
.lightbox img { max-width:90%; max-height:85%; border-radius:.75rem; }
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

    // Search: prevent empty submission
    const searchForm = document.getElementById('driver-search-form');
    const searchInput = document.getElementById('search-input');
    searchForm.addEventListener('submit', function(e){
        if(searchInput.value.trim() === ''){
            e.preventDefault();
            window.location.href = "{{ route('section_manager.dashboard') }}";
        }
    });
});
</script>
@endpush
