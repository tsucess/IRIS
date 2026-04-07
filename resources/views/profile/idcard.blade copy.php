<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Community ID Card') }}
        </h2>
    </x-slot>

    <div class="py-12 flex justify-center">
        <div class="w-full max-w-md bg-white p-6 rounded-xl shadow-lg border border-gray-300 text-center relative">

            <h1 class="text-2xl font-bold mb-2">COMMUNITY DEVELOPMENT SYSTEM</h1>
            <p class="text-gray-600 mb-4">Official Resident Identity Card</p>
            <a href="{{ route('profile.idcard.pdf') }}"
                class="inline-block mt-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                🖨️ Download ID Card (PDF)
            </a>
           


            <div class="flex flex-col items-center gap-4">
                @if ($user->photo)
                    <img src="{{ asset('uploads/' . $user->photo) }}" class="h-32 w-32 rounded-full border"
                        alt="User Photo">
                @else
                    <div class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                        No Photo
                    </div>
                @endif

                <div class="text-left w-full">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    <p><strong>Street:</strong> {{ $user->street?->name ?? 'N/A' }}</p>
                    <p><strong>ID Number:</strong> <span
                            class="text-blue-600 font-semibold">{{ $user->id_number }}</span></p>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('profile.edit') }}" class="text-sm text-blue-500 hover:underline">Edit Profile</a>
            </div>
        </div>
    </div>
</x-app-layout>
