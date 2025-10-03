@extends('layouts.driver')

@section('title', 'Request a Tyre')

@section('content')
<div class="container">
    <h2 class="mb-4">Request a Tyre</h2>

    {{-- ✅ Success & Error messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('driver.requests.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @php
            $vehicles = \App\Models\Vehicle::orderBy('plate_no')->get();
        @endphp

        {{-- Vehicle Plate Number --}}
        <div class="mb-3">
            <label for="plate_no" class="form-label">Vehicle Plate Number</label>
            <input list="plates"
                   type="text"
                   name="plate_no"
                   id="plate_no"
                   class="form-control"
                   placeholder="Enter plate number (e.g. ABC-1234)"
                   required
                   autocomplete="off">
            <datalist id="plates">
                @foreach($vehicles as $v)
                    <option value="{{ $v->plate_no }}"></option>
                @endforeach
            </datalist>
            <small class="text-muted">If the plate is registered, branch will auto-fill.</small>

            {{-- Hidden vehicle_id --}}
            <input type="hidden" name="vehicle_id" id="vehicle_id">
        </div>

        {{-- Branch (auto-filled, read-only) --}}
        <div class="mb-3">
            <label for="branch" class="form-label">Branch</label>
            <input type="text" name="branch" id="branch" class="form-control" readonly>
        </div>

        {{-- Tyre Size --}}
        <div class="mb-3">
            <label for="tire_id" class="form-label">Tyre Size</label>
            <select name="tire_id" id="tire_id" class="form-control" required>
                @foreach($tires as $tire)
                    <option value="{{ $tire->id }}">{{ $tire->size }}</option>
                @endforeach
            </select>
        </div>

        {{-- Tire Count --}}
        <div class="mb-3">
            <label for="tire_count" class="form-label">Number of Tires</label>
            <input type="number" name="tire_count" id="tire_count" class="form-control" min="1" value="1" required>
        </div>

        {{-- Damage Description --}}
        <div class="mb-3">
            <label for="damage_description" class="form-label">Damage Description</label>
            <textarea name="damage_description" id="damage_description" class="form-control" rows="3" required></textarea>
        </div>

        {{-- Upload Images --}}
        <div class="mb-3">
            <label for="images" class="form-label">Upload Tyre Images (max 4, each < 2MB)</label>
            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
        </div>

        {{-- Submit & Cancel Buttons --}}
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Submit Request</button>
            <a href="{{ route('driver.dashboard') }}" class="btn btn-secondary ms-2">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const plateInput = document.getElementById('plate_no');
    const branchInput = document.getElementById('branch');
    const vehicleId   = document.getElementById('vehicle_id');

    async function lookupPlate(plate) {
        plate = (plate || '').trim();
        if (!plate) {
            branchInput.value = '';
            vehicleId.value   = '';
            return;
        }

        try {
            const res = await fetch("{{ route('driver.vehicles.lookup') }}?plate_no=" + encodeURIComponent(plate), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!res.ok) throw new Error('Network response was not ok');
            const data = await res.json();

            if (data.found) {
                branchInput.value = data.branch;
                vehicleId.value   = data.id;
            } else {
                branchInput.value = '';
                vehicleId.value   = '';
            }
        } catch (err) {
            console.error('Lookup failed', err);
            branchInput.value = '';
            vehicleId.value   = '';
        }
    }

    plateInput.addEventListener('input', () => lookupPlate(plateInput.value));
    plateInput.addEventListener('change', () => lookupPlate(plateInput.value));
});
</script>
@endpush
