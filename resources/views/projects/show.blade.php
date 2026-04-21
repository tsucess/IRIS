<x-app-layout>
    <x-slot name="header" >
        <h2 class="text-xl font-semibold">Project: {{ $project->title }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 p-6">
        <div class="max-w-4xl mx-auto space-y-6">

            <div id="alertBox"></div>

            {{-- Add Task Form --}}
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6">
                <h3 class="text-white font-semibold text-lg mb-4">+ Add New Task</h3>
                <form id="addTaskForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Title *</label>
                        <input type="text" name="title" required placeholder="Task title"
                               class="block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Description</label>
                        <textarea name="description" rows="2" placeholder="Task description"
                                  class="block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 resize-y"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">Status</label>
                            <select name="status"
                                    class="block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-white mb-1">Due Date</label>
                            <input type="date" name="due_date"
                                   class="block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Assign To</label>
                        <select name="assigned_to[]" id="select_assign_to" multiple
                                class="block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors shadow">
                        + Add Task
                    </button>
                </form>
            </div>

            {{-- Tasks Table --}}
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/20 flex items-center justify-between">
                    <h3 class="text-white font-semibold text-lg">Tasks</h3>
                    <a href="{{ route('tasks.index', $project) }}"
                       class="text-white/70 text-sm hover:text-white transition-colors">View all &rsaquo;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="tasksTable">
                        <thead>
                            <tr class="bg-white/10 text-white/70 text-xs uppercase tracking-wider">
                                <th class="px-4 py-3 text-left">Title</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Due Date</th>
                                <th class="px-4 py-3 text-left">Assignees</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="taskList" class="divide-y divide-white/10">
                            @foreach ($tasks as $task)
                                @include('projects.partials.task-row', ['task' => $task])
                            @endforeach
                        </tbody>
                    </table>
                    @if($tasks->isEmpty())
                        <p class="text-center text-white/50 py-8 text-sm">No tasks yet. Add one above.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Edit Task Modal — pure Tailwind, no Bootstrap JS needed --}}
    <div id="editTaskModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <div class="relative z-10 bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="bg-indigo-700 text-white px-6 py-4 flex items-center justify-between">
                <h3 class="font-semibold text-lg">Edit Task</h3>
                <button type="button" onclick="closeEditModal()"
                        class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <form id="editTaskForm" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="edit_task_id">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" id="edit_title" required
                           class="block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="edit_description" rows="3"
                              class="block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 resize-y"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                        <select name="status" id="edit_status"
                                class="block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Due Date</label>
                        <input type="date" name="due_date" id="edit_due_date"
                               class="block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Assign To</label>
                    <select name="assigned_to[]" id="edit_assigned_to" multiple
                            class="block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-sm transition-colors">
                        Save Changes
                    </button>
                    <button type="button" onclick="closeEditModal()"
                            class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold text-sm transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const projectId = "{{ $project->id }}";
        const taskList  = document.getElementById('taskList');
        const alertBox  = document.getElementById('alertBox');

        /* ── Alert helper ─────────────────────────────────────────────── */
        function showAlert(msg, type = 'success') {
            const cls = type === 'success'
                ? 'bg-green-500/20 border border-green-400/30 text-green-200'
                : 'bg-red-500/20 border border-red-400/30 text-red-200';
            alertBox.innerHTML = `<div class="${cls} rounded-xl px-5 py-3 mb-4 text-sm">${msg}</div>`;
            setTimeout(() => alertBox.innerHTML = '', 3000);
        }

        /* ── Modal open / close ────────────────────────────────────────── */
        function openEditModal() {
            document.getElementById('editTaskModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeEditModal() {
            document.getElementById('editTaskModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeEditModal(); });

        /* ── Add Task ──────────────────────────────────────────────────── */
        document.getElementById('addTaskForm').addEventListener('submit', async e => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true; btn.textContent = 'Adding…';
            try {
                const res  = await fetch(`/projects/${projectId}/tasks`, { method: 'POST', body: new FormData(e.target) });
                const data = await res.json();
                if (data.success) { fetchTaskRow(data.task.id); e.target.reset(); showAlert('Task added!'); }
                else showAlert('Could not add task.', 'error');
            } catch { showAlert('Server error.', 'error'); }
            finally { btn.disabled = false; btn.textContent = '+ Add Task'; }
        });

        /* ── Open Edit Modal ───────────────────────────────────────────── */
        async function editTask(id) {
            try {
                const res  = await fetch(`/projects/${projectId}/tasks/${id}`);
                const data = await res.json();
                document.getElementById('edit_task_id').value    = id;
                document.getElementById('edit_title').value      = data.task.title;
                document.getElementById('edit_description').value = data.task.description || '';
                document.getElementById('edit_status').value     = data.task.status;
                document.getElementById('edit_due_date').value   = data.task.due_date || '';
                document.querySelectorAll('#edit_assigned_to option').forEach(opt => {
                    opt.selected = data.assignees.includes(parseInt(opt.value));
                });
                openEditModal();
            } catch { showAlert('Could not load task data.', 'error'); }
        }

        /* ── Update Task ───────────────────────────────────────────────── */
        document.getElementById('editTaskForm').addEventListener('submit', async e => {
            e.preventDefault();
            const id  = document.getElementById('edit_task_id').value;
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true; btn.textContent = 'Saving…';
            try {
                const fd = new FormData(e.target);
                fd.append('_method', 'PUT');
                const res  = await fetch(`/projects/${projectId}/tasks/${id}`, { method: 'POST', body: fd });
                const data = await res.json();
                if (data.success) { fetchTaskRow(id); closeEditModal(); showAlert('Task updated!'); }
                else showAlert('Could not update task.', 'error');
            } catch { showAlert('Server error.', 'error'); }
            finally { btn.disabled = false; btn.textContent = 'Save Changes'; }
        });

        /* ── Delete Task ───────────────────────────────────────────────── */
        async function deleteTask(id) {
            if (!confirm('Delete this task?')) return;
            try {
                const res  = await fetch(`/projects/${projectId}/tasks/${id}`, {
                    method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if (data.success) { document.getElementById(`task-${id}`)?.remove(); showAlert('Task deleted!'); }
            } catch { showAlert('Could not delete task.', 'error'); }
        }

        /* ── Refresh one row after add / update ────────────────────────── */
        async function fetchTaskRow(id) {
            const res  = await fetch(`/projects/${projectId}/tasks/${id}`);
            const data = await res.json();
            const assigneesHtml = data.assignees.map(uid => {
                const opt  = document.querySelector(`#edit_assigned_to option[value="${uid}"]`);
                const name = opt ? opt.textContent.trim() : uid;
                return `<span class="inline-block px-2 py-0.5 bg-indigo-500/20 text-indigo-300 rounded-full text-xs mr-1">${name}</span>`;
            }).join('');
            const sc = { 'Completed': 'bg-green-500/20 text-green-300', 'In Progress': 'bg-blue-500/20 text-blue-300', 'Pending': 'bg-yellow-500/20 text-yellow-300' };
            const statusCls = sc[data.task.status] || 'bg-gray-500/20 text-gray-300';
            const rowHtml = `
                <tr id="task-${id}" class="hover:bg-white/5 transition-colors">
                    <td class="px-4 py-3 text-white font-medium">${data.task.title}</td>
                    <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-semibold ${statusCls}">${data.task.status}</span></td>
                    <td class="px-4 py-3 text-white/70">${data.task.due_date || '—'}</td>
                    <td class="px-4 py-3">${assigneesHtml}</td>
                    <td class="px-4 py-3 space-x-1">
                        <button class="px-3 py-1 bg-yellow-500/20 text-yellow-300 hover:bg-yellow-500/30 rounded-lg text-xs font-semibold transition-colors" onclick="editTask(${id})">Edit</button>
                        <button class="px-3 py-1 bg-red-500/20 text-red-300 hover:bg-red-500/30 rounded-lg text-xs font-semibold transition-colors" onclick="deleteTask(${id})">Delete</button>
                    </td>
                </tr>`;
            const oldRow = document.getElementById(`task-${id}`);
            if (oldRow) oldRow.outerHTML = rowHtml;
            else taskList.insertAdjacentHTML('beforeend', rowHtml);
        }
    </script>
    @endpush
</x-app-layout>
