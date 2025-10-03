@extends('layouts.section_manager')

@section('title', 'Section Manager Dashboard')

@section('content')
<div class="container mx-auto p-6">

    {{-- Toolbar: Search + Add Driver --}}
    <div class="toolbar">
        {{-- Search Bar --}}
        <form id="driver-search-form" action="{{ route('section_manager.requests.search') }}" method="GET">
            <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search by Driver Name..." />
            <button type="submit" id="search-button">Search</button>
        </form>

        {{-- Add Driver Button --}}
        <a href="{{ Auth::user()->hasRole(['admin']) ? route('admin.drivers.create') : route('section_manager.drivers.create') }}"
           class="add-driver-btn">
           ➕ Add Driver
        </a>
    </div>

    {{-- Requests List --}}
    <ul class="requests-list">
        @foreach($pendingRequests as $req)
            <li class="request-card">
                <div class="request-content">
                    {{-- Info Section --}}
                    <div class="request-info">
                        <div class="request-header">
                            <strong>Request:</strong> User: {{ $req->user->name }}
                        </div>
                        <div class="request-vehicle">
                            Vehicle: {{ $req->vehicle->plate_no ?? 'N/A' }}<br>
                            Branch: {{ $req->vehicle->branch ?? 'N/A' }}<br>
                            Tire: {{ $req->tire->size ?? 'N/A' }}
                        </div>

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

                    {{-- Actions Section --}}
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
/* Toolbar: search bar + button side by side */
.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    gap: 1rem;
}

/* Search Form */
#driver-search-form {
    display: flex;
    flex: 1;
    max-width: 500px;
}

/* Input field */
#search-input {
    flex: 1;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-right: none;
    border-radius: 5px 0 0 5px;
    font-size: 16px;
    outline: none;
    transition: all 0.3s ease;
}
#search-input:focus {
    border-color: #007BFF;
    box-shadow: 0 0 5px rgba(0,123,255,0.5);
}

/* Search button */
#search-button {
    padding: 10px 16px;
    border: 1px solid #007BFF;
    background-color: #007BFF;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    transition: all 0.3s ease;
}
#search-button:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

/* Add Driver button */
.add-driver-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #1d4ed8; /* Tailwind blue-700 */
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s, transform 0.2s;
}
.add-driver-btn:hover {
    background-color: #2563eb;
    transform: scale(1.05);
}

/* Responsive: stack vertically */
@media (max-width: 768px) {
    .toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    .add-driver-btn {
        width: 100%;
        text-align: center;
    }
    #driver-search-form {
        max-width: 100%;
    }
}

/* ---- Requests List + Card Styles (untouched) ---- */
.requests-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.request-card {
    background: linear-gradient(145deg, #ffffff, #f0f4ff);
    padding: 1.5rem;
    margin-top: 10px;
    border-radius: 1rem;
    box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}
.request-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
}
.request-content {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.request-info { flex: 1; }
.request-header { font-weight: 600; font-size: 1.1rem; margin-bottom: 0.25rem; }
.request-vehicle { font-size: 1rem; margin-bottom: 0.5rem; color: #374151; }
.request-damage p { margin-top: 0.25rem; color: #4b5563; }
.request-status span { font-weight: 600; }
.text-green-600 { color: #16a34a; }
.text-red-600 { color: #dc2626; }
.text-yellow-600 { color: #d97706; }
.request-images { margin-top: 0.75rem; }
.images-container { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem; }
.request-img { width: 100px; border-radius: 0.5rem; border: 1px solid #d1d5db; transition: transform 0.2s; }
.request-img:hover { transform: scale(1.05); }
.no-images { margin-top: 0.5rem; color: #6b7280; font-style: italic; }
.request-actions {
    min-width: 140px;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.btn { padding: 0.5rem 1rem; border-radius: 0.75rem; font-weight: 600; color: white; font-size: 0.95rem; transition: all 0.3s ease; border: none; cursor: pointer; }
.btn-approve { background: #16a34a; }
.btn-approve:hover { background: #15803d; transform: scale(1.05); }
.btn-reject { background: #dc2626; }
.btn-reject:hover { background: #b91c1c; transform: scale(1.05); }
@media (max-width: 768px) {
    .request-content { flex-direction: column; }
    .request-actions { flex-direction: row; justify-content: space-between; min-width: 100%; }
    .dashboard-title { text-align: center; }
}
</style>

<script>
document.getElementById('search-input').addEventListener('keypress', function(e){
    if(e.key === 'Enter'){
        e.preventDefault();
        document.getElementById('driver-search-form').submit();
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const addDriverBtn = document.querySelector('.add-driver-btn');
    addDriverBtn.addEventListener('click', function (e) {
        if (!confirm('Are you sure you want to add a new driver?')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
