<flux:dropdown position="bottom" align="start">
    @if(auth()->user()->profile_photo)
        <flux:sidebar.profile
            :name="auth()->user()->name"
            :avatar="asset('storage/' . auth()->user()->profile_photo)"
            icon:trailing="chevrons-up-down"
            data-test="sidebar-menu-button"
        />
    @else
        <flux:sidebar.profile
            :name="auth()->user()->name"
            :initials="auth()->user()->initials()"
            icon:trailing="chevrons-up-down"
            data-test="sidebar-menu-button"
        />
    @endif

    <flux:menu>
        <div class="flex items-center gap-4 px-3 py-4 text-start text-sm bg-cyan-500/5 rounded-2xl mb-2 border border-cyan-500/10 backdrop-blur-md">
            @if(Auth::user()->profile_photo)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="h-16 w-16 rounded-2xl object-cover border-2 border-cyan-500 shadow-lg shadow-cyan-500/20">
            @else
                <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-cyan-600 to-blue-700 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-cyan-500/10">
                    {{ Auth::user()->initials() }}
                </div>
            @endif
            <div class="grid flex-1 text-start text-sm leading-tight">
                <flux:heading size="lg" class="truncate font-extrabold text-cyan-400 drop-shadow-[0_0_8px_rgba(6,182,212,0.3)]">{{ auth()->user()->name }}</flux:heading>
                <flux:text class="truncate text-gray-200 font-medium">{{ auth()->user()->email }}</flux:text>
            </div>
        </div>
        <flux:menu.separator />
        <flux:menu.radio.group>
            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                {{ __('Settings') }}
            </flux:menu.item>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item
                    as="button"
                    type="submit"
                    icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer"
                    data-test="logout-button"
                >
                    {{ __('Log out') }}
                </flux:menu.item>
            </form>
        </flux:menu.radio.group>
    </flux:menu>
</flux:dropdown>
