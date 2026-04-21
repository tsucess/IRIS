<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tasks — {{ $project->title }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 p-6">
        <div class="max-w-5xl mx-auto space-y-4">

            {{-- Actions + Flash --}}
            <div class="flex items-center justify-between">
                <a href="{{ route('tasks.create', $project) }}"
                   class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors shadow">
                    + Add Task
                </a>
                <a href="{{ route('projects.show', $project) }}"
                   class="text-white/70 text-sm hover:text-white transition-colors">← Back to project</a>
            </div>

            @if(session('success'))
                <div class="bg-green-500/20 border border-green-400/30 text-green-200 rounded-xl px-5 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Table card --}}
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-white/10 text-white/70 text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 text-left">Title</th>
                                <th class="px-4 py-3 text-left">Priority</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Assignee</th>
                                <th class="px-4 py-3 text-left">Due Date</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($tasks as $task)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-4 py-3 text-white font-medium">{{ $task->title }}</td>
                                    <td class="px-4 py-3">
                                        <span @class([
                                            'px-2 py-0.5 rounded-full text-xs font-semibold',
                                            'bg-red-500/20 text-red-300'    => ($task->priority ?? 'medium') === 'urgent',
                                            'bg-orange-500/20 text-orange-300' => ($task->priority ?? 'medium') === 'high',
                                            'bg-blue-500/20 text-blue-300'  => ($task->priority ?? 'medium') === 'medium',
                                            'bg-gray-500/20 text-gray-300'  => ($task->priority ?? 'medium') === 'low',
                                        ])>{{ ucfirst($task->priority ?? 'medium') }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span @class([
                                            'px-2 py-0.5 rounded-full text-xs font-semibold',
                                            'bg-green-500/20 text-green-300'   => $task->status === 'Completed',
                                            'bg-blue-500/20 text-blue-300'     => $task->status === 'In Progress',
                                            'bg-yellow-500/20 text-yellow-300' => $task->status === 'Pending',
                                        ])>{{ $task->status }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-white/80">
                                        {{ $task->assignee?->full_name ?? 'Unassigned' }}
                                    </td>
                                    <td class="px-4 py-3 text-white/70">{{ $task->due_date ?? '—' }}</td>
                                    <td class="px-4 py-3 space-x-1">
                                        <a href="{{ route('tasks.edit', [$project, $task]) }}"
                                           class="px-3 py-1 bg-yellow-500/20 text-yellow-300 hover:bg-yellow-500/30 rounded-lg text-xs font-semibold transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('tasks.destroy', [$project, $task]) }}" method="POST"
                                              class="inline" onsubmit="return confirm('Delete this task?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 bg-red-500/20 text-red-300 hover:bg-red-500/30 rounded-lg text-xs font-semibold transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-white/50 text-sm">
                                        No tasks found. <a href="{{ route('tasks.create', $project) }}" class="text-blue-300 hover:underline">Add the first one.</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($tasks->hasPages())
                    <div class="px-4 py-3 border-t border-white/10">{{ $tasks->links() }}</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
