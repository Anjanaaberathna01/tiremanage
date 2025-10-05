@extends('layouts.mechanic_officer')

@section('title', 'Pending Tire Requests')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="page-title">🔧 Pending Tire Requests</h2>

    @if($requests->isEmpty())
    <div class="empty-state">No pending requests from the Section Manager.</div>
    @else
    <ul class="requests-list">
        @foreach($requests as $req)
        <li class="request-card">
            <div class="request-content">
                <div class="request-info">
                    <strong>Driver:</strong> {{ $req->user->name ?? 'N/A' }}<br>
                    <strong>Vehicle:</strong> {{ $req->vehicle->plate_no ?? 'N/A' }}<br>
                    <strong>Tire Count:</strong> {{ $req->tire->size ?? 'N/A' }}<br>
                    <strong>Description:</strong> {{ $req->damage_description ?? 'No description' }}<br>

                    {{-- Images --}}
                    @php
                    $images = [];
                    if (!empty($req->tire_images)) {
                    $images = is_array($req->tire_images)
                    ? $req->tire_images
                    : json_decode($req->tire_images, true);
                    }
                    @endphp

                    @if(!empty($images))
                    <div class="request-images">
                        <strong>Images:</strong>
                        <div class="images-container">
                            @foreach($images as $img)
                            <img src="{{ asset('storage/' . $img) }}" class="request-img" alt="tire image">
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="request-actions">
                    <form action="{{ route('mechanic_officer.approve', $req->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-approve">Approve</button>
                    </form>
                    <form action="{{ route('mechanic_officer.reject', $req->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-reject">Reject</button>
                    </form>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
    @endif
</div>

<style>
.page-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1.5rem;
}

.empty-state {
    text-align: center;
    color: #6b7280;
    font-style: italic;
    margin-top: 2rem;
}

.requests-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.request-card {
    background: #f9fafb;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s;
}

.request-card:hover {
    transform: scale(1.02);
}

.request-content {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.request-info {
    flex: 1;
    color: #374151;
}

.request-images {
    margin-top: .75rem;
}

.images-container {
    display: flex;
    gap: .5rem;
    flex-wrap: wrap;
}

.request-img {
    width: 90px;
    height: 70px;
    object-fit: cover;
    border-radius: .5rem;
    border: 1px solid #d1d5db;
}

.request-actions {
    display: flex;
    flex-direction: column;
    gap: .5rem;
}

.btn {
    padding: .5rem 1rem;
    border: none;
    border-radius: .5rem;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: transform .2s;
}

.btn:hover {
    transform: scale(1.05);
}

.btn-approve {
    background-color: #16a34a;
}

.btn-reject {
    background-color: #dc2626;
}
</style>
@endsection
