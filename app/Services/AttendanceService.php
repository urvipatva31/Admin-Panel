<?php

namespace App\Services;

use App\Models\Leave;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceService
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public static function markLogin($memberId)
    {
        $today = Carbon::today()->toDateString();
        $now   = Carbon::now();

        // Check approved leave
        $leave = Leave::where('member_id', $memberId)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        // Default timings (Full day)
        $lateAfter = Carbon::createFromTime(9, 10);

        // Half day leave → afternoon shift
        if ($leave && $leave->leave_type === 'half_day') {
            $lateAfter = Carbon::createFromTime(14, 40);
        }

        // Fetch today's attendance
        $attendance = Attendance::where('member_id', $memberId)
            ->where('attendance_date', $today)
            ->first();

        // Prevent duplicate check-in
        if ($attendance && $attendance->check_in) {
            return;
        }

        // Late calculation
        $isLate = $now->gt($lateAfter);
        $lateMinutes = $isLate ? $lateAfter->diffInMinutes($now) : 0;

        Attendance::updateOrCreate(
            [
                'member_id' => $memberId,
                'attendance_date' => $today,
            ],
            [
                'check_in' => $now->format('H:i:s'),
                'status' => $isLate ? 'late' : 'present',
                'is_late' => $isLate,
                'late_minutes' => $lateMinutes,
                'remarks' => self::getLocation()
                    . ($isLate ? ' | Late login' : ''),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public static function markLogout($memberId)
    {
        $today = Carbon::today()->toDateString();

        $attendance = Attendance::where('member_id', $memberId)
            ->where('attendance_date', $today)
            ->first();

        // No record OR already logged out
        if (!$attendance || $attendance->check_out) {
            return;
        }

        $now = Carbon::now();
        $attendance->check_out = $now->format('H:i:s');

        // Working hours calculation
        if ($attendance->check_in) {

            $checkIn  = Carbon::createFromFormat('H:i:s', $attendance->check_in);
            $checkOut = $now;

            $totalMinutes = $checkOut->diffInMinutes($checkIn);

            /*
            |--------------------------------------------------------------------------
            | Lunch Deduction (1:30 PM – 2:30 PM)
            | Deduct only if employee worked during lunch period
            |--------------------------------------------------------------------------
            */
            $lunchStart = Carbon::createFromTime(13, 30);
            $lunchEnd   = Carbon::createFromTime(14, 30);

            if ($checkIn->lt($lunchEnd) && $checkOut->gt($lunchStart)) {
                $totalMinutes -= 60;
            }

            if ($totalMinutes < 0) {
                $totalMinutes = 0;
            }

            $attendance->total_work_minutes = $totalMinutes;

            // 8 hours = 480 minutes
            $attendance->is_full_day = $totalMinutes >= 480;
        }

        $attendance->save();
    }


    /*
    |--------------------------------------------------------------------------
    | LOCATION
    |--------------------------------------------------------------------------
    */
    private static function getLocation()
    {
        $ip = request()->ip();

        try {
            $data = json_decode(
                file_get_contents("http://ip-api.com/json/{$ip}"),
                true
            );

            if ($data && $data['status'] === 'success') {
                return "{$data['city']}, {$data['regionName']}, {$data['country']} (IP: {$ip})";
            }
        } catch (\Exception $e) {
        }

        return "Unknown Location (IP: {$ip})";
    }
}
