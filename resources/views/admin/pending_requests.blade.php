@extends('layouts.admin')

@section('title', 'Pending Requests Overview')

@section('content')
<style>
    body {
        background: #f0f4f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h2, h3 {
        font-family: 'Poppins', sans-serif;
    }

    .container {
        max-width: 1200px;
        margin: auto;
    }

    /* Tabs style */
    .tab-container {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 25px;
    }

    .tab-btn {
        background: linear-gradient(135deg, #ece9e6, #ffffff);
        border: 2px solid #ddd;
        padding: 12px 24px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #4ade80, #16a34a);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    }

    /* Tab content */
    .tab-content {
        display: none;
        animation: fadeIn 0.4s ease-in-out;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Table styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 15px rgba(0,0,0,0.05);
        background: #fff;
    }

    th {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        color: white;
        padding: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    td {
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
        text-align: center;
    }

    tr:hover {
        background: #f3f4f6;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }

    /* Badge styles */
    .badge {
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: transform 0.2s ease;
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
    }

    /* Empty state */
    .empty-state {
        background: #fef9c3;
        padding: 15px 20px;
        border-radius: 10px;
        font-weight: 500;
        text-align: center;
        color: #b45309;
        margin-top: 10px;
    }
</style>

<div class="container p-6">
    <h2 class="text-3xl font-bold mb-6 text-center">🕒 Pending Requests Overview</h2>

    <!-- Tabs -->
    <div class="tab-container">
        <button class="tab-btn active" data-tab="section">📋 Section Manager</button>
        <button class="tab-btn" data-tab="mechanic">🧰 Mechanic Officer</button>
        <button class="tab-btn" data-tab="transport">🚛 Transport Officer</button>
    </div>

    <!-- SECTION MANAGER -->
    <div id="section" class="tab-content active">
        <h3 class="text-xl font-semibold mb-3 text-blue-600">📋 Section Manager Pending Requests</h3>
        @if($sectionManagerRequests->isEmpty())
            <div class="empty-state">No pending requests at Section Manager level.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Driver</th>
                        <th>Vehicle</th>
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
                        <td>{{ $req->vehicle->license_plate ?? 'N/A' }}</td>
                        <td>{{ $req->tire->brand ?? 'N/A' }}</td>
                        <td>{{ $req->created_at->format('Y-m-d') }}</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- MECHANIC OFFICER -->
    <div id="mechanic" class="tab-content">
        <h3 class="text-xl font-semibold mb-3 text-orange-600">🧰 Mechanic Officer Pending Requests</h3>
        @if($mechanicOfficerRequests->isEmpty())
            <div class="empty-state">No pending requests at Mechanic Officer level.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Driver</th>
                        <th>Vehicle</th>
                        <th>Tire</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mechanicOfficerRequests as $req)
                    <tr>
                        <td>{{ $req->id }}</td>
                        <td>{{ $req->user->name ?? 'N/A' }}</td>
                        <td>{{ $req->vehicle->license_plate ?? 'N/A' }}</td>
                        <td>{{ $req->tire->brand ?? 'N/A' }}</td>
                        <td>{{ $req->created_at->format('Y-m-d') }}</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- TRANSPORT OFFICER -->
    <div id="transport" class="tab-content">
        <h3 class="text-xl font-semibold mb-3 text-green-600">🚛 Transport Officer Pending Requests</h3>
        @if($transportOfficerRequests->isEmpty())
            <div class="empty-state">No pending requests at Transport Officer level.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Driver</th>
                        <th>Vehicle</th>
                        <th>Tire</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transportOfficerRequests as $req)
                    <tr>
                        <td>{{ $req->id }}</td>
                        <td>{{ $req->user->name ?? 'N/A' }}</td>
                        <td>{{ $req->vehicle->license_plate ?? 'N/A' }}</td>
                        <td>{{ $req->tire->brand ?? 'N/A' }}</td>
                        <td>{{ $req->created_at->format('Y-m-d') }}</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<script>
    const tabs = document.querySelectorAll(".tab-btn");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach(btn => {
        btn.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active"));
            contents.forEach(c => c.classList.remove("active"));
            btn.classList.add("active");
            document.getElementById(btn.dataset.tab).classList.add("active");
        });
    });
</script>
@endsection
