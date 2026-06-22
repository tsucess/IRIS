<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark leading-tight">{{ __('Add Occupation') }}</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-dark p-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-glass-form method="POST" action="{{ route('admin.occupations.store') }}">
                @csrf

                <div class="mb-4">
                    <x-input-label for="name" :value="__('Name')" class="text-white" />
                    <x-text-input id="name" name="name" type="text"
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
                        required :value="old('name')" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-300" />
                </div>

                <div class="mb-4">
                    <x-input-label for="category" :value="__('Category (e.g. Skilled, Professional)')" class="text-white" />
                    <x-text-input id="category" name="category" type="text"
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm" :value="old('category')" />
                    <x-input-error :messages="$errors->get('category')" class="mt-2 text-red-300" />
                </div>

                <div class="mb-4">
                    <x-input-label for="sector" :value="__('Sector (e.g. Agriculture, Education, Health)')" class="text-white" />
                    <x-text-input id="sector" name="sector" type="text"
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm" :value="old('sector')" />
                    <x-input-error :messages="$errors->get('sector')" class="mt-2 text-red-300" />
                </div>

                <div class="mb-4">
                    <x-input-label for="description" :value="__('Description')" class="text-white" />
                    <textarea id="description" name="description" rows="3"
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 text-gray-900">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2 text-red-300" />
                </div>

                <div class="mb-4 flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0" />
                    <input id="is_active" name="is_active" type="checkbox" value="1"
                        {{ old('is_active', '1') ? 'checked' : '' }} class="rounded" />
                    <label for="is_active" class="text-white">Active</label>
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 shadow-lg">{{ __('💾 Save') }}</x-primary-button>
                    <a href="{{ route('admin.occupations.index') }}"
                        class="inline-flex items-center px-4 bg-white/30 border border-white/50 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white/50 transition"
                        style="padding: 0.75rem">↩️ {{ __('Back') }}</a>
                </div>
            </x-glass-form>
        </div>
    </div>
</x-app-layout>
