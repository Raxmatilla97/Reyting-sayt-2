<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsKpiReviewer
{
    public function handle(Request $request, Closure $next)
    {
        // Agar autentifikatsiya qilinmagan bo'lsa, login sahifasiga yo'naltirish
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Reviewer huquqi yo'q bo'lsa, dashboard'ga yo'naltirish
        if (!auth()->user()->is_kpi_reviewer) {
            return redirect()->route('dashboard')->with('error', 'Bu sahifaga kirish uchun huquqingiz yetarli emas.');
        }

        return $next($request);
    }
}
