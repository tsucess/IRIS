<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">🔔 Notifications</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-4xl mx-auto space-y-4">

            @if(session('success'))
                <div class="bg-green-500/30 border border-green-400 rounded-xl px-6 py-3">{{ session('success') }}</div>
            @endif

            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">
                    All Notifications
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="ml-2 px-2 py-0.5 text-xs bg-red-500 rounded-full">
                            {{ auth()->user()->unreadNotifications->count() }} unread
                        </span>
                    @endif
                </h3>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button class="px-4 py-1.5 bg-white/20 hover:bg-white/30 border border-white/30 rounded-lg text-sm font-semibold">
                            Mark All Read
                        </button>
                    </form>
                @endif
            </div>

            @forelse($notifications as $notification)
                <div class="backdrop-blur-lg border rounded-xl p-5 flex justify-between items-start gap-4
                    {{ $notification->read_at ? 'bg-white/10 border-white/20' : 'bg-white/25 border-white/40' }}">
                    <div>
                        <p class="font-bold text-sm">{{ $notification->data['title'] ?? 'Notification' }}</p>
                        <p class="text-sm text-white/80 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                        <p class="text-xs text-white/50 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex flex-col gap-2 shrink-0">
                        @if(! $notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                <button class="text-xs px-3 py-1 bg-blue-500 hover:bg-blue-600 rounded-lg">
                                    Mark Read
                                </button>
                            </form>
                        @endif
                        @if(isset($notification->data['url']))
                            <a href="{{ $notification->data['url'] }}"
                               class="text-xs px-3 py-1 bg-white/20 hover:bg-white/30 rounded-lg text-center">
                                View
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-10 text-center text-white/60">
                    No notifications yet.
                </div>
            @endforelse

            <div class="mt-4">{{ $notifications->links() }}</div>
        </div>
    </div>
</x-app-layout>
