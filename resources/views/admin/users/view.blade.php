<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->firstname }} {{ $user->lastname }} - Full Profile
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- User Photo --}}
                {{-- @if($user->photo) --}}
                    <div class="flex justify-end mb-6 p-2">
                        <img src="{{ $user->photo ? asset('uploads/' . $user->photo) : asset('images/avatar.png') }}" 
                        
                             alt="User Photo" 
                             class="w-60 h-60 rounded object-cover shadow-md border p-2">
                    </div>
                {{-- @endif --}}

                <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                <table class="table-auto w-full mb-6 border-collapse border border-gray-300">
                    <tbody>
                        <tr><th class="border px-4 py-2 text-left">First Name</th><td class="border px-4 py-2">{{ $user->firstname }}</td></tr>
                        <tr><th class="border px-4 py-2 text-left">Last Name</th><td class="border px-4 py-2">{{ $user->lastname }}</td></tr>
                        <tr><th class="border px-4 py-2 text-left">Email</th><td class="border px-4 py-2">{{ $user->email }}</td></tr>
                        <tr><th class="border px-4 py-2 text-left">Phone</th><td class="border px-4 py-2">{{ $user->phone }}</td></tr>
                        <tr><th class="border px-4 py-2 text-left">Role</th><td class="border px-4 py-2">{{ ucfirst($user->role) }}</td></tr>
                        <tr><th class="border px-4 py-2 text-left">ID Number</th><td class="border px-4 py-2">{{ $user->id_number }}</td></tr>
                        <tr><th class="border px-4 py-2 text-left">Street</th><td class="border px-4 py-2">{{ $user->street?->name ?? 'N/A' }}</td></tr>
                    </tbody>
                </table>

                @if($user->extended)
                    <h3 class="text-lg font-semibold mb-4">Extended Information</h3>
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <tbody>
                            <tr><th class="border px-4 py-2 text-left">Middle Name</th><td class="border px-4 py-2">{{ $user->extended->middle_name }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Gender</th><td class="border px-4 py-2">{{ $user->extended->gender }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Date of Birth</th><td class="border px-4 py-2">{{ $user->extended->date_of_birth }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Place of Birth</th><td class="border px-4 py-2">{{ $user->extended->place_of_birth }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Marital Status</th><td class="border px-4 py-2">{{ $user->extended->marital_status }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Number of Children</th><td class="border px-4 py-2">{{ $user->extended->number_of_children }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Ethnicity</th><td class="border px-4 py-2">{{ $user->extended->ethnicity }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Religion</th><td class="border px-4 py-2">{{ $user->extended->religion }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Address</th><td class="border px-4 py-2">{{ $user->extended->address }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">City</th><td class="border px-4 py-2">{{ $user->extended->city }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">State</th><td class="border px-4 py-2">{{ $user->extended->state }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Postal Code</th><td class="border px-4 py-2">{{ $user->extended->postal_code }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Education Level</th><td class="border px-4 py-2">{{ $user->extended->education_level }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Employment Status</th><td class="border px-4 py-2">{{ $user->extended->employment_status }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Occupation</th><td class="border px-4 py-2">{{ $user->extended->occupation }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Income Bracket</th><td class="border px-4 py-2">{{ $user->extended->income_bracket }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Blood Group</th><td class="border px-4 py-2">{{ $user->extended->blood_group }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Health Conditions</th><td class="border px-4 py-2">{{ $user->extended->health_conditions ? $user->extended->health_conditions : 'NIL' }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Disability</th><td class="border px-4 py-2">{{ $user->extended->has_disability ? 'Yes' : 'No' }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Disability Type</th><td class="border px-4 py-2">{{ $user->extended->disability_type ? $user->extended->disability_type : 'Not Applicable' }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Is Voter</th><td class="border px-4 py-2">{{ $user->extended->is_voter ? 'Yes' : 'No' }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Is Taxpayer</th><td class="border px-4 py-2">{{ $user->extended->is_taxpayer ? 'Yes' : 'No' }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Household Size</th><td class="border px-4 py-2">{{ $user->extended->household_size }}</td></tr>
                            <tr><th class="border px-4 py-2 text-left">Emergency Contact</th><td class="border px-4 py-2">{{ $user->extended->emergency_contact_name }} ({{ $user->extended->emergency_contact_relation }}) - {{ $user->extended->emergency_contact_phone }}</td></tr>
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-600 mt-4">No extended profile information available for this user.</p>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
