<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📢 Announcement</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-8 space-y-4">
                <div class="flex items-center gap-3">
                    @if($announcement->pinned)
                        <span class="text-yellow-400 text-2xl">📌</span>
                    @endif
                    <h3 class="text-2xl font-bold">{{ $announcement->title }}</h3>
                </div>

                <div class="text-sm text-white/60 flex gap-4">
                    <span>By {{ $announcement->author->full_name ?? 'Admin' }}</span>
                    <span>{{ $announcement->created_at->format('F d, Y \a\t H:i') }}</span>
                    @if($announcement->expires_at)
                        <span class="text-yellow-300">Expires: {{ $announcement->expires_at->format('F d, Y') }}</span>
                    @endif
                    <span class="capitalize px-2 bg-blue-500/30 rounded">{{ $announcement->audience }}</span>
                </div>

                <div class="border-t border-white/20 pt-4 text-white/90 whitespace-pre-wrap leading-relaxed">
                    {{ $announcement->body }}
                </div>
            </div>

            <a href="{{ route('announcements.index') }}" class="inline-block text-white/70 hover:text-white underline text-sm">
                ← Back to Announcements
            </a>
        </div>
    </div>
</x-app-layout>
