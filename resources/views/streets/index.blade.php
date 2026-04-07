{{-- resources/views/streets/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">All Streets</h2>
    </x-slot>

    {{-- <div class="py-12 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 min-h-screen"> --}}
    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Glassmorphism Card -->
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6">

                <a href="{{ route('streets.create') }}"
                    class="mb-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                    ➕ Add New Street
                </a>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded-lg mb-4 shadow-inner">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse bg-white/10 backdrop-blur-md rounded-lg">
                        <thead class="bg-white/30 text-gray-800 uppercase text-sm font-bold">
                            <tr>
                                <th class="py-3 px-4">Name</th>
                                <th class="py-3 px-4">Zone</th>
                                <th class="py-3 px-4">Description</th>
                                <th class="py-3 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($streets as $street)
                                <tr class="border-b border-white/20 hover:bg-white/30 transition">
                                    <td class="py-2 px-4">{{ $street->name }}</td>
                                    <td class="py-2 px-4">{{ $street->zone }}</td>
                                    <td class="py-2 px-4">{{ $street->description }}</td>
                                    <td class="py-2 px-4 space-x-2">
                                        <a href="{{ route('streets.edit', $street) }}"
                                            class="px-3 py-1 bg-yellow-400 rounded shadow hover:bg-yellow-500 transition">✏️
                                        </a>
                                        <form action="{{ route('streets.destroy', $street) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Delete this street?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="px-3 py-1 bg-red-500 text-white rounded shadow hover:bg-red-600 transition">
                                                🗑️
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No streets yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $streets->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
