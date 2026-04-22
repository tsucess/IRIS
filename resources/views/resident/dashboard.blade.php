<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <!-- Apple Glassmorphism Background -->
    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-3 sm:p-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

            {{-- ── Welcome Banner ─────────────────────────────────────────── --}}
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-2xl p-4 sm:p-6 shadow-xl flex items-center gap-4">
                <div class="shrink-0 h-12 w-12 sm:h-14 sm:w-14 rounded-full bg-white/20 border border-white/30 flex items-center justify-center text-xl sm:text-2xl font-bold">
                    {{ strtoupper(substr(auth()->user()->firstname, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-white/70 text-xs sm:text-sm">Welcome back</p>
                    <h1 class="text-lg sm:text-2xl font-bold truncate">{{ auth()->user()->full_name }}</h1>
                    @if(auth()->user()->street)
                        <p class="text-white/70 text-xs sm:text-sm mt-0.5">
                            📍 {{ auth()->user()->street->name }}
                            @if(auth()->user()->street->zone)
                                &mdash; {{ auth()->user()->street->zone }}
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            {{-- ── Quick Links ────────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                <a href="{{ route('profile.edit') }}"
                   class="flex flex-col items-center gap-2 backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-4 hover:bg-white/30 hover:border-white/50 transition text-center shadow-lg">
                    <span class="text-2xl">👤</span>
                    <span class="text-sm font-medium text-white">My Profile</span>
                </a>
                <a href="{{ route('announcements.index') }}"
                   class="flex flex-col items-center gap-2 backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-4 hover:bg-white/30 hover:border-white/50 transition text-center shadow-lg">
                    <span class="text-2xl">📢</span>
                    <span class="text-sm font-medium text-white">Announcements</span>
                </a>
                <a href="{{ route('projects.index') }}"
                   class="flex flex-col items-center gap-2 backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-4 hover:bg-white/30 hover:border-white/50 transition text-center shadow-lg">
                    <span class="text-2xl">🏗️</span>
                    <span class="text-sm font-medium text-white">Projects</span>
                </a>
                <a href="{{ route('complaints.index') }}"
                   class="flex flex-col items-center gap-2 backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-4 hover:bg-white/30 hover:border-white/50 transition text-center shadow-lg">
                    <span class="text-2xl">📝</span>
                    <span class="text-sm font-medium text-white">My Requests</span>
                </a>
            </div>

            {{-- ── Two-column: Announcements + Projects ───────────────────── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">

                {{-- Recent Announcements --}}
                <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-2xl shadow-xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-white/20">
                        <h2 class="font-semibold text-white">📢 Recent Announcements</h2>
                        <a href="{{ route('announcements.index') }}" class="text-blue-200 text-sm hover:text-white hover:underline">View all</a>
                    </div>
                    <div class="divide-y divide-white/10">
                        @forelse($announcements as $ann)
                            <a href="{{ route('announcements.show', $ann) }}"
                               class="block px-5 py-4 hover:bg-white/10 transition">
                                <div class="flex items-start gap-2">
                                    @if($ann->pinned)
                                        <span class="mt-0.5 text-yellow-300 shrink-0">📌</span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-medium text-white truncate">{{ $ann->title }}</p>
                                        <p class="text-xs text-white/60 mt-0.5">
                                            {{ $ann->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="px-5 py-8 text-center text-white/60 text-sm">No announcements yet.</div>
                        @endforelse
                    </div>
                </div>

                {{-- My Assigned Projects --}}
                <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-2xl shadow-xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-white/20">
                        <h2 class="font-semibold text-white">🏗️ My Projects</h2>
                        <a href="{{ route('projects.index') }}" class="text-blue-200 text-sm hover:text-white hover:underline">View all</a>
                    </div>
                    <div class="divide-y divide-white/10">
                        @forelse($myProjects as $project)
                            <a href="{{ route('projects.show', $project) }}"
                               class="block px-5 py-4 hover:bg-white/10 transition">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="font-medium text-white truncate">{{ $project->title }}</p>
                                        <p class="text-xs text-white/60 mt-0.5">{{ $project->tasks_count }} task(s)</p>
                                    </div>
                                    <span @class([
                                        'shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full',
                                        'bg-yellow-500/30 text-yellow-100' => $project->status === 'pending',
                                        'bg-blue-500/30 text-blue-100'     => $project->status === 'ongoing',
                                        'bg-green-500/30 text-green-100'   => $project->status === 'completed',
                                        'bg-white/20 text-white/80'        => !in_array($project->status, ['pending','ongoing','completed']),
                                    ])>{{ ucfirst($project->status) }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="px-5 py-8 text-center text-white/60 text-sm">No projects assigned yet.</div>
                        @endforelse
                    </div>
                </div>

            </div>{{-- /two-column --}}

        </div>
    </div>
</x-app-layout>
