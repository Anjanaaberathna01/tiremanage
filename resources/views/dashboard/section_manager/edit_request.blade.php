@extends('layouts.section_manager')

@section('title', 'Edit Request')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6 text-blue-600">✏️ Edit Request</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-600 rounded">
            <ul class="list-disc ml-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('section_manager.requests.update', $request->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Damage Description</label>
            <textarea name="damage_description"
                      class="w-full border rounded-lg p-3 mt-2"
                      rows="4">{{ old('damage_description', $request->damage_description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Status</label>
            <select name="status" class="w-full border rounded-lg p-3 mt-2">
                <option value="approved" {{ $request->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            💾 Save Changes
        </button>
        <a href="{{ url()->previous() }}"
           class="ml-3 bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition">
           ⬅ Back
        </a>
    </form>
</div>
@endsection
