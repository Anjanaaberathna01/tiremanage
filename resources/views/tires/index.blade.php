@extends('layouts.admin')

@section('title', 'Manage Tyres')

@section('content')
<div class="container mx-auto p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">🛞 Tyres Management</h2>
        <a href="{{ route('admin.tires.create') }}" class="btn btn-success">Add Tyre</a>
    </div>

    {{-- Search Bar --}}
    <div class="mb-3">
        <input type="text" id="tyreSearch" class="form-control" placeholder="🔍 Search by brand, size, or supplier...">
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered" id="tyresTable">
            <thead class="table-dark">
                <tr>
                    <th>Brand</th>
                    <th>Size</th>
                    <th>Supplier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tires as $tire)
                    <tr>
                        <td class="tyre-brand">{{ $tire->brand }}</td>
                        <td class="tyre-size">{{ $tire->size }}</td>
                        <td class="tyre-supplier">{{ $tire->supplier ? $tire->supplier->name : 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.tires.edit', $tire->id) }}" class="btn btn-sm btn-warning">✏️ Edit</a>
                            <form action="{{ route('admin.tires.destroy', $tire->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️ Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">🚫 No tyres found.</td>
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

    #tyreSearch {
        max-width: 300px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Simple search filter for tyres table
    document.getElementById('tyreSearch').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tyresTable tbody tr');

        rows.forEach(row => {
            let brand = row.querySelector('.tyre-brand').textContent.toLowerCase();
            let size = row.querySelector('.tyre-size').textContent.toLowerCase();
            let supplier = row.querySelector('.tyre-supplier').textContent.toLowerCase();

            row.style.display = (brand.includes(filter) || size.includes(filter) || supplier.includes(filter)) ? '' : 'none';
        });
    });
</script>
@endpush
