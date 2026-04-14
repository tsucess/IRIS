<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequiresTwoFactor
{
    /**
     * Redirect users who have 2FA enabled but not yet verified this session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (
            $user &&
            $user->two_factor_enabled &&
            $user->isAdmin() &&
            ! $user->two_factor_verified_at
        ) {
            auth()->logout();
            $request->session()->put('two_factor_user_id', $user->id);

            return redirect()->route('two-factor.verify');
        }

        return $next($request);
    }
}
