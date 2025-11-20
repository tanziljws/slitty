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

        // Cek petugas guard dulu (prioritas lebih tinggi)
        if (Auth::guard('petugas')->check()) {
            // Log untuk debugging
            \Log::info('RedirectIfAuthenticated: Petugas already logged in, redirecting to dashboard', [
                'petugas_id' => Auth::guard('petugas')->id(),
                'path' => $request->path(),
            ]);
            return redirect('/dashboard');
        }

        // Cek web guard
        if (Auth::check()) {
            \Log::info('RedirectIfAuthenticated: Web user already logged in, redirecting to homepage', [
                'user_id' => Auth::id(),
                'path' => $request->path(),
            ]);
            return redirect()->route('user.dashboard');
        }

        // Tidak ada yang login, lanjutkan ke next middleware
        return $next($request);
    }
}
