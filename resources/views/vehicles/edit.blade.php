@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Vehicle</h1>

    <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Model</label>
            <input type="text" name="model" class="form-control" value="{{ $vehicle->model }}" required>
        </div>
        <div class="mb-3">
            <label>Registration Number</label>
            <input type="text" name="plate_no" class="form-control" value="{{ $vehicle->plate_no }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
