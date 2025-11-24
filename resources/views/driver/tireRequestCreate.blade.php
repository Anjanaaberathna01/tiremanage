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
            <small class="text-muted">If the plate is registered, branch and other vehicle details will auto-fill.</small>

            {{-- Hidden vehicle_id --}}
            <input type="hidden" name="vehicle_id" id="vehicle_id">
        </div>

        {{-- Branch (auto-filled, read-only) --}}
        <div class="mb-3">
            <label for="branch" class="form-label">Branch</label>
            <input type="text" name="branch" id="branch" class="form-control" readonly>
        </div>

        {{-- Vehicle Type (auto-filled, read-only) --}}
        <div class="mb-3">
            <label for="vehicle_type" class="form-label">Vehicle Type</label>
            <input type="text" id="vehicle_type" class="form-control" readonly>
        </div>

        {{-- Vehicle Brand (auto-filled, read-only) --}}
        <div class="mb-3">
            <label for="vehicle_brand" class="form-label">Vehicle Brand</label>
            <input type="text" id="vehicle_brand" class="form-control" readonly>
        </div>

        {{-- User Section (auto-filled, read-only) --}}
        <div class="mb-3">
            <label for="user_section" class="form-label">User Section</label>
            <input type="text" id="user_section" class="form-control" readonly>
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

        {{-- Tyre Count --}}
        <div class="mb-3">
            <label for="tire_count" class="form-label">Number of Tyres</label>
            <input type="number" name="tire_count" id="tire_count" class="form-control" min="1" value="1" required>
        </div>


        {{-- Delivery Place - Office Name --}}
        <div class="mb-3">
            <label for="delivery_place_office" class="form-label">Delivery Place - Office Name</label>
            <input type="text" name="delivery_place_office" id="delivery_place_office" class="form-control" maxlength="255">
        </div>

        {{-- Delivery Place - Street Name --}}
        <div class="mb-3">
            <label for="delivery_place_street" class="form-label">Delivery Place - Street Name</label>
            <input type="text" name="delivery_place_street" id="delivery_place_street" class="form-control" maxlength="255">
        </div>

        {{-- Delivery Place - Town --}}
        <div class="mb-3">
            <label for="delivery_place_town" class="form-label">Delivery Place - Town</label>
            <input type="text" name="delivery_place_town" id="delivery_place_town" class="form-control" maxlength="255">
        </div>

        {{-- Last Tire Replacement Date --}}
        <div class="mb-3">
            <label for="last_tire_replacement_date" class="form-label">Last Tire Replacement Date</label>
            <input type="date" name="last_tire_replacement_date" id="last_tire_replacement_date" class="form-control">
        </div>

        {{-- Make of Existing Tyre --}}
        <div class="mb-3">
            <label for="existing_tire_make" class="form-label">Make of Existing Tyre</label>
            <input type="text" name="existing_tire_make" id="existing_tire_make" class="form-control" maxlength="255">
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
    const vehicleType = document.getElementById('vehicle_type');
    const vehicleBrand = document.getElementById('vehicle_brand');
    const userSection = document.getElementById('user_section');

    async function lookupPlate(plate) {
        plate = (plate || '').trim();
        if (!plate) {
            branchInput.value = '';
            vehicleId.value   = '';
            vehicleType.value = '';
            vehicleBrand.value = '';
            userSection.value = '';
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
                branchInput.value = data.branch || '';
                vehicleId.value   = data.id || '';
                vehicleType.value = data.vehicle_type || '';
                vehicleBrand.value = data.brand || '';
                userSection.value = data.user_section || '';
            } else {
                branchInput.value = '';
                vehicleId.value   = '';
                vehicleType.value = '';
                vehicleBrand.value = '';
                userSection.value = '';
            }
        } catch (err) {
            console.error('Lookup failed', err);
            branchInput.value = '';
            vehicleId.value   = '';
            vehicleType.value = '';
            vehicleBrand.value = '';
            userSection.value = '';
        }
    }

    plateInput.addEventListener('input', () => lookupPlate(plateInput.value));
    plateInput.addEventListener('change', () => lookupPlate(plateInput.value));

    // Set client-side max date to enforce "older than 3 months" rule and provide hint
    const lastDateInput = document.getElementById('last_tire_replacement_date');
    if (lastDateInput) {
        const now = new Date();
        // compute threshold: 3 months ago
        const threshold = new Date(now.getFullYear(), now.getMonth() - 3, now.getDate());
        // require strictly older than 3 months -> set max to the day before threshold
        threshold.setDate(threshold.getDate() - 1);
        const isoMax = threshold.toISOString().split('T')[0];
        lastDateInput.max = isoMax;

        // Add a small hint below the input
        const hint = document.createElement('small');
        hint.className = 'text-muted d-block';
        hint.textContent = 'Please enter a date older than 3 months (latest allowed: ' + isoMax + ').';
        lastDateInput.parentNode.appendChild(hint);

        // Prevent form submission if the date is not older than 3 months (client-side check)
        const form = lastDateInput.closest('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const val = lastDateInput.value;
                if (val) {
                    if (val >= lastDateInput.max) {
                        e.preventDefault();
                        alert('Last Tire Replacement Date must be older than 3 months.');
                        lastDateInput.focus();
                    }
                }
            });
        }
    }
});
</script>
@endpush
