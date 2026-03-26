<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;

class ForgotPasswordController extends Controller
{
    // 🔹 Show forgot password form
    public function showForm()
    {
        return view('forgot-password');
    }

    // 🔹 Send reset link to email
    public function sendLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $member = Member::where('email', $request->email)->first();

        if (!$member) {
            return back()->withErrors(['email' => 'Email not found']);
        }

        // Generate token
        $token = Str::random(64);

        // Store in DB
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Create reset link
        $link = url('/reset-password/'.$token.'?email='.$request->email);

        // Send email
        Mail::raw("Click here to reset your password: ".$link, function($message) use ($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('success', 'Reset link sent to your email');
    }

    // 🔹 Show reset password form
    public function showResetForm(Request $request, $token)
    {
        return view('reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // 🔹 Update password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4|confirmed'
        ]);

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Invalid or expired link']);
        }

        // Update password
        Member::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Delete token after use
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password reset successful');
    }
}