<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📜 Audit Log</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Filters --}}
            <form method="GET" action="{{ route('superadmin.audit-log') }}"
                  class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-4 flex flex-wrap gap-3">
                <select name="action" class="px-3 py-1.5 rounded-lg bg-white/20 border border-white/30 text-white text-sm">
                    <option value="">All Actions</option>
                    @foreach(['created','updated','deleted','restored'] as $a)
                        <option value="{{ $a }}" @selected(request('action') === $a)>{{ ucfirst($a) }}</option>
                    @endforeach
                </select>
                <input name="model" value="{{ request('model') }}" placeholder="Model (e.g. Project)"
                       class="px-3 py-1.5 rounded-lg bg-white/20 border border-white/30 text-white text-sm placeholder-white/50">
                <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-semibold">
                    Filter
                </button>
                <a href="{{ route('superadmin.audit-log') }}" class="px-4 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-sm">
                    Clear
                </a>
            </form>

            {{-- Log Table --}}
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white/10">
                            <tr>
                                <th class="px-4 py-3 text-left">Time</th>
                                <th class="px-4 py-3 text-left">User</th>
                                <th class="px-4 py-3 text-left">Action</th>
                                <th class="px-4 py-3 text-left">Model</th>
                                <th class="px-4 py-3 text-left">Record</th>
                                <th class="px-4 py-3 text-left">Changes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($logs as $log)
                                <tr class="hover:bg-white/10 transition">
                                    <td class="px-4 py-3 text-white/60 whitespace-nowrap">
                                        {{ $log->created_at->format('M d H:i') }}
                                    </td>
                                    <td class="px-4 py-3">{{ $log->user?->full_name ?? 'System' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold
                                            {{ match($log->action) {
                                                'created'  => 'bg-green-500',
                                                'updated'  => 'bg-blue-500',
                                                'deleted'  => 'bg-red-500',
                                                'restored' => 'bg-yellow-500',
                                                default    => 'bg-gray-500'
                                            } }}">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-white/70">
                                        {{ class_basename($log->model_type) }}
                                    </td>
                                    <td class="px-4 py-3">{{ $log->model_label ?? '#'.$log->model_id }}</td>
                                    <td class="px-4 py-3 text-xs text-white/60 max-w-xs truncate">
                                        @if($log->new_values)
                                            {{ implode(', ', array_keys($log->new_values)) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-6 text-center text-white/60">No audit logs yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-3">{{ $logs->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
