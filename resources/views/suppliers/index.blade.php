@extends('layouts.admin')

@section('title', 'Manage Suppliers')

@section('content')
<div class="container mx-auto p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">Suppliers Management</h2>
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-building-add"></i> Add Supplier</a>
    </div>

    {{-- Search Bar --}}
    <div class="mb-3">
        <input type="text" id="supplierSearch" class="form-control" placeholder="Search by name or contact...">
    </div>

    <div class="table-responsive">
        <table class="table table-hover" id="suppliersTable">
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
                        <td class="supplier-name">{{ $supplier->name }}</td>
                        <td class="supplier-contact">{{ $supplier->contact }}</td>
                        <td class="supplier-address">{{ $supplier->address }}</td>
                        <td>
                            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                            <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No suppliers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-title { font-size: 1.8rem; font-weight: 800; color: var(--text); }
    #supplierSearch { max-width: 320px; }
</style>
@endpush

@push('scripts')
<script>
    // Simple search filter for suppliers table
    document.getElementById('supplierSearch').addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#suppliersTable tbody tr');
        rows.forEach(row => {
            const name = row.querySelector('.supplier-name')?.textContent.toLowerCase() ?? '';
            const contact = row.querySelector('.supplier-contact')?.textContent.toLowerCase() ?? '';
            row.style.display = (name.includes(filter) || contact.includes(filter)) ? '' : 'none';
        });
    });
    </script>
@endpush

