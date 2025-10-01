@extends('layouts.admin')

@section('title', 'Manage Suppliers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Suppliers</h2>
    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-success">Add Supplier</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($suppliers as $supplier)
        <tr>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->contact }}</td>
            <td>
                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
