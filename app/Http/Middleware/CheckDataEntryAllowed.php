<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDataEntryAllowed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!config('settings.allow_data_entry')) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Ma\'lumot kiritish vaqtincha to\'xtatilgan'], 403);
            }
            
            return redirect()->back()
                ->with('error', 'Ma\'lumot kiritish vaqtincha to\'xtatilgan');
        }
    
        return $next($request);
    }
}
