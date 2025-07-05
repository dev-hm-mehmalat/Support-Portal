<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\Request;

class PreventRequestsDuringMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @throws MaintenanceModeException
     */
    public function handle(Request $request, Closure $next)
    {
        if (app()->isDownForMaintenance()) {
            throw new MaintenanceModeException(time(), null, null);
        }

        return $next($request);
    }
}
