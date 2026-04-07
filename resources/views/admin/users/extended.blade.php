<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ isset($user) ? "Extended Profile form {$user->name}" : 'My Extended Profile' }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <form method="POST"
                action="{{ isset($user) ? route('admin.users.extended.update', $user->id) : route('profile.extended.update') }}">
                @csrf

                <div class="row">
                    <div class="col-12">
                        <h2 class="fw-bolder">Bio Data</h2>
                    </div>
                </div>
                <div class="row">
                    <!-- Middle Name -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="middle_name" value="Middle Name" />
                        <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full"
                            value="{{ old('middle_name', $resident->middle_name ?? '') }}" />
                    </div>

                    <!-- Gender -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="gender" value="Gender" />
                        <select name="gender" id="gender" class="block mt-1 w-full border-gray-300 rounded-md">
                            <option value="">-- Select --</option>
                            @foreach (['male', 'female', 'other'] as $option)
                                <option value="{{ $option }}"
                                    {{ old('gender', $resident->gender ?? '') == $option ? 'selected' : '' }}>
                                    {{ ucfirst($option) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date of Birth -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="date_of_birth" value="Date of Birth" />
                        <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full"
                            value="{{ old('date_of_birth', $resident->date_of_birth ?? '') }}" />
                    </div>

                    <!-- Place of Birth -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="place_of_birth" value="Place of Birth" />
                        <x-text-input id="place_of_birth" name="place_of_birth" type="text" class="mt-1 block w-full"
                            value="{{ old('place_of_birth', $resident->place_of_birth ?? '') }}" />
                    </div>

                    <!-- Marital Status -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="marital_status" value="Marital Status" />
                        <select name="marital_status" id="marital_status"
                            class="block mt-1 w-full border-gray-300 rounded-md">
                            <option value="">-- Select --</option>
                            @foreach (['single', 'married', 'divorced', 'widowed'] as $option)
                                <option value="{{ $option }}"
                                    {{ old('marital_status', $resident->marital_status ?? '') == $option ? 'selected' : '' }}>
                                    {{ ucfirst($option) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Number of Children -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="number_of_children" value="Number of Children" />
                        <x-text-input id="number_of_children" name="number_of_children" type="number"
                            class="mt-1 block w-full"
                            value="{{ old('number_of_children', $resident->number_of_children ?? '') }}" />
                    </div>

                    {{-- <!-- Ethnicity -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="ethnicity" value="Ethnicity" />
                        <x-text-input id="ethnicity" name="ethnicity" type="text" class="mt-1 block w-full"
                            value="{{ old('ethnicity', $resident->ethnicity ?? '') }}" />
                    </div> --}}

                    <!-- Ethnicity -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="ethnicity" value="Ethnicity" />
                        <select id="ethnicity" name="ethnicity"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Select Ethnic Group --</option>
                            <option value="Hausa"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Hausa' ? 'selected' : '' }}>
                                Hausa</option>
                            <option value="Yoruba"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Yoruba' ? 'selected' : '' }}>
                                Yoruba</option>
                            <option value="Igbo"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Igbo' ? 'selected' : '' }}>
                                Igbo</option>
                            <option value="Fulani"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Fulani' ? 'selected' : '' }}>
                                Fulani</option>
                            <option value="Ijaw"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Ijaw' ? 'selected' : '' }}>
                                Ijaw</option>
                            <option value="Kanuri"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Kanuri' ? 'selected' : '' }}>
                                Kanuri</option>
                            <option value="Ibibio"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Ibibio' ? 'selected' : '' }}>
                                Ibibio</option>
                            <option value="Tiv"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Tiv' ? 'selected' : '' }}>
                                Tiv</option>
                            <option value="Nupe"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Nupe' ? 'selected' : '' }}>
                                Nupe</option>
                            <option value="Gwari"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Gwari' ? 'selected' : '' }}>
                                Gwari</option>
                            <option value="Idoma"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Idoma' ? 'selected' : '' }}>
                                Idoma</option>
                            <option value="Itsekiri"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Itsekiri' ? 'selected' : '' }}>
                                Itsekiri</option>
                            <option value="Urhobo"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Urhobo' ? 'selected' : '' }}>
                                Urhobo</option>
                            <option value="Efik"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Efik' ? 'selected' : '' }}>
                                Efik</option>
                            <option value="Berom"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Berom' ? 'selected' : '' }}>
                                Berom</option>
                            <option value="Jukun"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Jukun' ? 'selected' : '' }}>
                                Jukun</option>
                            <option value="Anang"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Anang' ? 'selected' : '' }}>
                                Anang</option>
                            <option value="Others"
                                {{ old('ethnicity', $resident->ethnicity ?? '') == 'Others' ? 'selected' : '' }}>
                                Others</option>
                        </select>
                    </div>




                    <!-- Religion -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="religion" :value="__('Religion')" />
                        <select name="religion" id="religion"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Select Religion --</option>
                            <option value="Islam"
                                {{ old('religion', $resident->religion ?? '') == 'Islam' ? 'selected' : '' }}>Islam
                            </option>
                            <option value="Christian"
                                {{ old('religion', $resident->religion ?? '') == 'Christian' ? 'selected' : '' }}>
                                Christian</option>
                            <option value="Traditional"
                                {{ old('religion', $resident->religion ?? '') == 'Traditional' ? 'selected' : '' }}>
                                Traditional</option>
                        </select>
                    </div>

                    <!-- Indigene -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="indigene" :value="__('Indigene')" />
                        <select name="indigene" id="indigene"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Are you an indigene? --</option>
                            <option value="1"
                                {{ old('indigene', $resident->indigene ?? '') == '1' ? 'selected' : '' }}>Yes
                            </option>
                            <option value="0"
                                {{ old('indigene', $resident->indigene ?? '') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <h2 class="fw-bolder"> Contact & Address</h2>
                    </div>
                </div>
                <div class="row">
                    <!-- Address -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="address" value="Address" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                            value="{{ old('address', $resident->address ?? '') }}" />
                    </div>

                    <!-- City -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="city" value="City" />
                        <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                            value="{{ old('city', $resident->city ?? '') }}" />
                    </div>

                    <!-- State -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="state" value="State" />
                        <x-text-input id="state" name="state" type="text" class="mt-1 block w-full"
                            value="{{ old('state', $resident->state ?? '') }}" />
                    </div>

                    <div class="col-md-3 mt-2">
                        <x-input-label for="country" value="Nationality" />
                        <select name="country" id="country" class="form-control mt-1">
                            <option value="">-- Select Country --</option>
                            @foreach (config('countries') as $country)
                                <option value="{{ $country }}"
                                    {{ old('country', $resident->country ?? '') == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <!-- Postal Code -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="postal_code" value="Postal Code" />
                        <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full"
                            value="{{ old('postal_code', $resident->postal_code ?? '') }}" />
                    </div>

                    <!-- Phone Number -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="phone_number" value="Phone Number" />
                        <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full"
                            value="{{ old('phone_number', $resident->phone_number ?? '') }}" />
                    </div>

                    <!-- Email -->
                    <div class="col-md-4 mt-2">
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                            value="{{ old('email', $resident->email ?? '') }}" />
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <h2 class="fw-bolder"> Education & Employment</h2>
                    </div>
                </div>
                <div class="row">
                    <!-- Education Level -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="education_level" value="Education Level" />
                        <select name="education_level" id="education_level"
                            class="block mt-1 w-full border-gray-300 rounded-md">
                            <option value="">-- Select --</option>
                            @foreach (['none', 'primary', 'secondary', 'tertiary', 'vocational'] as $option)
                                <option value="{{ $option }}"
                                    {{ old('education_level', $resident->education_level ?? '') == $option ? 'selected' : '' }}>
                                    {{ ucfirst($option) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Employment Status -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="employment_status" value="Employment Status" />
                        <select name="employment_status" id="employment_status"
                            class="block mt-1 w-full border-gray-300 rounded-md">
                            <option value="">-- Select --</option>
                            @foreach (['employed', 'unemployed', 'self-employed', 'retired'] as $option)
                                <option value="{{ $option }}"
                                    {{ old('employment_status', $resident->employment_status ?? '') == $option ? 'selected' : '' }}>
                                    {{ ucfirst($option) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Occupation -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="occupation" value="Occupation" />
                        <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full"
                            value="{{ old('occupation', $resident->occupation ?? '') }}" />
                    </div>

                    <!-- Income Bracket -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="income_bracket" value="Income Bracket" />
                        <select name="income_bracket" id="income_bracket"
                            class="block mt-1 w-full border-gray-300 rounded-md">
                            <option value="">-- Select --</option>
                            @foreach (['low', 'middle', 'high'] as $option)
                                <option value="{{ $option }}"
                                    {{ old('income_bracket', $resident->income_bracket ?? '') == $option ? 'selected' : '' }}>
                                    {{ ucfirst($option) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <h2 class="fw-bolder"> Health Information</h2>
                    </div>
                </div>
                <div class="row">
                    {{-- Has Disability --}}
                    <div class="col-md-3 mt-2">
                        <x-input-label for="has_disability" :value="__('Has Disability?')" />
                        <select name="has_disability" id="has_disability" class="block mt-1 w-full">
                            <option value="1"
                                {{ old('has_disability', $resident->has_disability ?? '') == 1 ? 'selected' : '' }}>Yes
                            </option>
                            <option value="0"
                                {{ old('has_disability', $resident->has_disability ?? '') == 0 ? 'selected' : '' }}>No
                            </option>
                        </select>
                        <x-input-error :messages="$errors->get('has_disability')" class="mt-2" />
                    </div>

                    <!-- Disability Type -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="disability_type" value="Disability Type" />
                        <x-text-input id="disability_type" name="disability_type" type="text"
                            class="mt-1 block w-full"
                            value="{{ old('disability_type', $resident->disability_type ?? '') }}" />
                    </div>

                    <!-- Blood Group -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="blood_group" value="Blood Group" />
                        <x-text-input id="blood_group" name="blood_group" type="text" class="mt-1 block w-full"
                            value="{{ old('blood_group', $resident->blood_group ?? '') }}" />
                    </div>

                    <!-- Health Conditions -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="health_conditions" value="Health Conditions" />
                        <x-text-input id="health_conditions" name="health_conditions" type="text"
                            class="mt-1 block w-full"
                            value="{{ old('health_conditions', $resident->health_conditions ?? '') }}" />
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <h2 class="fw-bolder"> Community & Civic</h2>
                    </div>
                </div>
                <div class="row">
                    <!-- Voter (hidden default 1 as in your original) -->
                    <input type="hidden" id="is_voter" name="is_voter" value="1" />

                    <!-- Taxpayer (hidden default 1) -->
                    <input type="hidden" id="is_taxpayer" name="is_taxpayer" value="1" />

                    <!-- Access to Electricity -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="access_to_electricity" :value="__('Access to Electricity')" />
                        <select name="access_to_electricity" id="access_to_electricity" class="block mt-1 w-full">
                            <option value="1"
                                {{ old('access_to_electricity', $resident->access_to_electricity ?? '') == 1 ? 'selected' : '' }}>
                                Yes</option>
                            <option value="0"
                                {{ old('access_to_electricity', $resident->access_to_electricity ?? '') == 0 ? 'selected' : '' }}>
                                No</option>
                        </select>
                        <x-input-error :messages="$errors->get('access_to_electricity')" class="mt-2" />
                    </div>

                    <!-- Access to Clean Water -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="access_to_clean_water" :value="__('Access to Clean Water')" />
                        <select name="access_to_clean_water" id="access_to_clean_water" class="block mt-1 w-full">
                            <option value="1"
                                {{ old('access_to_clean_water', $resident->access_to_clean_water ?? '') == 1 ? 'selected' : '' }}>
                                Yes</option>
                            <option value="0"
                                {{ old('access_to_clean_water', $resident->access_to_clean_water ?? '') == 0 ? 'selected' : '' }}>
                                No</option>
                        </select>
                        <x-input-error :messages="$errors->get('access_to_clean_water')" class="mt-2" />
                    </div>

                    <!-- Access to Sanitation -->
                    <div class="col-md-2 mt-2">
                        <x-input-label for="access_to_sanitation" :value="__('Access to Sanitation')" />
                        <select name="access_to_sanitation" id="access_to_sanitation" class="block mt-1 w-full">
                            <option value="1"
                                {{ old('access_to_sanitation', $resident->access_to_sanitation ?? '') == 1 ? 'selected' : '' }}>
                                Yes</option>
                            <option value="0"
                                {{ old('access_to_sanitation', $resident->access_to_sanitation ?? '') == 0 ? 'selected' : '' }}>
                                No</option>
                        </select>
                        <x-input-error :messages="$errors->get('access_to_sanitation')" class="mt-2" />
                    </div>

                    <!-- Internet Access (hidden default 1 as per your original) -->
                    <input type="hidden" id="internet_access" name="internet_access" value="1" />

                    <!-- Civic Participation -->
                    <div class="col-md-2 mt-2">
                        <x-input-label for="civic_participation" :value="__('Civic Participation')" />
                        <select name="civic_participation" id="civic_participation" class="block mt-1 w-full">
                            <option value="1"
                                {{ old('civic_participation', $resident->civic_participation ?? '') == 1 ? 'selected' : '' }}>
                                Yes</option>
                            <option value="0"
                                {{ old('civic_participation', $resident->civic_participation ?? '') == 0 ? 'selected' : '' }}>
                                No</option>
                        </select>
                        <x-input-error :messages="$errors->get('civic_participation')" class="mt-2" />
                    </div>

                    <!-- Volunteer Activities -->
                    <div class="col-md-2 mt-2">
                        <x-input-label for="volunteer_activities" :value="__('Volunteer Activities')" />
                        <select name="volunteer_activities" id="volunteer_activities" class="block mt-1 w-full">
                            <option value="1"
                                {{ old('volunteer_activities', $resident->volunteer_activities ?? '') == 1 ? 'selected' : '' }}>
                                Yes</option>
                            <option value="0"
                                {{ old('volunteer_activities', $resident->volunteer_activities ?? '') == 0 ? 'selected' : '' }}>
                                No</option>
                        </select>
                        <x-input-error :messages="$errors->get('volunteer_activities')" class="mt-2" />
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <h2 class="fw-bolder"> Emergency</h2>
                    </div>
                </div>
                <div class="row">
                    <!-- Emergency Contact Name -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="emergency_contact_name" value="Emergency Contact Name" />
                        <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text"
                            class="mt-1 block w-full"
                            value="{{ old('emergency_contact_name', $resident->emergency_contact_name ?? '') }}" />
                    </div>

                    <!-- Emergency Contact Phone -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="emergency_contact_phone" value="Emergency Contact Phone" />
                        <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="text"
                            class="mt-1 block w-full"
                            value="{{ old('emergency_contact_phone', $resident->emergency_contact_phone ?? '') }}" />
                    </div>

                    <!-- Emergency Contact Relation -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="emergency_contact_relation" value="Emergency Contact Relation" />
                        <x-text-input id="emergency_contact_relation" name="emergency_contact_relation"
                            type="text" class="mt-1 block w-full"
                            value="{{ old('emergency_contact_relation', $resident->emergency_contact_relation ?? '') }}" />
                    </div>

                    <!-- Household Size -->
                    <div class="col-md-3 mt-2">
                        <x-input-label for="household_size" value="Household Size" />
                        <x-text-input id="household_size" name="household_size" type="number"
                            class="mt-1 block w-full"
                            value="{{ old('household_size', $resident->household_size ?? '') }}" />
                    </div>
                </div>

                <!-- Date of Death (kept hidden as in your code) -->
                <x-text-input id="date_of_death" name="date_of_death" type="hidden" class="mt-1 block w-full"
                    value="{{ old('date_of_death', $resident->date_of_death ?? '') }}" />

                <!-- Submit -->
                <div class="mt-5 text-center">
                    <x-primary-button class="w-50 justify-center">
                        Save
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
