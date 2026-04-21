<x-public-layout>

    <div class="max-w-3xl mx-auto px-6 py-12 space-y-8">

        {{-- ── Breadcrumb ───────────────────────────────────────────────── --}}
        <div>
            <a href="{{ route('announcements.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-white/40 hover:text-white transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                All Announcements
            </a>
        </div>

        {{-- ── Article ───────────────────────────────────────────────────── --}}
        <article class="space-y-6">

            {{-- Pinned badge --}}
            @if($announcement->pinned)
                <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest
                            text-yellow-300 bg-yellow-500/15 border border-yellow-400/30 rounded-full px-4 py-1.5">
                    📌 Pinned Announcement
                </div>
            @endif

            {{-- Title --}}
            <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight tracking-tight">
                {{ $announcement->title }}
            </h1>

            {{-- Byline --}}
            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 py-4 border-y border-white/10 text-sm text-white/45">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="font-medium text-white/65">{{ $announcement->author->full_name ?? 'Admin' }}</span>
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $announcement->created_at->format('F d, Y \a\t g:i A') }}
                </span>
                @if($announcement->expires_at)
                    <span class="flex items-center gap-1.5 text-yellow-400/80">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Expires {{ $announcement->expires_at->format('M d, Y') }}
                    </span>
                @endif
            </div>

            {{-- Body --}}
            <div class="prose prose-invert prose-base max-w-none leading-8 text-white/80 whitespace-pre-wrap">
                {{ $announcement->body }}
            </div>

        </article>

        {{-- ── Footer CTA for guests ────────────────────────────────────── --}}
        @guest
            <div class="rounded-2xl bg-blue-600/15 border border-blue-400/25 p-6 text-center space-y-3">
                <p class="text-sm text-white/60">Want to see announcements meant for residents?</p>
                <div class="flex justify-center gap-3">
                    <a href="{{ route('login') }}"
                       class="text-sm px-5 py-2 bg-blue-600 hover:bg-blue-500 rounded-xl font-semibold transition-colors">
                        Log In
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-sm px-5 py-2 bg-white/10 hover:bg-white/20 rounded-xl font-semibold transition-colors">
                        Register
                    </a>
                </div>
            </div>
        @endguest

        {{-- ── Back link ────────────────────────────────────────────────── --}}
        <div class="pt-4 border-t border-white/10">
            <a href="{{ route('announcements.index') }}"
               class="text-sm text-white/40 hover:text-white transition-colors">
                ← Back to all announcements
            </a>
        </div>

    </div>

</x-public-layout>
