@extends('layouts.app')

@section('title', 'Mechanic Officer Dashboard')

@section('content')
<h2>Mechanic Officer Dashboard</h2>
<ul>
@foreach($vehicles as $vehicle)
    <li>Vehicle #{{ $vehicle->id }}</li>
@endforeach
</ul>
<ul>
@foreach($tires as $tire)
    <li>Tire #{{ $tire->id }}</li>
@endforeach
</ul>
@endsection
