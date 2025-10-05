@extends('layouts.section_manager')

@section('title', 'Approved Requests')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="dashboard-title">✅ Approved Requests</h2>

    <ul class="requests-list">
        @forelse($approvedRecords as $approval)
            @php $req = $approval->request; @endphp

            @if($req)
            <li class="request-card">
                <div class="request-content">
                    <div class="request-info">
                        <div class="request-header">
                            <strong>Request:</strong> User: {{ $req->user->name ?? 'N/A' }}
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
                            if(!empty($req->tire_images)) {
                                if(is_array($req->tire_images)) {
                                    $images = $req->tire_images;
                                } elseif(is_string($req->tire_images)) {
                                    $decoded = json_decode($req->tire_images, true);
                                    if(is_array($decoded)) $images = $decoded;
                                }
                            }
                            if(empty($images) && !empty($req->images)) {
                                $images = is_array($req->images) ? $req->images : array_map('trim', explode(',', $req->images));
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
                        <a href="{{ route('section_manager.requests.edit', $req->id) }}" class="edit-btn">✏️ Edit</a>
                    </div>
                </div>
            </li>
            @endif
        @empty
            <li class="request-card empty-card">No approved requests found.</li>
        @endforelse
    </ul>
</div>
@endsection

@push('styles')
<style>
.dashboard-title { font-size:2rem; font-weight:700; text-align:center; margin-bottom:1.5rem; color:#065f46; }
.requests-list { display:flex; flex-direction:column; gap:1.2rem; }
.request-card { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border:1px solid rgba(6,95,70,0.2); border-radius:1rem; padding:1.5rem; box-shadow:0 6px 14px rgba(0,0,0,0.06); transition:transform .3s, box-shadow .3s; }
.request-card:hover { transform: translateY(-5px) scale(1.01); box-shadow:0 12px 24px rgba(0,0,0,0.12); }
.empty-card { text-align:center; color:#6b7280; font-style:italic; }
.request-header { font-size:1.1rem; font-weight:600; color:#065f46; margin-bottom:0.25rem; }
.request-vehicle { color:#374151; margin-bottom:0.6rem; }
.request-damage p { margin-top:0.3rem; color:#4b5563; }
.images-container { display:flex; flex-wrap:wrap; gap:0.6rem; margin-top:0.6rem; }
.request-img { width:110px; height:80px; object-fit:cover; border-radius:0.5rem; border:1px solid #ddd; cursor:pointer; transition:transform .25s, box-shadow .25s; }
.request-img:hover { transform:scale(1.05); box-shadow:0 10px 20px rgba(0,0,0,0.15); }
.no-images { margin-top:0.5rem; font-style:italic; color:#6b7280; }
.request-actions { margin-top:1rem; display:flex; justify-content:flex-end; }
.edit-btn { display:inline-block; background:#16a34a; color:#fff; font-size:0.9rem; font-weight:600; padding:0.5rem 1rem; border-radius:0.5rem; text-decoration:none; transition:background 0.25s, transform 0.25s; box-shadow:0 4px 10px rgba(22,163,74,0.3); }
.edit-btn:hover { background:#15803d; transform:translateY(-2px); box-shadow:0 6px 14px rgba(21,128,61,0.35); }
/* Lightbox Styles */
.lightbox { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,0.7); z-index:10000; animation:fadeIn .3s ease; }
.lightbox.active { display:flex; }
.lightbox img { max-width:90%; max-height:85%; border-radius:.75rem; box-shadow:0 20px 40px rgba(0,0,0,.5); animation:zoomIn .3s ease; }
.lightbox .close-btn { position:absolute; top:20px; right:30px; font-size:2rem; color:#fff; cursor:pointer; }
@keyframes fadeIn { from {opacity:0;} to {opacity:1;} }
@keyframes zoomIn { from {transform:scale(.85);} to {transform:scale(1);} }
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
