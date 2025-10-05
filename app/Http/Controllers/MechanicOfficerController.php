<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TireRequest;

class MechanicOfficerController extends Controller
{
    public function pending()
    {
        $requests = TireRequest::where('status', 'pending')->get();
        return view('dashboard.mechanic_officer.pending', compact('requests'));
    }

    public function approved()
    {
        $requests = TireRequest::where('status', 'approved')->get();
        return view('dashboard.mechanic_officer.approved', compact('requests'));
    }

    public function rejected()
    {
        $requests = TireRequest::where('status', 'rejected')->get();
        return view('dashboard.mechanic_officer.rejected', compact('requests'));
    }
}
