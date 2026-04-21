<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;

class LeaveController extends Controller
{
    // Show ONLY pending leaves
   public function index()
{
    $leaves = Leave::with('member')
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    $approvedLeaves = Leave::with('member')
        ->whereIn('status', ['approved', 'rejected'])
        ->whereMonth('start_date', now()->month)
->whereYear('start_date', now()->year)
        ->orderBy('approved_at', 'desc')
        ->paginate(10, ['*'], 'approved_page');

    $year = now()->year;
    $month = now()->month;

    // helper
    $leaveDays = function ($leaves) {
        return $leaves->sum(function ($l) {
            return \Carbon\Carbon::parse($l->start_date)
                ->diffInDays(\Carbon\Carbon::parse($l->end_date)) + 1;
        });
    };

    $monthFilter = function ($query) use ($month, $year) {
        $query->where(function ($q) use ($month, $year) {
            $q->whereMonth('start_date', $month)->whereYear('start_date', $year)
              ->orWhereMonth('end_date', $month)->whereYear('end_date', $year);
        });
    };

    // attach leave usage to each row
    foreach ($leaves as $leave) {

        $memberId = $leave->member_id;

        // CASUAL
        $casualYear = $leaveDays(
            Leave::where('member_id', $memberId)
                ->whereRaw('LOWER(leave_type)="casual"')
                ->where('status', 'approved')
                ->whereYear('start_date', $year)
                ->get()
        );

        $casualMonth = $leaveDays(
            Leave::where('member_id', $memberId)
                ->whereRaw('LOWER(leave_type)="casual"')
                ->where('status', 'approved')
                ->where($monthFilter)
                ->get()
        );

        // SICK
        $sickYear = $leaveDays(
            Leave::where('member_id', $memberId)
                ->whereRaw('LOWER(leave_type)="sick"')
                ->where('status', 'approved')
                ->whereYear('start_date', $year)
                ->get()
        );

        $sickMonth = $leaveDays(
            Leave::where('member_id', $memberId)
                ->whereRaw('LOWER(leave_type)="sick"')
                ->where('status', 'approved')
                ->where($monthFilter)
                ->get()
        );

        // ANNUAL
        $annualYear = $leaveDays(
            Leave::where('member_id', $memberId)
                ->whereRaw('LOWER(leave_type)="annual"')
                ->where('status', 'approved')
                ->whereYear('start_date', $year)
                ->get()
        );

        // attach to object
        $leave->leave_usage = [
            'casualYear' => $casualYear,
            'casualMonth' => $casualMonth,
            'sickYear' => $sickYear,
            'sickMonth' => $sickMonth,
            'annualYear' => $annualYear,
        ];
    }

    return view('pages.leave-management', compact('leaves', 'approvedLeaves'));
}

