@extends('layouts.section_manager')

@section('title', 'Edit Request')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="page-title">✏️ Edit Request</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('section_manager.requests.update', $requestItem->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="font-semibold">Driver</label>
            <div>{{ $requestItem->user->name ?? 'N/A' }}</div>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Vehicle</label>
            <div>{{ $requestItem->vehicle->plate_no ?? 'N/A' }}</div>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Damage Description</label>
            <textarea name="damage_description" class="w-full p-2 border rounded">{{ old('damage_description', $requestItem->damage_description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Status</label>
            <select name="status" class="w-full p-2 border rounded">
                <option value="pending" {{ $requestItem->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $requestItem->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $requestItem->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Remarks (Optional)</label>
            <textarea name="remarks" class="w-full p-2 border rounded">{{ old('remarks', $requestItem->approvals->where('level', App\Models\Approval::LEVEL_SECTION_MANAGER)->first()?->remarks) }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
    </form>
</div>
@endsection
