<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">✏️ Edit Announcement</h2>
            <a href="{{ route('announcements.manage') }}"
               class="text-sm text-gray-500 hover:text-gray-800 transition-colors">
                ← Back to Management
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 p-6">
        <x-glass-form method="POST" action="{{ route('announcements.update', $announcement) }}">
            @csrf
            @method('PUT')

            {{-- Validation errors --}}
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
                    value="{{ old('title', $announcement->title) }}" required />
                <x-input-error :messages="$errors->get('title')" class="text-red-300" />
            </div>

            {{-- Audience --}}
            <div class="mb-3">
                <x-input-label for="audience" :value="'Audience'" class="text-white" />
                <select id="audience" name="audience" required
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="all"       @selected(old('audience', $announcement->audience)==='all')>🌍 Everyone (public)</option>
                    <option value="residents" @selected(old('audience', $announcement->audience)==='residents')>🏘 Residents Only</option>
                    <option value="admins"    @selected(old('audience', $announcement->audience)==='admins')>🔒 Admins Only</option>
                </select>
                <p class="text-sm text-gray-200 mt-1">"Everyone" is publicly visible — even to guests who are not logged in.</p>
            </div>

            {{-- Body --}}
            <div class="mb-3">
                <x-input-label for="body" :value="'Body'" class="text-white" />
                <textarea id="body" name="body" rows="8" required
                          class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 resize-y">{{ old('body', $announcement->body) }}</textarea>
                <x-input-error :messages="$errors->get('body')" class="text-red-300" />
            </div>

            {{-- Expires At --}}
            <div class="mb-3">
                <x-input-label for="expires_at" :value="'Expires At (optional)'" class="text-white" />
                <input type="datetime-local" id="expires_at" name="expires_at"
                       value="{{ old('expires_at', optional($announcement->expires_at)->format('Y-m-d\TH:i')) }}"
                       class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="text-sm text-gray-200 mt-1">Leave blank to never expire.</p>
            </div>

            {{-- Pinned --}}
            <div class="mb-4 flex items-center gap-3">
                <input type="checkbox" name="pinned" id="pinned" value="1"
                       class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                       @checked(old('pinned', $announcement->pinned))>
                <label for="pinned" class="text-sm font-medium text-white cursor-pointer">
                    📌 Pin this announcement to the top
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-4">
                <x-primary-button class="bg-blue-600 hover:bg-blue-700">💾 Save Changes</x-primary-button>
                <a href="{{ route('announcements.manage') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">↩ Cancel</a>
            </div>
        </x-glass-form>
    </div>
</x-app-layout>
