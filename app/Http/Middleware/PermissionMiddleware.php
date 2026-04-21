<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next, $permission)
    {
    //       if (session('role') == 'superadmin') {
    //     return $next($request);
    // }

    if (!hasPermission($permission)) {
        return redirect()->back()->with('error', 'Access Denied: You do not have the required permission.');
    }

    return $next($request);
    }
}
