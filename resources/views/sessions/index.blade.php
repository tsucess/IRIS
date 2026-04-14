<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">💻 Active Sessions</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-4xl mx-auto space-y-4">

            @if(session('success'))
                <div class="bg-green-500/30 border border-green-400 rounded-xl px-6 py-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-500/30 border border-red-400 rounded-xl px-6 py-3">{{ session('error') }}</div>
            @endif

            <p class="text-sm text-white/70">
                These are your currently active login sessions. Revoke any session you don't recognise.
            </p>

            <div class="space-y-3">
                @forelse($sessions as $session)
                    <div class="backdrop-blur-lg border rounded-xl p-5 flex justify-between items-center gap-4
                        {{ $session->is_current ? 'bg-green-500/20 border-green-400/40' : 'bg-white/20 border-white/30' }}">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-sm">
                                    {{ $session->ip_address ?? 'Unknown IP' }}
                                </span>
                                @if($session->is_current)
                                    <span class="px-2 py-0.5 bg-green-500 text-white text-xs rounded-full font-bold">
                                        Current
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-white/60 truncate max-w-lg">{{ $session->user_agent }}</p>
                            <p class="text-xs text-white/50">
                                Last activity: {{ $session->last_activity->diffForHumans() }}
                            </p>
                        </div>
                        @if(! $session->is_current)
                            <form method="POST" action="{{ route('sessions.destroy', $session->id) }}">
                                @csrf @method('DELETE')
                                <button class="px-4 py-1.5 bg-red-600 hover:bg-red-700 rounded-lg text-sm font-semibold shrink-0">
                                    Revoke
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-10 text-center text-white/60">
                        No active sessions found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
