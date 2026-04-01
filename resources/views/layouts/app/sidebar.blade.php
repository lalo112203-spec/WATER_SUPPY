<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    <style>
        /* Custom Theme Styles */
        @if(auth()->check() && auth()->user()->background_image)
            html.custom-theme body {
                background-image: url("{{ asset('storage/' . auth()->user()->background_image) }}") !important;
                background-size: cover !important;
                background-attachment: fixed !important;
                background-position: center !important;
                background-color: transparent !important;
            }
            /* Make main components transparent so custom background shines through */
            html.custom-theme main,
            html.custom-theme main > div,
            html.custom-theme main > section,
            html.custom-theme [data-flux-main] section,
            html.custom-theme main [class*="bg-white"],
            html.custom-theme main [class*="dark:bg-"],
            html.custom-theme main .bg-slate-50,
            html.custom-theme main .bg-gray-100 {
                background-color: rgba(0, 0, 0, 0.25) !important; 
                backdrop-filter: blur(12px) !important;
                -webkit-backdrop-filter: blur(12px) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
            }
            html.custom-theme main .border-gray-200, 
            html.custom-theme main .border-slate-200 { 
                border-color: rgba(255, 255, 255, 0.1) !important; 
            }
        @endif

        @if(auth()->check() && auth()->user()->text_color)
            html.custom-theme {
                --flux-custom-text: {{ auth()->user()->text_color }};
            }
            /* Penetrate all elements in main */
            html.custom-theme main,
            html.custom-theme main *,
            html.custom-theme [data-flux-main],
            html.custom-theme [data-flux-main] * {
                color: var(--flux-custom-text) !important;
            }
            /* Make text inputs transparent and follow the custom text color */
            html.custom-theme main input,
            html.custom-theme main textarea,
            html.custom-theme main select,
            html.custom-theme [data-flux-main] input,
            html.custom-theme [data-flux-main] textarea,
            html.custom-theme [data-flux-main] select {
                background-color: rgba(0, 0, 0, 0.3) !important;
                border-color: rgba(255, 255, 255, 0.2) !important;
                color: var(--flux-custom-text) !important;
            }
            html.custom-theme main input:focus,
            html.custom-theme main textarea:focus,
            html.custom-theme main select:focus {
                background-color: rgba(0, 0, 0, 0.4) !important;
                border-color: rgba(255, 255, 255, 0.4) !important;
            }
        @endif
        html.custom-theme main { background-color: transparent !important; backdrop-filter: none !important; }
        
        /* Permanent Dark Sidebar Styling (Unaffected by Theme) */
        flux\:sidebar,
        [data-flux-sidebar] {
            background-color: #0b121c !important; /* Permanent Dark Blue */
        }

        flux\:sidebar *,
        [data-flux-sidebar] * {
            background-color: transparent !important;
            color: #ffffff !important; /* Pure White */
        }
        
        flux\:sidebar .text-white, 
        [data-flux-sidebar] .text-white {
            color: #ffffff !important;
        }

        /* Light Mode Styles (ONLY affect Main Content) */
        html:not(.dark):not(.custom-theme) body {
            background-color: #ffffff !important;
            background-image: none !important;
            color: #000000 !important;
        }
        
        html:not(.dark):not(.custom-theme) main {
            background-color: #ffffff !important;
            color: #000000 !important;
        }

        /* Force black text ONLY in the Main area for absolute visibility in Light Mode */
        html:not(.dark):not(.custom-theme) main,
        html:not(.dark):not(.custom-theme) main * {
            color: #000000 !important;
        }
        
        /* Exception for custom colored buttons in main */
        html:not(.dark):not(.custom-theme) main button[class*="bg-"],
        html:not(.dark):not(.custom-theme) main a[class*="bg-"] {
             color: inherit !important;
        }

        /* Ensure form labels and descriptions are visible */
        html:not(.dark):not(.custom-theme) main [data-flux-field] label,
        html:not(.dark):not(.custom-theme) main [data-flux-header] h2,
        html:not(.dark):not(.custom-theme) main [data-flux-header] p {
            color: #000000 !important;
            opacity: 1 !important;
        }

        /* Ensure input boxes are visible and have borders */
        html:not(.dark):not(.custom-theme) main input:not([type="checkbox"]),
        html:not(.dark):not(.custom-theme) main textarea,
        html:not(.dark):not(.custom-theme) main select {
            background-color: #ffffff !important;
            border: 1px solid #d1d5db !important;
            color: #111827 !important;
        }

        /* Dark Mode Styles */
        html.dark:not(.custom-theme) body {
            background-color: #000000 !important;
            background-image: none !important;
        }
        html.dark:not(.custom-theme) main {
            color: #ffffff !important;
        }

        html.dark:not(.custom-theme) main .text-gray-100, 
        html.dark:not(.custom-theme) main .text-gray-200, 
        html.dark:not(.custom-theme) main .text-gray-300, 
        html.dark:not(.custom-theme) main .text-gray-400, 
        html.dark:not(.custom-theme) main .text-gray-500, 
        html.dark:not(.custom-theme) main .text-black,
        html.dark:not(.custom-theme) main label,
        html.dark:not(.custom-theme) main button,
        html.dark:not(.custom-theme) main button span {
            color: #ffffff !important;
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
        
        /* Custom Scrollbar - Custom Theme */
        html.custom-theme ::-webkit-scrollbar { width: 8px; height: 8px; }
        html.custom-theme ::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.1); }
        html.custom-theme ::-webkit-scrollbar-thumb { background: rgba(56, 189, 248, 0.3); border-radius: 4px; }
        html.custom-theme ::-webkit-scrollbar-thumb:hover { background: rgba(56, 189, 248, 0.5); }

        /* Ensure body and html allow scrolling */
        html, body {
            overflow-y: auto !important;
            height: auto !important;
            min-height: 100vh !important;
        }
        
        /* Add cool pattern overlay only in Dark Mode */
        html.dark:not(.custom-theme) body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none; z-index: -1;
            opacity: 0.15;
            background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 20.5V18H0v-2h20v-2.5L40 20 20 20.5zM0 38h20v-2.5L40 40 20 40.5V38H0v-2h20v-2.5L40 38 20 38.5V38H0z' fill='%2338bdf8' fill-opacity='0.2' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        /* Map hardcoded dark themes back to light styles globally for Light Mode only */
        html:not(.dark):not(.custom-theme) main .bg-\[\#121a25\]\/80 { background-color: rgba(255, 255, 255, 0.95) !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important; }
        html:not(.dark):not(.custom-theme) main .bg-\[\#0b121c\] { background-color: #ffffff !important; }
        html:not(.dark):not(.custom-theme) main .border-\[\#263548\], html:not(.dark):not(.custom-theme) main .border-\[\#1e293b\], html:not(.dark):not(.custom-theme) main .border-\[\#2d4059\] { border-color: #e5e7eb !important; }
        html:not(.dark):not(.custom-theme) main .text-gray-100, html:not(.dark):not(.custom-theme) main .text-white { color: #111827 !important; }
        html:not(.dark):not(.custom-theme) main .text-gray-200 { color: #374151 !important; }
        html:not(.dark):not(.custom-theme) main .text-gray-300 { color: #4b5563 !important; }
        html:not(.dark):not(.custom-theme) main .text-gray-400 { color: #6b7280 !important; }
        html:not(.dark):not(.custom-theme) main .text-gray-500 { color: #9ca3af !important; }
        html:not(.dark):not(.custom-theme) main .bg-\[\#0f1722\] { background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important; }
        html:not(.dark):not(.custom-theme) main .bg-\[\#0f1722\]\/80 { background-color: rgba(243, 244, 246, 0.9) !important; border: 1px solid #e5e7eb !important; }
        html:not(.dark):not(.custom-theme) main .from-\[\#1b2636\] { background-image: none !important; background-color: white !important; }
        html:not(.dark):not(.custom-theme) main .bg-\[\#091522\]\/80 { background-color: #f0fdfa !important; }
        html:not(.dark):not(.custom-theme) main .shadow-\[0_8px_30px_rgb\(0\,0\,0\,0\.5\)\] { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; }

        /* Fix text color turning white while typing in forms */
        html input, html textarea, html select {
            color: #111827 !important;
        }
        html input:-webkit-autofill {
            -webkit-text-fill-color: #111827 !important;
        }
    </style>
    <script>
        function shouldBeCustom() {
            let localApp = window.localStorage.getItem('flux.appearance');
            // Auto-upgrade legacy 'system' states from previous session bug
            if (localApp === 'system') {
                window.localStorage.setItem('flux.appearance', 'custom');
                localApp = 'custom';
            }
            return localApp === 'custom';
        }

        function enforceCustomTheme() {
            const isCustom = shouldBeCustom();
            const hasClass = document.documentElement.classList.contains('custom-theme');
            if (isCustom && !hasClass) {
                document.documentElement.classList.add('custom-theme');
            } else if (!isCustom && hasClass) {
                document.documentElement.classList.remove('custom-theme');
            }
        }

        // Run immediately
        enforceCustomTheme();
        
        // Patch Flux's native appearance method to support 'custom'
        if (window.Flux) {
            const originalApply = window.Flux.applyAppearance;
            window.Flux.applyAppearance = function(appearance) {
                if (appearance === 'custom') {
                    window.localStorage.setItem('flux.appearance', 'custom');
                } else {
                    originalApply.apply(this, arguments);
                }
                enforceCustomTheme();
            };
        }
        
        // Listen for storage events across windows
        window.addEventListener('storage', enforceCustomTheme);
        document.addEventListener('livewire:navigated', () => {
             enforceCustomTheme();
             // Re-patch flux if Livewire restored the page
             if (window.Flux && !window.Flux.applyAppearance.isPatched) {
                 const originalApply = window.Flux.applyAppearance;
                 window.Flux.applyAppearance = function(appearance) {
                     if (appearance === 'custom') {
                         window.localStorage.setItem('flux.appearance', 'custom');
                     } else {
                         originalApply.apply(this, arguments);
                     }
                     enforceCustomTheme();
                 };
                 window.Flux.applyAppearance.isPatched = true;
             }
        });
        
        // Intercept local storage updates on this window
        const originalSetItem = window.localStorage.setItem;
        window.localStorage.setItem = function(key, value) {
            originalSetItem.apply(this, arguments);
            if (key === 'flux.appearance') enforceCustomTheme();
        };

        // Bulletproof observer against Flux Appearance overwriting classes
        const themeObserver = new MutationObserver(() => enforceCustomTheme());
        if (document.documentElement) {
            themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        }
    </script>
</head>

<body class="min-h-screen text-gray-300 antialiased selection:bg-cyan-500/30">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-[#1e293b] bg-[#0b121c] bg-opacity-65 backdrop-blur-2xl shadow-2xl">
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
                    $unreadCount = \App\Models\Message::where('receiver_id', auth()->id())->whereNull('read_at')->count();
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

                    <flux:sidebar.item icon="arrow-top-right-on-square" href="https://watersystem-production-00ee.up.railway.app" 
                        target="_blank">
                        {{ __('Visit Live Site') }}
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
            @if(auth()->user()->profile_photo)
                <flux:profile :avatar="asset('storage/' . auth()->user()->profile_photo)" icon-trailing="chevron-down" />
            @else
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
            @endif

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-3 px-2 py-3 text-start">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="h-10 w-10 rounded-xl object-cover">
                            @else
                                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                            @endif
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