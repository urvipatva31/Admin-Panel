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

        return view('pages.leave-management', compact('leaves'));
    }

    // Apply Leave
    public function apply(Request $request)
    {
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

        $leave->update([
            'status' => 'approved',
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