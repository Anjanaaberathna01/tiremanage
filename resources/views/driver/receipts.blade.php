@extends('layouts.driver')

@section('title', 'Receipts')

@section('content')
<div class="container p-6">
    <h2 class="page-title mb-4">🧾 Tyre Request Receipts</h2>

    @if($receipts->isEmpty())
        <p class="text-muted">No receipts found.</p>
    @else
        <!-- Search Box -->
        <div class="mb-3">
            <input type="text" id="receiptSearch" class="form-control" placeholder="🔍 Search by Vehicle, Supplier or Description...">
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped" id="receiptsTable" style="border-radius:8px; overflow:hidden;">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Vehicle</th>
                        <th>Tyre Count</th>
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
                            <td class="desc">{{ $receipt->description ?? 'N/A' }}</td>
                            <td>{{ number_format($receipt->amount, 2) ?? '0.00' }}</td>
                            <td>
                                <a href="{{ route('driver.receipt.download', $receipt->id) }}" class="btn btn-sm btn-primary">
                                    ⬇️ Download PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Table styling */
    #receiptsTable th, #receiptsTable td {
        text-align: center;
        vertical-align: middle;
    }

    #receiptsTable tbody tr:hover {
        background-color: #f0f8ff;
        transition: background-color 0.3s;
    }

    .btn-primary {
        background-color: #0062cc;
        border-color: #0056b3;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    #receiptSearch {
        max-width: 400px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Simple Search Filter
    const searchInput = document.getElementById('receiptSearch');
    const tableRows = document.querySelectorAll('#receiptsTable tbody tr');

    searchInput.addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
@endpush
