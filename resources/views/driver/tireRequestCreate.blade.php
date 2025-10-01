@extends('layouts.driver')

@section('content')
<div class="container">
    <h2 class="mb-4">Request a Tire</h2>

    {{-- Success & Error messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<form action="{{ route('driver.requests.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="vehicle_id" class="form-label">Vehicle Number</label>
        <select name="vehicle_id" id="vehicle_id" class="form-control" required>
            @foreach($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}">{{ $vehicle->plate_no }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="tire_id" class="form-label">Tire Size</label>
        <select name="tire_id" id="tire_id" class="form-control" required>
            @foreach($tires as $tire)
                <option value="{{ $tire->id }}">{{ $tire->size }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="damage_description" class="form-label">Damage Description</label>
        <textarea name="damage_description" id="damage_description" class="form-control" rows="3" required></textarea>
    </div>

    <div class="mb-3">
        <label for="images" class="form-label">Upload Tire Images (max 4, each < 2MB)</label>
        <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*" />
    </div>

    <button type="submit" class="btn btn-primary">Submit Request</button>
</form>

</div>
@endsection
