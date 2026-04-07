{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Manage Users</h2>
    </x-slot>

    <div class="p-6">
        @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Role</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b">
                        <td class="p-2">{{ $user->name }}</td>
                        <td class="p-2">{{ $user->email }}</td>
                        <td class="p-2">{{ ucfirst($user->role) }}</td>
                        <td class="p-2 space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout> --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-dark leading-tight">Manage Users</h2>
    </x-slot>

    <!-- Dashboard-like background -->
    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Add User Button -->
            <div class="mb-4">
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 transition">
                    + Add New User
                </a>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 bg-green-500/20 text-green-200 px-4 py-2 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Glassmorphism Table -->
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead class="bg-white/10">
                            <tr>
                                <th class="px-4 py-3 text-left">Name</th>
                                <th class="px-4 py-3 text-left">Email</th>
                                <th class="px-4 py-3 text-left">Phone</th>
                                <th class="px-4 py-3 text-left">Street</th>
                                <th class="px-4 py-3 text-left">Role</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach ($users as $user)
                                <tr class="hover:bg-white/10 transition">
                                    <td class="px-4 py-2">{{ $user->firstname }} {{ $user->lastname }}</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>
                                    <td class="px-4 py-2">{{ $user->phone }}</td>
                                    <td class="px-4 py-2">{{ $user->street?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                                    <td class="py-2 px-4 space-x-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="px-3 py-1 bg-yellow-400 rounded shadow hover:bg-yellow-500 transition">✏️</a>

                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            class="inline-block" onsubmit="return confirm('Delete user?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-500 text-white rounded shadow hover:bg-red-600 transition">
                                                🗑️
                                            </button>
                                        </form>

                                        {{-- ID Card Preview --}}
                                        <a href="{{ route('admin.users.idcard', $user->id) }}"
                                            class="px-3 py-1 bg-green-400 rounded shadow hover:bg-green-500 transition">💳</a>

                                        {{-- ID Card Download --}}
                                        <a href="{{ route('admin.users.idcard.download', $user->id) }}"
                                            class="px-3 py-1 bg-sky-200 text-sky-800 rounded shadow hover:bg-sky-300 transition">⬇️</a>

                                        {{-- Extended Profile --}}
                                        <a href="{{ route('admin.users.extended.edit', $user->id) }}"
                                            class="px-3 py-1 bg-purple-400 text-white rounded shadow hover:bg-purple-500 transition">👤</a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $users->links() }}
            </div>

        </div>



    </div>
</x-app-layout>
