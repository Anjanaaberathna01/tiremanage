<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TireRequest;
use Illuminate\Support\Facades\Redirect;

class SectionManagerController extends Controller
{
    // Approve a tire request
    public function approve($id)
    {
        $req = TireRequest::findOrFail($id);
        $req->status = 'approved';
        $req->save();

        return Redirect::back()->with('success', "Request #{$id} approved.");
    }

    // Reject a tire request
    public function reject($id)
    {
        $req = TireRequest::findOrFail($id);
        $req->status = 'rejected';
        $req->save();

        return Redirect::back()->with('success', "Request #{$id} rejected.");
    }
}
