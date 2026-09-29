<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-4">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4" x-data="{ account_number: '{{ old('account_number') }}' }">
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
                x-model="account_number"
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

            <!-- Registration Code -->
            <div x-data="{ showCode: {{ $errors->has('registration_code') || $errors->has('account_number') || old('registration_code') ? 'true' : 'false' }} }">
                <div class="mb-2 text-right">
                    <button type="button" @click="showCode = !showCode" class="text-xs text-blue-500 hover:text-blue-400 font-medium underline focus:outline-none">
                        <span x-show="!showCode">+ Have a Registration Code?</span>
                        <span x-show="showCode">- Hide Registration Code Field</span>
                    </button>
                </div>
                <div x-show="showCode" x-transition class="p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-2xl mb-4">
                    <flux:input
                        name="registration_code"
                        :label="__('Registration Code')"
                        :value="old('registration_code')"
                        type="text"
                        maxlength="8"
                        placeholder="Enter 8-digit code"
                    />
                    <p class="mt-2 text-[11px] text-yellow-500/80 italic leading-snug">
                        Provide an 8-digit registration code from the **D.W.S.S. Office** if you are overriding an existing account or verifying identity.
                    </p>
                </div>
            </div>

            {{-- Email/Username hidden as requested. Auto-populates from account_number. --}}
            <input type="hidden" name="email" :value="account_number" />

            <!-- Password -->
            <div x-data="{ show: false }">
                <label for="password" class="block text-sm font-medium text-zinc-800 dark:text-zinc-200 mb-1">{{ __('Password') }}</label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        :type="show ? 'text' : 'password'"
                        required
                        autocomplete="new-password"
                        placeholder="{{ __('Password') }}"
                        class="w-full bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg pl-3 pr-10 py-2.5 text-zinc-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                    />
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-200 transition-colors focus:outline-none">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 0110.665 4.937c-1.274 4.057-5.064 7-9.542 7-1.07 0-2.1-.17-3.064-.486m-2.868-2.868A8.966 8.966 0 013 12c.5-1.278 1.258-2.42 2.215-3.375" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div x-data="{ show: false }">
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-800 dark:text-zinc-200 mb-1">{{ __('Confirm password') }}</label>
                <div class="relative">
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        :type="show ? 'text' : 'password'"
                        required
                        autocomplete="new-password"
                        placeholder="{{ __('Confirm password') }}"
                        class="w-full bg-white dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 rounded-lg pl-3 pr-10 py-2.5 text-zinc-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                    />
                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-200 transition-colors focus:outline-none">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 0110.665 4.937c-1.274 4.057-5.064 7-9.542 7-1.07 0-2.1-.17-3.064-.486m-2.868-2.868A8.966 8.966 0 013 12c.5-1.278 1.258-2.42 2.215-3.375" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

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
