<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                // Trim whitespace
                $value = trim($value);

                // Remove null bytes
                $value = str_replace("\0", '', $value);

                // Convert empty strings to null
                if ($value === '') {
                    $value = null;
                }
            }
        });

        $request->merge($input);

        return $next($request);
    }
}

