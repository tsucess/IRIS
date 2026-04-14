<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📢 Community Announcements</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-4xl mx-auto space-y-4">

            @if(session('success'))
                <div class="bg-green-500/30 border border-green-400 rounded-xl px-6 py-3">{{ session('success') }}</div>
            @endif

            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">Active Announcements</h3>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('announcements.create') }}"
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold text-white text-sm">
                        + New Announcement
                    </a>
                @endif
            </div>

            @forelse($announcements as $announcement)
                <div class="backdrop-blur-lg border rounded-xl p-5 space-y-2
                    {{ $announcement->pinned ? 'bg-yellow-500/20 border-yellow-400/40' : 'bg-white/20 border-white/30' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-2">
                            @if($announcement->pinned)
                                <span class="text-yellow-400 text-lg">📌</span>
                            @endif
                            <h4 class="font-bold text-base">{{ $announcement->title }}</h4>
                        </div>
                        <span class="text-xs text-white/50 shrink-0">
                            {{ $announcement->created_at->format('M d, Y') }}
                        </span>
                    </div>
                    <p class="text-sm text-white/80 line-clamp-3">{{ $announcement->body }}</p>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-white/50">
                            By {{ $announcement->author->full_name ?? 'Admin' }}
                            @if($announcement->expires_at)
                                · Expires {{ $announcement->expires_at->format('M d, Y') }}
                            @endif
                        </p>
                        <div class="flex gap-2">
                            <a href="{{ route('announcements.show', $announcement) }}"
                               class="text-xs px-3 py-1 bg-blue-500 hover:bg-blue-600 rounded-lg">Read</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('announcements.edit', $announcement) }}"
                                   class="text-xs px-3 py-1 bg-yellow-500 hover:bg-yellow-600 rounded-lg">Edit</a>
                                <form method="POST" action="{{ route('announcements.destroy', $announcement) }}">
                                    @csrf @method('DELETE')
                                    <button class="text-xs px-3 py-1 bg-red-500 hover:bg-red-600 rounded-lg">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-10 text-center text-white/60">
                    No announcements at this time.
                </div>
            @endforelse

            <div class="mt-4">{{ $announcements->links() }}</div>
        </div>
    </div>
</x-app-layout>
