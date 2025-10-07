@extends('layouts.admin')

@section('title', 'Manage Suppliers')

@section('content')
<div class="container mx-auto p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">🏢 Suppliers Management</h2>
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-success">Add Supplier</a>
    </div>

    {{-- Search Bar --}}
    <div class="mb-3">
        <input type="text" id="supplierSearch" class="form-control" placeholder="🔍 Search by name or contact...">
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered" id="suppliersTable">
            <thead class="table-dark">
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
                        <td class="supplier-name">{{ $supplier->name }}</td>
                        <td class="supplier-contact">{{ $supplier->contact }}</td>
                        <td class="supplier-address">{{ $supplier->address }}</td>
                        <td>
                            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning">✏️ Edit</a>
                            <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️ Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">🚫 No suppliers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: #1f2937;
    }

    table {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    table th, table td {
        vertical-align: middle !important;
        text-align: center;
    }

    table tbody tr:hover {
        background-color: #f1f5f9;
    }

    .btn-success {
        background-color: #10b981;
        border: none;
    }

    .btn-success:hover {
        background-color: #059669;
    }

    .btn-warning {
        background-color: #f59e0b;
        border: none;
    }

    .btn-warning:hover {
        background-color: #d97706;
    }

    .btn-danger {
        background-color: #ef4444;
        border: none;
    }

    .btn-danger:hover {
        background-color: #b91c1c;
    }

    #supplierSearch {
        max-width: 300px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Simple search filter for suppliers table
    document.getElementById('supplierSearch').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#suppliersTable tbody tr');

        rows.forEach(row => {
            let name = row.querySelector('.supplier-name').textContent.toLowerCase();
            let contact = row.querySelector('.supplier-contact').textContent.toLowerCase();
            row.style.display = (name.includes(filter) || contact.includes(filter)) ? '' : 'none';
        });
    });
</script>
@endpush
