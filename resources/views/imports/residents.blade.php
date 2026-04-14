<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">📥 Import Residents</h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-white p-6">
        <div class="max-w-2xl mx-auto space-y-6">

            @if(session('success'))
                <div class="bg-green-500/30 border border-green-400 rounded-xl px-6 py-3">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-500/30 border border-red-400 rounded-xl px-4 py-3 text-sm">
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- Template Download --}}
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-6">
                <h3 class="text-lg font-bold mb-2">📋 Required CSV / Excel Format</h3>
                <p class="text-sm text-white/70 mb-3">Your file must contain a header row with the following columns:</p>
                <div class="bg-black/20 rounded-lg p-3 font-mono text-xs overflow-x-auto">
                    firstname | lastname | email | phone | street
                </div>
                <ul class="mt-3 text-sm text-white/70 list-disc list-inside space-y-1">
                    <li><strong>firstname</strong>, <strong>lastname</strong>, <strong>email</strong> are required per row.</li>
                    <li>Rows with existing emails are skipped automatically.</li>
                    <li>Passwords are auto-generated; users must use "Forgot Password" to set their own.</li>
                    <li>Maximum file size: 5 MB.</li>
                </ul>
            </div>

            {{-- Upload Form --}}
            <div class="backdrop-blur-lg bg-white/20 border border-white/30 rounded-xl p-8">
                <h3 class="text-lg font-bold mb-4">Upload File</h3>
                <form method="POST" action="{{ route('imports.residents.upload') }}"
                      enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-1">Select File (xlsx, xls, csv) *</label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                               class="w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white file:font-semibold">
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold">
                            Import Residents
                        </button>
                        <a href="{{ route('admin.users.index') }}"
                           class="px-6 py-2 bg-white/20 hover:bg-white/30 rounded-lg font-semibold">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
