@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-3 text-primary fw-bold">
        📊 Reports Dashboard
    </h2>
    <p class="text-muted mb-4">
        Export detailed reports in Excel format for record keeping and analysis.
    </p>

    {{-- ---------------- General Data Reports ---------------- --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-gradient-info text-white">
            <h5 class="mb-0"><i class="fa fa-file-excel-o me-2"></i>General Data Reports</h5>
        </div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('admin.reports.vehicles') }}" class="btn btn-outline-success report-btn">🚗 Vehicles</a>
            <a href="{{ route('admin.reports.drivers') }}" class="btn btn-outline-primary report-btn">👨‍✈️ Drivers</a>
            <a href="{{ route('admin.reports.suppliers') }}" class="btn btn-outline-info report-btn">🏢 Suppliers</a>
            <a href="{{ route('admin.reports.tires') }}" class="btn btn-outline-warning report-btn">🛞 Tyres</a>
        </div>
    </div>

    {{-- ---------------- Section Manager Reports ---------------- --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-gradient-warning text-white">
            <h5 class="mb-0"><i class="fa fa-user-tie me-2"></i>Section Manager Requests</h5>
        </div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('admin.reports.section_manager', ['status' => 'pending']) }}" class="btn btn-outline-warning report-btn">⏳ Pending</a>
            <a href="{{ route('admin.reports.section_manager', ['status' => 'approved']) }}" class="btn btn-outline-success report-btn">✅ Approved</a>
            <a href="{{ route('admin.reports.section_manager', ['status' => 'rejected']) }}" class="btn btn-outline-danger report-btn">❌ Rejected</a>
            <a href="{{ route('admin.reports.section_manager', ['status' => 'all']) }}" class="btn btn-outline-dark report-btn">📁 All</a>
        </div>
    </div>

    {{-- ---------------- Mechanic Officer Reports ---------------- --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-gradient-secondary text-white">
            <h5 class="mb-0"><i class="fa fa-wrench me-2"></i>Mechanic Officer Requests</h5>
        </div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('admin.reports.mechanic_officer', ['status' => 'pending']) }}" class="btn btn-outline-warning report-btn">⏳ Pending</a>
            <a href="{{ route('admin.reports.mechanic_officer', ['status' => 'approved']) }}" class="btn btn-outline-success report-btn">✅ Approved</a>
            <a href="{{ route('admin.reports.mechanic_officer', ['status' => 'rejected']) }}" class="btn btn-outline-danger report-btn">❌ Rejected</a>
            <a href="{{ route('admin.reports.mechanic_officer', ['status' => 'all']) }}" class="btn btn-outline-dark report-btn">📁 All</a>
        </div>
    </div>

    {{-- ---------------- Transport Officer Reports ---------------- --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-primary text-white">
            <h5 class="mb-0"><i class="fa fa-truck me-2"></i>Transport Officer Requests</h5>
        </div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('admin.reports.transport_officer', ['status' => 'pending']) }}" class="btn btn-outline-warning report-btn">⏳ Pending</a>
            <a href="{{ route('admin.reports.transport_officer', ['status' => 'approved']) }}" class="btn btn-outline-success report-btn">✅ Approved</a>
            <a href="{{ route('admin.reports.transport_officer', ['status' => 'rejected']) }}" class="btn btn-outline-danger report-btn">❌ Rejected</a>
            <a href="{{ route('admin.reports.transport_officer', ['status' => 'all']) }}" class="btn btn-outline-dark report-btn">📁 All</a>
        </div>
    </div>
</div>

{{-- ---------------- CSS Styling ---------------- --}}
<style>
    .report-btn {
        min-width: 160px;
        transition: all 0.3s ease;
        font-weight: 500;
        border-radius: 6px;
    }

    .report-btn:hover {
        transform: scale(1.06);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    }

    .card-header {
        font-weight: 600;
        letter-spacing: 0.5px;
        border-bottom: none;
    }

    .bg-gradient-info { background: linear-gradient(90deg, #17a2b8, #138496); }
    .bg-gradient-warning { background: linear-gradient(90deg, #f6c23e, #dda20a); }
    .bg-gradient-secondary { background: linear-gradient(90deg, #858796, #6c757d); }
    .bg-gradient-primary { background: linear-gradient(90deg, #4e73df, #224abe); }

    .card { border-radius: 10px; overflow: hidden; }
</style>

{{-- ---------------- JavaScript Confirmation ---------------- --}}
<script>
    document.querySelectorAll('.report-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            const name = btn.textContent.trim();
            if (!confirm(`📦 Are you sure you want to download the "${name}" report as Excel?`)) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
