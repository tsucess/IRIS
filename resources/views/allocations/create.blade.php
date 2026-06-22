<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-dark leading-tight">
            {{ __('Allocate Resource · ') . $project->title }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-dark p-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-glass-form method="POST" action="{{ route('projects.allocations.store', $project) }}">
                @csrf
                @include('allocations.partials.form', ['allocation' => null])

                <div class="flex items-center gap-4 mt-4">
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 shadow-lg">
                        {{ __('💾 Save Allocation') }}
                    </x-primary-button>
                    <a href="{{ route('projects.allocations.index', $project) }}"
                        class="inline-flex items-center px-4 bg-white/30 border border-white/50 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white/50 transition"
                        style="padding: 0.75rem">↩️ {{ __('Back') }}</a>
                </div>
            </x-glass-form>
        </div>
    </div>
</x-app-layout>
