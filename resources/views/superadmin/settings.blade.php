<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">⚙️ System Settings — Superadmin</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-7xl mx-auto space-y-8">

            @if(session('success'))
                <div class="bg-green-500/30 border border-green-400 rounded-xl px-6 py-3">{{ session('success') }}</div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach([
                    ['Total Users', $stats['total_users']],
                    ['Admins', $stats['total_admins']],
                    ['Superadmins', $stats['total_superadmins']],
                    ['Verified', $stats['verified_users']],
                    ['Unverified', $stats['unverified_users']],
                    ['Soft Deleted', $stats['soft_deleted']],
                ] as [$label, $val])
                    <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-4 text-center">
                        <p class="text-2xl font-bold">{{ $val }}</p>
                        <p class="text-sm text-white/70">{{ $label }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Cache Control --}}
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-6">
                <h3 class="text-lg font-bold mb-3">🗑️ Cache Management</h3>
                <form method="POST" action="{{ route('superadmin.settings.clear-cache') }}">
                    @csrf
                    <button class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg font-semibold text-white">
                        Clear All Caches
                    </button>
                </form>
            </div>

            {{-- Admin Users Table --}}
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/20">
                    <h3 class="text-lg font-bold">👥 Admin & Superadmin Users</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-white/10">
                            <tr>
                                <th class="px-4 py-3 text-left">Name</th>
                                <th class="px-4 py-3 text-left">Email</th>
                                <th class="px-4 py-3 text-left">Role</th>
                                <th class="px-4 py-3 text-left">Street</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($adminUsers as $user)
                                <tr class="hover:bg-white/10 transition">
                                    <td class="px-4 py-3">{{ $user->full_name }}</td>
                                    <td class="px-4 py-3">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold
                                            {{ $user->role === 'superadmin' ? 'bg-purple-500' : 'bg-blue-500' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $user->street->name ?? '—' }}</td>
                                    <td class="px-4 py-3 flex gap-2">
                                        @if($user->role === 'admin')
                                            <form method="POST" action="{{ route('superadmin.settings.demote', $user) }}">
                                                @csrf @method('PATCH')
                                                <button class="text-xs px-3 py-1 bg-yellow-500 hover:bg-yellow-600 rounded-lg">
                                                    Demote
                                                </button>
                                            </form>
                                        @endif
                                        @if($user->role !== 'superadmin')
                                            <form method="POST" action="{{ route('superadmin.settings.promote', $user) }}">
                                                @csrf @method('PATCH')
                                                <button class="text-xs px-3 py-1 bg-green-500 hover:bg-green-600 rounded-lg">
                                                    Promote to Admin
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-6 text-center text-white/60">No admin users found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-3">{{ $adminUsers->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
