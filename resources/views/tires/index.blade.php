@extends('layouts.admin')

@section('title', 'Manage Tyres')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Tyre</h2>
    <a href="{{ route('admin.tires.create') }}" class="btn btn-success">Add Tyre</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Brand</th>
            <th>Size</th>
            <th>Supplier</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse($tires as $tire)
        <tr>
            <td>{{ $tire->brand }}</td>
            <td>{{ $tire->size }}</td>
            <td>{{ $tire->supplier ? $tire->supplier->name : 'N/A' }}</td>
            <td>
                <a href="{{ route('admin.tires.edit', $tire->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.tires.destroy', $tire->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4">No tyres found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
