@extends('layouts.app')

@section('title', 'Section Manager Dashboard')

@section('content')
<h2>Section Manager Dashboard</h2>
<ul>
@foreach($pendingRequests as $req)
    <li style="margin-bottom:18px; padding:12px; border:1px solid #e5e7eb; border-radius:8px; background:#fff">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px;">
            <div style="flex:1">
                <div><strong>Request #{{ $req->id }}</strong> - User: {{ $req->user->name }}</div>
                <div>Vehicle: {{ $req->vehicle->plate_no ?? 'N/A' }} - Tire: {{ $req->tire->size ?? 'N/A' }}</div>
                <div style="margin-top:8px"><strong>Damage Description:</strong>
                    <p style="margin:6px 0 0 0">{{ $req->damage_description ?? 'No description provided' }}</p>
                </div>

                @if($req->tire_images && is_array($req->tire_images))
                    <div style="margin-top:8px">
                        <strong>Images:</strong>
                        <div style="display:flex; gap:8px; margin-top:6px; flex-wrap:wrap">
                            @foreach($req->tire_images as $img)
                                <a href="{{ asset('storage/' . $img) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $img) }}" alt="image-{{ $req->id }}" width="120" style="border-radius:6px; border:1px solid #ddd"/>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div style="margin-top:8px"><em>No images provided</em></div>
                @endif
            </div>

            <div style="min-width:140px">
                <form action="{{ route('section_manager.requests.approve', $req->id) }}" method="POST" style="display:block; margin-bottom:8px">
                    @csrf
                    <button type="submit" class="btn btn-success w-full">Approve</button>
                </form>
                <form action="{{ route('section_manager.requests.reject', $req->id) }}" method="POST" style="display:block">
                    @csrf
                    <button type="submit" class="btn btn-danger w-full">Reject</button>
                </form>
            </div>
        </div>
    </li>
@endforeach
</ul>
@endsection
