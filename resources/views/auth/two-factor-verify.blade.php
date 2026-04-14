<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Verification – IRIS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-800 flex items-center justify-center">
    <div class="w-full max-w-md backdrop-blur-lg bg-white/20 border border-white/30 rounded-2xl p-8 shadow-2xl text-white">

        <div class="text-center mb-6">
            <div class="text-5xl mb-3">🔐</div>
            <h1 class="text-2xl font-bold">Two-Factor Verification</h1>
            <p class="text-sm text-white/70 mt-1">
                Enter the 6-character code sent to your email to continue.
            </p>
        </div>

        @if($errors->any())
            <div class="bg-red-500/30 border border-red-400 rounded-xl px-4 py-3 mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('two-factor.verify.submit') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Verification Code</label>
                <input type="text" name="code" maxlength="6" required autofocus
                       class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 text-white text-center text-2xl tracking-widest font-mono uppercase placeholder-white/40"
                       placeholder="XXXXXX">
            </div>

            <button type="submit"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 rounded-xl font-semibold text-white text-lg">
                Verify & Continue
            </button>
        </form>
    </div>
</body>
</html>
