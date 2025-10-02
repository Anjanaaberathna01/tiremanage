@extends('layouts.admin')

@section('title', 'Pending Requests')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="dashboard-title">Pending Requests</h2>

    <ul class="requests-list">
        @forelse($pendingRequests as $req)
            <li class="request-card">
                <div class="request-header" onclick="toggleDetails(this)">
                    <span>📌 Request from <strong>{{ $req->user->name }}</strong></span>
                    <button class="toggle-btn">▼</button>
                </div>

                <div class="request-details">
                    <div class="request-vehicle">
                        🚗 Vehicle: {{ $req->vehicle->plate_no ?? 'N/A' }} <br>
                        🏢 Branch: {{ $req->vehicle->branch ?? 'N/A' }} <br>
                        🛞 Tire: {{ $req->tire->size ?? 'N/A' }}
                    </div>

                    <div class="request-damage">
                        <strong>📝 Damage Description:</strong>
                        <p>{{ $req->damage_description ?? 'No description provided' }}</p>
                    </div>

                    @if($req->tire_images && is_array($req->tire_images))
                        <div class="request-images">
                            <strong>📷 Images:</strong>
                            <div class="images-container">
                                @foreach($req->tire_images as $img)
                                    <img src="{{ asset('storage/' . $img) }}"
                                         alt="image-{{ $req->id }}"
                                         class="request-img"
                                         onclick="openModal(this)">
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="no-images"><em>No images provided</em></div>
                    @endif
                </div>
            </li>
        @empty
            <p class="text-center text-gray-600">No pending requests found.</p>
        @endforelse
    </ul>
</div>

{{-- Image Preview Modal --}}
<div id="imgModal" class="modal" onclick="closeModal()">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

{{-- Styles --}}
<style>
.dashboard-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
    color: #1e3a8a;
}

/* List Layout */
.requests-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    list-style: none;
    padding: 0;
}

/* Request Card */
.request-card {
    background: linear-gradient(135deg, #f9fafb, #e0e7ff);
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}
.request-card:hover {
    transform: scale(1.02);
}

/* Header */
.request-header {
    background: #1e40af;
    color: #fff;
    padding: 1rem;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}
.toggle-btn {
    background: transparent;
    border: none;
    color: #fff;
    font-size: 1.2rem;
    transition: transform 0.3s;
}
.request-card.open .toggle-btn {
    transform: rotate(180deg);
}

/* Details Section */
.request-details {
    display: none;
    padding: 1rem;
    color: #374151;
}
.request-card.open .request-details {
    display: block;
}

/* Images */
.images-container {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-top: 0.5rem;
}
.request-img {
    width: 100px;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.2s;
}
.request-img:hover {
    transform: scale(1.1);
}
.no-images {
    font-style: italic;
    color: #6b7280;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 999;
    padding-top: 80px;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.9);
    text-align: center;
}
.modal-content {
    max-width: 80%;
    max-height: 80%;
    border-radius: 10px;
}
.close {
    position: absolute;
    top: 20px; right: 35px;
    font-size: 2rem;
    color: #fff;
    cursor: pointer;
}
</style>

{{-- JavaScript --}}
<script>
function toggleDetails(element) {
    element.parentElement.classList.toggle("open");
}

function openModal(img) {
    document.getElementById("imgModal").style.display = "block";
    document.getElementById("modalImage").src = img.src;
}
function closeModal() {
    document.getElementById("imgModal").style.display = "none";
}
</script>
@endsection
