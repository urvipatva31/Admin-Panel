<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\AttendanceService;

class MembersController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'useremail' => 'required|email|unique:members,email',
            'password' => 'required|min:4|confirmed',
            'password_confirmation' => 'required',
            'role_selection' => 'required'
        ]);

        Member::create([
            'full_name' => $request->name,
            'email' => $request->useremail,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_selection,
        ]);

        return redirect()->route('login')->with('success', 'Registered successfully');
    }


    public function login(Request $request)
    {
        $request->validate([
            'useremail' => 'required|email',
            'password' => 'required'
        ]);

        $member = Member::where('email', $request->useremail)->first();

        if (!$member || !Hash::check($request->password, $member->password)) {
            return back()->withErrors(['useremail' => 'Invalid credentials']);
        }

        session([
            'member_id' => $member->id,
            'member_name' => $member->full_name,
            'member_role' => $member->role_id,
        ]);

        AttendanceService::markLogin($member->id);

        AuditLog::logActivity(
            $member->id,
            'Login',
            'Authentication',
            'User logged in'
        );
        return redirect()->route('dashboard');
    }

    public function logout()
    {
        AttendanceService::markLogout(session('member_id'));
        AuditLog::logActivity(
            session('member_id'),
            'Logout',
            'Authentication',
            'User logged out'
        );
        Session::flush();
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
}
