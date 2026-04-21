<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">📢 Announcement Management</h2>
            <a href="{{ route('announcements.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold text-sm transition-colors shadow-sm">
                + New Announcement
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     x-transition class="flex items-center gap-3 bg-green-500/25 border border-green-400/50 rounded-xl px-5 py-3 text-sm">
                    <svg class="w-5 h-5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-green-300 hover:text-white">✕</button>
                </div>
            @endif

            {{-- Stat cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="backdrop-blur-lg bg-white/15 border border-white/20 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold">{{ $totalCount }}</p>
                    <p class="text-sm text-white/60 mt-1">Total</p>
                </div>
                <div class="backdrop-blur-lg bg-green-500/15 border border-green-400/30 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-green-300">{{ $activeCount }}</p>
                    <p class="text-sm text-white/60 mt-1">Active</p>
                </div>
                <div class="backdrop-blur-lg bg-yellow-500/15 border border-yellow-400/30 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-yellow-300">{{ $pinnedCount }}</p>
                    <p class="text-sm text-white/60 mt-1">Pinned</p>
                </div>
                <div class="backdrop-blur-lg bg-red-500/15 border border-red-400/30 rounded-2xl p-5 text-center">
                    <p class="text-3xl font-bold text-red-300">{{ $expiredCount }}</p>
                    <p class="text-sm text-white/60 mt-1">Expired</p>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="flex flex-wrap gap-3 items-center">
                <a href="{{ route('announcements.index') }}" target="_blank"
                   class="inline-flex items-center gap-2 text-sm text-blue-300 hover:text-white border border-blue-400/30 hover:border-blue-400/60 px-4 py-1.5 rounded-lg transition-colors">
                    🌐 View Public Page ↗
                </a>
            </div>

            {{-- Table --}}
            <div class="backdrop-blur-lg bg-white/10 border border-white/20 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/15 flex items-center justify-between">
                    <h3 class="font-semibold text-base">All Announcements</h3>
                    <span class="text-xs text-white/40">{{ $all->total() }} total</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white/10 text-white/60 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3 text-left">Title</th>
                                <th class="px-5 py-3 text-left">Audience</th>
                                <th class="px-5 py-3 text-left">Status</th>
                                <th class="px-5 py-3 text-left">Pin</th>
                                <th class="px-5 py-3 text-left">Created</th>
                                <th class="px-5 py-3 text-left">Expires</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($all as $announcement)
                                @php
                                    $isExpired = $announcement->expires_at && $announcement->expires_at->isPast();
                                @endphp
                                <tr class="hover:bg-white/8 transition-colors {{ $isExpired ? 'opacity-60' : '' }}">

                                    {{-- Title --}}
                                    <td class="px-5 py-3 font-medium max-w-xs">
                                        <a href="{{ route('announcements.show', $announcement) }}"
                                           class="hover:text-blue-300 transition-colors line-clamp-1">
                                            {{ $announcement->title }}
                                        </a>
                                        @if($announcement->author)
                                            <p class="text-xs text-white/40 mt-0.5">by {{ $announcement->author->full_name }}</p>
                                        @endif
                                    </td>

                                    {{-- Audience --}}
                                    <td class="px-5 py-3">
                                        @php
                                            $audienceConfig = [
                                                'all'       => ['label' => '🌍 Everyone',  'class' => 'bg-green-500/25 text-green-300'],
                                                'residents' => ['label' => '🏘 Residents', 'class' => 'bg-blue-500/25 text-blue-300'],
                                                'admins'    => ['label' => '🔒 Admins',    'class' => 'bg-red-500/25 text-red-300'],
                                            ];
                                            $ac = $audienceConfig[$announcement->audience] ?? ['label' => $announcement->audience, 'class' => 'bg-white/20'];
                                        @endphp
                                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $ac['class'] }}">
                                            {{ $ac['label'] }}
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-5 py-3">
                                        @if($isExpired)
                                            <span class="text-xs px-2.5 py-1 rounded-full bg-red-500/20 text-red-300">Expired</span>
                                        @else
                                            <span class="text-xs px-2.5 py-1 rounded-full bg-green-500/20 text-green-300">Active</span>
                                        @endif
                                    </td>

                                    {{-- Pinned --}}
                                    <td class="px-5 py-3">
                                        <form method="POST" action="{{ route('announcements.toggle-pin', $announcement) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="text-lg leading-none transition-opacity hover:opacity-100
                                                        {{ $announcement->pinned ? 'opacity-100' : 'opacity-30' }}"
                                                    title="{{ $announcement->pinned ? 'Click to unpin' : 'Click to pin' }}">
                                                📌
                                            </button>
                                        </form>
                                    </td>

                                    {{-- Created --}}
                                    <td class="px-5 py-3 text-white/50 text-xs whitespace-nowrap">
                                        {{ $announcement->created_at->format('M d, Y') }}
                                    </td>

                                    {{-- Expires --}}
                                    <td class="px-5 py-3 text-xs whitespace-nowrap {{ $isExpired ? 'text-red-400' : 'text-white/50' }}">
                                        {{ $announcement->expires_at ? $announcement->expires_at->format('M d, Y') : '—' }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-5 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('announcements.edit', $announcement) }}"
                                               class="inline-flex items-center gap-1 text-xs px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-lg font-medium transition-colors">
                                                ✏️ Edit
                                            </a>
                                            <form method="POST" action="{{ route('announcements.destroy', $announcement) }}"
                                                  onsubmit="return confirm('Delete «{{ addslashes($announcement->title) }}»? This cannot be undone.')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 text-xs px-3 py-1.5 bg-red-500/20 hover:bg-red-500/50 text-red-300 hover:text-white rounded-lg font-medium transition-colors">
                                                    🗑 Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center text-white/40">
                                        No announcements yet.
                                        <a href="{{ route('announcements.create') }}" class="text-blue-400 hover:text-blue-300 underline ml-1">
                                            Create the first one.
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($all->hasPages())
                    <div class="px-5 py-4 border-t border-white/15">
                        {{ $all->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
