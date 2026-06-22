<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">Resource Allocations Overview</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-stat-card title="Total Allocated" :value="number_format($summary['total_allocated'], 2)" />
                <x-stat-card title="Total Used"      :value="number_format($summary['total_used'], 2)" />
                <x-stat-card title="Total Remaining" :value="number_format($summary['total_remaining'], 2)" />
            </div>

            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-4">
                <form method="GET" class="flex flex-wrap items-end gap-2 mb-4">
                    <select name="type" class="rounded-md text-gray-900 px-2 py-2 text-sm">
                        <option value="">All Types</option>
                        @foreach (['funds','materials','manpower','equipment','other'] as $t)
                            <option value="{{ $t }}" @selected(request('type') === $t)>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="rounded-md text-gray-900 px-2 py-2 text-sm">
                        <option value="">All Statuses</option>
                        @foreach (['planned','approved','in_use','depleted','cancelled'] as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                    <button class="px-3 py-2 bg-indigo-600 rounded-md text-sm hover:bg-indigo-700">Filter</button>
                    <a href="{{ route('allocations.overview') }}" class="text-sm underline">Clear</a>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead class="bg-white/30 text-gray-800 uppercase text-sm font-bold">
                            <tr>
                                <th class="py-3 px-4">Project</th>
                                <th class="py-3 px-4">Resource</th>
                                <th class="py-3 px-4">Type</th>
                                <th class="py-3 px-4">Allocated</th>
                                <th class="py-3 px-4">Used</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Allocated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allocations as $a)
                                <tr class="border-b border-white/20 hover:bg-white/30 transition">
                                    <td class="py-2 px-4">
                                        <a href="{{ route('projects.allocations.index', $a->project) }}"
                                            class="underline">{{ $a->project->title ?? '—' }}</a>
                                    </td>
                                    <td class="py-2 px-4">{{ $a->name }}</td>
                                    <td class="py-2 px-4 capitalize">{{ $a->resource_type }}</td>
                                    <td class="py-2 px-4">{{ number_format($a->allocated_amount, 2) }} {{ $a->unit }}</td>
                                    <td class="py-2 px-4">{{ number_format($a->used_amount, 2) }} {{ $a->unit }}</td>
                                    <td class="py-2 px-4 capitalize">{{ str_replace('_',' ', $a->status) }}</td>
                                    <td class="py-2 px-4">{{ optional($a->allocated_at)->format('Y-m-d') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center py-6 text-white/70">No allocations yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $allocations->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
