<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📋 Service Requests / Complaints</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            @if(session('success'))
                <div class="bg-green-500/30 border border-green-400 rounded-xl px-6 py-3">{{ session('success') }}</div>
            @endif

            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold">All Complaints</h3>
                <a href="{{ route('complaints.create') }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold text-white">
                    + New Complaint
                </a>
            </div>

            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white/10">
                            <tr>
                                <th class="px-4 py-3 text-left">Title</th>
                                <th class="px-4 py-3 text-left">Category</th>
                                <th class="px-4 py-3 text-left">Priority</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Submitted By</th>
                                <th class="px-4 py-3 text-left">Date</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($complaints as $complaint)
                                <tr class="hover:bg-white/10 transition">
                                    <td class="px-4 py-3 font-medium">{{ $complaint->title }}</td>
                                    <td class="px-4 py-3 capitalize">{{ $complaint->category }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold
                                            bg-{{ $complaint->priority_badge_color }}-500">
                                            {{ ucfirst($complaint->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold
                                            bg-{{ $complaint->status_badge_color }}-500">
                                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $complaint->user->full_name }}</td>
                                    <td class="px-4 py-3">{{ $complaint->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 flex gap-2">
                                        <a href="{{ route('complaints.show', $complaint) }}"
                                           class="text-xs px-3 py-1 bg-blue-500 hover:bg-blue-600 rounded-lg">View</a>
                                        @if($complaint->user_id === auth()->id() && $complaint->isOpen())
                                            <a href="{{ route('complaints.edit', $complaint) }}"
                                               class="text-xs px-3 py-1 bg-yellow-500 hover:bg-yellow-600 rounded-lg">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 py-6 text-center text-white/60">No complaints found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-3">{{ $complaints->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
