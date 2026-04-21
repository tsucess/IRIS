<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">{{ isset($task) ? 'Edit Task' : 'Add Task' }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 p-6">
        <x-glass-form method="POST" action="{{ isset($task)
            ? route('tasks.update', [$project, $task])
            : route('tasks.store', $project) }}">
            @csrf
            @isset($task) @method('PUT') @endisset

            @if($errors->any())
                <div class="mb-4 bg-red-500/20 border border-red-400/30 rounded-xl px-5 py-4 text-sm text-red-200 space-y-1">
                    <p class="font-semibold mb-1">Please fix the following:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            {{-- Title --}}
            <div class="mb-3">
                <x-input-label for="title" :value="'Title'" class="text-white" />
                <x-text-input id="title" name="title" type="text"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
                    value="{{ old('title', $task->title ?? '') }}" required />
                <x-input-error :messages="$errors->get('title')" class="text-red-300" />
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <x-input-label for="description" :value="'Description'" class="text-white" />
                <textarea id="description" name="description" rows="3"
                          class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 resize-y">{{ old('description', $task->description ?? '') }}</textarea>
            </div>

            {{-- Assign To --}}
            <div class="mb-3">
                <x-input-label for="assigned_to" :value="'Assign To'" class="text-white" />
                <select id="assigned_to" name="assigned_to"
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            @selected(old('assigned_to', $task->assigned_to ?? '') == $user->id)>
                            {{ $user->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <x-input-label for="status" :value="'Status'" class="text-white" />
                <select id="status" name="status" required
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach(['Pending','In Progress','Completed'] as $s)
                        <option value="{{ $s }}" @selected(old('status', $task->status ?? 'Pending') == $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Priority --}}
            <div class="mb-3">
                <x-input-label for="priority" :value="'Priority'" class="text-white" />
                <select id="priority" name="priority" required
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'] as $val => $lbl)
                        <option value="{{ $val }}" @selected(old('priority', $task->priority ?? 'medium') == $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Due Date --}}
            <div class="mb-4">
                <x-input-label for="due_date" :value="'Due Date'" class="text-white" />
                <x-text-input id="due_date" name="due_date" type="date"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
                    value="{{ old('due_date', $task->due_date ?? '') }}" />
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-4">
                <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                    {{ isset($task) ? '💾 Update Task' : '+ Create Task' }}
                </x-primary-button>
                <a href="{{ route('tasks.index', $project) }}"
                   class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">↩ Cancel</a>
            </div>
        </x-glass-form>
    </div>
</x-app-layout>
