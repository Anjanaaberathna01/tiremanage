@extends('layouts.transportofficer')

@section('title', 'Generate Receipt')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="dashboard-title">🧾 Generate Receipt for Request #{{ $request->id }}</h2>

    <form action="{{ route('transport_officer.receipt.store') }}" method="POST">
        @csrf
        <input type="hidden" name="request_id" value="{{ $request->id }}">

        <div class="mb-4">
            <label class="block font-semibold mb-1">Supplier</label>
            <select name="supplier_id" class="border p-2 rounded w-full" required>
                <option value="">Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }} - {{ $supplier->contact }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" class="border p-2 rounded w-full" rows="3"></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Amount</label>
            <input type="number" step="0.01" name="amount" class="border p-2 rounded w-full">
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Generate Receipt</button>
    </form>
</div>
@endsection
