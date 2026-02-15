<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

$middleware->use([
    function (Request $request, Closure $next): Response {
        // Headers CORS
        $origin = $request->headers->get('Origin');
        $headers = [
            'Access-Control-Allow-Origin' => $origin ?: '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, Accept',
        ];

        // Preflight OPTIONS
        if ($request->getMethod() === 'OPTIONS') {
            return response()->noContent(200)->withHeaders($headers);
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
]);
