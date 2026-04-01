<?php

namespace App\Services;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceService
{
    public static function markLogin($memberId)
    {
        $today = Carbon::today()->toDateString();
        $now   = Carbon::now();

        $attendance = Attendance::where('member_id', $memberId)
            ->whereDate('attendance_date', $today)
            ->first();

        // ✅ CASE 1: NO RECORD → CREATE NEW
        if (!$attendance) {

            // Morning login
            if ($now->lt(Carbon::createFromTime(13, 0))) {

                Attendance::create([
                    'member_id' => $memberId,
                    'attendance_date' => $today,
                    'check_in' => $now->format('H:i:s'),
                    'status' => 'late',
                    'is_late' => 1,
                ]);
            } else {

                // Direct afternoon login (half day)
                Attendance::create([
                    'member_id' => $memberId,
                    'attendance_date' => $today,
                    'post_lunch_in' => $now->format('H:i:s'),
                    'status' => 'late',
                ]);
            }

            return;
        }

        // ✅ CASE 2: EMPTY ROW (created earlier) → FILL IT
        if (empty($attendance->check_in) && empty($attendance->post_lunch_in)) {

            if ($now->lt(Carbon::createFromTime(13, 0))) {

                $attendance->check_in = $now->format('H:i:s');
                $attendance->status = 'late';
                $attendance->is_late = 1;
            } else {

                $attendance->post_lunch_in = $now->format('H:i:s');
                $attendance->status = 'late';
            }

            $attendance->save();
            return;
        }

        // ✅ CASE 3: POST LUNCH LOGIN (after lunch out)
        if (!empty($attendance->lunch_out) && empty($attendance->post_lunch_in)) {

            $attendance->post_lunch_in = $now->format('H:i:s');
            $attendance->save();
            return;
        }
    }

    public static function markLogout($memberId)
{
    $today = Carbon::today()->toDateString();
    $now   = Carbon::now();

    $attendance = Attendance::where('member_id', $memberId)
        ->whereDate('attendance_date', $today)
        ->first();

    if (!$attendance) return;

    // SET CHECKOUT TIME
    $attendance->check_out = $now->format('H:i:s');

    $morningMinutes = 0;
    $afternoonMinutes = 0;

    // ✅ MORNING: (Lunch out - Check in)
    if (!empty($attendance->check_in) && !empty($attendance->lunch_out)) {
        $morningMinutes = Carbon::createFromFormat('H:i:s', $attendance->lunch_out)
            ->diffInMinutes(Carbon::createFromFormat('H:i:s', $attendance->check_in));
    }

    // ✅ AFTERNOON: (Check out - Post lunch in)
    if (!empty($attendance->post_lunch_in)) {
        $afternoonMinutes = Carbon::createFromFormat('H:i:s', $attendance->check_out)
            ->diffInMinutes(Carbon::createFromFormat('H:i:s', $attendance->post_lunch_in));
    }

    // ✅ TOTAL
    $totalMinutes = $morningMinutes + $afternoonMinutes;

    // ❗ Safety fix (NO NEGATIVE VALUES EVER)
    if ($totalMinutes < 0) {
        $totalMinutes = 0;
    }

    $attendance->total_work_minutes = $totalMinutes;
    $attendance->is_full_day = $totalMinutes >= 480;

    $attendance->save();
}
}
