<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthPetugas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login sebagai petugas menggunakan guard yang benar
        if (!Auth::guard('petugas')->check()) {
            // Jika belum login, redirect ke login page
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        return $next($request);
    }
}
