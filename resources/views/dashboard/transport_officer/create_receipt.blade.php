@extends('layouts.transportofficer')

@section('title', 'Create Receipt')
@section('page_title', 'Create Receipt')

@section('content')
<div class="container px-0">
    <form action="{{ route('transport_officer.receipt.store') }}" method="POST" class="card">
        @csrf
        <div class="card-body">
            <input type="hidden" name="request_id" value="{{ $tireRequest->id }}">

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Driver</label>
                    <input type="text" class="form-control" value="{{ $tireRequest->driver->full_name ?? ($tireRequest->user->name ?? 'N/A') }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Vehicle</label>
                    <input type="text" class="form-control" value="{{ $tireRequest->vehicle->plate_no ?? 'N/A' }}" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tire</label>
                    <input type="text" class="form-control" value="{{ ($tireRequest->tire->brand ?? 'N/A') . ' ' . ($tireRequest->tire->size ?? '') }}" disabled>
                </div>
            </div>

            <hr class="my-4">

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select name="supplier_id" id="supplier_id" required class="form-select">
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }} - {{ $supplier->contact }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" placeholder="0.00" step="0.01" required>
                </div>
                <div class="col-md-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter description (optional)"></textarea>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send-check me-1"></i> Save Receipt</button>
                <a href="{{ route('transport_officer.approved') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

