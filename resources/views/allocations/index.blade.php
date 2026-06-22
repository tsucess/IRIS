<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Allocations · {{ $project->title }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex items-center justify-between">
                <a href="{{ route('projects.show', $project) }}"
                    class="text-sm text-white/80 underline">← Back to project</a>
                <a href="{{ route('projects.allocations.create', $project) }}"
                    class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                    ➕ Allocate Resource
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead class="bg-white/30 text-gray-800 uppercase text-sm font-bold">
                            <tr>
                                <th class="py-3 px-4">Resource</th>
                                <th class="py-3 px-4">Type</th>
                                <th class="py-3 px-4">Allocated</th>
                                <th class="py-3 px-4">Used</th>
                                <th class="py-3 px-4">Remaining</th>
                                <th class="py-3 px-4">Utilization</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">By</th>
                                <th class="py-3 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allocations as $a)
                                <tr class="border-b border-white/20 hover:bg-white/30 transition">
                                    <td class="py-2 px-4">{{ $a->name }}</td>
                                    <td class="py-2 px-4 capitalize">{{ $a->resource_type }}</td>
                                    <td class="py-2 px-4">{{ number_format($a->allocated_amount, 2) }} {{ $a->unit }}</td>
                                    <td class="py-2 px-4">{{ number_format($a->used_amount, 2) }} {{ $a->unit }}</td>
                                    <td class="py-2 px-4">{{ number_format($a->remaining, 2) }} {{ $a->unit }}</td>
                                    <td class="py-2 px-4">
                                        <div class="w-24 bg-white/20 rounded h-2">
                                            <div class="h-2 rounded {{ $a->isOverAllocated() ? 'bg-red-500' : 'bg-emerald-500' }}"
                                                style="width: {{ min(100, $a->utilization_percent) }}%"></div>
                                        </div>
                                        <span class="text-xs">{{ $a->utilization_percent }}%</span>
                                    </td>
                                    <td class="py-2 px-4 capitalize">{{ str_replace('_',' ', $a->status) }}</td>
                                    <td class="py-2 px-4">{{ $a->allocatedBy->full_name ?? '—' }}</td>
                                    <td class="py-2 px-4 space-x-2 whitespace-nowrap">
                                        <a href="{{ route('projects.allocations.edit', [$project, $a]) }}"
                                            class="px-3 py-1 bg-yellow-400 rounded shadow hover:bg-yellow-500 transition">✏️</a>
                                        <form action="{{ route('projects.allocations.destroy', [$project, $a]) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('Delete this allocation?')">
                                            @csrf @method('DELETE')
                                            <button class="px-3 py-1 bg-red-500 text-white rounded shadow hover:bg-red-600 transition">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-center py-6 text-white/70">No allocations for this project yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $allocations->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
