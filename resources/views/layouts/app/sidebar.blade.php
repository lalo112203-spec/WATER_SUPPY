<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    <style>
        :root {
            --sidebar-bg: #0a1931;
            --main-bg-light: #f4f6fa;
            --main-bg-dark: #020617;
            --card-bg-light: #ffffff;
            --card-bg-dark: #0f172a;
            --accent-blue: #0026ff;
            --text-main-light: #0f172a;
            --text-main-dark: #f8fafc;
        }

        /* Base resets and layout */
        html, body {
            overflow-y: auto !important;
            height: auto !important;
            min-height: 100vh !important;
            font-family: 'Inter', system-ui, sans-serif !important;
        }

        /* Sidebar Styling (Fixed, Dark, Premium) */
        flux\:sidebar,
        [data-flux-sidebar] {
            background-color: #020617 !important; /* Deepest blue/black */
            border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.3) !important;
            z-index: 50 !important;
        }

        /* Active Sidebar Item - Modern Pill Style */
        flux\:sidebar [data-flux-sidebar-item][data-current],
        [data-flux-sidebar] [data-flux-sidebar-item][data-current] {
            background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
            color: #ffffff !important;
            border-radius: 12px !important;
            margin: 0 12px !important;
            padding: 8px 16px !important;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3) !important;
        }

        flux\:sidebar [data-flux-sidebar-item],
        [data-flux-sidebar] [data-flux-sidebar-item] {
            margin: 4px 12px !important;
            padding: 8px 16px !important;
            border-radius: 12px !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        flux\:sidebar [data-flux-sidebar-item][data-current] *,
        [data-flux-sidebar] [data-flux-sidebar-item][data-current] * {
            color: #ffffff !important;
        }

        flux\:sidebar [data-flux-sidebar-item]:hover:not([data-current]),
        [data-flux-sidebar] [data-flux-sidebar-item]:hover:not([data-current]) {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #ffffff !important;
            border-radius: 9999px !important;
            margin: 0 16px !important;
        }

        /* Default Light Mode Appearance (Clean & Comfortable) */
        html:not(.dark):not(.custom-theme) body {
            background-color: var(--main-bg-light) !important;
            color: var(--text-main-light) !important;
        }

        /* Map hardcoded dark themes to beautiful light cards in Default Mode */
        html:not(.dark):not(.custom-theme) main [class*="bg-[#121a25]"],
        html:not(.dark):not(.custom-theme) main [class*="bg-[#1b2636]"],
        html:not(.dark):not(.custom-theme) main [class*="bg-[#0f1722]"],
        html:not(.dark):not(.custom-theme) main [class*="bg-[#091522]"],
        html:not(.dark):not(.custom-theme) main [class*="bg-[#0b121c]"],
        html:not(.dark):not(.custom-theme) [data-flux-main] [class*="bg-[#121a25]"],
        html:not(.dark):not(.custom-theme) [data-flux-main] [class*="bg-[#1b2636]"] {
            background-color: var(--card-bg-light) !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02) !important;
            color: var(--text-main-light) !important;
        }

        html:not(.dark):not(.custom-theme) h1,
        html:not(.dark):not(.custom-theme) h2,
        html:not(.dark):not(.custom-theme) h3 {
            color: #1e293b !important;
        }

        /* Text Overrides for Light Mode */
        html:not(.dark):not(.custom-theme) main .text-gray-100,
        html:not(.dark):not(.custom-theme) main .text-gray-200,
        html:not(.dark):not(.custom-theme) main .text-gray-300,
        html:not(.dark):not(.custom-theme) main .text-white {
            color: var(--text-main-light) !important;
        }

        html:not(.dark):not(.custom-theme) main .text-gray-400,
        html:not(.dark):not(.custom-theme) main .text-gray-500 {
            color: #475569 !important;
        }

        /* Default Dark Mode Appearance (Deep Slate & Eye-Comfortable) */
        html.dark:not(.custom-theme) body {
            background-color: var(--main-bg-dark) !important;
            color: var(--text-main-dark) !important;
        }

        html.dark:not(.custom-theme) main [class*="bg-[#121a25]"],
        html.dark:not(.custom-theme) main [class*="bg-[#1b2636]"],
        html.dark:not(.custom-theme) [data-flux-main] [class*="bg-[#121a25]"],
        html.dark:not(.custom-theme) [data-flux-main] [class*="bg-[#1b2636]"] {
             background-color: var(--card-bg-dark) !important;
             border: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        /* Text Overrides for Dark Mode */
        html.dark:not(.custom-theme) main .text-gray-800,
        html.dark:not(.custom-theme) main .text-gray-900,
        html.dark:not(.custom-theme) main .text-black {
            color: #f8fafc !important;
        }

        html.dark:not(.custom-theme) main .text-gray-400,
        html.dark:not(.custom-theme) main .text-gray-500 {
            color: #94a3b8 !important;
        }

        /* Enforce readable cards in Custom Theme mode */
        html.custom-theme main [class*="bg-[#121a25]"],
        html.custom-theme main [class*="bg-[#1b2636]"],
        html.custom-theme main [class*="bg-[#0f1722]"],
        html.custom-theme main [class*="bg-[#0b121c]"],
        html.custom-theme [data-flux-main] [class*="bg-[#121a25]"],
        html.custom-theme [data-flux-main] [class*="bg-[#1b2636]"] {
            background-color: rgba(15, 23, 42, 0.75) !important;
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 16px !important;
            color: #f8fafc !important;
        }

        html.custom-theme main .text-gray-800,
        html.custom-theme main .text-gray-900,
        html.custom-theme main .text-black {
            color: #f8fafc !important;
        }

        /* Custom Theme Overrides (Keeping Logic Intact) */
        @if(auth()->check() && (auth()->user()->background_image || auth()->user()->background_color))
            html.custom-theme body {
                @if(auth()->user()->background_image)
                    background-image: url("{{ asset('storage/' . auth()->user()->background_image) }}") !important;
                    background-size: cover !important;
                    background-attachment: fixed !important;
                @elseif(auth()->user()->background_color)
                    background-color: {{ auth()->user()->background_color }} !important;
                @endif
            }
        @endif

        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        /* Scrollbars (Modern Slate) */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { 
            background: #cbd5e1; 
            border-radius: 20px; 
            border: 2px solid transparent;
            background-clip: content-box;
        }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }

        /* Ensure main content is shifted correctly */
        main, [data-flux-main], flux\:main {
            padding-top: 1rem !important; /* Mobile padding so it doesn't hide behind mobile header */
        }
        @media (min-width: 1024px) {
            main, [data-flux-main], flux\:main {
                margin-left: 16rem !important;
                padding-top: 2rem !important;
            }
        }

        /* Form Controls visibility in Light Mode */
        html:not(.dark) input:not([type="checkbox"]),
        html:not(.dark) textarea,
        html:not(.dark) select {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #0f172a !important;
        }
        
        /* Vibrant action buttons */
        .bg-cyan-600\/80 { background-color: var(--accent-blue) !important; }
        .text-cyan-400 { color: var(--accent-blue) !important; }
        
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
            window.Flux.applyAppearance = function (appearance) {
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
                window.Flux.applyAppearance = function (appearance) {
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
        window.localStorage.setItem = function (key, value) {
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
    <flux:sidebar collapsible="mobile"
        class="border-e border-[#1e293b] bg-[#0b121c] bg-opacity-65 backdrop-blur-2xl shadow-2xl">
        <flux:sidebar.header class="flex flex-col items-start px-6 py-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="h-10 w-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-900/50">D</div>
                <div class="flex flex-col">
                    <span class="text-white font-bold tracking-tight text-lg">W.S.S.S</span>
                    <span class="text-blue-400 text-[10px] uppercase tracking-widest font-medium opacity-80">Water Supply</span>
                </div>
            </div>
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group class="grid">
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

                    <flux:sidebar.item icon="key" :href="route('registration-codes.index')"
                        :current="request()->routeIs('registration-codes.*')" wire:navigate>
                        {{ __('Registration Codes') }}
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
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                    class="h-10 w-10 rounded-xl object-cover">
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