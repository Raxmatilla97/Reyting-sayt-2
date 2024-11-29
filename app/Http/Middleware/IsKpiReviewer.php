<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsKpiReviewer
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user() || !auth()->user()->is_kpi_reviewer) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
