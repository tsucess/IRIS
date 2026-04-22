{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Projects</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="container">
            <!-- Flash Message -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">
                + Add New Project
            </a>

            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-4">
                <table class="table table-hover text-white align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Street</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td>{{ $project->title }}</td>
                                <td>
                                    @php
                                        $badgeClass = match ($project->status) {
                                            'Completed' => 'bg-success',
                                            'Ongoing' => 'bg-info',
                                            default => 'bg-warning',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $project->status }}</span>
                                </td>
                                <td>{{ $project->street?->name ?? 'N/A' }}</td>
                                <td>{{ $project->start_date ?? '—' }}</td>
                                <td>{{ $project->end_date ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('projects.show', $project) }}"
                                        class="btn btn-sm btn-secondary">View</a>
                                    <a href="{{ route('projects.edit', $project) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete('{{ route('projects.destroy', $project) }}')">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No projects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $projects->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this project?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(url) {
                document.getElementById('deleteForm').action = url;
                var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            }
        </script>
    @endpush
</x-app-layout> --}}

{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Projects</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="container">
            <!-- Flash Message -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">
                + Add New Project
            </a>

            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-4">
                <table class="table table-hover text-white align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Street</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Assigned Users</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td>{{ $project->title }}</td>
                                <td>
                                    @php
                                        $badgeClass = match ($project->status) {
                                            'Completed' => 'bg-success',
                                            'Ongoing' => 'bg-info',
                                            default => 'bg-warning',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($project->status) }}</span>
                                </td>
                                <td>{{ $project->street?->name ?? 'N/A' }}</td>
                                <td>{{ $project->start_date ?? '—' }}</td>
                                <td>{{ $project->end_date ?? '—' }}</td>
                                <td>
                                    @forelse($project->users as $user)
                                        <span class="badge bg-primary">{{ $user->name }}</span>
                                    @empty
                                        <span class="text-muted">None</span>
                                    @endforelse
                                </td>
                                <td>
                                    <a href="{{ route('projects.show', $project) }}"
                                        class="btn btn-sm btn-secondary">View</a>
                                    <a href="{{ route('projects.edit', $project) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete('{{ route('projects.destroy', $project) }}')">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No projects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $projects->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this project?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDelete(url) {
                document.getElementById('deleteForm').action = url;
                var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            }
        </script>
    @endpush
</x-app-layout> --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-dark leading-tight">Projects</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Add Project Button -->
            @can('create', App\Models\Project::class)
                <div class="mb-4">
                    <a href="{{ route('projects.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 transition">
                        + Add New Project
                    </a>
                </div>
            @endcan

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 bg-green-500/20 text-green-200 px-4 py-2 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Glassmorphism Table -->
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead class="bg-white/10">
                            <tr>
                                <th class="px-4 py-3 text-left">Title</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Street</th>
                                <th class="px-4 py-3 text-left">Start Date</th>
                                <th class="px-4 py-3 text-left">End Date</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($projects as $project)
                                <tr class="hover:bg-white/10 transition">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('projects.show', $project) }}" class="text-white font-semibold hover:underline">
                                            {{ $project->title }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2">
                                        @php
                                            $statusColor = match ($project->status) {
                                                'completed' => 'bg-green-500/30 text-green-200',
                                                'ongoing'   => 'bg-blue-500/30 text-blue-200',
                                                default     => 'bg-yellow-500/30 text-yellow-200',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusColor }}">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">{{ $project->street?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $project->start_date ?? '—' }}</td>
                                    <td class="px-4 py-2">{{ $project->end_date ?? '—' }}</td>
                                    <td class="py-2 px-4 space-x-2">
                                        @if(auth()->user()->role !== 'user')
                                            <a href="{{ route('projects.show', $project) }}"
                                                class="px-3 py-1 bg-sky-200 text-sky-800 rounded shadow hover:bg-sky-300 transition">👁️
                                            </a>
                                        @endif

                                        @can('update', $project)
                                            <a href="{{ route('projects.edit', $project) }}"
                                                class="px-3 py-1 bg-yellow-400 rounded shadow hover:bg-yellow-500 transition">✏️
                                            </a>
                                        @endcan

                                        @can('delete', $project)
                                            <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                                class="inline-block" onsubmit="return confirm('Delete this project?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-500 text-white rounded shadow hover:bg-red-600 transition">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-white/60">No projects found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $projects->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
