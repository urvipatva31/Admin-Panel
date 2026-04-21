<?php
namespace App\Http\Controllers;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\Salary;


class PayrollController extends Controller
{
 public function index()
{
    $salaries = Salary::with('member.role')->latest()->paginate(5);
    $totalPayout = Salary::where('month', date('Y-m'))->sum('total_salary');
    $paidPercent = Salary::count() ? round((Salary::where('status', 'paid')->count() / Salary::count()) * 100) : 0;
    $pendingCount = Salary::where('status', 'pending')->count();

    $users = Member::with('role')->where('status', 'active')->get();

    foreach ($users as $user) {
        $month = date('m');
        $year = date('Y');

        $attendanceQuery = \App\Models\Attendance::where('member_id', $user->id)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month);

        $totalMinutes = (clone $attendanceQuery)->sum('total_work_minutes');

        // Count any day they logged in (On Time, Late, WFH)
        $daysLogged = (clone $attendanceQuery)
            ->whereIn('status', ['ontime', 'late', 'wfh', 'present']) // 'present' included for old records
            ->count();
        
        $halfDays = (clone $attendanceQuery)->where('status', 'half_day')->count();

        if(($daysLogged > 0 || $halfDays > 0) && $user->base_salary > 0) {
            // Expected: 8 hours for full days, 4 hours for half days
            $expectedMinutes = ($daysLogged * 480) + ($halfDays * 240); 
            $shortfall = max(0, $expectedMinutes - $totalMinutes);
            
            $minuteRate = ($user->base_salary / 26) / 480;
            $user->suggested_deduction = round($shortfall * $minuteRate, 2);
        } else {
            $user->suggested_deduction = 0;
        }

        $leaves = \App\Models\Leave::where('member_id', $user->id)
    ->where('status', 'approved')
    ->whereMonth('start_date', $month)
    ->get();

$user->leave_days = $leaves->sum(function ($leave) {
    return \Carbon\Carbon::parse($leave->start_date)
        ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
});
    }
    return view('pages.payroll', compact('salaries', 'totalPayout', 'paidPercent', 'pendingCount', 'users'));
}

    public function store(Request $request)
{
    $request->validate([
        'member_id' => 'required|exists:members,id',
        'month' => 'required',
    ]);

    $member = Member::findOrFail($request->member_id);
    $baseSalary = $member->base_salary; 

    $yearMonth = explode('-', $request->month);
    $year = $yearMonth[0];
    $month = $yearMonth[1];

    $attendances = \App\Models\Attendance::where('member_id', $member->id)
                    ->whereYear('attendance_date', $year)
                    ->whereMonth('attendance_date', $month)
                    ->get();

    // 1. Calculate Paid Leaves
   $leaves = \App\Models\Leave::where('member_id', $member->id)
    ->where('status', 'approved')
    ->where('is_paid', 1) // ✅ ONLY PAID LEAVES
    ->where(function ($query) use ($month, $year) {
    $query->whereBetween('start_date', ["$year-$month-01", "$year-$month-31"])
          ->orWhereBetween('end_date', ["$year-$month-01", "$year-$month-31"])
          ->orWhere(function ($q) use ($month, $year) {
              $q->where('start_date', '<', "$year-$month-01")
                ->where('end_date', '>', "$year-$month-31");
          });
})->get();

$totalPaidLeaveDays = 0;

foreach ($leaves as $leave) {
    $start = \Carbon\Carbon::parse($leave->start_date);
    $end   = \Carbon\Carbon::parse($leave->end_date);

    $leaveDays = 0;

    for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
        if ($date->month == $month && $date->year == $year) {
            $leaveDays++;
        }
    }

    $totalPaidLeaveDays += $leaveDays;
}

    // 2. Calculate Work Totals
    // Daily & Minute Rates
$dailyRate = $baseSalary / 26;
$minuteRate = $dailyRate / 480;

// Work counts
$fullWorkDays = $attendances->whereIn('status', ['ontime', 'late', 'wfh', 'present'])->count();
$halfDays = $attendances->where('status', 'half_day')->count();

// Payable days
$payableDays = $fullWorkDays + $totalPaidLeaveDays + ($halfDays * 0.5);

// Earnings
$earnedBeforeDeductions = $payableDays * $dailyRate;

// Time calculation
$totalActualMinutes = $attendances->sum('total_work_minutes');
$expectedMinutes = ($fullWorkDays * 480) + ($halfDays * 240);
$shortfallMinutes = max(0, $expectedMinutes - $totalActualMinutes);

// Deduction
$latenessDeduction = $shortfallMinutes * $minuteRate;

// Final salary
$bonus = $request->bonus ?? 0;
$manualDeductions = $request->deductions ?? 0;

$totalDeductions = $manualDeductions + $latenessDeduction;

$finalTotal = ($earnedBeforeDeductions + $bonus) - $totalDeductions;

    Salary::create([
        'member_id' => $member->id,
        'month' => $request->month,
        'base_salary' => $baseSalary, 
        'bonus' => $bonus,
        'deductions' => round($manualDeductions + $latenessDeduction, 2),
        'total_salary' => round($finalTotal, 2),
        'status' => $request->status ?? 'pending',
        'notes' => $request->notes . " (Late minutes: $shortfallMinutes)"
    ]);

    return redirect()->back()->with('success', "Payroll processed. Payable Days: $payableDays");
}

public function destroy($id)
{
    // Find the salary record
    $salary = Salary::findOrFail($id);
    
    // Optional: Get member name for the audit log or success message
    $memberName = $salary->member->full_name ?? 'Unknown';

    // Delete the record
    $salary->delete();

    // Log the activity (consistent with your User Management)
    \App\Models\AuditLog::logActivity(
        session('member_id'),
        'Delete',
        'Payroll',
        'Deleted payroll record for: ' . $memberName . ' for month ' . $salary->month
    );

    return redirect()->back()->with('success', 'Payroll record deleted successfully.');
}
}