<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📢 New Announcement</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-2xl mx-auto">
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-8 space-y-5">

                @if($errors->any())
                    <div class="bg-red-500/30 border border-red-400 rounded-xl px-4 py-3 text-sm">
                        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('announcements.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-1">Title *</label>
                        <input name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/50"
                               placeholder="Announcement title">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Audience *</label>
                        <select name="audience" required
                                class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white">
                            <option value="all" @selected(old('audience','all')==='all')>Everyone</option>
                            <option value="admins" @selected(old('audience')==='admins')>Admins Only</option>
                            <option value="residents" @selected(old('audience')==='residents')>Residents Only</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Body *</label>
                        <textarea name="body" rows="6" required
                                  class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/50"
                                  placeholder="Announcement content...">{{ old('body') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Expires At (optional)</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                               class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white">
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="pinned" id="pinned" value="1"
                               class="rounded" @checked(old('pinned'))>
                        <label for="pinned" class="text-sm font-medium">📌 Pin this announcement</label>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold">
                            Post Announcement
                        </button>
                        <a href="{{ route('announcements.index') }}"
                           class="px-6 py-2 bg-white/20 hover:bg-white/30 rounded-lg font-semibold">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
