<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMemberLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('member_id')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}