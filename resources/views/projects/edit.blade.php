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

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 p-6">
        <x-glass-form method="POST" action="{{ route('projects.update', $project) }}">
            @csrf
            @method('PUT')

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
                <x-input-label for="title" :value="'Project Title'" class="text-white" />
                <x-text-input id="title" name="title" type="text"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
                    value="{{ old('title', $project->title) }}" required />
                <x-input-error :messages="$errors->get('title')" class="text-red-300" />
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <x-input-label for="description" :value="'Description'" class="text-white" />
                <textarea id="description" name="description" rows="4"
                          class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 resize-y">{{ old('description', $project->description) }}</textarea>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <x-input-label for="start_date" :value="'Start Date'" class="text-white" />
                    <x-text-input id="start_date" name="start_date" type="date"
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
                        value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}" />
                </div>
                <div>
                    <x-input-label for="end_date" :value="'End Date'" class="text-white" />
                    <x-text-input id="end_date" name="end_date" type="date"
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
                        value="{{ old('end_date', optional($project->end_date)->format('Y-m-d')) }}" />
                </div>
            </div>

            {{-- Street --}}
            <div class="mb-3">
                <x-input-label for="street_id" :value="'Street (optional)'" class="text-white" />
                <select id="street_id" name="street_id"
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Select Street --</option>
                    @foreach ($streets as $street)
                        <option value="{{ $street->id }}" @selected(old('street_id', $project->street_id) == $street->id)>
                            {{ $street->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <x-input-label for="status" :value="'Status'" class="text-white" />
                <select id="status" name="status"
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="pending"   @selected(old('status', $project->status) === 'pending')>Pending</option>
                    <option value="ongoing"   @selected(old('status', $project->status) === 'ongoing')>Ongoing</option>
                    <option value="completed" @selected(old('status', $project->status) === 'completed')>Completed</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-4">
                <x-primary-button class="bg-blue-600 hover:bg-blue-700">💾 Update Project</x-primary-button>
                <a href="{{ route('projects.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">↩ Cancel</a>
            </div>
        </x-glass-form>
    </div>
</x-app-layout>
