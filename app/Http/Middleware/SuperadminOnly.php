<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperadminOnly
{
    /**
     * Only allow superadmin role through.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'superadmin') {
            return $next($request);
        }

        abort(403, 'Access denied. Superadmin privileges required.');
    }
}
