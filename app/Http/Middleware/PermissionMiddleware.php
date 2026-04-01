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
        abort(403, 'Unauthorized');
    }

    return $next($request);
    }
}
