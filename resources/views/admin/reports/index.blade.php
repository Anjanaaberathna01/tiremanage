@extends('layouts.admin')

@section('title', 'Reports')

@push('styles')
<style>
    /* Page-specific background: Lotus Tower with stronger dark overlay for contrast */
    body {
        background: linear-gradient(rgba(0,0,0,.68), rgba(0,0,0,.68)),
                    url('{{ asset('assets/images/Lotus-Tower.jpg') }}') center / cover fixed no-repeat !important;
        color: #e5e7eb; /* keep text readable over image */
    }
    .text-muted { color: rgba(255,255,255,.72) !important; }

    /* Improve readability on image background */
    .card { background-color: rgba(17, 24, 39, 0.94); backdrop-filter: blur(2px); border: 1px solid rgba(255,255,255,.06); }
    .card-header { background-color: transparent !important; }
    .bg-brand-gradient { background: linear-gradient(90deg, rgba(0,87,168,.95), rgba(57,181,74,.95)); }
    .report-btn { color: #e5e7eb; }
    .report-btn:hover { color: #ffffff; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-2 text-primary fw-bold">Reports</h2>
    <p class="text-muted mb-4">1) Choose date range, 2) Click a report to download Excel.</p>

    <!-- Filters -->
    <form method="GET" action="{{ url()->current() }}" class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-brand-gradient text-white">
            <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filters</h6>
        </div>
        <div class="card-body row g-3 align-items-end">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <label for="start" class="form-label text-muted">Start date</label>
                <input type="date" id="start" name="start" value="{{ request('start') }}" class="form-control">
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <label for="end" class="form-label text-muted">End date</label>
                <input type="date" id="end" name="end" value="{{ request('end') }}" class="form-control">
            </div>
            <div class="col-sm-12 col-md-4 col-lg-3">
                <button type="submit" class="btn btn-primary btn-elevated"><i class="bi bi-filter"></i> Apply</button>
                <a href="{{ url()->current() }}" class="btn btn-outline-dark ms-2">Clear</a>
            </div>
        </div>
    </form>

    <!-- General Data Reports -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-brand-gradient text-white">
            <h5 class="mb-0"><i class="bi bi-file-earmark-excel me-2"></i>General Data</h5>
        </div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('admin.reports.vehicles', ['start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-success report-btn" title="Download Vehicles (Excel)"><i class="bi bi-file-earmark-excel me-1"></i> Vehicles</a>
            <a href="{{ route('admin.reports.drivers', ['start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-primary report-btn" title="Download Drivers (Excel)"><i class="bi bi-file-earmark-excel me-1"></i> Drivers</a>
            <a href="{{ route('admin.reports.suppliers', ['start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-info report-btn" title="Download Suppliers (Excel)"><i class="bi bi-file-earmark-excel me-1"></i> Suppliers</a>
            <a href="{{ route('admin.reports.tires', ['start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-warning report-btn" title="Download Tyres (Excel)"><i class="bi bi-file-earmark-excel me-1"></i> Tyres</a>
        </div>
    </div>

    <!-- Section Manager Reports -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-brand-gradient text-white">
            <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Section Manager Requests</h5>
        </div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('admin.reports.section_manager', ['status' => 'pending', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-warning report-btn" title="Pending Requests (Excel)">Pending</a>
            <a href="{{ route('admin.reports.section_manager', ['status' => 'approved', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-success report-btn" title="Approved Requests (Excel)">Approved</a>
            <a href="{{ route('admin.reports.section_manager', ['status' => 'rejected', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-danger report-btn" title="Rejected Requests (Excel)">Rejected</a>
            <a href="{{ route('admin.reports.section_manager', ['status' => 'all', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-dark report-btn" title="All Requests (Excel)">All</a>
        </div>
    </div>

    <!-- Mechanic Officer Reports -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-brand-gradient text-white">
            <h5 class="mb-0"><i class="bi bi-tools me-2"></i>Mechanic Officer Requests</h5>
        </div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('admin.reports.mechanic_officer', ['status' => 'pending', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-warning report-btn" title="Pending Requests (Excel)">Pending</a>
            <a href="{{ route('admin.reports.mechanic_officer', ['status' => 'approved', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-success report-btn" title="Approved Requests (Excel)">Approved</a>
            <a href="{{ route('admin.reports.mechanic_officer', ['status' => 'rejected', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-danger report-btn" title="Rejected Requests (Excel)">Rejected</a>
            <a href="{{ route('admin.reports.mechanic_officer', ['status' => 'all', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-dark report-btn" title="All Requests (Excel)">All</a>
        </div>
    </div>

    <!-- Transport Officer Reports -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-brand-gradient text-white">
            <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Transport Officer Requests</h5>
        </div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('admin.reports.transport_officer', ['status' => 'pending', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-warning report-btn" title="Pending Requests (Excel)">Pending</a>
            <a href="{{ route('admin.reports.transport_officer', ['status' => 'approved', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-success report-btn" title="Approved Requests (Excel)">Approved</a>
            <a href="{{ route('admin.reports.transport_officer', ['status' => 'rejected', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-danger report-btn" title="Rejected Requests (Excel)">Rejected</a>
            <a href="{{ route('admin.reports.transport_officer', ['status' => 'all', 'start'=>request('start'), 'end'=>request('end')]) }}" class="btn btn-outline-dark report-btn" title="All Requests (Excel)">All</a>
        </div>
    </div>
</div>

<style>
    .report-btn { min-width: 160px; transition: all 0.2s ease; font-weight: 500; border-radius: 6px; }
    .report-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25); }
    .card-header { font-weight: 600; letter-spacing: 0.5px; border-bottom: none; }
    .bg-brand-gradient { background: linear-gradient(90deg, var(--primary), var(--brand-green)); }
    .card { border-radius: 10px; overflow: hidden; }
</style>

@endsection
