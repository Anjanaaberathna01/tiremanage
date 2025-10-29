@extends('layouts.transportofficer')

@section('title', 'Edit Tire Request')
@section('page_title', 'Edit Request')

@section('content')
<div class="container px-0">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transport_officer.update', $requestItem->id) }}" method="POST" class="card">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Damage Description</label>
                <textarea name="damage_description" class="form-control" rows="3">{{ old('damage_description', $requestItem->damage_description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $requestItem->approvals->where('level', \App\Models\Approval::LEVEL_TRANSPORT_OFFICER)->first()->remarks ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ $requestItem->status == \App\Models\Approval::STATUS_PENDING_TRANSPORT ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $requestItem->status == \App\Models\Approval::STATUS_APPROVED_BY_TRANSPORT ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $requestItem->status == \App\Models\Approval::STATUS_REJECTED_BY_TRANSPORT ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Save Changes</button>
                <a href="{{ route('transport_officer.pending') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

