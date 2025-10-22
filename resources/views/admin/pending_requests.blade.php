@extends('layouts.admin')

@section('title', 'Pending Requests Overview')

@section('content')
<div class="container p-4">
    <h2 class="mb-4 fw-bold">Pending Requests Overview</h2>

    {{-- Tabs --}}
    <ul class="nav nav-pills gap-2 mb-3" id="pendingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="section-tab" data-bs-toggle="tab" data-bs-target="#section" type="button" role="tab" aria-controls="section" aria-selected="true">
                <i class="bi bi-people"></i> Section Manager
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="mechanic-tab" data-bs-toggle="tab" data-bs-target="#mechanic" type="button" role="tab" aria-controls="mechanic" aria-selected="false">
                <i class="bi bi-wrench"></i> Mechanic Officer
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="transport-tab" data-bs-toggle="tab" data-bs-target="#transport" type="button" role="tab" aria-controls="transport" aria-selected="false">
                <i class="bi bi-truck"></i> Transport Officer
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pendingTabsContent">
        {{-- Section Manager --}}
        <div class="tab-pane fade show active" id="section" role="tabpanel" aria-labelledby="section-tab">
            <div class="card mb-4">
                <div class="card-header">Section Manager Pending Requests</div>
                <div class="card-body p-0">
                    @if($sectionManagerRequests->isEmpty())
                        <div class="p-3 text-center text-muted">No pending requests at Section Manager level.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Driver</th>
                                        <th>Vehicle</th>
                                        <th>Size</th>
                                        <th>Tyre</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sectionManagerRequests as $req)
                                    <tr>
                                        <td>{{ $req->id }}</td>
                                        <td>{{ $req->user->name ?? 'N/A' }}</td>
                                        <td>{{ $req->vehicle->plate_no ?? 'N/A' }}</td>
                                        <td>{{ $req->tire->size ?? 'N/A' }}</td>
                                        <td>{{ $req->tire->brand ?? 'N/A' }}</td>
                                        <td>{{ $req->created_at->format('Y-m-d') }}</td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Mechanic Officer --}}
        <div class="tab-pane fade" id="mechanic" role="tabpanel" aria-labelledby="mechanic-tab">
            <div class="card mb-4">
                <div class="card-header">Mechanic Officer Pending Requests</div>
                <div class="card-body p-0">
                    @if($mechanicOfficerRequests->isEmpty())
                        <div class="p-3 text-center text-muted">No pending requests at Mechanic Officer level.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Driver</th>
                                        <th>Vehicle</th>
                                        <th>Size</th>
                                        <th>Tyre Brand</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mechanicOfficerRequests as $req)
                                    <tr>
                                        <td>{{ $req->id }}</td>
                                        <td>{{ $req->user->name ?? 'N/A' }}</td>
                                        <td>{{ $req->vehicle->plate_no ?? 'N/A' }}</td>
                                        <td>{{ $req->tire->size ?? 'N/A' }}</td>
                                        <td>{{ $req->tire->brand ?? 'N/A' }}</td>
                                        <td>{{ $req->created_at->format('Y-m-d') }}</td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Transport Officer --}}
        <div class="tab-pane fade" id="transport" role="tabpanel" aria-labelledby="transport-tab">
            <div class="card mb-4">
                <div class="card-header">Transport Officer Pending Requests</div>
                <div class="card-body p-0">
                    @if($transportOfficerRequests->isEmpty())
                        <div class="p-3 text-center text-muted">No pending requests at Transport Officer level.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Driver</th>
                                        <th>Vehicle</th>
                                        <th>Size</th>
                                        <th>Tyre Brand</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transportOfficerRequests as $req)
                                    <tr>
                                        <td>{{ $req->id }}</td>
                                        <td>{{ $req->user->name ?? 'N/A' }}</td>
                                        <td>{{ $req->vehicle->plate_no ?? 'N/A' }}</td>
                                        <td>{{ $req->tire->size ?? 'N/A' }}</td>
                                        <td>{{ $req->tire->brand ?? 'N/A' }}</td>
                                        <td>{{ $req->created_at->format('Y-m-d') }}</td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

