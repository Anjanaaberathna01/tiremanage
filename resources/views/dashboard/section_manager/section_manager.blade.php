@extends('layouts.section_manager')

@section('title', 'Pending Requests')

@section('content')
<div class="container mx-auto p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-hourglass-split me-2"></i> Pending Requests</h3>
    <form id="driver-search-form" action="{{ route('section_manager.requests.search') }}" method="GET" class="m-0">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
        <input type="text" name="search" id="search-input" value="{{ request('search') }}" class="form-control" placeholder="Search by driver name">
        @if(request('search'))
          <a href="{{ route('section_manager.dashboard') }}" class="btn btn-outline-secondary">Reset</a>
        @endif
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
  </div>

  <ul class="requests-list list-unstyled">
    @forelse($pendingRequests as $req)
      <li class="request-card card shadow-sm">
        <div class="request-content p-3">
          <div class="request-info">
            <div class="request-header d-flex justify-content-between align-items-center">
              <div class="fw-semibold">
                <i class="bi bi-person-circle me-1"></i>{{ $req->user->name ?? 'N/A' }}
              </div>
              <div class="small text-muted">
                <i class="bi bi-calendar3 me-1"></i>{{ optional($req->created_at)->format('Y-m-d H:i') ?? '-' }}
              </div>
            </div>

            <div class="request-vehicle mb-2">
              <span class="badge text-bg-light border fw-semibold"><i class="bi bi-truck me-1"></i>{{ $req->vehicle->plate_no ?? 'N/A' }}</span>
              <span class="badge bg-secondary-subtle text-secondary border"><i class="bi bi-geo-alt me-1"></i>{{ $req->vehicle->branch ?? 'N/A' }}</span>
              <span class="badge bg-info-subtle text-info border"><i class="bi bi-record2 me-1"></i>{{ $req->tire->brand ?? 'N/A' }}</span>
              <span class="badge bg-dark-subtle text-dark border"><i class="bi bi-aspect-ratio me-1"></i>{{ $req->tire->size ?? 'N/A' }}</span>
              <span class="badge bg-primary-subtle text-primary border"><i class="bi bi-123 me-1"></i>{{ $req->tire_count ?? 'N/A' }}</span>
            </div>

            <div class="request-damage">
              <strong>Damage Description:</strong>
              <p class="mb-2">{{ $req->damage_description ?? 'No description provided' }}</p>
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
            @endphp

            @if(count($images) > 0)
              <div class="request-images mt-2">
                <strong>Images:</strong>
                <div class="images-container">
                  @foreach($images as $img)
                    <img src="{{ asset('storage/' . $img) }}" alt="image-{{ $req->id }}" class="request-img" data-full="{{ asset('storage/' . $img) }}" />
                  @endforeach
                </div>
              </div>
            @else
              <div class="no-images"><em>No images provided</em></div>
            @endif
          </div>

          <div class="request-actions">
            <form action="{{ route('section_manager.requests.approve', $req->id) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-outline-success btn-sm btn-icon" data-bs-toggle="tooltip" title="Approve">
                <i class="bi bi-check2-circle"></i> <span class="d-none d-sm-inline">Approve</span>
              </button>
            </form>
            <form action="{{ route('section_manager.requests.reject', $req->id) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-outline-danger btn-sm btn-icon" data-bs-toggle="tooltip" title="Reject">
                <i class="bi bi-x-circle"></i> <span class="d-none d-sm-inline">Reject</span>
              </button>
            </form>
          </div>
        </div>
      </li>
    @empty
      <li class="request-card empty-card">No pending requests found.</li>
    @endforelse
  </ul>
</div>
@endsection

@push('styles')
<style>
.requests-list { display:flex; flex-direction:column; gap:1rem; }
.request-card { border:1px solid #e5e7eb; border-left:4px solid #0d6efd; border-radius:.75rem; background:#fff; }
.request-card:hover { transform:translateY(-2px); box-shadow:0 12px 18px rgba(0,0,0,.08) !important; }
.request-header { font-weight:600; color:#111827; font-size:1.05rem; }
.request-vehicle, .request-damage p { color:#374151; }
.images-container { display:flex; flex-wrap:wrap; gap:0.5rem; margin-top:0.5rem; }
.request-img { width:100px; height:70px; object-fit:cover; border-radius:6px; border:1px solid #ccc; cursor:pointer; transition:transform .25s, box-shadow .25s; }
.request-img:hover { transform:scale(1.05); box-shadow:0 6px 15px rgba(0,0,0,0.15); }
.no-images { font-style:italic; color:#6b7280; margin-top:5px; }
.request-actions { margin-top:10px; display:flex; gap:8px; }
.empty-card { text-align:center; color:#6b7280; font-style:italic; padding:1rem; }
/* helpers for badges */
.bg-primary-subtle { background: rgba(13,110,253,.10) !important; }
.bg-secondary-subtle { background: rgba(108,117,125,.12) !important; }
.bg-info-subtle { background: rgba(13,202,240,.12) !important; }
.bg-dark-subtle { background: rgba(33,37,41,.10) !important; }
/* Lightbox */
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
  const close = () => { lightbox.classList.remove('active'); imgEl.src=''; };
  closeBtn.addEventListener('click', close);
  lightbox.addEventListener('click', (e) => { if (e.target === lightbox) close(); });

  // Enable tooltips
  if (window.bootstrap && bootstrap.Tooltip) {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
  }
});
</script>
@endpush

