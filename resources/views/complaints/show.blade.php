<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">🔍 Complaint Details</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-3xl mx-auto space-y-6">

            @if(session('success'))
                <div class="bg-green-500/30 border border-green-400 rounded-xl px-6 py-3">{{ session('success') }}</div>
            @endif

            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-8 space-y-4">
                <div class="flex justify-between items-start">
                    <h3 class="text-xl font-bold">{{ $complaint->title }}</h3>
                    <span class="px-3 py-1 rounded-full text-sm font-bold bg-{{ $complaint->status_badge_color }}-500">
                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm text-white/80">
                    <div><span class="font-medium text-white">Category:</span> {{ ucfirst($complaint->category) }}</div>
                    <div><span class="font-medium text-white">Priority:</span> {{ ucfirst($complaint->priority) }}</div>
                    <div><span class="font-medium text-white">Submitted By:</span> {{ $complaint->user->full_name }}</div>
                    <div><span class="font-medium text-white">Submitted:</span> {{ $complaint->created_at->format('M d, Y H:i') }}</div>
                    @if($complaint->assignedAdmin)
                        <div><span class="font-medium text-white">Assigned To:</span> {{ $complaint->assignedAdmin->full_name }}</div>
                    @endif
                    @if($complaint->resolved_at)
                        <div><span class="font-medium text-white">Resolved At:</span> {{ $complaint->resolved_at->format('M d, Y H:i') }}</div>
                    @endif
                </div>

                <div>
                    <p class="font-medium text-white mb-1">Description:</p>
                    <p class="text-white/80 whitespace-pre-wrap">{{ $complaint->description }}</p>
                </div>

                @if($complaint->admin_notes)
                    <div class="border-t border-white/20 pt-4">
                        <p class="font-medium text-white mb-1">Admin Notes:</p>
                        <p class="text-white/80 whitespace-pre-wrap">{{ $complaint->admin_notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Admin Update Form --}}
            @if(auth()->user()->isAdmin())
                <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-8">
                    <h4 class="text-lg font-bold mb-4">Update Complaint</h4>
                    <form method="POST" action="{{ route('complaints.update', $complaint) }}" class="space-y-4">
                        @csrf @method('PUT')

                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <select name="status" class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white">
                                @foreach(['open','in_review','resolved','rejected'] as $s)
                                    <option value="{{ $s }}" @selected($complaint->status === $s)>
                                        {{ ucfirst(str_replace('_', ' ', $s)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Assign To</label>
                            <select name="assigned_to" class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white">
                                <option value="">-- Select Admin --</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" @selected($complaint->assigned_to == $admin->id)>
                                        {{ $admin->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Admin Notes</label>
                            <textarea name="admin_notes" rows="3"
                                      class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white">{{ $complaint->admin_notes }}</textarea>
                        </div>

                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold">
                            Update Complaint
                        </button>
                    </form>
                </div>
            @endif

            <a href="{{ route('complaints.index') }}" class="inline-block text-white/70 hover:text-white underline text-sm">
                ← Back to Complaints
            </a>
        </div>
    </div>
</x-app-layout>
