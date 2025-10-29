@extends('layouts.transportofficer')

@section('title', 'Pending Tire Requests')
@section('page_title', 'Pending Requests')

@section('content')
<div class="container px-0">

    {{-- Toolbar: Search --}}
    <div class="mb-3">
        <form id="driver-search-form" action="{{ route('transport_officer.pending') }}" method="GET">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" id="search-input" value="{{ request('search') }}" class="form-control" placeholder="Search by driver, vehicle, or branch">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="alert alert-danger"><i class="bi bi-exclamation-octagon me-2"></i>{{ session('error') }}</div>
    @endif

    {{-- Pending Requests --}}
    <ul class="list-unstyled d-flex flex-column gap-3 mb-5">
        @forelse($pendingRequests as $req)
            <li>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                            <div>
                                <div class="fw-semibold text-primary mb-1">
                                    <i class="bi bi-person-badge me-1"></i> Driver: {{ $req->user->name ?? 'N/A' }}
                                </div>
                                <div class="text-muted small">
                                    <span class="me-3"><i class="bi bi-truck me-1"></i>Vehicle: {{ $req->vehicle->plate_no ?? 'N/A' }}</span>
                                    <span class="me-3"><i class="bi bi-geo-alt me-1"></i>Branch: {{ $req->vehicle->branch ?? 'N/A' }}</span>
                                    <span class="me-3"><i class="bi bi-record2 me-1"></i>Tire: {{ $req->tire->brand ?? 'N/A' }} {{ $req->tire->size ?? '' }}</span>
                                    <span class="me-3"><i class="bi bi-123 me-1"></i>Count: {{ $req->tire_count ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="badge text-bg-warning"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="mb-2">
                            <div class="fw-semibold mb-1">Damage Description</div>
                            <div class="text-muted">{{ $req->damage_description ?? 'No description provided' }}</div>
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
                            <div class="mt-3">
                                <div class="fw-semibold mb-2">Images</div>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($images as $img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="image-{{ $req->id }}" class="rounded border request-img" style="width:110px;height:80px;object-fit:cover;cursor:pointer" data-full="{{ asset('storage/' . $img) }}" />
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-muted fst-italic">No images provided</div>
                        @endif

                        <div class="d-flex gap-2 mt-3">
                            <form action="{{ route('transport_officer.approve', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-elevated">
                                    <i class="bi bi-check2-circle me-1"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('transport_officer.reject', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-elevated">
                                    <i class="bi bi-x-circle me-1"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center text-muted">
                        <i class="bi bi-inbox me-1"></i> No pending requests found.
                    </div>
                </div>
            </li>
        @endforelse
    </ul>
</div>
@endsection

@push('styles')
<style>
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
    if (searchInput) {
        searchInput.addEventListener('input', function(){
            const term = searchInput.value.toLowerCase();
            document.querySelectorAll('.card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.parentElement.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
});
</script>
@endpush

