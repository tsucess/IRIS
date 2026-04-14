<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'admin'      => \App\Http\Middleware\AdminOnly::class,
            'superadmin' => \App\Http\Middleware\SuperadminOnly::class,
            'throttle'   => \App\Http\Middleware\ThrottleRequests::class,
            'sanitize'   => \App\Http\Middleware\SanitizeInput::class,
            'two_factor' => \App\Http\Middleware\RequiresTwoFactor::class,
        ]);

        // Apply security headers and input sanitization to all web requests only.
        // SanitizeInput is intentionally excluded from API routes to avoid
        // converting empty JSON strings to null unexpectedly.
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\SanitizeInput::class,
        ]);

        // Rate limiting for API routes
        $middleware->api(append: [
            \App\Http\Middleware\ThrottleRequests::class.':60,1',
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        // Log all exceptions with context
        $exceptions->report(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });

        // Render custom error pages
        $exceptions->render(function (Throwable $e, $request) {
            // Don't handle validation exceptions - let Laravel handle them
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return null;
            }

            // Don't handle authentication exceptions - let Laravel handle them
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return null;
            }

            if ($request->is('api/*')) {
                return response()->json([
                    'message' => app()->environment('production')
                        ? 'An error occurred'
                        : $e->getMessage(),
                    'error' => app()->environment('production') ? null : [
                        'type' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ],
                ], 500);
            }
        });
    })->create();