    // Apply Leave
    public function apply(Request $request)
    {
        $request->validate([
            'leave_type' => 'required',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            // 'reason' => 'required|string|max:500'
        ]);

        $leave = Leave::create([
            'member_id' => session('member_id'),
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        AuditLog::logActivity(
            session('member_id'),
            'Applied',
            'Leave Management',
            'Applied for leave from ' . $leave->start_date . ' to ' . $leave->end_date
        );

        return back()->with('success', 'Leave request submitted');
    }

    // Approve Leave
    public function approve($id)
    {
        $leave = Leave::with('member')->findOrFail($id);

        $approverId = session('member_id');

        // Always fetch role from DB
        $approverRoleId = Member::where('id', $approverId)->value('role_id');

        $applicantId     = $leave->member_id;
        $applicantRoleId = $leave->member->role_id;

        if (!$this->canApprove($approverId, $approverRoleId, $applicantId, $applicantRoleId)) {
            return back()->with('error', 'You are not authorized to approve this leave.');
        }

        $start = \Carbon\Carbon::parse($leave->start_date);
$end   = \Carbon\Carbon::parse($leave->end_date);

$days = $start->diffInDays($end) + 1;

$leaveType = strtolower($leave->leave_type);

$monthlyUsed = Leave::where('member_id', $leave->member_id)
    ->whereRaw('LOWER(leave_type) = ?', [$leaveType])
    ->where('status', 'approved')
    ->whereMonth('start_date', $start->month)
    ->get()
    ->sum(function ($l) {
        return \Carbon\Carbon::parse($l->start_date)
            ->diffInDays(\Carbon\Carbon::parse($l->end_date)) + 1;
    });

$yearlyUsed = Leave::where('member_id', $leave->member_id)
    ->whereRaw('LOWER(leave_type) = ?', [$leaveType])
    ->where('status', 'approved')
    ->whereYear('start_date', $start->year)
    ->get()
    ->sum(function ($l) {
        return \Carbon\Carbon::parse($l->start_date)
            ->diffInDays(\Carbon\Carbon::parse($l->end_date)) + 1;
    });

// Default
$isPaid = 1;

// 🎯 Apply rules
if ($leaveType === 'casual') {

    if ($monthlyUsed + $days > 2 || $yearlyUsed + $days > 12) {
        $isPaid = 0;
    }

} elseif ($leaveType === 'sick') {

    if ($monthlyUsed + $days > 2 || $yearlyUsed + $days > 10) {
        $isPaid = 0;
    }

} elseif ($leaveType === 'annual') {

    if ($yearlyUsed + $days > 15) {
        $isPaid = 0;
    }
}
$override = request('is_paid_override');
if ($override === '1') {
    $isPaid = 1; // Paid
} elseif ($override === '0') {
    $isPaid = 0; // Unpaid
} elseif ($override === 'auto') {
    // do nothing → keep system logic
}


        $leave->update([
            'status' => 'approved',
            'is_paid' => $isPaid,
            'approved_by' => $approverId,
            'approved_at' => now()
        ]);

        AuditLog::logActivity(
            $approverId,
            'Approved',
            'Leave Management',
            'Approved leave for ' . $leave->member->full_name .
                ' from ' . $leave->start_date . ' to ' . $leave->end_date
        );

        $period = CarbonPeriod::create($leave->start_date, $leave->end_date);

        foreach ($period as $date) {
            Attendance::updateOrCreate(
                [
                    'member_id' => $leave->member_id,
                    'attendance_date' => $date->toDateString()
                ],
                [
                    'status' => 'leave',
                    'leave_id' => $leave->id,
                    'check_in' => null,
                    'check_out' => null,
                    'total_work_minutes' => 0
                ]
            );
        }

        return back()->with('success', 'Leave approved successfully.');
    }

    // Reject Leave
    public function reject($id)
    {
        $leave = Leave::with('member')->findOrFail($id);

        $approverId = session('member_id');

        // Fetch role from DB (same as approve)
        $approverRoleId = Member::where('id', $approverId)->value('role_id');

        $applicantId     = $leave->member_id;
        $applicantRoleId = $leave->member->role_id;

        if (!$this->canApprove($approverId, $approverRoleId, $applicantId, $applicantRoleId)) {
            return back()->with('error', 'You are not authorized to reject this leave.');
        }

        $leave->update([
            'status' => 'rejected',
            'is_paid' => 0,
            'approved_by' => $approverId,
            'approved_at' => now()
        ]);

        AuditLog::logActivity(
            $approverId,
            'Rejected',
            'Leave Management',
            'Rejected leave for ' . $leave->member->full_name .
                ' from ' . $leave->start_date . ' to ' . $leave->end_date
        );

        return back()->with('success', 'Leave rejected successfully.');
    }


    private function canApprove($approverId, $approverRoleId, $applicantId, $applicantRoleId)
    {

        if ($approverId == $applicantId) {
            return false;
        }

        /*
        Your hierarchy:
        1 = Superadmin (highest)
        2 = Admin
        3 = HR
        4 = Manager
        5 = Employee (lowest)

        Smaller number = higher power
        */

        return $approverRoleId < $applicantRoleId;
    }
}
