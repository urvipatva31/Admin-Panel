<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::all();
        $allPermissions = Permission::all()->groupBy('module');

        $moduleOrder = [
            'dashboard',
            'members',
            'hr',
            'permissions',
            'attendance',
            'leaves',
            'projects',
            'tasks',
            'dwr',
            'reports',
            'payroll',
            'settings',
            'audit'
        ];

        $permissions = collect($moduleOrder)
            ->mapWithKeys(function ($module) use ($allPermissions) {
                return [$module => $allPermissions[$module] ?? collect()];
            });

        $selectedRoleId = $request->role_id ?? $roles->first()->id;

        $assignedPermissions = DB::table('role_permissions')
            ->where('role_id', $selectedRoleId)
            ->pluck('permission_id')
            ->toArray();

        return view('pages.role-permission', compact(
            'roles',
            'permissions',
            'selectedRoleId',
            'assignedPermissions'
        ));
    }

    public function store(Request $request)
    {
        $roleId = $request->role_id;
        $permissions = $request->permissions ?? [];

        DB::table('role_permissions')
            ->where('role_id', $roleId)
            ->delete();

        foreach ($permissions as $permissionId) {
            DB::table('role_permissions')->insert([
                'role_id' => $roleId,
                'permission_id' => $permissionId,
            ]);
        }

        return redirect()->back()->with('success', 'Permissions updated successfully');
    }
}
