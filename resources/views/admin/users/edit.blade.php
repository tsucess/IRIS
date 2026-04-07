<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-dark">Edit User</h2>
    </x-slot>

    <!-- Dashboard Gradient Background -->
    <div class="min-h-screen bg-gradient-to-br from-blue-900/60 via-indigo-900/40 to-purple-800/60 text-dark p-6">


        <x-glass-form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            <!-- form fields -->
            <!-- Name -->
            <div class="mb-3">
                <x-input-label for="firstname" :value="'First Name'" class="text-white" />
                <x-text-input id="firstname" name="firstname" type="text"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
                    value="{{ old('firstname', $user->firstname) }}" />
                <x-input-error :messages="$errors->get('firstname')" class="text-red-300" />
            </div>
            <div class="mb-3">
                <x-input-label for="lastname" :value="'Last Name'" class="text-white" />
                <x-text-input id="lastname" name="lastname" type="text"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
                    value="{{ old('lastname', $user->lastname) }}" />
                <x-input-error :messages="$errors->get('lastname')" class="text-red-300" />
            </div>

            <!-- Email -->
            <div class="mb-3">
                <x-input-label for="email" :value="'Email'" class="text-white" />
                <x-text-input id="email" name="email" type="email"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
                    value="{{ old('email', $user->email) }}" />
                <x-input-error :messages="$errors->get('email')" class="text-red-300" />
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <x-input-label for="phone" :value="'Phone'" class="text-white" />
                <x-text-input id="phone" name="phone" type="text"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm"
                    value="{{ old('phone', $user->phone) }}" />
            </div>

            <!-- Street -->
            <div class="mb-3">
                <x-input-label for="street_id" :value="'Street'" class="text-white" />
                <select id="street_id" name="street_id"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Select Street --</option>
                    @foreach ($streets as $street)
                        <option value="{{ $street->id }}" @selected($user->street_id == $street->id)>
                            {{ $street->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Role -->
            <div class="mb-3">
                <x-input-label for="role" :value="'Role'" class="text-white" />
                <select id="role" name="role"
                    class="mt-1 block w-full text-gray-900 rounded-md shadow-sm border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach (['user', 'author', 'admin', 'superadmin'] as $role)
                        <option value="{{ $role }}" @selected($user->role === $role)>{{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4">
                <x-primary-button class="bg-blue-600 hover:bg-blue-700 py-3">Save Changes</x-primary-button>
                <a href="{{ URL::previous() }}"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                    ↩ Back
                </a>
                {{-- <a href="{{ URL::previous() }}" class="btn btn-secondary p-1 px-5 shadow">Back</a> --}}
            </div>
        </x-glass-form>

    </div>
</x-app-layout>




{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Edit User</h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <x-input-label for="name" :value="'Name'" />
                <x-text-input id="name" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full" required />
            </div>

            <div class="mb-3">
                <x-input-label for="email" :value="'Email'" />
                <x-text-input id="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full" required />
            </div>

            <div class="mb-3">
                <x-input-label for="role" :value="'Role'" />
                <select name="role" id="role" class="block mt-1 w-full">
                    <option value="user" @selected($user->role === 'user')>User</option>
                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                </select>
            </div>

            <x-primary-button>Save</x-primary-button>
        </form>
    </div>
</x-app-layout> --}}
