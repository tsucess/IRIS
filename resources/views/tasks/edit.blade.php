<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">{{ isset($task) ? 'Edit Task' : 'Add Task' }}</h2>
    </x-slot>

    <div class="d-flex justify-content-center">
        <div class="card glass-card w-50">
            <div class="card-body">
                <form method="POST" action="{{ isset($task) 
                    ? route('tasks.update', [$project, $task]) 
                    : route('tasks.store', $project) }}">
                    @csrf
                    @isset($task) @method('PUT') @endisset

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input name="title" class="form-control" 
                               value="{{ old('title', $task->title ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control">{{ old('description', $task->description ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assign To</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">-- Select User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    @selected(old('assigned_to', $task->assigned_to ?? '') == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach(['Pending','In Progress','Completed'] as $status)
                                <option value="{{ $status }}" 
                                    @selected(old('status', $task->status ?? 'Pending') == $status)>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-control"
                               value="{{ old('due_date', $task->due_date ?? '') }}">
                    </div>

                    <button class="btn btn-primary">{{ isset($task) ? 'Update Task' : 'Create Task' }}</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
