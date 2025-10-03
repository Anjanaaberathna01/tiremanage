@extends('layouts.admin')

@section('title', 'Add Tyre')

@section('content')
<div class="container">
    <h1>Add Tyre</h1>
    <form action="{{ route('admin.tires.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Brand</label>
            <input type="text" name="brand" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Size</label>
            <input type="text" name="size" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control" required>
                <option value="">Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
    <button type="submit" class="btn btn-success">Save Tyre</button>
        <a href="{{ route('admin.tires.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
