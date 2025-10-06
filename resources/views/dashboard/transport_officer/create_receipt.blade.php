@extends('layouts.transportofficer')

@section('title', 'Create Receipt')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">🧾 Create Receipt</h2>

    <form action="{{ route('transport_officer.receipt.store') }}" method="POST" class="space-y-4">
        @csrf

        <input type="hidden" name="request_id" value="{{ $tireRequest->id }}">

        <div>
            <label class="block font-semibold">Driver:</label>
            <p>{{ $tireRequest->driver->full_name ?? 'N/A' }}</p>
        </div>

        <div>
            <label for="supplier_id" class="block font-semibold">Supplier:</label>
            <select name="supplier_id" id="supplier_id" required class="border rounded w-full p-2">
                <option value="">-- Select Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="amount" class="block font-semibold">Amount:</label>
            <input type="number" name="amount" id="amount" class="border rounded w-full p-2" placeholder="Enter amount">
        </div>

        <div>
            <label for="description" class="block font-semibold">Description:</label>
            <textarea name="description" id="description" class="border rounded w-full p-2" rows="3" placeholder="Enter description"></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Receipt</button>
    </form>
</div>
@endsection
