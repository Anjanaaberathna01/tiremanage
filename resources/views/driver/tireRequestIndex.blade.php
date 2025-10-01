@extends('layouts.driver')

@section('content')
<div class="container">
    <h2 class="mb-4">My Tire Requests</h2>

    @if($requests->isEmpty())
        <div class="alert alert-info">You have not submitted any tire requests yet.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Vehicle</th>
                    <th>Tire Size</th>
                    <th>Description</th>
                    <th>Images</th>
                    <th>Status</th>
                    <th>Requested At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $index => $request)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $request->vehicle->plate_no ?? 'N/A' }}</td>
                        <td>{{ $request->tire->size ?? 'N/A' }}</td>
                        <td>{{ $request->damage_description }}</td>
                        <td>
                            @if($request->images && is_array(json_decode($request->images)))
                                @foreach(json_decode($request->images) as $image)
                                    <img src="{{ asset('storage/' . $image) }}"
                                         alt="Tire Image"
                                         width="70"
                                         class="img-thumbnail mb-1">
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
