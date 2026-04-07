{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Edit Project: {{ $project->title }}</h2>
    </x-slot>

    <div class="container py-6 d-flex justify-content-center">
        <div class="col-12 col-lg-6 backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6">
            <form method="POST" action="{{ route('projects.update', $project) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <x-input-label for="title" :value="'Title'" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                        value="{{ old('title', $project->title) }}" required />
                </div>

                <div class="mb-4">
                    <x-input-label for="description" :value="'Description'" />
                    <textarea id="description" name="description" class="form-control">{{ old('description', $project->description) }}</textarea>
                </div>
                <!-- Dates -->
                <div class="mb-3">
                    <x-input-label for="start_date" value="Start Date" />
                    <x-text-input id="start_date" name="start_date" type="date" class="form-control"
                        value="{{ old('start_date', $project->start_date) }}" />
                </div>
                <div class="mb-3">
                    <x-input-label for="end_date" value="End Date" />
                    <x-text-input id="end_date" name="end_date" type="date" class="form-control"
                        value="{{ old('end_date', $project->end_date) }}" />
                </div>

                <div class="mb-4">
                    <x-input-label for="street_id" :value="'Street (optional)'" />
                    <select name="street_id" class="form-select">
                        <option value="">-- Select Street --</option>
                        @foreach ($streets as $street)
                            <option value="{{ $street->id }}" @selected(old('street_id', $project->street_id) == $street->id)>
                                {{ $street->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <x-input-label for="status" :value="'Status'" />
                    <select name="status" class="form-select">
                        <option value="pending" @selected(old('status', $project->status) == 'pending')>Pending</option>
                        <option value="ongoing" @selected(old('status', $project->status) == 'ongoing')>Ongoing</option>
                        <option value="completed" @selected(old('status', $project->status) == 'completed')>Completed</option>
                    </select>
                </div>

                <div class="mb-4">
                    <x-input-label for="user_ids" :value="'Assign Users'" />
                    <select name="user_ids[]" id="user_ids" class="form-select" multiple>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected($project->users->contains($user->id))>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-light">Hold CTRL (or CMD) to select multiple</small>
                </div>

                <x-primary-button>Update Project</x-primary-button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#user_ids').select2({
                placeholder: "Select users...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</x-app-layout> --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-dark">Edit Project: {{ $project->title }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white py-6">
        <div class="container d-flex justify-content-center">
            <div class="col-12 col-lg-6 backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-5">
                <form method="POST" action="{{ route('projects.update', $project) }}">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-3">
                        <x-input-label for="title" :value="'Project Title'" />
                        <x-text-input id="title" name="title" type="text" class="form-control mt-1"
                            value="{{ old('title', $project->title) }}" required />
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <x-input-label for="description" :value="'Description'" />
                        <textarea id="description" name="description" class="form-control">{{ old('description', $project->description) }}</textarea>
                    </div>

                    <!-- Dates -->
                    <div class="row mb-3">
                        <div class="col">
                            <x-input-label for="start_date" :value="'Start Date'" />
                            <x-text-input id="start_date" name="start_date" type="date" class="form-control"
                                value="{{ old('start_date', $project->start_date) }}" />
                        </div>
                        <div class="col">
                            <x-input-label for="end_date" :value="'End Date'" />
                            <x-text-input id="end_date" name="end_date" type="date" class="form-control"
                                value="{{ old('end_date', $project->end_date) }}" />
                        </div>
                    </div>

                    <!-- Street -->
                    <div class="mb-3">
                        <x-input-label for="street_id" :value="'Street (optional)'" />
                        <select name="street_id" class="form-select">
                            <option value="">-- Select Street --</option>
                            @foreach ($streets as $street)
                                <option value="{{ $street->id }}" @selected(old('street_id', $project->street_id) == $street->id)>
                                    {{ $street->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <x-input-label for="status" :value="'Status'" />
                        <select name="status" class="form-select">
                            <option value="pending" @selected(old('status', $project->status) == 'pending')>Pending</option>
                            <option value="ongoing" @selected(old('status', $project->status) == 'ongoing')>Ongoing</option>
                            <option value="completed" @selected(old('status', $project->status) == 'completed')>Completed</option>
                        </select>
                    </div>

                    <!-- Assign Users -->
                    {{-- <div class="mb-3">
                        <x-input-label for="user_ids" :value="'Assign Users'" />
                        <select name="user_ids[]" id="user_ids" class="form-select" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected($project->users->contains($user->id))>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <x-primary-button>Update Project</x-primary-button>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#user_ids').select2({
                    placeholder: "Select users...",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#user_ids').parent()
                });
            });
        </script>
    @endpush
</x-app-layout>
