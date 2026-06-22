<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">Occupations</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl shadow-xl p-6">

                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <a href="{{ route('admin.occupations.create') }}"
                        class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                        ➕ Add Occupation
                    </a>

                    <form method="GET" action="{{ route('admin.occupations.index') }}"
                        class="flex flex-wrap items-end gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search name/category/sector"
                            class="rounded-md text-gray-900 px-3 py-2 text-sm w-56" />
                        <select name="category" class="rounded-md text-gray-900 px-2 py-2 text-sm">
                            <option value="">All Categories</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c }}" @selected(request('category') === $c)>{{ $c }}</option>
                            @endforeach
                        </select>
                        <select name="sector" class="rounded-md text-gray-900 px-2 py-2 text-sm">
                            <option value="">All Sectors</option>
                            @foreach ($sectors as $s)
                                <option value="{{ $s }}" @selected(request('sector') === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                        <button class="px-3 py-2 bg-indigo-600 rounded-md text-sm hover:bg-indigo-700">Filter</button>
                        <a href="{{ route('admin.occupations.index') }}" class="text-sm underline">Clear</a>
                    </form>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-300 text-red-800 p-3 rounded-lg mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse bg-white/10 backdrop-blur-md rounded-lg">
                        <thead class="bg-white/30 text-gray-800 uppercase text-sm font-bold">
                            <tr>
                                <th class="py-3 px-4">Name</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4">Sector</th>
                                <th class="py-3 px-4">Residents</th>
                                <th class="py-3 px-4">Active</th>
                                <th class="py-3 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($occupations as $occupation)
                                <tr class="border-b border-white/20 hover:bg-white/30 transition">
                                    <td class="py-2 px-4 font-medium">{{ $occupation->name }}</td>
                                    <td class="py-2 px-4">{{ $occupation->category ?? '—' }}</td>
                                    <td class="py-2 px-4">{{ $occupation->sector ?? '—' }}</td>
                                    <td class="py-2 px-4">{{ $occupation->residents_count }}</td>
                                    <td class="py-2 px-4">
                                        @if ($occupation->is_active)
                                            <span class="px-2 py-1 text-xs bg-green-500 rounded">Yes</span>
                                        @else
                                            <span class="px-2 py-1 text-xs bg-gray-500 rounded">No</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 space-x-2 whitespace-nowrap">
                                        <a href="{{ route('admin.occupations.edit', $occupation) }}"
                                            class="px-3 py-1 bg-yellow-400 rounded shadow hover:bg-yellow-500 transition">✏️</a>
                                        <form action="{{ route('admin.occupations.destroy', $occupation) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('Delete this occupation?')">
                                            @csrf @method('DELETE')
                                            <button class="px-3 py-1 bg-red-500 text-white rounded shadow hover:bg-red-600 transition">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-6 text-white/70">No occupations yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $occupations->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
