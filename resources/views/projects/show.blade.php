<x-app-layout>
    <x-slot name="header" >
        <h2 class="text-xl font-semibold">Project: {{ $project->title }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="container">

            <div id="alertBox"></div>

            <!-- Task Form -->
            <div class="card glass-card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Add New Task</h5>
                    <form id="addTaskForm">
                        @csrf
                        <input type="text" name="title" class="form-control mb-2" placeholder="Task title" required>
                        <textarea name="description" class="form-control mb-2" placeholder="Task description"></textarea>
                        <select name="status" class="form-select mb-2">
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                        <input type="date" name="due_date" class="form-control mb-2">
                        <label>Assign To</label>
                        <select name="assigned_to[]" multiple id="select_assign_to" class="form-select mb-3">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        {{-- <label for="assigned_to" class="form-label">Assign Users</label>
                        <select name="assigned_to[]" id="assigned_to" class="form-control" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(in_array($user->id, old('assigned_to', $task->assignees->pluck('id')->toArray() ?? [])))>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select> --}}
                        <button class="btn btn-primary mt-2">Add Task</button>
                    </form>
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="card glass-card">
                <div class="card-body">
                    <h5>Tasks</h5>
                    <table class="table text-white" id="tasksTable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Assignees</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="taskList">
                            @foreach ($tasks as $task)
                                @include('projects.partials.task-row', ['task' => $task])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editTaskForm" class="modal-content">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_task_id">
                    <input type="text" name="title" id="edit_title" class="form-control mb-2" required>
                    <textarea name="description" id="edit_description" class="form-control mb-2"></textarea>
                    <select name="status" id="edit_status" class="form-select mb-2">
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                    <input type="date" name="due_date" id="edit_due_date" class="form-control mb-2">
                    <label>Assign To</label>
                    <select name="assigned_to[]" id="edit_assigned_to" multiple class="form-select mb-2">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#select_assign_to').select2({
                    placeholder: "Select users...",
                    allowClear: true,
                    width: '100%'
                });
            });
      
            $(document).ready(function() {
                $('#edit_assigned_to').select2({
                    placeholder: "Select users...",
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>

        <script>
            const projectId = "{{ $project->id }}";
            const taskList = document.getElementById('taskList');
            const alertBox = document.getElementById('alertBox');

            function showAlert(msg, type = 'success') {
                alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
                setTimeout(() => alertBox.innerHTML = '', 3000);
            }

            // Add Task AJAX
            document.getElementById('addTaskForm').addEventListener('submit', async e => {
                e.preventDefault();
                let formData = new FormData(e.target);
                let res = await fetch(`/projects/${projectId}/tasks`, {
                    method: 'POST',
                    body: formData
                });
                let data = await res.json();
                if (data.success) {
                    fetchTaskRow(data.task.id);
                    e.target.reset();
                    showAlert('Task added!');
                }
            });

            // Edit Modal Fill
            async function editTask(id) {
                let res = await fetch(`/projects/${projectId}/tasks/${id}`);
                let data = await res.json();
                document.getElementById('edit_task_id').value = id;
                document.getElementById('edit_title').value = data.task.title;
                document.getElementById('edit_description').value = data.task.description || '';
                document.getElementById('edit_status').value = data.task.status;
                document.getElementById('edit_due_date').value = data.task.due_date || '';
                document.querySelectorAll('#edit_assigned_to option').forEach(opt => {
                    opt.selected = data.assignees.includes(parseInt(opt.value));
                });
                new bootstrap.Modal(document.getElementById('editTaskModal')).show();
            }

            // Update Task AJAX
            document.getElementById('editTaskForm').addEventListener('submit', async e => {
                e.preventDefault();
                let id = document.getElementById('edit_task_id').value;
                let formData = new FormData(e.target);
                formData.append('_method', 'PUT');
                let res = await fetch(`/projects/${projectId}/tasks/${id}`, {
                    method: 'POST',
                    body: formData
                });
                let data = await res.json();
                if (data.success) {
                    fetchTaskRow(id);
                    bootstrap.Modal.getInstance(document.getElementById('editTaskModal')).hide();
                    showAlert('Task updated!');
                }
            });

            // Delete Task
            async function deleteTask(id) {
                if (!confirm('Delete this task?')) return;
                let res = await fetch(`/projects/${projectId}/tasks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                let data = await res.json();
                if (data.success) {
                    document.getElementById(`task-${id}`).remove();
                    showAlert('Task deleted!');
                }
            }

            // Refresh a single row via partial view
            // Refresh a single row via partial view
            async function fetchTaskRow(id) {
                let res = await fetch(`/projects/${projectId}/tasks/${id}`);
                let data = await res.json();

                // Build assignees badges
                let assigneesHtml = data.assignees.map(uid => {
                    let option = document.querySelector(`#edit_assigned_to option[value="${uid}"]`);
                    let name = option ? option.textContent : uid;
                    return `<span class="badge bg-secondary me-1">${name}</span>`;
                }).join(' ');

                let rowHtml = `
                <tr id="task-${id}">
                    <td>${data.task.title}</td>
                    <td><span class="badge bg-info">${data.task.status}</span></td>
                    <td>${data.task.due_date || '—'}</td>
                    <td>${assigneesHtml}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editTask(${id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteTask(${id})">Delete</button>
                    </td>
                </tr>`;

                let oldRow = document.getElementById(`task-${id}`);
                if (oldRow) oldRow.outerHTML = rowHtml;
                else taskList.insertAdjacentHTML('beforeend', rowHtml);
            }
        </script>
    @endpush
</x-app-layout>
