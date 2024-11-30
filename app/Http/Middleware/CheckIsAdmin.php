<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Agar autentifikatsiya qilinmagan bo'lsa
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Admin emas bo'lsa
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Bu sahifaga kirish uchun admin huquqi kerak.');
        }

        return $next($request);
    }
}
