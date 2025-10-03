@extends('layouts.section_manager')

@section('title', 'Mechanic Officer Dashboard')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="dashboard-title">Mechanic Officer — Manager Approved Requests</h2>

    <ul class="requests-list">
        @forelse($requests as $req)
            <li class="request-card">
                <div class="request-content">
                    <div class="request-info">
                        <div class="request-header">
                            <strong>Request #{{ $req->id }}</strong> — User: {{ $req->user->name ?? 'N/A' }}
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

                    {{-- Approve / Reject actions only (mechanic cannot edit/delete) --}}
                    <div class="request-actions">
                        <form action="{{ route('mechanic_officer.requests.approve', $req->id) }}" method="POST" class="action-form me-2">
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
            <li class="request-card">No manager-approved requests available for mechanic.</li>
        @endforelse
    </ul>
</div>
@endsection

@push('styles')
<style>
/* Keep styling consistent with section manager */
.dashboard-title { font-size:1.75rem; font-weight:700; text-align:center; margin-bottom:1rem; color:#0f766e; }
.requests-list { display:flex; flex-direction:column; gap:1rem; }
.request-card { background:#fff; border-radius:0.75rem; padding:1rem; border:1px solid #e6f4f1; box-shadow:0 6px 12px rgba(0,0,0,0.04); }
.request-actions { margin-top:1rem; display:flex; justify-content:flex-end; gap:0.5rem; }
.btn-approve { background:#16a34a; color:#fff; padding:0.5rem 0.9rem; border-radius:0.5rem; border:none; cursor:pointer; }
.btn-reject { background:#dc2626; color:#fff; padding:0.5rem 0.9rem; border-radius:0.5rem; border:none; cursor:pointer; }
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
