<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Tasks - {{ $project->title }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="container">
            <a href="{{ route('tasks.create', $project) }}" class="btn btn-primary mb-3">+ Add Task</a>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card glass-card">
                <div class="card-body table-responsive">
                    <table class="table text-white align-middle">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Assignee</th>
                                <th>Due Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>
                                        <span class="badge bg-{{ $task->priority_badge_color }}">
                                            {{ ucfirst($task->priority ?? 'medium') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $task->status == 'Completed' ? 'success' : ($task->status == 'In Progress' ? 'info' : 'warning') }}">
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                    <td>{{ $task->assignee?->firstname ?? 'Unassigned' }}</td>
                                    <td>{{ $task->due_date ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('tasks.edit', [$project, $task]) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('tasks.destroy', [$project, $task]) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No tasks found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>


    
</x-app-layout>
