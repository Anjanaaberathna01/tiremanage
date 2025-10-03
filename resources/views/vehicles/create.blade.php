@extends(auth()->user()->role->name === 'Admin' ? 'layouts.admin' : 'layouts.section_manager')


@section('content')
<div class="container">
    <h1>Add Vehicle</h1>

    <form action="{{ auth()->user()->role->name === 'Admin' ? route('admin.vehicles.store') : route('section_manager.vehicles.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Model</label>
        <input type="text" name="model" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Registration Number</label>
        <input type="text" name="plate_no" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Branch</label>
        <input type="text" name="branch" class="form-control" value="{{ old('branch') }}" required>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ auth()->user()->role->name === 'Admin' ? route('admin.vehicles.index') : route('section_manager.vehicles.index') }}" class="btn btn-secondary">Cancel</a>
</form>

</div>
@endsection
