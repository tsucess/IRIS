<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for the
    | Community Development System application.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for different parts of the application.
    |
    */

    'rate_limiting' => [
        'login' => [
            'max_attempts' => env('RATE_LIMIT_LOGIN', 5),
            'decay_minutes' => env('RATE_LIMIT_LOGIN_DECAY', 1),
        ],
        'register' => [
            'max_attempts' => env('RATE_LIMIT_REGISTER', 5),
            'decay_minutes' => env('RATE_LIMIT_REGISTER_DECAY', 1),
        ],
        'password_reset' => [
            'max_attempts' => env('RATE_LIMIT_PASSWORD_RESET', 3),
            'decay_minutes' => env('RATE_LIMIT_PASSWORD_RESET_DECAY', 1),
        ],
        'api' => [
            'max_attempts' => env('RATE_LIMIT_API', 60),
            'decay_minutes' => env('RATE_LIMIT_API_DECAY', 1),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    |
    | Configure file upload restrictions and security settings.
    |
    */

    'file_upload' => [
        'max_size' => env('FILE_UPLOAD_MAX_SIZE', 2048), // KB
        'allowed_mimes' => [
            'images' => ['jpeg', 'jpg', 'png', 'webp'],
            'documents' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
        ],
        'image_dimensions' => [
            'min_width' => 100,
            'min_height' => 100,
            'max_width' => 4000,
            'max_height' => 4000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Policy
    |--------------------------------------------------------------------------
    |
    | Configure password requirements and policies.
    |
    */

    'password' => [
        'min_length' => env('PASSWORD_MIN_LENGTH', 8),
        'require_uppercase' => env('PASSWORD_REQUIRE_UPPERCASE', true),
        'require_lowercase' => env('PASSWORD_REQUIRE_LOWERCASE', true),
        'require_numbers' => env('PASSWORD_REQUIRE_NUMBERS', true),
        'require_symbols' => env('PASSWORD_REQUIRE_SYMBOLS', false),
        'max_age_days' => env('PASSWORD_MAX_AGE_DAYS', 90),
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | Configure session security settings.
    |
    */

    'session' => [
        'timeout_minutes' => env('SESSION_TIMEOUT', 120),
        'regenerate_on_login' => true,
        'invalidate_on_password_change' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Whitelist/Blacklist
    |--------------------------------------------------------------------------
    |
    | Configure IP-based access control.
    |
    */

    'ip_control' => [
        'enabled' => env('IP_CONTROL_ENABLED', false),
        'whitelist' => explode(',', env('IP_WHITELIST', '')),
        'blacklist' => explode(',', env('IP_BLACKLIST', '')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | Configure security headers for HTTP responses.
    |
    */

    'headers' => [
        'x_frame_options' => 'SAMEORIGIN',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'strict_transport_security' => 'max-age=31536000; includeSubDomains',
        'referrer_policy' => 'strict-origin-when-cross-origin',
    ],

    /*
    |--------------------------------------------------------------------------
    | CSRF Protection
    |--------------------------------------------------------------------------
    |
    | Configure CSRF protection settings.
    |
    */

    'csrf' => [
        'enabled' => true,
        'token_lifetime' => 7200, // 2 hours in seconds
    ],

];

