<tr id="task-{{ $task->id }}">
    <td>{{ $task->title }}</td>
    <td><span class="badge bg-info">{{ $task->status }}</span></td>
    <td>{{ $task->due_date ?? '—' }}</td>
    <td>
        @foreach($task->assignees as $u)
            <span class="badge bg-secondary">{{ $u->name }}</span>
        @endforeach
    </td>
    <td>
        <button class="btn btn-sm btn-warning" onclick="editTask({{ $task->id }})">Edit</button>
        <button class="btn btn-sm btn-danger" onclick="deleteTask({{ $task->id }})">Delete</button>
    </td>
</tr>
