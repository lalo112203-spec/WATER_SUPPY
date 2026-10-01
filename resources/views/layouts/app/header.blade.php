<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#121a25]/80 backdrop-blur-md dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

            <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:tooltip :content="__('Search')" position="bottom">
                    <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
                </flux:tooltip>
                <flux:tooltip :content="__('Repository')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="folder-git-2"
                        href="https://github.com/laravel/livewire-starter-kit"
                        target="_blank"
                        :label="__('Repository')"
                    />
                </flux:tooltip>
                <flux:tooltip :content="__('Documentation')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="book-open-text"
                        href="https://laravel.com/docs/starter-kits#livewire"
                        target="_blank"
                        :label="__('Documentation')"
                    />
                </flux:tooltip>
            </flux:navbar>

            <x-desktop-user-menu />
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header class="relative !flex !flex-col !items-center !h-auto !min-h-0 pt-4 pb-3 px-2">
                <div class="absolute right-2 top-2 z-10">
                    <flux:sidebar.collapse class="text-gray-400 hover:text-white" />
                </div>

                <a href="{{ route('dashboard') }}" wire:navigate class="flex flex-col items-center group w-full text-center dwss-logo-container transition-all duration-300">
                    <div class="flex aspect-square size-16 items-center justify-center rounded-full bg-white/10 p-1 shadow-[0_0_25px_rgba(34,211,238,0.35)] border-2 border-cyan-400/50 group-hover:scale-105 transition-all duration-300 dwss-logo shrink-0">
                        <x-app-logo-icon class="size-13 dwss-logo-icon transition-all duration-300" />
                    </div>

                    <div class="dwss-brand-text flex items-center justify-center w-full mt-2.5">
                        <span class="text-4xl sm:text-[44px] font-black tracking-widest uppercase drop-shadow-[0_0_18px_rgba(34,211,238,0.9)] inline-block transition-all duration-300 group-hover:scale-105" 
                            style="color: #22d3ee !important; font-family: 'Inter', system-ui, sans-serif !important; -webkit-text-stroke: 0 !important;">
                            D.W.S.S
                        </span>
                    </div>
                </a>
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')">
                    <flux:sidebar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        <span>{{ __('Dashboard') }}</span>
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>

