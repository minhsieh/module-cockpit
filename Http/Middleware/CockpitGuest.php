<?php

namespace Modules\Cockpit\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CockpitGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/cockpit');
        }

        return $next($request);
    }
}