<?php

namespace App\Services;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceService
{
    public static function markLogin($memberId)
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();
        $time = $now->format('H:i:s');

        // Set fixed boundary times for today
        $morningStart   = Carbon::today()->setHour(9)->setMinute(0)->setSecond(0);
        $morningLate    = Carbon::today()->setHour(9)->setMinute(15)->setSecond(0);
        $lunchEnd       = Carbon::today()->setHour(14)->setMinute(0)->setSecond(0);
        $afternoonLate  = Carbon::today()->setHour(14)->setMinute(15)->setSecond(0);

        $attendance = Attendance::firstOrCreate(
            ['member_id' => $memberId, 'attendance_date' => $today],
            [
                'status' => 'present',
                'approval_status' => 'approved',
                'total_work_minutes' => 0,
                'is_late' => 0,
                'is_afternoon_late' => 0,
                'late_minutes' => 0,
                'afternoon_late_minutes' => 0
            ]
        );

        // --- LOGIN LOGIC ---
        // --- LOGIN LOGIC ---
    if ($now->lt($lunchEnd)) {
        // ... (Keep your existing morning login logic here) ...
        if (!$attendance->check_in) {
            $attendance->check_in = $time;
            if ($now->gt($morningLate)) {
            $attendance->is_late = 1;
            $attendance->status = 'late';
            $attendance->late_minutes = $now->diffInMinutes($morningLate, true);
        } else {
            $attendance->status = 'ontime';
        }
        }
        $attendance->lunch_out = null;
        // ... (rest of your morning code) ...
    } else {
        // --- AFTERNOON LOGIN LOGIC ---
        
        // ADD THIS CHECK: If they checked in this morning but NEVER clicked lunch out
        if ($attendance->check_in && !$attendance->lunch_out) {
            // Assume they went out at 1:00 PM (13:00)
            $attendance->lunch_out = '13:00:00';
            
            // Calculate morning minutes: Check-in until 1:00 PM
            $startTime = Carbon::parse($attendance->attendance_date . ' ' . $attendance->check_in);
            $assumedLunch = Carbon::parse($attendance->attendance_date . ' 13:00:00');
            
            $morningMinutes = $assumedLunch->diffInMinutes($startTime, true);
            
            // Add those morning minutes to the total work minutes immediately
            $attendance->total_work_minutes = (int)$attendance->total_work_minutes + $morningMinutes;
        }

        if (!$attendance->post_lunch_in) {
            $attendance->post_lunch_in = $time;
        }
        $attendance->check_out = null;

        // ... (keep your existing afternoon lateness logic below) ...
        if ($now->gt($lunchEnd)) {
            $attendance->afternoon_late_minutes = $now->diffInMinutes($lunchEnd, true);
            if ($now->gt($afternoonLate)) {
                $attendance->is_afternoon_late = 1;
                $attendance->status = 'late';
            }
        }
    }

        $attendance->save();
    }

    public static function markLogout($memberId)
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();
        $time = $now->format('H:i:s');

        $lunchStart = Carbon::today()->setHour(13)->setMinute(0)->setSecond(0);
        $lunchEnd   = Carbon::today()->setHour(14)->setMinute(0)->setSecond(0);

        $attendance = Attendance::where('member_id', $memberId)
            ->whereDate('attendance_date', $today)
            ->first();

        if (!$attendance) return;

        $sessionMinutes = 0;

        // --- CALCULATION LOGIC ---
        if ($now->lt($lunchEnd)) {
            if ($attendance->check_in) {
                $startTime = Carbon::parse($attendance->attendance_date . ' ' . $attendance->check_in);
                $endTime = ($now->gt($lunchStart)) ? $lunchStart : $now;
                
                // Use absolute difference to prevent negative numbers
                $diffInSeconds = $endTime->diffInSeconds($startTime, true);
                $sessionMinutes = round($diffInSeconds / 60);
                
                if ($sessionMinutes == 0 && $diffInSeconds > 30) {
                    $sessionMinutes = 1;
                }
            }
            $attendance->lunch_out = $time;
        } else {
            if ($attendance->post_lunch_in) {
                $startTime = Carbon::parse($attendance->attendance_date . ' ' . $attendance->post_lunch_in);
                $diffInSeconds = $now->diffInSeconds($startTime, true);
                $sessionMinutes = round($diffInSeconds / 60);
                
                if ($sessionMinutes == 0 && $diffInSeconds > 30) {
                    $sessionMinutes = 1;
                }
            } elseif ($attendance->check_in && !$attendance->lunch_out) {
                // Forgot Lunch: (Total time from check_in until now) - 60
                $startTime = Carbon::parse($attendance->attendance_date . ' ' . $attendance->check_in);
                $totalDiff = $now->diffInMinutes($startTime, true);
                $sessionMinutes = max(0, $totalDiff - 60);
            }
            $attendance->check_out = $time;
        }

        // Add the new session minutes to whatever is already there
        $attendance->total_work_minutes = (int)$attendance->total_work_minutes + (int)$sessionMinutes;
        
        // Final Status Checks
        $attendance->is_full_day = ($attendance->total_work_minutes >= 420) ? 1 : 0;
        
        // Keep status as late if they were late at any point
        if ($attendance->is_late || $attendance->is_afternoon_late) {
            $attendance->status = 'late';
        }

        $attendance->save();
    }
}