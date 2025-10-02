<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Reload user with role to avoid null
            $user = Auth::user()->load('role');

            if (!$user->role) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'User has no role assigned');
            }

            // Normalize role name to a predictable key (lowercase, spaces and hyphens -> underscore)
            $roleRaw = $user->role->name ?? '';
            $role = strtolower(trim($roleRaw));
            $role = str_replace([' ', '-'], '_', $role);

            // Redirect based on normalized role
            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');

                case 'driver':
                    return redirect()->route('driver.dashboard');

                case 'section_manager':
                    return redirect()->route('section_manager.dashboard');

                case 'mechanic_officer':
                    return redirect()->route('mechanic_officer.dashboard');

                case 'transport_officer':
                    return redirect()->route('transport_officer.dashboard');

                default:
                    // Unknown role: logout for safety and show a message
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Role not recognized');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])
            ->withInput($request->only('email'));
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}