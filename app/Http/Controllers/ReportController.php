<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VehiclesExport;
use App\Exports\DriversExport;
use App\Exports\SuppliersExport;
use App\Exports\SectionManagerRequestsExport;
use App\Exports\MechanicOfficerRequestsExport;
use App\Exports\TransportOfficerRequestsExport;
use App\Models\Approval;

use App\Exports\TiresExport;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Ensure only admin role can access (case-insensitive)
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || strtolower($user->role->name ?? '') !== 'admin') {
                abort(403, 'Unauthorized.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('admin.reports.index');
    }

    public function exportVehicles()
    {
        return Excel::download(new VehiclesExport, 'vehicles_report.xlsx');
    }

    public function exportDrivers()
    {
        return Excel::download(new DriversExport, 'drivers_report.xlsx');
    }

    public function exportSuppliers()
    {
        return Excel::download(new SuppliersExport, 'suppliers_report.xlsx');
    }

    public function exportTires()
    {
        return Excel::download(new TiresExport, 'tires_report.xlsx');
    }
        /** SECTION MANAGER REPORTS **/
    public function exportSectionManager($status)
    {
        return Excel::download(
            new SectionManagerRequestsExport($status),
            "section_manager_{$status}_requests.xlsx"
        );
    }

    /** MECHANIC OFFICER REPORTS **/
public function exportMechanicOfficer($status)
{
    return Excel::download(new MechanicOfficerRequestsExport($status), 'mechanic_officer_requests.xlsx');
}


    /** TRANSPORT OFFICER REPORTS **/
    public function exportTransportOfficer($status)
    {
        return Excel::download(
            new TransportOfficerRequestsExport($status),
            "transport_officer_{$status}_requests.xlsx"
        );
    }

}
