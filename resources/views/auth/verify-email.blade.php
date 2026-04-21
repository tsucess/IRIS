<x-guest-layout>
    {{-- Registration success banner --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-300 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Resend success message --}}
    @if (session('status') == 'verification-code-sent')
        <div class="mb-4 p-4 rounded-lg bg-blue-50 border border-blue-300 text-sm text-blue-800 font-medium">
            {{ __('A new verification code has been sent to your email address.') }}
        </div>
    @endif

    <div class="mb-5 text-sm text-gray-600">
        {{ __('We sent a 4-digit verification code to your email address. Please enter it below to confirm your account. The code expires in 15 minutes.') }}
    </div>

    {{-- Code submission form --}}
    <form method="POST" action="{{ route('verification.verify') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="code" :value="__('Verification Code')" />

            {{-- Four individual digit boxes that auto-advance --}}
            <div class="flex gap-3 mt-2" id="code-boxes">
                @foreach ([0,1,2,3] as $i)
                    <input
                        type="text"
                        inputmode="numeric"
                        maxlength="1"
                        pattern="[0-9]"
                        style="width: 3rem; height:3rem"
                        class="digit-box w-14 h-14 text-center text-2xl font-bold border border-gray-300 rounded-lg shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        data-index="{{ $i }}"
                        autocomplete="off"
                    />
                @endforeach
            </div>

            {{-- Hidden input that holds the combined 4-digit value --}}
            <input type="hidden" name="code" id="code-hidden" />

            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <x-primary-button id="verify-btn">
                {{ __('Verify Email') }}
            </x-primary-button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </form>

    {{-- Resend code --}}
    <div class="mt-4 text-sm text-gray-600 text-center">
        {{ __("Didn't receive a code?") }}
        <form method="POST" action="{{ route('verification.send') }}" class="inline">
            @csrf
            <button type="submit" class="underline text-indigo-600 hover:text-indigo-800 focus:outline-none">
                {{ __('Resend Code') }}
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const boxes = document.querySelectorAll('.digit-box');
            const hidden = document.getElementById('code-hidden');

            function syncHidden() {
                hidden.value = Array.from(boxes).map(b => b.value).join('');
            }

            boxes.forEach((box, idx) => {
                box.addEventListener('input', function () {
                    // Keep only digits
                    this.value = this.value.replace(/[^0-9]/g, '').slice(-1);
                    syncHidden();
                    if (this.value && idx < boxes.length - 1) {
                        boxes[idx + 1].focus();
                    }
                });

                box.addEventListener('keydown', function (e) {
                    if (e.key === 'Backspace' && !this.value && idx > 0) {
                        boxes[idx - 1].focus();
                    }
                });

                box.addEventListener('paste', function (e) {
                    e.preventDefault();
                    const pasted = (e.clipboardData || window.clipboardData)
                        .getData('text').replace(/[^0-9]/g, '').slice(0, 4);
                    pasted.split('').forEach((ch, i) => {
                        if (boxes[i]) boxes[i].value = ch;
                    });
                    syncHidden();
                    const next = Math.min(pasted.length, boxes.length - 1);
                    boxes[next].focus();
                });
            });

            // Pre-fill boxes if old input exists (on validation failure)
            const oldCode = '{{ old('code') }}';
            if (oldCode) {
                oldCode.split('').forEach((ch, i) => {
                    if (boxes[i]) boxes[i].value = ch;
                });
                syncHidden();
            }
        });
    </script>
</x-guest-layout>
