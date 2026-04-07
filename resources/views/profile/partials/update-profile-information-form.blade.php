<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <!-- Email verification form -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Profile update form -->
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')
        <div class="row">
            <div class="col-12 col-md-6">
                <!-- Name -->
                <div>
                    <x-input-label for="firstname" :value="__('First Name')" />
                    <x-text-input id="firstname" name="firstname" type="text" class="mt-1 block w-full"
                        :value="old('firstname', $user->firstname)" required autofocus autocomplete="firstname" />
                    <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
                </div>
                <div>
                    <x-input-label for="lastname" :value="__('Last Name')" />
                    <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full"
                        :value="old('lastname', $user->lastname)" required autofocus autocomplete="lastname" />
                    <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Phone -->
                <div>
                    <x-input-label for="phone" :value="__('Phone')" />
                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
                        value="{{ old('phone', $user->phone) }}" autocomplete="tel" />
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                </div>

                <!-- Street -->
                <div>
                    <x-input-label for="street_id" :value="__('Street')" />
                    <select name="street_id" id="street_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Select Street --</option>
                        @foreach ($streets as $street)
                            <option value="{{ $street->id }}" @selected($user->street_id == $street->id)>
                                {{ $street->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('street_id')" />
                </div>
            </div>
            <div class="col-12 col-md-6 mx-auto px-5">
                <div class="row mt-3">
                    <div class="col-12 col-6 mb-4">
                        <a href="{{ route('profile.idcard') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 me-2">
                            View ID Card
                        </a>
                        <a href="{{ route('profile.extended.edit') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
                            Update Resident Profile
                        </a>
                    </div>
                </div>
                <div class="card shadow p-5 py-4 text-center">
                    <!-- ID Number (read-only) -->
                    @if ($user->id_number)
                        <div class="mb-3">
                            <h6 class="fw-bold">ID Number</h6>
                            <p class="border rounded py-2 bg-secondary text-white">
                                {{ $user->id_number }}
                            </p>
                        </div>
                    @endif

                    <!-- Profile Photo -->
                    <div class="mx-auto">
                        <div class="mt-2 border rounded overflow-hidden mx-auto" style="width: 10rem; height:10rem;">
                            <img src="{{ $user->photo ? asset('uploads/' . $user->photo) : asset('images/avatar.png') }}"
                                alt="User Photo" class="img-fluid" style="width:100%; height:100%; object-fit: cover;">
                        </div>

                        <div class="mt-3">
                            <x-input-label for="photo" :value="__('Profile Photo')" class="fw-bold" />
                            <input type="file" id="photo" name="photo"
                                class="form-control mt-2 border-gray-300 rounded shadow-sm p-2">
                            <x-input-error class="mt-2 text-warning" :messages="$errors->get('photo')" />
                        </div>
                    </div>

                </div>
            </div>

        </div>


        <!-- Submit -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

{{-- <section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section> --}}
