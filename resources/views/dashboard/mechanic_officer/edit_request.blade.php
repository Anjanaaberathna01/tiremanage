@extends('layouts.mechanicofficer')

@section('title', 'Edit Request')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="page-title" style="text-align:center; font-size:1.8rem; font-weight:700; color:#0d6efd; margin-bottom:25px;">✏️ Edit Request</h2>

    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div style="background:#ffe5e5; color:#b91c1c; padding:15px; border-radius:8px; margin-bottom:20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mechanic_officer.update', $requestItem->id) }}" method="POST" id="edit-request-form" style="background:#fff; padding:25px; border-radius:10px; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
        @csrf
        @method('PUT')

        {{-- Driver --}}
        <div class="mb-4">
            <label style="font-weight:bold;">Driver</label>
            <div style="padding:8px; background:#f1f5f9; border-radius:6px;">{{ $requestItem->user->name ?? 'N/A' }}</div>
        </div>

        {{-- Vehicle --}}
        <div class="mb-4">
            <label style="font-weight:bold;">Vehicle</label>
            <div style="padding:8px; background:#f1f5f9; border-radius:6px;">{{ $requestItem->vehicle->plate_no ?? 'N/A' }}</div>
        </div>

        {{-- Damage Description --}}
        <div class="mb-4">
            <label style="font-weight:bold;">Damage Description</label>
            <textarea name="damage_description" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">{{ old('damage_description', $requestItem->damage_description) }}</textarea>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label style="font-weight:bold;">Status</label>
            <select name="status" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                <option value="pending" {{ $requestItem->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $requestItem->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $requestItem->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        {{-- Remarks --}}
        <div class="mb-4">
            <label style="font-weight:bold;">Remarks (Optional)</label>
            <textarea name="remarks" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">{{ old('remarks', $requestItem->approvals->where('level', \App\Models\Approval::LEVEL_MECHANIC_OFFICER)->first()?->remarks) }}</textarea>
        </div>

        {{-- Submit --}}
        <button type="submit" style="background:#0d6efd; color:#fff; padding:10px 20px; border:none; border-radius:6px; cursor:pointer; font-weight:bold; transition:background 0.3s, transform 0.2s;">Save Changes</button>
    </form>
</div>

{{-- JS Interactivity --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const fields = document.querySelectorAll('#edit-request-form textarea, #edit-request-form select');
    fields.forEach(field => {
        field.addEventListener('focus', () => field.style.borderColor = '#0d6efd');
        field.addEventListener('blur', () => field.style.borderColor = '#ccc');
    });

    const form = document.getElementById('edit-request-form');
    form.addEventListener('submit', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
});
</script>
@endsection
