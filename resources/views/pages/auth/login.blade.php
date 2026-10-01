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
    <div class="flex flex-col gap-4">
        <x-auth-header size="md" :title="__('Log in to your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Login Identifier -->
            <flux:input
                name="email"
                :label="__('Username')"
                :value="old('email')"
                type="text"
                required
                autofocus
                autocomplete="username"
                placeholder="Username or Account Number"
            />

            <!-- Password -->
            <div x-data="{ show: false }">
                <label for="password" class="block text-sm font-medium text-zinc-800 dark:text-zinc-200 mb-1">{{ __('Password') }}</label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        :type="show ? 'text' : 'password'"
                        required
                        autocomplete="current-password"
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

