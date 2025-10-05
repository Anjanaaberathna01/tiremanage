@extends('layouts.driver')

@section('title', 'My Tyre Requests')

@section('content')
<style>
    /* 🌈 Page Styling */
    body {
        background: linear-gradient(135deg, #e3f2fd, #f8f9fa);
        font-family: "Poppins", sans-serif;
    }

    h2 {
        color: #0d6efd;
        font-weight: 700;
        text-shadow: 1px 1px 2px rgba(13, 110, 253, 0.2);
    }

    /* 🌟 Table Styling */
    .table {
        border-radius: 12px;
        overflow: hidden;
        background-color: white;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    .table thead {
        background: linear-gradient(90deg, #0d6efd, #6610f2);
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .table tbody tr {
        transition: all 0.3s ease-in-out;
    }

    .table tbody tr:hover {
        background-color: #e9f5ff;
        transform: scale(1.01);
    }

    /* 🖼️ Image Styling */
    .img-thumbnail {
        border-radius: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .img-thumbnail:hover {
        transform: scale(1.2);
        box-shadow: 0 4px 20px rgba(13, 110, 253, 0.3);
    }

    /* 🟢 Badges */
    .badge {
        padding: 8px 12px;
        font-size: 0.85rem;
        border-radius: 20px;
        text-transform: capitalize;
    }

    /* 🗑️ Delete Button */
    .btn-danger {
        border-radius: 20px;
        background: linear-gradient(90deg, #dc3545, #c82333);
        border: none;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-danger:hover {
        background: linear-gradient(90deg, #c82333, #dc3545);
        transform: scale(1.05);
    }

    /* 💡 No Requests Message */
    .alert-info {
        background: #e3f2fd;
        color: #0d6efd;
        border-radius: 10px;
        font-weight: 500;
    }

    /* 🎬 Row Fade-In Animation */
    .fade-in {
        opacity: 0;
        transform: translateY(10px);
        animation: fadeInRow 0.6s forwards;
    }

    @keyframes fadeInRow {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container my-5">
    <h2 class="mb-4 text-center">🚗 My Tyre Requests</h2>

    @if($requests->isEmpty())
        <div class="alert alert-info text-center">You have not submitted any tyre requests yet.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Branch</th>
                        <th>Vehicle</th>
                        <th>Tire Size</th>
                        <th>Description</th>
                        <th>Tire Count</th>
                        <th>Images</th>
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $index => $request)
                        <tr class="fade-in" style="animation-delay: {{ $index * 0.1 }}s;">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $request->branchName() ?? 'N/A' }}</td>
                            <td>{{ $request->vehicle->plate_no ?? 'N/A' }}</td>
                            <td>{{ $request->tire->size ?? 'N/A' }}</td>
                            <td>{{ $request->damage_description }}</td>
                            <td>{{ $request->tire_count ?? 1 }}</td>

                            <td>
                                @php
                                    $images = [];
                                    if(!empty($request->tire_images) && is_array($request->tire_images)) {
                                        $images = $request->tire_images;
                                    } elseif(!empty($request->images)) {
                                        $decoded = json_decode($request->images);
                                        if(is_array($decoded)) $images = $decoded;
                                    }
                                @endphp

                                @if(count($images) > 0)
                                    @foreach($images as $image)
                                        <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $image) }}" width="70" class="img-thumbnail mb-1">
                                        </a>
                                    @endforeach
                                @else
                                    <span class="text-muted">No Images</span>
                                @endif
                            </td>

                            <td>
                                @if($request->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($request->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($request->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($request->status) }}</span>
                                @endif
                            </td>

                            <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>

                            <td>
                                @if($request->status == 'pending')
                                    <form action="{{ route('driver.requests.destroy', $request->id) }}" method="POST" onsubmit="return confirmDelete(this);">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{--JavaScript Interactivity --}}
<script>
    // Animate rows as they appear
    document.addEventListener("DOMContentLoaded", () => {
        const rows = document.querySelectorAll(".fade-in");
        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.1}s`;
        });
    });

    // Sweet-like confirm (simple animation)
    function confirmDelete(form) {
        if (confirm("🗑️ Are you sure you want to delete this request?")) {
            form.submit();
        } else {
            const row = form.closest("tr");
            row.style.backgroundColor = "#f8d7da";
            setTimeout(() => (row.style.backgroundColor = ""), 500);
            return false;
        }
    }
</script>
@endsection
