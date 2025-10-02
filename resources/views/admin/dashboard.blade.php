@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<style>
    /* Background setup */
    body {
       background:
      linear-gradient(135deg, rgba(11,11,11,0.85), rgba(179,190,209,0.85)),
      url("{{ asset('assets/images/background2.jpg') }}") no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Card Styles */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s ease;
    }

    /* Custom card color */
    .card-custom {
    background-color: #476EAE !important;
    color: #fff !important;
    }

    .card:hover {
        transform: translateY(-5px);
    }
    .hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px) scale(1.03);
    box-shadow: 0 6px 15px rgba(0,0,0,0.25);
}


    /* Table Styling */
    .table {
        border-radius: 10px;
        overflow: hidden;
    }

    .table th {
        background: #476EAE; /* Navy Blue */
        color: #fff;
        text-align: center;
    }

    .table td {
        vertical-align: middle;
        background-color: #CBDCEB
    }

    /* Button Styling */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 6px 14px;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: #3949ab;
        border: none;
    }

    .btn-primary:hover {
        background-color: #283593;
    }

    .btn-success {
        background-color: #3949ab;
        border: none;
    }

    .btn-success:hover {
        background-color: #283593;
    }

    .btn-info {
        background-color: #3949ab;
        color: #fff;
        border: none;
    }

    .btn-info:hover {
        background-color: #283593;
        color: #fff;
    }

    .btn-warning {
        background-color: #fbc02d;
        border: none;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #f9a825;
    }

    .btn-danger {
        background-color: #e53935;
        border: none;
    }

    .btn-danger:hover {
        background-color: #b71c1c;
    }

    /* Responsive layout */
    @media (max-width: 768px) {
        .card h3 {
            font-size: 1.5rem;
        }

        .table th, .table td {
            font-size: 0.9rem;
            padding: 8px;
        }

        .btn {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
    }
</style>

<h2 class="mb-4 text-white">Admin Dashboard</h2>

{{-- Stats --}}
<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3">
        <div class="card card-custom text-center p-3">
            <h5>Total Vehicles</h5>
            <h3>{{ $vehicles_count }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card card-custom text-center p-3">
            <h5>Total Tires</h5>
            <h3>{{ $tires_count }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card card-custom text-center p-3">
            <h5>Suppliers</h5>
            <h3>{{ $suppliers_count }}</h3>
        </div>
    </div>
<div class="col-md-3 col-6 mb-3">
    <a href="{{ route('admin.pending.requests') }}" class="text-decoration-none">
        <div class="card bg-danger text-white text-center p-3 hover-card">
            <h5>Pending Requests</h5>
            <h3>{{ $pending_requests }}</h3>
        </div>
    </a>
</div>


</div>


{{-- Vehicles --}}
<h3 class="text-white">Vehicles</h3>
<a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary mb-2">Add Vehicle</a>

<table class="table table-bordered text-center align-middle">
    <thead>
        <tr>
            <th style="width: 10%;">No</th>
            <th style="width: 25%;">Model</th>
            <th style="width: 25%;">Plate Number</th>
            <th style="width: 20%;">Branch</th>
            <th style="width: 20%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($vehicles as $vehicle)
        <tr>
            <td>{{ $loop->iteration }}</td>
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
        @empty
        <tr>
            <td colspan="4">No vehicles found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Tires --}}
<h3 class="text-white">Tires</h3>
<a href="{{ route('admin.tires.create') }}" class="btn btn-success mb-2">Add Tire</a>
<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>Brand</th>
            <th>Size</th>
            <th>Supplier Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tires as $tire)
        <tr>
            <td>{{ $tire->brand }}</td>
            <td>{{ $tire->size }}</td>
            <td>{{ $tire->supplier ? $tire->supplier->name : 'N/A' }}</td>
            <td>
                <a href="{{ route('admin.tires.edit', $tire->id) }}" class="btn btn-sm btn-warning">
    <i class="bi bi-pencil-square"></i> Edit
</a>
<form action="{{ route('admin.tires.destroy', $tire->id) }}" method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">
        <i class="bi bi-trash"></i> Delete
    </button>
</form>

                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4">No tires found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Suppliers --}}
<h3 class="text-white">Suppliers</h3>
<a href="{{ route('admin.suppliers.create') }}" class="btn btn-info mb-2">Add Supplier</a>
<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($suppliers as $supplier)
        <tr>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->contact }}</td>
            <td>{{ $supplier->address }}</td>
            <td>
                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4">No suppliers found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

        {{-- Drivers --}}
        <h3 class="text-white">Drivers</h3>
        <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary mb-2">Add Driver</a>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Mobile</th>
                    <th>ID Number</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $driver)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $driver->user->name ?? 'N/A' }}</td>
                    <td>{{ $driver->user->email ?? 'N/A' }}</td>
                    <td>{{ $driver->full_name }}</td>
                    <td>{{ $driver->mobile }}</td>
                    <td>{{ $driver->id_number }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">No drivers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

@endsection
