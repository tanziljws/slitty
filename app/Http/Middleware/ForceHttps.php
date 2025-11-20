<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ForceHttps
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
        // Force HTTPS untuk semua request (tidak hanya production)
        // Cek apakah request sudah secure atau melalui proxy (Railway menggunakan X-Forwarded-Proto)
        $isSecure = $request->secure() || 
                    $request->header('X-Forwarded-Proto') === 'https' ||
                    $request->header('X-Forwarded-Ssl') === 'on';
        
        if (!$isSecure) {
            // Redirect ke HTTPS
            return redirect()->secure($request->getRequestUri());
        }

        // Force scheme untuk semua URL generation
        URL::forceScheme('https');

        return $next($request);
    }
}

