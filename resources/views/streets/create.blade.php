{{-- resources/views/streets/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark leading-tight">
            {{ __('Add New Street') }}
        </h2>
    </x-slot>

    <!-- Background matches dashboard theme -->
    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-dark p-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <x-glass-form method="POST" action="{{ route('streets.store') }}">
                @csrf
                <!-- form fields -->
                <!-- Street Name -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Street Name')" class="text-white" />
                    <x-text-input id="name" name="name" type="text"
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm" required :value="old('name')" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-300" />
                </div>

                <!-- Zone -->
                <div class="mb-4">
                    <x-input-label for="zone" :value="__('Zone (optional)')" class="text-white" />
                    <x-text-input id="zone" name="zone" type="text"
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm" :value="old('zone')" />
                    <x-input-error :messages="$errors->get('zone')" class="mt-2 text-red-300" />
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <x-input-label for="description" :value="__('Description (optional)')" class="text-white" />
                    <textarea id="description" name="description"
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-gray-900">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2 text-red-300" />
                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-4">
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 shadow-lg">
                        {{ __('💾 Save') }}
                    </x-primary-button>
                    <a href="{{ route('streets.index') }}"
                        class="inline-flex items-center px-4 bg-white/30 border border-white/50 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition" style="padding: 0.75rem">
                        ↩️ {{ __('Back') }}
                    </a>
                </div>
            </x-glass-form>

        </div>
    </div>
</x-app-layout>
