@extends('layouts.mechanic_officer')

@section('title', 'Mechanic Approved Requests')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="dashboard-title">✅ Approved Requests</h2>
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
                                Tyre: {{ $req->tire->size ?? 'N/A' }}
                        </div>
                        <div class="request-damage">
                            <strong>Damage Description:</strong>
                            <p>{{ $req->damage_description ?? 'No description provided' }}</p>
                        </div>

                        @php
                            $images = [];
                            if (isset($req->tire_images)) {
                                if (is_array($req->tire_images)) {
                                    $images = $req->tire_images;
                                } elseif (is_string($req->tire_images) && trim($req->tire_images) !== '') {
                                    $decoded = json_decode($req->tire_images, true);
                                    if (is_array($decoded)) {
                                        $images = $decoded;
                                    }
                                }
                            }
                        @endphp

                        @if(count($images) > 0)
                            <div class="request-images">
                                <strong>Images:</strong>
                                <div class="images-container">
                                    @foreach($images as $img)
                                        @php $imgPath = str_replace('\\/', '/', trim($img)); @endphp
                                        <img src="{{ asset('storage/' . $imgPath) }}"
                                             alt="image-{{ $req->id }}"
                                             class="request-img"
                                             data-full="{{ asset('storage/' . $imgPath) }}"/>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="no-images"><em>No images provided</em></div>
                        @endif
                    </div>

                    <div class="request-actions">
                        {{-- Mechanic cannot edit/delete from here; actions handled on dashboard --}}
                    </div>
                </div>
            </li>
        @empty
            <li class="request-card">No approved requests found.</li>
        @endforelse
    </ul>
</div>
@endsection

@push('styles')
<style>
.dashboard-title { font-size:1.75rem; font-weight:700; text-align:center; margin-bottom:1rem; color:#0f766e; }
.requests-list { display:flex; flex-direction:column; gap:1rem; }
.request-card { background:#fff; border-radius:0.75rem; padding:1rem; border:1px solid #e6f4f1; box-shadow:0 6px 12px rgba(0,0,0,0.04); }
.request-actions { margin-top:1rem; display:flex; justify-content:flex-end; gap:0.5rem; }
.request-img { width:110px; height:80px; object-fit:cover; border-radius:0.5rem; border:1px solid #ddd; cursor:pointer; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
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
        if (e.target === lightbox) {
            lightbox.classList.remove('active');
            imgEl.src = '';
        }
    });
});
</script>
@endpush
