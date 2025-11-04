@extends('layouts.section_manager')

@section('title', 'Rejected Requests')

@section('content')
<div class="container mx-auto p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="bi bi-x-circle me-2"></i> Rejected Requests</h3>
    <div class="w-auto">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
        <input type="text" id="rejectedSearch" class="form-control" placeholder="Search by driver name">
      </div>
    </div>
  </div>

  <ul class="requests-list list-unstyled" id="rejectedList">
    @forelse($requests as $req)
      @if($req->status === \App\Models\Approval::STATUS_REJECTED)
      <li class="request-card card shadow-sm">
        <div class="request-content p-3">
          <div class="request-info">
            <div class="request-header d-flex justify-content-between align-items-center">
              <div class="fw-semibold">
                <i class="bi bi-person-circle me-1"></i><span class="driver-name">{{ $req->user->name ?? 'N/A' }}</span>
              </div>
              <div class="small text-muted">
                <i class="bi bi-calendar3 me-1"></i>{{ optional($req->updated_at)->format('Y-m-d H:i') ?? '-' }}
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
              if (isset($req->tire_images)) {
                $decoded = is_array($req->tire_images) ? $req->tire_images : json_decode(str_replace('\\/', '/', $req->tire_images), true);
                if (is_array($decoded)) $images = $decoded;
              }
              if (empty($images) && isset($req->images) && $req->images) {
                $images = is_array($req->images) ? $req->images : array_map('trim', explode(',', $req->images));
              }
            @endphp

            @if(count($images) > 0)
              <div class="request-images mt-2">
                <strong>Images:</strong>
                <div class="images-container">
                  @foreach($images as $img)
                    @php $imgPath = str_replace('\\/', '/', trim($img)); @endphp
                    <img src="{{ asset('storage/' . $imgPath) }}" alt="image-{{ $req->id }}" class="request-img" data-full="{{ asset('storage/' . $imgPath) }}"/>
                  @endforeach
                </div>
              </div>
            @else
              <div class="no-images"><em>No images provided</em></div>
            @endif
          </div>

          <div class="request-actions">
            <a href="{{ route('section_manager.requests.edit', $req->id) }}" class="btn btn-outline-primary btn-sm btn-icon" data-bs-toggle="tooltip" title="Edit">
              <i class="bi bi-pencil"></i>
              <span class="d-none d-sm-inline">Edit</span>
            </a>
          </div>
        </div>
      </li>
      @endif
    @empty
      <li class="request-card empty-card">No rejected requests found.</li>
    @endforelse
  </ul>
</div>
@endsection

@push('styles')
<style>
.requests-list { display:flex; flex-direction:column; gap:1rem; }
.request-card { border:1px solid #e5e7eb; border-left:4px solid #dc3545; border-radius:.75rem; background:#fff; }
.request-card:hover { transform:translateY(-2px); box-shadow:0 12px 18px rgba(0,0,0,.08) !important; }
.request-header { font-size:1.05rem; font-weight:600; color:#991b1b; }
.request-vehicle { color:#374151; margin-bottom:.5rem; }
.request-damage p { margin-top:.3rem; color:#4b5563; }
.images-container { display:flex; flex-wrap:wrap; gap:0.6rem; margin-top:0.6rem; }
.request-img { width:110px; height:80px; object-fit:cover; border-radius:0.5rem; border:1px solid #ddd; cursor:pointer; transition:transform .25s, box-shadow .25s; }
.request-img:hover { transform:scale(1.05); box-shadow:0 10px 20px rgba(0,0,0,0.15); }
.no-images { margin-top:0.5rem; font-style:italic; color:#6b7280; }
.request-actions { margin-top:.75rem; display:flex; justify-content:flex-end; }
.bg-primary-subtle { background: rgba(13,110,253,.10) !important; }
.bg-secondary-subtle { background: rgba(108,117,125,.12) !important; }
.bg-info-subtle { background: rgba(13,202,240,.12) !important; }
.bg-dark-subtle { background: rgba(33,37,41,.10) !important; }
.text-bg-light { background-color: #f8f9fa !important; }
.lightbox { position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,0.7); z-index:10000; }
.lightbox.active { display:flex; }
.lightbox img { max-width:90%; max-height:85%; border-radius:.75rem; box-shadow:0 20px 40px rgba(0,0,0,.5); }
.lightbox .close-btn { position:absolute; top:20px; right:30px; font-size:2rem; color:#fff; cursor:pointer; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Client-side filter
  const input = document.getElementById('rejectedSearch');
  if (input) {
    input.addEventListener('input', function(){
      const q = this.value.toLowerCase();
      document.querySelectorAll('#rejectedList .request-card').forEach(card => {
        const nameEl = card.querySelector('.driver-name');
        const name = nameEl ? nameEl.textContent.toLowerCase() : '';
        card.style.display = (q === '' || name.includes(q)) ? '' : 'none';
      });
    });
  }

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
});
</script>
@endpush

