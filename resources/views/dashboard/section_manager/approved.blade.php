@extends('layouts.app')

@section('title', 'Approved Requests')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="dashboard-title">Approved Requests</h2>
    <ul class="requests-list">
        @forelse($requests as $req)
            <li class="request-card">
                <div class="request-content">
                    <div class="request-info">
                        <div class="request-header">
                            <strong>Request:</strong> User: {{ $req->user->name }}
                        </div>
                        <div class="request-vehicle">
                            Vehicle: {{ $req->vehicle->plate_no ?? 'N/A' }} - Tire: {{ $req->tire->size ?? 'N/A' }}
                        </div>
                        <div class="request-damage">
                            <strong>Damage Description:</strong>
                            <p>{{ $req->damage_description ?? 'No description provided' }}</p>
                        </div>

                        @php
                            // Normalize tire images field to an array of paths
                            $images = [];
                            if (isset($req->tire_images)) {
                                if (is_array($req->tire_images)) {
                                    $images = $req->tire_images;
                                } elseif (is_string($req->tire_images) && trim($req->tire_images) !== '') {
                                    $decoded = json_decode($req->tire_images, true);
                                    if (is_array($decoded)) {
                                        $images = $decoded;
                                    } else {
                                        // try unescaping common escaped slashes and decode again
                                        $unescaped = str_replace('\\/', '/', $req->tire_images);
                                        $decoded2 = json_decode($unescaped, true);
                                        if (is_array($decoded2)) {
                                            $images = $decoded2;
                                        }
                                    }
                                }
                            }
                            // fallback to legacy `images` column if present
                            if (empty($images) && isset($req->images) && $req->images) {
                                $images = is_array($req->images) ? $req->images : array_map('trim', explode(',', $req->images));
                            }
                        @endphp

                        @if(count($images) > 0)
                            <div class="request-images">
                                <strong>Images:</strong>
                                <div class="images-container">
                                    @foreach($images as $img)
                                        @php $imgPath = str_replace('\\/', '/', $img); @endphp
                                        <a href="{{ asset('storage/' . $imgPath) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $imgPath) }}" alt="image-{{ $req->id }}" class="request-img"/>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="no-images"><em>No images provided</em></div>
                        @endif
                    </div>
                </div>
            </li>
        @empty
            <li class="request-card">No approved requests found.</li>
        @endforelse
    </ul>
</div>

@endsection
