<?php

if (!function_exists('hasPermission')) {

    function hasPermission($key)
    {
        // Get logged-in user ID from session
        $memberId = session('member_id');

        if (!$memberId) {
            return false;
        }

        // Load user with role & permissions
        $user = \App\Models\Member::with('role.permissions')
            ->find($memberId);

        if (!$user || !$user->role) {
            return false;
        }

        // SUPER ADMIN FULL ACCESS
        if (strtolower($user->role->role_name) === 'superadmin') {
            return true;
        }

        // CHECK PERMISSION
        return $user->role->permissions
            ->pluck('permission_key')
            ->contains($key);
    }
}