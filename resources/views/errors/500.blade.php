<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-purple-50 to-pink-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <!-- Error Icon -->
            <div class="mb-6">
                <svg class="mx-auto h-24 w-24 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <!-- Error Code -->
            <h1 class="text-6xl font-bold text-gray-900 mb-2">500</h1>
            
            <!-- Error Message -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Server Error</h2>
            <p class="text-gray-600 mb-8">
                Oops! Something went wrong on our end. We're working to fix it.
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" 
                   class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Go to Dashboard
                </a>
                <button onclick="window.location.reload()" 
                        class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Try Again
                </button>
            </div>

            <!-- Help Text -->
            <p class="mt-6 text-sm text-gray-500">
                Error ID: {{ uniqid() }} - Please reference this when contacting support.
            </p>
        </div>
    </div>
</body>
</html>

