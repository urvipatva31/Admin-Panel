<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\Member;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = Member::with('role')->paginate(5);
        $roles = Role::all();
        return view('pages.user-management', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:members,email',
            'status' => 'required|in:active,pending,inactive',
            'role_id'   => 'required',
        ]);

        Member::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'password'  => Hash::make('employee@123'),
            'role_id'   => $request->role_id,
            'status'    => $request->status,
        ]);

        return redirect()->route('users')->with('success', 'User created. Default password is: employee@123');
    }

    public function edit($id)
    {
        $users = Member::with('role')->get();
        $roles = Role::all();
        $editUser = Member::findOrFail($id);

        return view('pages.user-management', compact('users', 'roles', 'editUser'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required',
            'email' => 'required|email',
            'role_id' => 'required',
            'status' => 'required'
        ]);

        $user = Member::findOrFail($id);
        $user->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        return redirect()->route('users')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = Member::findOrFail($id);

        // Protect Super Admin
        if ($user->role->role_name === 'superadmin') {
            return redirect()->route('users');
        }

        $user->delete();

        return redirect()->route('users')
            ->with('success', 'User deleted successfully');
    }
}
