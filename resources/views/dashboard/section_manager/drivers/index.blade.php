@extends('layouts.section_manager')

@section('title', 'Driver List')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark"><i class="bi bi-people-fill me-2"></i> Drivers</h3>
    <a href="{{ route('section_manager.drivers.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-lg"></i> Add Driver
    </a>
</div>

<div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-list-check me-2"></i> Driver Records</span>
        <input type="text" id="tableSearch" class="form-control form-control-sm w-25" placeholder="🔍 Search by name...">
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="driversTable">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Mobile</th>
                        <th>ID Number</th>
                        <th>Actions</th> <!-- ✅ New column -->
                    </tr>
                </thead>
                <tbody>
                    @forelse($drivers as $driver)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $driver->user->name ?? 'N/A' }}</td>
                            <td>{{ $driver->user->email ?? 'N/A' }}</td>
                            <td class="driver-name">{{ $driver->full_name ?? 'N/A' }}</td>
                            <td>{{ $driver->mobile ?? 'N/A' }}</td>
                            <td>{{ $driver->id_number ?? 'N/A' }}</td>
                            <td>
                                <!-- Delete Button -->
                                <form action="{{ route('section_manager.drivers.destroy', $driver->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this driver?')" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted py-4">🚫 No drivers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Search only by Driver Full Name
    document.getElementById('tableSearch').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#driversTable tbody tr');

        rows.forEach(row => {
            let nameCell = row.querySelector('.driver-name');
            if (nameCell) {
                let name = nameCell.textContent.toLowerCase();
                row.style.display = name.includes(filter) ? '' : 'none';
            }
        });
    });
</script>
@endpush
