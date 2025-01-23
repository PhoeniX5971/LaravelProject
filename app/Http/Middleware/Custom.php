<?php

namespace App\Http\Middleware;

use Closure;

class Custom
{
    public function handle($request, Closure $next)
    {
        // Handle preflight (OPTIONS) requests
        if ($request->isMethod('OPTIONS')) {
            return response('', 204)
                ->header('Access-Control-Allow-Origin', 'http://localhost:3000')
                ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        // Process other requests
        return $next($request);
    }
}
