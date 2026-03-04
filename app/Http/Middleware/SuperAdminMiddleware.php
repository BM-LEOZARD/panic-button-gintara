<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'SuperAdmin') {
            return $next($request);
        }
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'EndUser') return redirect()->route('dashboard');
            if ($role === 'Admin') return redirect()->route('admin.dashboard');
        }

        return redirect()->route('portal.login')
            ->with('error', 'Anda tidak memiliki akses ke halaman SuperAdmin.');
    }
}
