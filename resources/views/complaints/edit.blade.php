<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">✏️ Edit Complaint</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-2xl mx-auto">
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-8 space-y-5">

                @if($errors->any())
                    <div class="bg-red-500/30 border border-red-400 rounded-xl px-4 py-3 text-sm">
                        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('complaints.update', $complaint) }}" class="space-y-5">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-sm font-medium mb-1">Title *</label>
                        <input name="title" value="{{ old('title', $complaint->title) }}" required
                               class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Category *</label>
                        <select name="category" required
                                class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white">
                            @foreach(['road','water','electricity','sanitation','security','noise','other'] as $cat)
                                <option value="{{ $cat }}" @selected(old('category', $complaint->category) === $cat)>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Priority *</label>
                        <select name="priority" required
                                class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white">
                            @foreach(['low','medium','high','urgent'] as $p)
                                <option value="{{ $p }}" @selected(old('priority', $complaint->priority) === $p)>
                                    {{ ucfirst($p) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Description *</label>
                        <textarea name="description" rows="5" required
                                  class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white">{{ old('description', $complaint->description) }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold">
                            Save Changes
                        </button>
                        <a href="{{ route('complaints.show', $complaint) }}"
                           class="px-6 py-2 bg-white/20 hover:bg-white/30 rounded-lg font-semibold">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
