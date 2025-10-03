@extends('layouts.section_manager')

@section('title', 'Section Manager Dashboard')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="dashboard-title">Section Manager Dashboard</h2>

{{-- Search Bar (goes to search page) --}}
<form action="{{ route('section_manager.requests.search') }}" method="GET" class="mb-6 flex justify-center">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Search by Driver Name..."
        class="w-1/2 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
    <button type="submit"
        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-r-lg hover:bg-blue-700 transition">
        Search
    </button>
</form>

{{-- Add Driver Button --}}
<a href="{{ Auth::user()->hasRole(['admin']) ? route('admin.drivers.create') : route('section_manager.drivers.create') }}"
   class="btn btn-primary">
   ➕ Add Driver
</a>




{{-- Requests List --}}
<ul class="requests-list">
    @foreach($pendingRequests as $req)
        <li class="request-card">
            <div class="request-content">
                <div class="request-info">
                    <div class="request-header">
                        <strong>Request:</strong> User: {{ $req->user->name }}
                    </div>
                    <div class="request-vehicle">
                        Vehicle: {{ $req->vehicle->plate_no ?? 'N/A' }}<br>
                        Branch: {{ $req->vehicle->branch ?? 'N/A' }}<br>
                        Tire: {{ $req->tire->size ?? 'N/A' }}
                    </div>

                    {{-- Status --}}
                    <div class="request-status">
                        <strong>Status:</strong>
                        <span class="text-yellow-600">Pending</span>
                    </div>

                    <div class="request-damage">
                        <strong>Damage Description:</strong>
                        <p>{{ $req->damage_description ?? 'No description provided' }}</p>
                    </div>

                    {{-- Tire Images --}}
                    @if($req->tire_images && is_array($req->tire_images))
                        <div class="request-images">
                            <strong>Images:</strong>
                            <div class="images-container">
                                @foreach($req->tire_images as $img)
                                    <a href="{{ asset('storage/' . $img) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $img) }}" alt="image-{{ $req->id }}" class="request-img"/>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="no-images"><em>No images provided</em></div>
                    @endif
                </div>

                {{-- Actions for pending --}}
                <div class="request-actions">
                    <form action="{{ route('section_manager.requests.approve', $req->id) }}" method="POST" class="action-form">
                        @csrf
                        <button type="submit" class="btn btn-approve">Approve</button>
                    </form>
                    <form action="{{ route('section_manager.requests.reject', $req->id) }}" method="POST" class="action-form">
                        @csrf
                        <button type="submit" class="btn btn-reject">Reject</button>
                    </form>
                </div>
            </div>
        </li>
    @endforeach
</ul>

{{-- Empty State --}}
@if($pendingRequests->isEmpty())
    <div class="text-center text-gray-500 italic mt-6">
        No pending requests found
    </div>
@endif

</div>

<style>
/* Dashboard Title */
.dashboard-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #1e40af;
    text-align: center;
}

/* Requests List */
.requests-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Request Card */
.request-card {
    background: linear-gradient(145deg, #ffffff, #f0f4ff);
    padding: 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}
.request-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
}

/* Request Content Flex */
.request-content {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Info Section */
.request-info {
    flex: 1;
}

/* Headers & Text */
.request-header {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}
.request-vehicle {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    color: #374151;
}
.request-damage p {
    margin-top: 0.25rem;
    color: #4b5563;
}

/* Status colors */
.request-status span { font-weight: 600; }
.text-green-600 { color: #16a34a; }
.text-red-600 { color: #dc2626; }
.text-yellow-600 { color: #d97706; }

/* Images */
.request-images {
    margin-top: 0.75rem;
}
.images-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}
.request-img {
    width: 100px;
    border-radius: 0.5rem;
    border: 1px solid #d1d5db;
    transition: transform 0.2s;
}
.request-img:hover {
    transform: scale(1.05);
}

/* No images text */
.no-images {
    margin-top: 0.5rem;
    color: #6b7280;
    font-style: italic;
}

/* Actions */
.request-actions {
    min-width: 140px;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

/* Buttons */
.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    font-weight: 600;
    color: white;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}
.btn-approve { background: #16a34a; }
.btn-approve:hover { background: #15803d; transform: scale(1.05); }
.btn-reject { background: #dc2626; }
.btn-reject:hover { background: #b91c1c; transform: scale(1.05); }

/* Responsive adjustments */
@media (max-width: 768px) {
    .request-content { flex-direction: column; }
    .request-actions { flex-direction: row; justify-content: space-between; min-width: 100%; }
}
</style>
@endsection
