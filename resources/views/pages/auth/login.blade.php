<x-layouts::auth :title="__('Log in')">
    <style>
        /* Override dark mode text contrast issues globally on this page */
        html.dark h1, html.dark h2, html.dark h3, html.dark label, html.dark p, html.dark span, html.dark [data-flux-label], html.dark [data-flux-heading], html.dark [data-flux-subheading], html.dark .text-zinc-600, html.dark .text-zinc-800 {
            color: #f3f4f6 !important;
        }
        html.dark input {
            color: #ffffff !important; 
        }
        html.dark button[type="submit"] {
            background-color: #000000 !important;
            color: #ffffff !important;
        }
        html.dark button[type="submit"] * {
            color: #ffffff !important;
        }
    </style>
    <div class="flex flex-col gap-6">
        <x-auth-header size="md" :title="__('Log in to your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Login Identifier -->
            <flux:input
                name="email"
                :label="__('Account Number')"
                :value="old('email')"
                type="text"
                required
                autofocus
                autocomplete="username"
                placeholder="1001"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    id="password"
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                />
                <button type="button" onclick="togglePassword()" id="togglePasswordBtn"
                    class="absolute right-3 p-1 text-cyan-400 hover:text-white hover:bg-cyan-500/20 rounded-md transition-all"
                    style="top: 28px;"
                    title="Show/Hide Password">
                    <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                    </svg>
                </button>

                @if (Route::has('password.request'))
                    <div class="flex justify-start mt-2">
                        <flux:link class="text-sm" :href="route('password.request')" wire:navigate>
                            {{ __('Forgot your password?') }}
                        </flux:link>
                    </div>
                @endif
            </div>

            <script>
                function togglePassword() {
                    const password = document.getElementById('password');
                    const eyeOpen = document.getElementById('eyeOpen');
                    const eyeClosed = document.getElementById('eyeClosed');
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

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link :href="route('register')" wire:navigate>{{ __('Register New Account') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>

