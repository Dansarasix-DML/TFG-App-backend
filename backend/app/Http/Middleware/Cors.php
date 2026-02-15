<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', 'https://gameverse-app.vercel.app')
            // ->header('Access-Control-Allow-Origin', 'http://localhost:3000')
            // ->header('Access-Control-Allow-Origin', 'http://localhost:3001')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}
