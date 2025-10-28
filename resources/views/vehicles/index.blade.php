@extends(($layout ?? null) === 'admin' ? 'layouts.admin' : 'layouts.section_manager')

@section('title', 'Vehicles List')

@section('content')
<h3 class="mb-3">Vehicles</h3>

{{-- Search Vehicle --}}
@php($isAdmin = (($layout ?? null) === 'admin'))
<form action="{{ route($isAdmin ? 'admin.vehicles.index' : 'section_manager.vehicles.index') }}" method="GET" class="mb-3 d-flex gap-2">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by Plate Number...">
    <button class="btn btn-primary">Search</button>
</form>

{{-- Add Vehicle --}}
<a href="{{ route($isAdmin ? 'admin.vehicles.create' : 'section_manager.vehicles.create') }}" class="btn btn-primary btn-elevated mb-3"><i class="bi bi-plus-lg"></i> Add Vehicle</a>

<table class="table text-center align-middle">
    <thead>
        <tr>
            <th style="width: 10%;">No</th>
            <th style="width: 25%;">Model</th>
            <th style="width: 25%;">Plate Number</th>
            <th style="width: 15%;">Branch</th>
            <th style="width: 15%;">Vehicle Type</th>
            <th style="width: 15%;">Brand</th>
            <th style="width: 15%;">User Section</th>
            <th style="width: 15%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($vehicles as $vehicle)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $vehicle->model }}</td>
            <td>{{ $vehicle->plate_no }}</td>
            <td>{{ $vehicle->branch }}</td>
            <td>{{ $vehicle->vehicle_type }}</td>
            <td>{{ $vehicle->brand }}</td>
            <td>{{ $vehicle->user_section }}</td>
            <td>
                @if ($isAdmin)
                    <div class="action-buttons">
                        <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-outline-primary btn-icon btn-sm" data-bs-toggle="tooltip" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-icon btn-sm" data-bs-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure to delete this vehicle?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('section_manager.vehicles.edit', $vehicle->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                    <form action="{{ route('section_manager.vehicles.destroy', $vehicle->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete this vehicle?')"><i class="bi bi-trash"></i> Delete</button>
                    </form>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8">No vehicles found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
