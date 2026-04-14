<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">🔐 Two-Factor Authentication</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-lg mx-auto space-y-6">

            @if(session('success'))
                <div class="bg-green-500/30 border border-green-400 rounded-xl px-6 py-3">{{ session('success') }}</div>
            @endif

            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-8 space-y-5">
                <div class="flex items-center gap-3">
                    <div class="text-4xl">{{ $user->two_factor_enabled ? '✅' : '🔓' }}</div>
                    <div>
                        <h3 class="text-lg font-bold">
                            Two-Factor Authentication is
                            <span class="{{ $user->two_factor_enabled ? 'text-green-400' : 'text-red-400' }}">
                                {{ $user->two_factor_enabled ? 'ENABLED' : 'DISABLED' }}
                            </span>
                        </h3>
                        <p class="text-sm text-white/70">
                            Adds an extra layer of security when you log in.
                        </p>
                    </div>
                </div>

                @if(! $user->two_factor_enabled)
                    <form method="POST" action="{{ route('two-factor.enable') }}">
                        @csrf
                        <button type="submit"
                                class="w-full py-2 bg-green-600 hover:bg-green-700 rounded-lg font-semibold text-white">
                            Enable 2FA
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('two-factor.disable') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium mb-1">Confirm your password to disable 2FA</label>
                            <input type="password" name="password" required
                                   class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white">
                            @error('password')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                class="w-full py-2 bg-red-600 hover:bg-red-700 rounded-lg font-semibold text-white">
                            Disable 2FA
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
