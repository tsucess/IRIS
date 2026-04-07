<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Street Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">{{ $street->name }}</h3>
                        <p class="text-gray-600">Zone: {{ $street->zone }}</p>
                        @if($street->description)
                            <p class="mt-2">{{ $street->description }}</p>
                        @endif
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('streets.index') }}" class="text-blue-500 hover:underline">
                            Back to Streets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

