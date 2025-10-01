<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if(Auth::attempt($request->only('email','password'))){
            $request->session()->regenerate();
            $user = Auth::user();

            switch($user->role->name){
                case 'Admin': return redirect()->route('dashboard.admin');
                case 'Driver': return redirect()->route('dashboard.driver');
                case 'Section Manager': return redirect()->route('dashboard.section_manager');
                case 'Mechanic Officer': return redirect()->route('dashboard.mechanic_officer');
                case 'Transport Officer': return redirect()->route('dashboard.transport_officer');
                default:
                    Auth::logout();
                    return redirect()->route('login')->with('error','Role not recognized');
            }
        }

        return back()->withErrors(['email'=>'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}