@extends(auth()->user()->role->name === 'Admin' ? 'layouts.admin' : 'layouts.section_manager')

@section('title', 'Add Vehicle')

@section('content')
<div class="container mt-5 p-4 bg-light rounded shadow-sm">
    <h2 class="text-center text-primary mb-4">🚗 Add New Vehicle</h2>

    {{-- ✅ Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ✅ Error Message (e.g. duplicate plate number) --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ✅ Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Add Vehicle Form --}}
    <form
        action="{{ auth()->user()->role->name === 'Admin'
            ? route('admin.vehicles.store')
            : route('section_manager.vehicles.store') }}"
        method="POST"
        class="needs-validation"
        novalidate
    >
        @csrf

        <div class="mb-3">
            <label for="model" class="form-label fw-bold">Model</label>
            <input type="text" name="model" id="model"
                   class="form-control @error('model') is-invalid @enderror"
                   value="{{ old('model') }}"
                   placeholder="Enter vehicle model" required>
            @error('model')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="plate_no" class="form-label fw-bold">Registration Number</label>
            <input type="text" name="plate_no" id="plate_no"
                   class="form-control text-uppercase @error('plate_no') is-invalid @enderror"
                   value="{{ old('plate_no') }}"
                   placeholder="Enter registration number (e.g., ABC123)" required>
            @error('plate_no')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="branch" class="form-label fw-bold">Branch</label>
            <input type="text" name="branch" id="branch"
                   class="form-control @error('branch') is-invalid @enderror"
                   value="{{ old('branch') }}"
                   placeholder="Enter branch name" required>
            @error('branch')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success px-4">💾 Save</button>
            <a href="{{ auth()->user()->role->name === 'Admin'
                ? route('admin.vehicles.index')
                : route('section_manager.vehicles.index') }}"
                class="btn btn-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
@endsection
