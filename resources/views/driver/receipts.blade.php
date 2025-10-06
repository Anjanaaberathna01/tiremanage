@extends('layouts.driver')

@section('title', 'Receipts')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="page-title">🧾 Tire Request Receipts</h2>

    @if($receipts->isEmpty())
        <p>No receipts found.</p>
    @else
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vehicle</th>
                    <th>Tire Count</th>
                    <th>Supplier</th>
                    <th>Supplier Address</th>
                    <th>Supplier Contact</th>
                    <th>Issued Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipts as $receipt)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $receipt->tireRequest->vehicle->plate_no ?? 'N/A' }}</td>
                        <td>{{ $receipt->tireRequest->tire_count ?? 'N/A' }}</td>
                        <td>{{ $receipt->supplier->name ?? 'N/A' }}</td>
                        <td>{{ $receipt->supplier->address ?? 'N/A' }}</td>
                        <td>{{ $receipt->supplier->contact ?? 'N/A' }}</td>
                        <td>{{ $receipt->created_at ? $receipt->created_at->format('Y-m-d') : 'N/A' }}</td>
                        <td>{{ $receipt->description ?? 'N/A' }}</td>
                        <td>{{ number_format($receipt->amount, 2) ?? '0.00' }}</td>
                        <td><a href="{{ route('driver.receipt.download', $receipt->id) }}"class="btn btn-sm btn-primary"> ⬇️ Download PDF</a></td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
