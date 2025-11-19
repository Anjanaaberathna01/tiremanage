@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Vehicle</h1>

    <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Model</label>
            <input type="text" name="model" class="form-control" value="{{ $vehicle->model }}" required pattern="^[^0-9]*$" title="Model must not contain numbers">
        </div>
        <div class="mb-3">
            <label>Registration Number</label>
            <input type="text" name="plate_no" class="form-control" value="{{ $vehicle->plate_no }}" required>
        </div>
        <div class="mb-3">
            <label>Branch</label>
            <input type="text" name="branch" class="form-control" value="{{ old('branch', $vehicle->branch) }}" required pattern="^[^0-9]*$" title="Branch must not contain numbers">
        </div>
        <div class="mb-3">
            <label>Vehicle Type</label>
            <input type="text" name="vehicle_type" class="form-control" value="{{ old('vehicle_type', $vehicle->vehicle_type) }}" pattern="^[^0-9]*$" title="Vehicle type must not contain numbers">
        </div>
        <div class="mb-3">
            <label>Brand</label>
            <input type="text" name="brand" class="form-control" value="{{ old('brand', $vehicle->brand) }}" pattern="^[^0-9]*$" title="Brand must not contain numbers">
        </div>
        <div class="mb-3">
            <label>User Section</label>
            <input type="text" name="user_section" class="form-control" value="{{ old('user_section', $vehicle->user_section) }}" pattern="^[^0-9]*$" title="User section must not contain numbers">
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
