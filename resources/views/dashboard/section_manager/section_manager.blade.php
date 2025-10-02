@extends('layouts.app')

@section('title', 'Section Manager Dashboard')

@section('content')
<h2>Section Manager Dashboard</h2>
<ul>
@foreach($pendingRequests as $req)
    <li>Request #{{ $req->id }} - User: {{ $req->user->name }}</li>
@endforeach
</ul>
@endsection
