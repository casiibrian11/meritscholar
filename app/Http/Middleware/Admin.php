<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $deadline = '2024-06-28';
        
        if (now()->gt($deadline)) {
            abort( 403 );
        }

        $userType = auth()->user()->user_type;

        if ($userType == 'admin' || $userType == 'director'  || $userType == 'support') {
            return $next($request);
        }
        
        abort(401);
    }
}
