@extends('layouts.driver')

@section('title', 'My Tire Requests')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">🚗 My Tire Requests</h2>

    @if($requests->isEmpty())
        <div class="alert alert-info text-center">You have not submitted any tire requests yet.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Branch</th>
                        <th>Vehicle</th>
                        <th>Tire Size</th>
                        <th>Description</th>
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
                                    No Images
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

                            {{-- Delete button for each request --}}
                            <td>
                                @if($request->status == 'pending')
                                    <form action="{{ route('driver.requests.destroy', $request->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
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
@endsection
