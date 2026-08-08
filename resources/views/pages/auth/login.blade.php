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
                    viewable
                />

                @if (Route::has('password.request'))
                    <div class="flex justify-start mt-2">
                        <flux:link class="text-sm" :href="route('password.request')" wire:navigate>
                            {{ __('Forgot your password?') }}
                        </flux:link>
                    </div>
                @endif
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

