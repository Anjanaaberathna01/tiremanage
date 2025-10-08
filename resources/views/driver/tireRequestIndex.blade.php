@extends('layouts.driver')

@section('title', 'My Tyre Requests')

@section('content')
<style>
/* Basic Page Styling */
body {
    background-color: #f2f2f2;
    font-family: Arial, sans-serif;
}

h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

/* Table Styling */
.table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    margin-bottom: 20px;
    border-radius: 8px;
    overflow: hidden;
}

.table th, .table td {
    border: 1px solid #ccc;
    padding: 10px;
    text-align: center;
}

.table th {
    background-color: #333;
    color: white;
}

.table tr:hover {
    background-color: #e6f0ff;
}

/* Image Thumbnail */
.img-thumbnail {
    width: 70px;
    border-radius: 4px;
    transition: transform 0.2s;
}

.img-thumbnail:hover {
    transform: scale(1.2);
}

/* Status Badges */
.badge {
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: bold;
    display: inline-block;
}

.bg-warning { background-color: #facc15; color: #000; }
.bg-success { background-color: #16a34a; color: #fff; }
.bg-danger { background-color: #dc2626; color: #fff; }
.bg-secondary { background-color: #6b7280; color: #fff; }

/* Delete Button */
.btn-danger {
    background-color: #dc3545;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 12px;
    cursor: pointer;
}

.btn-danger:hover {
    background-color: #b91c1c;
}

/* No Requests Message */
.alert-info {
    background-color: #e6f0ff;
    padding: 15px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
}
</style>

<div class="container">
    <h2>🚗 My Tyre Requests</h2>

    @if($requests->isEmpty())
        <div class="alert-info">You have not submitted any tyre requests yet.</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Branch</th>
                    <th>Vehicle</th>
                    <th>Tire Size</th>
                            <th>Tyre Size</th>
                            <th>Tyre Count</th>
                    <th>Images</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $index => $request)
                <tr>
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
                                    <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail">
                                </a>
                            @endforeach
                        @else
                            <span>No Images</span>
                                        <td>{{ $request->tire->size ?? 'N/A' }}</td>
                                        <td>{{ $request->tire_count ?? 1 }}</td>
                    <td>
                        @if($request->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
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
                                <button type="submit" class="btn-danger">Delete</button>
                            </form>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script>
// Simple Delete Confirmation
function confirmDelete(form) {
    if (confirm("Are you sure you want to delete this request?")) {
        return true;
    } else {
        return false;
    }
}
</script>
@endsection
