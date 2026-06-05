<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist aria-label="{{ __('Settings') }}">
            @if(auth()->user()->role === 'consumer')
                <flux:navlist.item icon="home" class="!text-gray-200 hover:!text-white" :href="route('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
            @endif
            <flux:navlist.item class="!text-gray-200 hover:!text-white" :href="route('profile.edit')" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
            <flux:navlist.item class="!text-gray-200 hover:!text-white" :href="route('user-password.edit')" wire:navigate>{{ __('Password') }}</flux:navlist.item>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <flux:navlist.item class="!text-gray-200 hover:!text-white" :href="route('two-factor.show')" wire:navigate>{{ __('Two-factor auth') }}</flux:navlist.item>
            @endif
            <flux:navlist.item class="!text-gray-200 hover:!text-white" :href="route('appearance.edit')" wire:navigate>{{ __('Appearance') }}</flux:navlist.item>
            @if(auth()->user()->role === 'admin')
                <flux:navlist.item class="!text-gray-200 hover:!text-white" :href="route('settings.index')" wire:navigate>{{ __('System') }}</flux:navlist.item>
            @endif
            
            @if(auth()->user()->role === 'consumer')
                <div class="mt-4 pt-4 border-t border-[#263548]">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-red-500 hover:bg-red-500/10 rounded-lg transition-all w-full text-left">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            @endif
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading class="!text-white">{{ $heading ?? '' }}</flux:heading>

        <div class="mt-5 w-full max-w-lg">
            <style>
                /* Force all text on settings pages to be white with proper spacing */
                [data-flux-label], [data-flux-heading], label, h3 {
                    color: white !important;
                }
                [data-flux-label], label {
                    margin-bottom: 0.35rem !important;
                    display: inline-block;
                }
            </style>
            {{ $slot }}
        </div>
    </div>
</div>
