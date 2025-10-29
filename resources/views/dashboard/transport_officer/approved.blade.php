@extends('layouts.transportofficer')

@section('title', 'Approved Requests')
@section('page_title', 'Approved Requests')

@section('content')
<div class="container px-0">
    @if(session('error'))
        <div class="alert alert-danger"><i class="bi bi-exclamation-octagon me-2"></i>{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <ul class="list-unstyled d-flex flex-column gap-3 mb-5">
        @forelse($approvedRequests as $req)
            <li>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                            <div>
                                <div class="fw-semibold text-success mb-1">
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
                                <span class="badge text-bg-success"><i class="bi bi-check2-circle me-1"></i>Approved</span>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="mb-2">
                            <div class="fw-semibold mb-1">Damage Description</div>
                            <div class="text-muted">{{ $req->damage_description ?? 'No description provided' }}</div>
                        </div>

                        {{-- Images --}}
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
                            <div class="mt-3">
                                <div class="fw-semibold mb-2">Images</div>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($images as $img)
                                        @php $imgPath = str_replace('\\/', '/', trim($img)); @endphp
                                        <img src="{{ asset('storage/' . $imgPath) }}" alt="image-{{ $req->id }}" class="rounded border request-img" style="width:110px;height:80px;object-fit:cover;cursor:pointer" data-full="{{ asset('storage/' . $imgPath) }}" />
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-muted fst-italic">No images provided</div>
                        @endif

                        {{-- Actions --}}
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="{{ route('transport_officer.edit_request', $req->id) }}" class="btn btn-outline-secondary btn-elevated">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                            <button class="btn btn-primary btn-elevated" data-bs-toggle="collapse" data-bs-target="#receipt-form-{{ $req->id }}" aria-expanded="false">
                                <i class="bi bi-receipt me-1"></i> Generate Receipt
                            </button>
                        </div>

                        {{-- Receipt Form --}}
                        <div id="receipt-form-{{ $req->id }}" class="collapse mt-3">
                            <form action="{{ route('transport_officer.receipt.store') }}" method="POST" class="border rounded p-3">
                                @csrf
                                <input type="hidden" name="request_id" value="{{ $req->id }}">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label for="supplier_id-{{ $req->id }}" class="form-label">Supplier</label>
                                        <select name="supplier_id" id="supplier_id-{{ $req->id }}" class="form-select">
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }} - {{ $supplier->contact }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="description-{{ $req->id }}" class="form-label">Description</label>
                                        <input type="text" name="description" id="description-{{ $req->id }}" class="form-control" placeholder="Optional">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="amount-{{ $req->id }}" class="form-label">Amount</label>
                                        <input type="number" step="0.01" name="amount" id="amount-{{ $req->id }}" class="form-control" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send-check me-1"></i> Generate & Send
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center text-muted">
                        <i class="bi bi-inbox me-1"></i> No approved requests found.
                    </div>
                </div>
            </li>
        @endforelse
    </ul>
</div>
@endsection

@push('styles')
<style>
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

    @if(session('wa_link'))
    (function() {
        const wa = @json(session('wa_link'));
        const opened = window.open(wa, '_blank');
        if (!opened) {
            const wrap = document.createElement('div');
            wrap.style = 'position:fixed;right:18px;bottom:18px;background:#fff;padding:12px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.12);z-index:9999;';
            wrap.innerHTML = '<div style="font-weight:700;color:#065f46;margin-bottom:6px;">Open WhatsApp</div>' +
                             '<a href="'+wa+'" target="_blank" style="color:#065f46;text-decoration:none;font-weight:600;">Open WhatsApp Chat</a>';
            document.body.appendChild(wrap);
            setTimeout(() => { wrap.remove(); }, 15000);
        }
    })();
    @endif
});
</script>
@endpush
