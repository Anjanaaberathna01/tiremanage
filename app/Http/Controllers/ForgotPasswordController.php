<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Step 1: Show Forgot Password Form
     */
    public function showRequestForm()
    {
        return view('driver.forgot_password');
    }

    /**
     * Step 2: Handle OTP Sending
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()->with('status', 'If that email exists, an OTP has been sent.');
        }

        // Generate and store OTP
        $otp = random_int(100000, 999999);

        DB::table('password_otps')->insert([
            'user_id' => $user->id,
            'email' => $email,
            'otp_hash' => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send OTP email
        Mail::to($email)->send(new SendOtpMail($otp, $user->name ?? $user->email));

        // Store email in session for continuity
        session(['email_for_otp' => $email]);

        return redirect()
            ->route('driver.password.verify.form')
            ->with('status', 'OTP sent to your email. Please check your inbox.');
    }

    /**
     * Step 3: Show OTP Verification Form
     */
    public function showVerifyForm(Request $request)
    {
        $email = session('email_for_otp');
        if (! $email) {
            return redirect()->route('driver.password.request.form')
                ->with('error', 'Please enter your email to get an OTP.');
        }

        return view('emails.verify_otp', compact('email'));
    }

    /**
     * Step 4: Verify the OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $record = DB::table('password_otps')
            ->where('email', $request->email)
            ->orderByDesc('created_at')
            ->first();

        if (! $record) {
            return back()->withErrors(['otp' => 'OTP not found. Please request a new one.']);
        }

        if (Carbon::parse($record->expires_at)->isPast()) {
            return back()->withErrors(['otp' => 'OTP has expired.']);
        }

        if (! Hash::check($request->otp, $record->otp_hash)) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        // OTP verified — allow reset
        session([
            'password_reset_allowed' => true,
            'password_reset_email' => $request->email,
        ]);

        return redirect()
            ->route('driver.password.reset.form')
            ->with('status', 'OTP verified. You can now reset your password.');
    }

    /**
     * Step 5: Show Reset Password Form
     */
    public function showResetForm()
    {
        if (! session('password_reset_allowed') || ! session('password_reset_email')) {
            return redirect()->route('driver.password.request.form')
                ->with('error', 'Please verify OTP first.');
        }

        $email = session('password_reset_email');
        return view('driver.reset_password', compact('email'));
    }

    /**
     * Step 6: Reset the Password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if (! session('password_reset_allowed') || session('password_reset_email') !== $request->email) {
            return redirect()->route('driver.password.request.form')
                ->with('error', 'OTP verification required.');
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return redirect()->route('driver.password.request.form')
                ->with('error', 'User not found.');
        }

        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        // Clear session flags
        session()->forget([
            'password_reset_allowed',
            'password_reset_email',
            'email_for_otp'
        ]);

        return redirect()->route('login')
            ->with('success', 'Password reset successfully! You can now login.');
    }
}
