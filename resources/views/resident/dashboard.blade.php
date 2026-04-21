<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ── Welcome Banner ─────────────────────────────────────────── --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg flex items-center gap-4">
                <div class="shrink-0 h-14 w-14 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold">
                    {{ strtoupper(substr(auth()->user()->firstname, 0, 1)) }}
                </div>
                <div>
                    <p class="text-blue-100 text-sm">Welcome back</p>
                    <h1 class="text-2xl font-bold">{{ auth()->user()->full_name }}</h1>
                    @if(auth()->user()->street)
                        <p class="text-blue-200 text-sm mt-0.5">
                            📍 {{ auth()->user()->street->name }}
                            @if(auth()->user()->street->zone)
                                &mdash; {{ auth()->user()->street->zone }}
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            {{-- ── Quick Links ────────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('profile.edit') }}"
                   class="flex flex-col items-center gap-2 bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition text-center">
                    <span class="text-2xl">👤</span>
                    <span class="text-sm font-medium text-gray-700">My Profile</span>
                </a>
                <a href="{{ route('announcements.index') }}"
                   class="flex flex-col items-center gap-2 bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition text-center">
                    <span class="text-2xl">📢</span>
                    <span class="text-sm font-medium text-gray-700">Announcements</span>
                </a>
                <a href="{{ route('projects.index') }}"
                   class="flex flex-col items-center gap-2 bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition text-center">
                    <span class="text-2xl">🏗️</span>
                    <span class="text-sm font-medium text-gray-700">Projects</span>
                </a>
                <a href="{{ route('complaints.index') }}"
                   class="flex flex-col items-center gap-2 bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition text-center">
                    <span class="text-2xl">📝</span>
                    <span class="text-sm font-medium text-gray-700">My Requests</span>
                </a>
            </div>

            {{-- ── Two-column: Announcements + Projects ───────────────────── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Recent Announcements --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800">📢 Recent Announcements</h2>
                        <a href="{{ route('announcements.index') }}" class="text-blue-600 text-sm hover:underline">View all</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($announcements as $ann)
                            <a href="{{ route('announcements.show', $ann) }}"
                               class="block px-5 py-4 hover:bg-gray-50 transition">
                                <div class="flex items-start gap-2">
                                    @if($ann->pinned)
                                        <span class="mt-0.5 text-yellow-500 shrink-0">📌</span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 truncate">{{ $ann->title }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $ann->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="px-5 py-8 text-center text-gray-400 text-sm">No announcements yet.</div>
                        @endforelse
                    </div>
                </div>

                {{-- My Assigned Projects --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800">🏗️ My Projects</h2>
                        <a href="{{ route('projects.index') }}" class="text-blue-600 text-sm hover:underline">View all</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($myProjects as $project)
                            <a href="{{ route('projects.show', $project) }}"
                               class="block px-5 py-4 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-800 truncate">{{ $project->title }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $project->tasks_count }} task(s)</p>
                                    </div>
                                    <span @class([
                                        'shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full',
                                        'bg-yellow-100 text-yellow-700' => $project->status === 'pending',
                                        'bg-blue-100 text-blue-700'    => $project->status === 'ongoing',
                                        'bg-green-100 text-green-700'  => $project->status === 'completed',
                                        'bg-gray-100 text-gray-600'    => !in_array($project->status, ['pending','ongoing','completed']),
                                    ])>{{ ucfirst($project->status) }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="px-5 py-8 text-center text-gray-400 text-sm">No projects assigned yet.</div>
                        @endforelse
                    </div>
                </div>

            </div>{{-- /two-column --}}

        </div>
    </div>
</x-app-layout>
