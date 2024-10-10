<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Define allowed origins
        $allowedOrigins = ['http://your-allowed-origin.com'];

        // Check if the request origin is in the allowed list
        if (in_array($request->headers->get('Origin'), $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin'));
        }

        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization');

        if ($request->getMethod() === "OPTIONS") {
            return response()->json(['status' => 'OK'], 200);
        }

        return $response;
    }
}
