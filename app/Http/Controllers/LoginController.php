<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('login'); // resources/views/login.blade.php
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            $roleName = strtolower(trim($user->role->name ?? ''));

            if (str_contains($roleName, 'admin')) {
                return redirect()->route('admin.dashboard');
            }

            if (str_contains($roleName, 'driver')) {
                return redirect()->route('driver.dashboard');
            }

            // section manager variations: 'section manager', 'section_manager', 'section-manager'
            if (str_contains($roleName, 'section') && str_contains($roleName, 'manager')) {
                return redirect()->route('section_manager.dashboard');
            }

            if (str_contains($roleName, 'mechanic')) {
                return redirect()->route('mechanic_officer.dashboard');
            }

            if (str_contains($roleName, 'transport')) {
                return redirect()->route('transport_officer.dashboard');
            }

            Auth::logout();
            return redirect()->route('login')->with('error', 'Role not recognized');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
