<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-6" x-data="{ account_number: '{{ old('account_number') }}' }">
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

            <!-- Registration Code (Only appears if override is needed or duplicate detected) -->
            <div x-data="{ showCode: {{ $errors->has('registration_code') || $errors->has('account_number') ? 'true' : 'false' }} }">
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

            {{-- Email/Username hidden as requested. Auto-populates from account_number. --}}
            <input type="hidden" name="email" :value="account_number" />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    id="password"
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Password')"
                />
                <button type="button" onclick="togglePassword('password')" class="absolute right-3 p-1 text-cyan-400 hover:text-white hover:bg-cyan-500/20 rounded-md transition-all" style="top: 28px;" id="toggle_password" title="Show/Hide Password">
                    <svg id="eyeOpen_password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg id="eyeClosed_password" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/></svg>
                </button>
            </div>

            <!-- Confirm Password -->
            <div class="relative">
                <flux:input
                    id="password_confirmation"
                    name="password_confirmation"
                    :label="__('Confirm password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Confirm password')"
                />
                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 p-1 text-cyan-400 hover:text-white hover:bg-cyan-500/20 rounded-md transition-all" style="top: 28px;" id="toggle_password_confirmation" title="Show/Hide Password">
                    <svg id="eyeOpen_password_confirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg id="eyeClosed_password_confirmation" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/></svg>
                </button>
            </div>

            <script>
                function togglePassword(id) {
                    const password = document.getElementById(id);
                    const eyeOpen = document.getElementById('eyeOpen_' + id);
                    const eyeClosed = document.getElementById('eyeClosed_' + id);
                    if (password.type === 'password') {
                        password.type = 'text';
                        eyeOpen.classList.add('hidden');
                        eyeClosed.classList.remove('hidden');
                    } else {
                        password.type = 'password';
                        eyeOpen.classList.remove('hidden');
                        eyeClosed.classList.add('hidden');
                    }
                }
            </script>

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
