<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? ['web', 'petugas'] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Jika login sebagai petugas, redirect ke dashboard admin
                if ($guard === 'petugas') {
                    return redirect('/dashboard');
                }
                // Jika login sebagai user biasa (web guard), redirect ke homepage
                if ($guard === 'web') {
                    return redirect()->route('user.dashboard');
                }
            }
        }

        return $next($request);
    }
}
