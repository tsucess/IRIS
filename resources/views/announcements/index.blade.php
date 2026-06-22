<x-public-layout>

    {{-- ── Hero header ────────────────────────────────────────────────────── --}}
    {{-- <div class="relative overflow-hidden border-b border-white/10 bg-black/20 backdrop-blur-sm"> --}}
    <div class="relative overflow-hidden font-sans antialiased bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900 text-black" style="background-color:#1e1b4b  ">
        <div class="max-w-5xl mx-auto px-6 py-14 text-center">
            <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-blue-300
                         bg-blue-500/15 border border-blue-400/30 rounded-full px-4 py-1.5 mb-5">
                📢 Community Board
            </span>
            <h1 class="text-4xl text-white sm:text-5xl font-extrabold tracking-tight leading-tight">
                Announcements
            </h1>
            <p class="mt-3 text-white text-base max-w-lg mx-auto">
                Stay up to date with the latest news and notices from the community.
            </p>
        </div>
        {{-- decorative glow --}}
        <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    <div class="max-w-5xl mx-auto px-6 py-10 space-y-10">

        {{-- ── Pinned announcements ──────────────────────────────────────── --}}
        @php $pinned = $announcements->filter(fn($a) => $a->pinned); @endphp
        @if($pinned->isNotEmpty())
            <section>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-yellow-400 text-lg">📌</span>
                    <h2 class="text-sm font-bold uppercase tracking-widest text-yellow-300/80">Pinned</h2>
                </div>
                <div class="grid sm:grid-cols-2 gap-5">
                    @foreach($pinned as $announcement)
                        <a href="{{ route('announcements.show', $announcement) }}"
                           class="group block backdrop-blur-lg bg-yellow-500/10 border border-yellow-400/35
                                  hover:bg-yellow-500/20 hover:border-yellow-400/60
                                  rounded-2xl p-6 space-y-3 transition-all hover:shadow-2xl hover:shadow-yellow-900/30 hover:-translate-y-0.5">
                            <div class="flex justify-between items-start gap-2">
                                <h3 class="font-bold text-base leading-snug group-hover:text-yellow-200 transition-colors">
                                    {{ $announcement->title }}
                                </h3>
                                <span class="text-xs text-white/35 shrink-0 mt-0.5">
                                    {{ $announcement->created_at->format('M d') }}
                                </span>
                            </div>
                            <p class="text-sm text-white/65 line-clamp-3 leading-relaxed">{{ $announcement->body }}</p>
                            <div class="flex items-center justify-between pt-1">
                                <p class="text-xs text-white/35">By {{ $announcement->author->full_name ?? 'Admin' }}</p>
                                <span class="text-xs text-yellow-400/80 font-medium group-hover:text-yellow-300 transition-colors">
                                    Read →
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ── All other announcements ───────────────────────────────────── --}}
        @php $regular = $announcements->filter(fn($a) => ! $a->pinned); @endphp
        @if($regular->isNotEmpty())
            <section>
                @if($pinned->isNotEmpty())
                    <div class="flex items-center gap-3 mb-4">
                        <h2 class="text-sm font-bold uppercase tracking-widest text-white/40">Latest</h2>
                        <div class="flex-1 h-px bg-white/10"></div>
                    </div>
                @endif
                <div class="grid sm:grid-cols-2 gap-5">
                    @foreach($regular as $announcement)
                        <a href="{{ route('announcements.show', $announcement) }}"
                           class="group block backdrop-blur-lg bg-white/10 border border-white/15
                                  hover:bg-white/15 hover:border-white/30
                                  rounded-2xl p-6 space-y-3 transition-all hover:shadow-xl hover:-translate-y-0.5">
                            <div class="flex justify-between items-start gap-2">
                                <h3 class="font-bold text-base leading-snug group-hover:text-blue-300 transition-colors">
                                    {{ $announcement->title }}
                                </h3>
                                <span class="text-xs text-white/35 shrink-0 mt-0.5">
                                    {{ $announcement->created_at->format('M d') }}
                                </span>
                            </div>
                            <p class="text-sm text-white/60 line-clamp-3 leading-relaxed">{{ $announcement->body }}</p>
                            <div class="flex items-center justify-between pt-1">
                                <p class="text-xs text-white/35">By {{ $announcement->author->full_name ?? 'Admin' }}</p>
                                <span class="text-xs text-blue-400/70 font-medium group-hover:text-blue-300 transition-colors">
                                    Read →
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ── Empty state ──────────────────────────────────────────────── --}}
        @if($announcements->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="text-6xl mb-4 opacity-40">📭</div>
                <p class="text-black text-base">No announcements yet. Check back soon.</p>
                @guest
                    <a href="{{ route('login') }}"
                       class="mt-5 inline-flex items-center gap-2 text-sm text-black hover:text-black-300 transition-colors">
                        Log in to see more
                    </a>
                @endguest
            </div>
        @endif

        {{-- ── Pagination ───────────────────────────────────────────────── --}}
        @if($announcements->hasPages())
            <div class="pt-4 border-t border-white/10">{{ $announcements->links() }}</div>
        @endif

    </div>

</x-public-layout>
