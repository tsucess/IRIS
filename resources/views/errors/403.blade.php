<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <!-- Error Icon -->
            <div class="mb-6">
                <svg class="mx-auto h-24 w-24 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>

            <!-- Error Code -->
            <h1 class="text-6xl font-bold text-gray-900 mb-2">403</h1>
            
            <!-- Error Message -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Access Denied</h2>
            <p class="text-gray-600 mb-8">
                {{ $exception->getMessage() ?: "You don't have permission to access this resource." }}
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" 
                   class="block w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Go to Dashboard
                </a>
                <button onclick="window.history.back()" 
                        class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Go Back
                </button>
            </div>

            <!-- Help Text -->
            <p class="mt-6 text-sm text-gray-500">
                If you need access, please contact your administrator.
            </p>
        </div>
    </div>
</body>
</html>

