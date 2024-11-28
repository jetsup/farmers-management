<div>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <form method="POST" action="{{ route('become-farmer') }}">
            @csrf

            <div>
                <x-label for="phone" value="{{ __('Phone') }}" />
                <x-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required autofocus autocomplete="phone" />
            </div>

            <div class="mt-4">
                <x-label for="location" value="{{ __('Location') }}" />
                <x-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" required autocomplete="location" />
            </div>

            <div class="mt-4">
                <x-label for="payment_method" value="{{ __('Payment Method') }}" />
                <x-input id="payment_method" class="block mt-1 w-full" type="text" name="payment_method" :value="old('payment_method')" required autocomplete="payment_method" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Register as Farmer') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</div>
