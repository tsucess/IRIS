<x-guest-layout>
    {{-- Success / status messages --}}
    @if (session('status') == 'verification-code-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
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
