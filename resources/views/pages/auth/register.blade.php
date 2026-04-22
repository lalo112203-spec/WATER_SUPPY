<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <!-- Account Number -->
            <flux:input
                name="account_number"
                :label="__('Account Number')"
                :value="old('account_number')"
                type="text"
                required
                placeholder="Enter your account number"
            />

            <!-- Address -->
            <flux:input
                name="address"
                :label="__('Address')"
                :value="old('address')"
                type="text"
                required
                placeholder="Enter your address"
            />

            <!-- Registration Code (Only appears if override is needed) -->
            <div x-data="{ showCode: {{ $errors->has('registration_code') ? 'true' : 'false' }} }">
                <div x-show="showCode" x-transition class="p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-2xl mb-4">
                    <flux:input
                        name="registration_code"
                        :label="__('Registration Code')"
                        :value="old('registration_code')"
                        type="text"
                        maxlength="8"
                        placeholder="Enter 8-digit code"
                    />
                    <p class="mt-2 text-[11px] text-yellow-500/70 italic leading-snug">
                        An account already exists for this customer. Please provide a registration code from the **D.W.S.S. Office** to confirm your identity and override the existing account.
                    </p>
                </div>
            </div>

            <!-- Username -->
            <flux:input
                name="email"
                :label="__('Username')"
                :value="old('email')"
                type="text"
                required
                autocomplete="username"
                placeholder="Choose a username"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
