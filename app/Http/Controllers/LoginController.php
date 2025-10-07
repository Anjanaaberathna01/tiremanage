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
$remember = $request->has('remember'); // true if checkbox is checked

if (Auth::attempt($credentials, $remember)) {
    $request->session()->regenerate();

    // Reload user with role to avoid null
    $user = Auth::user()->load('role');

    if (!$user->role) {
        Auth::logout();
        return redirect()->route('login')->with('error', 'User has no role assigned');
    }

    $roleRaw = $user->role->name ?? '';
    $role = strtolower(trim($roleRaw));
    $role = str_replace([' ', '-'], '_', $role);

    switch ($role) {
        case 'admin':
            return redirect()->route('admin.dashboard');

        case 'driver':
            return redirect()->route('driver.dashboard');

        case 'section_manager':
            return redirect()->route('section_manager.dashboard');

        case 'mechanic_officer':
            return redirect()->route('mechanic_officer.pending');

        case 'transport_officer':
            return redirect()->route('transport_officer.dashboard');

        default:
            Auth::logout();
            return redirect()->route('login')->with('error', 'Role not recognized');
    }
}

return back()->withErrors(['email' => 'Invalid credentials.'])
    ->withInput($request->only('email', 'remember'));


    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
