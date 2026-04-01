<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
{
    $today = \Carbon\Carbon::today()->toDateString();

    $attendance = Attendance::with('member')
        ->where('attendance_date', $today)
        ->paginate(5);

    $totalEmployees = Member::count();

    // Get IDs of people who marked attendance today
    $presentIds = Attendance::where('attendance_date', $today)
        ->pluck('member_id');

    // Absentees = those NOT in attendance
    $absentCount = Member::whereNotIn('id', $presentIds)->count();

    return view('pages.attendance', compact('attendance','totalEmployees','absentCount'));
}

    // public static function markLoginAttendance($memberId)
    // {
    //     $today = Carbon::today()->toDateString();

    //     $exists = Attendance::where('member_id', $memberId)
    //         ->where('attendance_date', $today)
    //         ->exists();

    //     if (!$exists) {
    //         Attendance::create([
    //             'member_id' => $memberId,
    //             'attendance_date' => $today,
    //             'status' => 'present',
    //             'approval_status' => 'approved'
    //         ]);
    //     }
    // }
}
