<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-dark">Add New User</h2>
    </x-slot>

    <!-- Dashboard Gradient Background -->
    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-dark p-6">
        <x-glass-form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <!-- form fields -->

            <!-- First Name -->
            <div class="mb-3">
                <x-input-label for="firstname" :value="'First Name'" class="text-white" />
                <x-text-input id="firstname" name="firstname" type="text"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm" value="{{ old('firstname') }}" required />
                <x-input-error :messages="$errors->get('firstname')" class="text-red-300" />
            </div>
            <!-- Last Name -->
            <div class="mb-3">
                <x-input-label for="lastname" :value="'Last Name'" class="text-white" />
                <x-text-input id="lastname" name="lastname" type="text"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm" value="{{ old('lastname') }}" required />
                <x-input-error :messages="$errors->get('lastname')" class="text-red-300" />
            </div>

            <!-- Email -->
            <div class="mb-3">
                <x-input-label for="email" :value="'Email'" class="text-white" />
                <x-text-input id="email" name="email" type="email"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm" value="{{ old('email') }}" required />
                <x-input-error :messages="$errors->get('email')" class="text-red-300" />
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <x-input-label for="phone" :value="'Phone'" class="text-white" />
                <x-text-input id="phone" name="phone" type="text"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm" value="{{ old('phone') }}" />
            </div>

            <!-- Street -->
            <div class="mb-3">
                <x-input-label for="street_id" :value="'Street'" class="text-white" />
                <select name="street_id" id="street_id"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Select Street --</option>
                    @foreach ($streets as $street)
                        <option value="{{ $street->id }}" @selected(old('street_id') == $street->id)>{{ $street->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Role -->
            <div class="mb-3">
                <x-input-label for="role" :value="'Role'" class="text-white" />
                <select name="role" id="role"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                    @foreach (['user', 'author', 'admin', 'superadmin'] as $role)
                        <option value="{{ $role }}" @selected(old('role') == $role)>{{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <x-input-label for="password" :value="'Password'" class="text-white" />
                <div class="relative flex items-center gap-2">
                    <x-text-input id="password" name="password" type="text"
                        class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
                        value="{{ old('password', $autoPassword ?? '') }}" />

                    <!-- Copy Button -->
                    <button type="button" onclick="copyPassword()" title="Copy"
                        class="px-3 py-1 bg-white/30 rounded hover:bg-white/50 transition">
                        📋
                    </button>

                    <!-- Generate Button -->
                    <button type="button" onclick="generatePassword()" title="Generate New"
                        class="px-3 py-1 bg-white/30 rounded hover:bg-white/50 transition">
                        🔁
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="text-red-300" />
                <p class="text-sm text-gray-200 mt-1">
                    Leave as-is, or generate a new secure password.
                </p>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-4">
                <x-primary-button class="bg-blue-600 hover:bg-blue-700">💾 Create User</x-primary-button>
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">↩ Back</a>
            </div>
        </x-glass-form>
    </div>

    <script>
        function copyPassword() {
            const input = document.getElementById('password');
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand('copy');
            alert('Password copied!');
        }

        function generatePassword(length = 10) {
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            let password = "";
            for (let i = 0; i < length; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            document.getElementById('password').value = password;
        }
    </script>
</x-app-layout>
