@extends('layouts.admin')

@section('title', 'Manage Tyres')

@section('content')
<div class="container mx-auto p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">Tyres Management</h2>
        <a href="{{ route('admin.tires.create') }}" class="btn btn-success btn-elevated"><i class="bi bi-plus-circle"></i> Add Tyre</a>
    </div>

    {{-- Search Bar --}}
    <div class="mb-3">
        <input type="text" id="tyreSearch" class="form-control" placeholder="Search by brand, size, or supplier...">
    </div>

    <div class="table-responsive">
        <table class="table table-hover" id="tyresTable">
            <thead>
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
                            <a href="{{ route('admin.tires.edit', $tire->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                            <form action="{{ route('admin.tires.destroy', $tire->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No tyres found.</td>
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
    #tyreSearch { max-width: 320px; }
</style>
@endpush

@push('scripts')
<script>
    // Simple search filter for tyres table
    document.getElementById('tyreSearch').addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tyresTable tbody tr');
        rows.forEach(row => {
            const brand = row.querySelector('.tyre-brand')?.textContent.toLowerCase() ?? '';
            const size = row.querySelector('.tyre-size')?.textContent.toLowerCase() ?? '';
            const supplier = row.querySelector('.tyre-supplier')?.textContent.toLowerCase() ?? '';
            row.style.display = (brand.includes(filter) || size.includes(filter) || supplier.includes(filter)) ? '' : 'none';
        });
    });
</script>
@endpush

