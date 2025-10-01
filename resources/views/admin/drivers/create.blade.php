@extends('layouts.driver')

@section('title', 'Register Driver')

@section('content')
<div class="container">
    <h2>Register Driver</h2>

    <form action="{{ route('admin.drivers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Username (for login)</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control">
        </div>
        <div class="mb-3">
            <label for="mobile" class="form-label">Mobile</label>
            <input type="text" name="mobile" class="form-control">
        </div>
        <div class="mb-3">
            <label for="id_number" class="form-label">ID Number</label>
            <input type="text" name="id_number" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Register Driver</button>
    </form>
</div>
@endsection
