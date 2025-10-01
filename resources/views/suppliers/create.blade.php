@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Add Supplier</h1>
    <form action="{{ route('admin.suppliers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contact</label>
            <input type="text" name="contact" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Save Supplier</button>
        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
