@php
    $layout = Auth::user() && Auth::user()->role
        ? strtolower(str_replace(' ', '_', Auth::user()->role->name))
        : 'admin';
@endphp
@extends($layout === 'admin' ? 'layouts.admin' : 'layouts.section_manager')
@section('title', 'Driver List')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0"><i class="bi bi-people-fill me-2"></i> Drivers</h3>
  <a href="{{ $layout === 'admin' ? route('admin.drivers.create') : route('section_manager.drivers.create') }}" class="btn btn-primary btn-elevated">
    <i class="bi bi-person-plus"></i> Add Driver
  </a>
</div>

<form action="{{ route('section_manager.drivers.index') }}" method="GET" class="mb-3">
  <div class="row g-2 align-items-center">
    <div class="col-sm-8 col-md-6">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
        <input type="text" id="driverSearch" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by full name">
        @if(request('search'))
          <a href="{{ route('section_manager.drivers.index') }}" class="btn btn-outline-secondary">Reset</a>
        @endif
        <button class="btn btn-primary" type="submit">Search</button>
      </div>
    </div>
    <div class="col-sm-4 col-md-6 text-sm-end mt-2 mt-sm-0">
      <span class="text-muted small">Showing</span>
      <span class="badge bg-primary-subtle text-primary border small">{{ $drivers->count() }}</span>
      <span class="text-muted small">drivers</span>
    </div>
  </div>
</form>

<div class="card shadow-sm">
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
            <td><span class="badge text-bg-light border fw-semibold">{{ $driver->mobile ?? 'N/A' }}</span></td>
            <td><span class="badge bg-secondary-subtle text-secondary border">{{ $driver->id_number ?? 'N/A' }}</span></td>
            <td>
              <form action="{{ $layout === 'admin' ? route('admin.drivers.destroy', $driver->id) : route('section_manager.drivers.destroy', $driver->id) }}"
                    method="POST" onsubmit="return confirm('Are you sure you want to delete this driver?')" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-icon btn-sm" data-bs-toggle="tooltip" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="py-5">
              <div class="text-center text-muted">
                <div class="mb-2"><i class="bi bi-people fs-3"></i></div>
                <div class="fw-semibold">No drivers found</div>
                <div class="small">Try adjusting your search or add a new driver.</div>
                <div class="mt-3">
                  <a href="{{ $layout === 'admin' ? route('admin.drivers.create') : route('section_manager.drivers.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus"></i> Add Driver
                  </a>
                </div>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('styles')
<style>
/* Subtle badge helpers + sticky head, align icons */
.bg-primary-subtle { background: rgba(13,110,253,.10) !important; }
.bg-secondary-subtle { background: rgba(108,117,125,.12) !important; }
.text-bg-light { background-color: #f8f9fa !important; }
.table-responsive thead th { position: sticky; top: 0; background: #fff; z-index: 1; }
.btn-icon i { line-height: 1; font-size: 1rem; }
</style>
@endpush

@push('scripts')
<script>
    // Live client-side filter by full name; enable tooltips
    const input = document.getElementById('driverSearch');
    if (input) {
        input.addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('#driversTable tbody tr').forEach(row => {
                const nameCell = row.querySelector('.driver-name');
                const name = nameCell ? nameCell.textContent.toLowerCase() : '';
                row.style.display = name.includes(filter) ? '' : 'none';
            });
        });
    }
    if (window.bootstrap && bootstrap.Tooltip) {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    }
</script>
@endpush
