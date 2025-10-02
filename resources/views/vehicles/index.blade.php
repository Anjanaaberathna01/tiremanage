@extends('layouts.admin')

@section('content')
<div class="container">
   {{-- Vehicles --}}
<h3>Vehicles</h3>
<a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary mb-2">Add Vehicle</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Model</th>
            <th>Registration Number</th>
            <th>Branch</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vehicles as $vehicle)
        <tr>
            <td>{{ $vehicle->model }}</td>
            <td>{{ $vehicle->plate_no }}</td>
            <td>{{ $vehicle->branch }}</td>
            <td>
                <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
@endsection
