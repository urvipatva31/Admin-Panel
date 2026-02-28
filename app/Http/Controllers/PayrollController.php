<?php
namespace App\Http\Controllers;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\Salary;


class PayrollController extends Controller
{
    public function index()
{
    // Recent payslips with member and role
    $salaries = Salary::with('member.role')
                ->latest()
                ->paginate(5);

    // Dashboard data
    $totalPayout = Salary::where('month', date('Y-m'))
                    ->sum('total_salary');

    $paidCount = Salary::where('status', 'paid')->count();
    $totalCount = Salary::count();
    $paidPercent = $totalCount ? round(($paidCount / $totalCount) * 100) : 0;

    $pendingCount = Salary::where('status', 'pending')->count();

    // THIS WAS MISSING
    $users = Member::with('role')
            ->where('status', 'active') // optional if you have status column
            ->get();

    return view('pages.payroll', compact(
        'salaries',
        'totalPayout',
        'paidPercent',
        'pendingCount',
        'users'
    ));
}

    // Process payroll form
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'month' => 'required',
            'base_salary' => 'required|numeric'
        ]);

        $total = $request->base_salary
                + $request->bonus
                - $request->deductions;

        Salary::create([
            'member_id' => $request->member_id,
            'month' => $request->month,
            'base_salary' => $request->base_salary,
            'bonus' => $request->bonus ?? 0,
            'deductions' => $request->deductions ?? 0,
            'total_salary' => $total,
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Payroll processed successfully');
    }
}