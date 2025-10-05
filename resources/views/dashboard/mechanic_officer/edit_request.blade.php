@extends('layouts.mechanic_officer')

@section('title', 'Edit Tire Request')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold text-blue-600 mb-6">✏️ Edit Tire Request</h2>

    {{-- Show success or error messages --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Error validation --}}
    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            <ul class="list-disc pl-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mechanic_officer.requests.update', $request->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
        @csrf
        @method('PUT')

        {{-- Driver Info --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Driver Name:</label>
            <input type="text" value="{{ $request->driver->name ?? 'N/A' }}" readonly
                class="w-full px-4 py-2 border rounded bg-gray-100 cursor-not-allowed">
        </div>

        {{-- Vehicle Info --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Vehicle Number:</label>
            <input type="text" value="{{ $request->vehicle->plate_number ?? 'N/A' }}" readonly
                class="w-full px-4 py-2 border rounded bg-gray-100 cursor-not-allowed">
        </div>

        {{-- Tire Count --}}
        <div class="mb-4">
            <label for="tire_count" class="block text-gray-700 font-semibold mb-1">Tire Count:</label>
            <input type="number" id="tire_count" name="tire_count"
                value="{{ old('tire_count', $request->tire_count) }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                required>
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-semibold mb-1">Description:</label>
            <textarea id="description" name="description" rows="3"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                required>{{ old('description', $request->description) }}</textarea>
        </div>

        {{-- Status --}}
        <div class="mb-6">
            <label for="status" class="block text-gray-700 font-semibold mb-1">Status:</label>
            <select id="status" name="status"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                required>
                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $request->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        {{-- Buttons --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('mechanic_officer.dashboard') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                ⬅️ Back
            </a>
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                💾 Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
