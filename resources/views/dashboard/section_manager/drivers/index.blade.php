@php
    $layout = Auth::user() && Auth::user()->role
        ? strtolower(str_replace(' ', '_', Auth::user()->role->name))
        : 'admin';
@endphp
@extends($layout === 'admin' ? 'layouts.admin' : 'layouts.section_manager')
@section('title', 'Driver List')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark"><i class="bi bi-people-fill me-2"></i> Drivers</h3>
    <a href="{{ $layout === 'admin' ? route('admin.drivers.create') : route('section_manager.drivers.create') }}"
       class="btn btn-primary btn-elevated">
        <i class="bi bi-person-plus"></i> Add Driver
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-list-check me-2"></i> Driver Records</span>
        <input type="text" id="tableSearch" class="form-control form-control-sm w-25" placeholder="Search by name...">
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="driversTable">
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
                            <td class="driver-name">{{ $driver->full_name ?? 'N/A' }}</td>
                            <td>{{ $driver->mobile ?? 'N/A' }}</td>
                            <td>{{ $driver->id_number ?? 'N/A' }}</td>
                            <td>
                                @if ($layout === 'admin')
                                    <div class="action-buttons">
                                        <form action="{{ route('admin.drivers.destroy', $driver->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this driver?')"
                                              style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-icon btn-sm" data-bs-toggle="tooltip" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <form action="{{ route('section_manager.drivers.destroy', $driver->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this driver?')"
                                          style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted py-4">No drivers found.</td>
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
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#driversTable tbody tr');
        rows.forEach(row => {
            const nameCell = row.querySelector('.driver-name');
            const name = nameCell ? nameCell.textContent.toLowerCase() : '';
            row.style.display = name.includes(filter) ? '' : 'none';
        });
    });
</script>
@endpush
