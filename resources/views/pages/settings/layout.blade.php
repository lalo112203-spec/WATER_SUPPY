<div class="flex items-start max-md:flex-col">
    <!-- Desktop Navigation -->
    <div class="hidden md:block me-10 w-[220px] pb-4">
        <flux:navlist aria-label="{{ __('Settings') }}">
            <flux:navlist.item icon="arrow-left" class="!text-gray-200 hover:!text-white font-bold mb-2" :href="route('dashboard')" wire:navigate>{{ __('Back') }}</flux:navlist.item>
            <flux:navlist.item class="!text-gray-200 hover:!text-white" :href="route('profile.edit')" wire:navigate :current="request()->routeIs('profile.edit')">{{ __('Profile') }}</flux:navlist.item>
            <flux:navlist.item class="!text-gray-200 hover:!text-white" :href="route('user-password.edit')" wire:navigate :current="request()->routeIs('user-password.edit')">{{ __('Password') }}</flux:navlist.item>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <flux:navlist.item class="!text-gray-200 hover:!text-white" :href="route('two-factor.show')" wire:navigate :current="request()->routeIs('two-factor.show')">{{ __('Two-factor auth') }}</flux:navlist.item>
            @endif
            <flux:navlist.item class="!text-gray-200 hover:!text-white" :href="route('appearance.edit')" wire:navigate :current="request()->routeIs('appearance.edit')">{{ __('Appearance') }}</flux:navlist.item>
            @if(auth()->user()->role === 'admin')
                <flux:navlist.item class="!text-gray-200 hover:!text-white" :href="route('settings.index')" wire:navigate :current="request()->routeIs('settings.index')">{{ __('System') }}</flux:navlist.item>
            @endif
        </flux:navlist>
    </div>

    <!-- Mobile Navigation -->
    <div class="md:hidden w-full pb-4 flex items-center gap-3">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center p-2.5 bg-white/5 border border-white/10 rounded-xl text-gray-200 hover:text-white hover:bg-white/10 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <select onchange="window.location.href=this.value" class="flex-1 bg-[#1b2636]/60 backdrop-blur-md border border-[#2d4059]/50 text-white rounded-xl px-4 py-2.5 text-sm font-semibold outline-none focus:ring-2 focus:ring-blue-500/50 appearance-none shadow-sm">
            <option value="{{ route('profile.edit') }}" {{ request()->routeIs('profile.edit') ? 'selected' : '' }}>Profile</option>
            <option value="{{ route('user-password.edit') }}" {{ request()->routeIs('user-password.edit') ? 'selected' : '' }}>Password</option>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <option value="{{ route('two-factor.show') }}" {{ request()->routeIs('two-factor.show') ? 'selected' : '' }}>Two-factor auth</option>
            @endif
            <option value="{{ route('appearance.edit') }}" {{ request()->routeIs('appearance.edit') ? 'selected' : '' }}>Appearance</option>
            @if(auth()->user()->role === 'admin')
                <option value="{{ route('settings.index') }}" {{ request()->routeIs('settings.index') ? 'selected' : '' }}>System Configuration</option>
            @endif
        </select>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading class="!text-white">{{ $heading ?? '' }}</flux:heading>

        <div class="mt-5 w-full {{ $maxWidth ?? 'max-w-lg' }}">
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
