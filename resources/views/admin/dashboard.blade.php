@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<style>
    /* Background */
    body {
        background:
            linear-gradient(135deg, rgba(90, 90, 90, 0.85), rgba(42, 42, 42, 0.85)),
            url("{{ asset('assets/images/background2.jpg') }}") no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
    }

    h2, h3 {
        font-family: 'Poppins', sans-serif;
        text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    /* Stats cards */
    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        flex: 1 1 calc(25% - 20px);
        min-width: 220px;
        cursor: pointer;
        text-align: center;
        padding: 25px 15px;
        background: #F75270;
        color: #fff;
    }

    .card h5 {
        font-size: 1.1rem;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .card h3 {
        font-size: 2rem;
        font-weight: 700;
    }

    .card:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    .card-danger {
        background: #DC143C;
        color: #fff;
    }

    /* Table styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: #fefefe;
        color: #333;
    }

    th {
        background: #0BA6DF;
        color: #fff;
        padding: 12px;
        text-align: center;
    }

    td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
    }

    tr:hover {
        background: #f0f4f8;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        padding: 6px 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary { background: #F75270; color: #fff; }
    .btn-primary:hover { background: #DC143C; }
    .btn-success { background: #F75270; color: #fff; }
    .btn-success:hover { background: #DC143C; }
    .btn-info { background: #F75270; color: #fff; }
    .btn-info:hover { background: #DC143C; }
    .btn-warning { background: #0BA6DF; color: #fff; }
    .btn-warning:hover { background: #4fbfe8; }
    .btn-danger { background: #DC143C; color: #fff; }
    .btn-danger:hover { background: #d62828; }

    /* Hover card effect */
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .row { flex-direction: column; }
        .card { flex: 1 1 100%; }
        th, td { font-size: 0.85rem; padding: 8px; }
    }
</style>

<h2 class="mb-4">🌟 Admin Dashboard</h2>

{{-- Stats Cards --}}
<div class="row mb-4">
    <div class="card">
        <h5>Total Vehicles</h5>
        <h3>{{ $vehicles_count }}</h3>
    </div>
    <div class="card">
        <h5>Total Tyres</h5>
        <h3>{{ $tires_count }}</h3>
    </div>
    <div class="card">
        <h5>Suppliers</h5>
        <h3>{{ $suppliers_count }}</h3>
    </div>
    <div class="card card-danger hover-card" onclick="window.location='{{ route('admin.request.pending') }}'">
        <h5>Pending Requests</h5>
        <h3>{{ $pending_requests }}</h3>
    </div>
</div>

{{-- Vehicles --}}
<h3>Vehicles</h3>
<a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary mb-2">Add Vehicle</a>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Model</th>
            <th>Plate Number</th>
            <th>Branch</th>
            <th>Actions</th>
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
                <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this vehicle?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5">No vehicles found.</td></tr>
        @endforelse
    </tbody>
</table>

{{-- Tires --}}
<h3>Tyres</h3>
<a href="{{ route('admin.tires.create') }}" class="btn btn-success mb-2">Add Tyre</a>
<table>
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
            <td>{{ $tire->supplier->name ?? 'N/A' }}</td>
            <td>
                <a href="{{ route('admin.tires.edit', $tire->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.tires.destroy', $tire->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @empty
    <tr><td colspan="4">No tyres found.</td></tr>
        @endforelse
    </tbody>
</table>

{{-- Suppliers --}}
<h3>Suppliers</h3>
<a href="{{ route('admin.suppliers.create') }}" class="btn btn-info mb-2">Add Supplier</a>
<table>
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
                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="4">No suppliers found.</td></tr>
        @endforelse
    </tbody>
</table>

{{-- Drivers --}}
<h3>Drivers</h3>
<a href="{{ route('admin.drivers.create') }}" class="btn btn-primary mb-2">Add Driver</a>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Email</th>
            <th>Full Name</th>
            <th>Mobile</th>
            <th>ID Number</th>
            <th>Actions</th>
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
            <td>
                <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this driver?')">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7">No drivers found.</td></tr>
        @endforelse
    </tbody>
</table>

{{-- Simple JS for card click feedback --}}
<script>
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => card.style.cursor = 'pointer');
    });
</script>

@endsection
