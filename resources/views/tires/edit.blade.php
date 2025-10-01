@extends('layouts.admin')

@section('title', 'Edit Tire')

@section('content')
<div class="container">
    <h1>Edit Tire</h1>
    <form action="{{ route('admin.tires.update', $tire->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Brand</label>
            <input type="text" name="brand" class="form-control" value="{{ $tire->brand }}" required>
        </div>
        <div class="mb-3">
            <label>Size</label>
            <input type="text" name="size" class="form-control" value="{{ $tire->size }}" required>
        </div>
        <div class="mb-3">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control" required>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $tire->supplier_id == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update Tire</button>
        <a href="{{ route('admin.tires.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
