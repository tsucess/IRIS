<tr id="task-{{ $task->id }}" class="hover:bg-white/5 transition-colors">
    <td class="px-4 py-3 text-white font-medium">{{ $task->title }}</td>
    <td class="px-4 py-3">
        <span @class([
            'px-2 py-0.5 rounded-full text-xs font-semibold',
            'bg-green-500/20 text-green-300'   => $task->status === 'Completed',
            'bg-blue-500/20 text-blue-300'     => $task->status === 'In Progress',
            'bg-yellow-500/20 text-yellow-300' => $task->status === 'Pending',
            'bg-gray-500/20 text-gray-300'     => !in_array($task->status, ['Completed','In Progress','Pending']),
        ])>{{ $task->status }}</span>
    </td>
    <td class="px-4 py-3 text-white/70">{{ $task->due_date ?? '—' }}</td>
    <td class="px-4 py-3">
        @foreach($task->assignees as $u)
            <span class="inline-block px-2 py-0.5 bg-indigo-500/20 text-indigo-300 rounded-full text-xs mr-1">
                {{ $u->full_name }}
            </span>
        @endforeach
    </td>
    <td class="px-4 py-3 space-x-1">
        <button class="px-3 py-1 bg-yellow-500/20 text-yellow-300 hover:bg-yellow-500/30 rounded-lg text-xs font-semibold transition-colors"
                onclick="editTask({{ $task->id }})">Edit</button>
        <button class="px-3 py-1 bg-red-500/20 text-red-300 hover:bg-red-500/30 rounded-lg text-xs font-semibold transition-colors"
                onclick="deleteTask({{ $task->id }})">Delete</button>
    </td>
</tr>
