<?php

namespace Modules\Cockpit\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class CockpitAuth extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('cockpit::login');
        }
    }
}
