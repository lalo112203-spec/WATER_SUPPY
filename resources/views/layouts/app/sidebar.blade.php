<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    <style>
        @if(auth()->check() && auth()->user()->background_image)
            html.dark body, html:not(.dark) body {
                background-image: url("{{ asset('storage/' . auth()->user()->background_image) }}") !important;
                background-size: cover !important;
                background-attachment: fixed !important;
                background-position: center !important;
                background-color: transparent !important;
            }
        @endif

        @if(auth()->check() && auth()->user()->text_color)
            html body, html.dark body,
            html .text-gray-100, html .text-gray-200, html .text-gray-300, html .text-gray-400, html .text-gray-500, html .text-white,
            html.dark .text-gray-100, html.dark .text-gray-200, html.dark .text-gray-300, html.dark .text-gray-400, html.dark .text-gray-500, html.dark .text-white {
                color: {{ auth()->user()->text_color }} !important;
            }
        @endif
        
        /* Light Mode Styles */
        html:not(.dark) body {
            background-color: #ffffff;
            background-image: none;
            color: #000000;
        }

        /* Dark Mode Styles */
        html.dark body {
            background-color: #ffffff;
            background-image: none;
            color: #000000;
        }

        /* Custom Scrollbar - Light */
        html:not(.dark) ::-webkit-scrollbar { width: 8px; height: 8px; }
        html:not(.dark) ::-webkit-scrollbar-track { background: #f1f5f9; }
        html:not(.dark) ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        html:not(.dark) ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Custom Scrollbar - Dark */
        html.dark ::-webkit-scrollbar { width: 8px; height: 8px; }
        html.dark ::-webkit-scrollbar-track { background: #0b1118; }
        html.dark ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
        html.dark ::-webkit-scrollbar-thumb:hover { background: #334155; }
        
        /* Add cool pattern overlay only in Dark Mode */
        html.dark body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none; z-index: -1;
            opacity: 0.15;
            background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 20.5V18H0v-2h20v-2.5L40 20 20 20.5zM0 38h20v-2.5L40 40 20 40.5V38H0v-2h20v-2.5L40 38 20 38.5V38H0z' fill='%2338bdf8' fill-opacity='0.2' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        /* Map hardcoded dark themes back to light styles globally to ensure consistency across devices */
        html .bg-\[\#121a25\]\/80 { background-color: rgba(255, 255, 255, 0.95) !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important; }
        html .bg-\[\#0b121c\] { background-color: #ffffff !important; }
        html .border-\[\#263548\], html .border-\[\#1e293b\], html .border-\[\#2d4059\] { border-color: #e5e7eb !important; }
        html .text-gray-100, html .text-white { color: #111827 !important; }
        html .text-gray-200 { color: #374151 !important; }
        html .text-gray-300 { color: #4b5563 !important; }
        html .text-gray-400 { color: #6b7280 !important; }
        html .text-gray-500 { color: #9ca3af !important; }
        html .bg-\[\#0f1722\] { background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important; }
        html .bg-\[\#0f1722\]\/80 { background-color: rgba(243, 244, 246, 0.9) !important; border: 1px solid #e5e7eb !important; }
        html .from-\[\#1b2636\] { background-image: none !important; background-color: white !important; }
        html .bg-\[\#091522\]\/80 { background-color: #f0fdfa !important; }
        html .shadow-\[0_8px_30px_rgb\(0\,0\,0\,0\.5\)\] { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; }

        /* Fix text color turning white while typing in forms */
        html input, html textarea, html select {
            color: #111827 !important;
        }
        html input:-webkit-autofill {
            -webkit-text-fill-color: #111827 !important;
        }
    </style>
</head>

<body class="min-h-screen text-gray-300 antialiased selection:bg-cyan-500/30">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-[#1e293b] bg-[#0b121c] bg-opacity-95 backdrop-blur-xl shadow-2xl">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Water System')" class="grid">
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                @if(auth()->user()->role === 'admin')
                    <flux:sidebar.item icon="users" :href="route('customers.index')"
                        :current="request()->routeIs('customers.*')" wire:navigate>
                        {{ __('Customers') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="list-bullet" :href="route('billing.index')"
                        :current="request()->routeIs('billing.*')" wire:navigate>
                        {{ __('Billing Reports') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="adjustments-horizontal" :href="route('settings.index')"
                        :current="request()->routeIs('settings.*')" wire:navigate>
                        {{ __('Settings') }}
                    </flux:sidebar.item>
                @endif

                @php
                    if (auth()->user()->role === 'admin') {
                        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
                        $unreadCount = \App\Models\Message::whereIn('receiver_id', $adminIds)->whereNull('read_at')->count();
                    } else {
                        $unreadCount = \App\Models\Message::where('receiver_id', auth()->id())->whereNull('read_at')->count();
                    }
                @endphp
                <flux:sidebar.item icon="chat-bubble-left" :href="route('messages.index')"
                    :current="request()->routeIs('messages.*')" wire:navigate>
                    <div class="flex items-center justify-between w-full">
                        @if(auth()->user()->role === 'admin')
                            <span>{{ __('Message & Posting') }}</span>
                        @else
                            <span>{{ __('Messages') }}</span>
                        @endif
                        @if($unreadCount > 0)
                            <span
                                class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                        @endif
                    </div>
                </flux:sidebar.item>

                @if(auth()->user()->role === 'admin')
                    <flux:sidebar.item icon="trash" :href="route('recovery.index')"
                        :current="request()->routeIs('recovery.*')" wire:navigate>
                        {{ __('Recovery / Trash') }}
                    </flux:sidebar.item>
                @endif
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />


        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>