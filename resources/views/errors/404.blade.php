<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <!-- Error Icon -->
            <div class="mb-6">
                <svg class="mx-auto h-24 w-24 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Error Code -->
            <h1 class="text-6xl font-bold text-gray-900 mb-2">404</h1>
            
            <!-- Error Message -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Page Not Found</h2>
            <p class="text-gray-600 mb-8">
                Sorry, we couldn't find the page you're looking for. It might have been moved or deleted.
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" 
                   class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Go to Dashboard
                </a>
                <button onclick="window.history.back()" 
                        class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Go Back
                </button>
            </div>

            <!-- Help Text -->
            <p class="mt-6 text-sm text-gray-500">
                If you believe this is an error, please contact support.
            </p>
        </div>
    </div>
</body>
</html>

