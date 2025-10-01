@extends('layouts.app')

@section('title', 'Transport Officer Dashboard')

@section('content')
<h2>Transport Officer Dashboard</h2>
<ul>
@foreach($approvals as $req)
    <li>Request #{{ $req->id }} - Status: {{ $req->status }}</li>
@endforeach
</ul>
@endsection
