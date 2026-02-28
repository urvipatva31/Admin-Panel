<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Role;
use App\Models\AuditLog;

class HrManagementController extends Controller
{

    public function index()
    {
        $employeeRole = Role::where('role_name', 'employee')->first();

        $employees = Member::with('role')
            ->where('role_id', $employeeRole->id)
            ->paginate(5);

        return view('pages.hr-management', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:members,email',
            'status'    => 'required',
        ]);

        $employeeRole = Role::where('role_name', 'employee')->first();

        $employee = Member::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'role_id'   => $employeeRole->id,
            'status'    => $request->status,
            'password'  => bcrypt('employee@123'), 
        ]);

        AuditLog::logActivity(
            session('member_id'),
            'Created',
            'HR Management',
            'Added employee: ' . $employee->full_name
        );

        return redirect()->route('hr-management')
            ->with('success', 'Employee added successfully');
    }

    public function edit($id)
{
    $employeeRole = Role::where('role_name', 'employee')->first();

    $employees = Member::with('role')
        ->where('role_id', $employeeRole->id)
        ->paginate(5);  

    $editEmployee = Member::findOrFail($id);

    return view('pages.hr-management', compact('employees', 'editEmployee'));
}

    public function update(Request $request, $id)
    {
        $employee = Member::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:members,email,' . $employee->id,
            'status'    => 'required',
        ]);

        $employee->update([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'status'    => $request->status,
        ]);

        AuditLog::logActivity(
            session('member_id'),
            'Updated',
            'HR Management',
            'Updated employee: ' . $employee->full_name
        );

        return redirect()->route('hr-management')
            ->with('success', 'Employee updated successfully');
    }

    public function destroy($id)
    {
        $employee = Member::findOrFail($id);
        $employeeName = $employee->full_name;
        $employee->delete();

        AuditLog::logActivity(
            session('member_id'),
            'Deleted',
            'HR Management',
            'Deleted employee: ' . $employeeName
        );

        return redirect()->route('hr-management')
            ->with('success', 'Employee deleted successfully');
    }
}