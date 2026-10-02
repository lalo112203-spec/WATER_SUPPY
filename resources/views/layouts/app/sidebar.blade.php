<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

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
        html,
        body {
            overflow-y: auto !important;
            height: auto !important;
            min-height: 100vh !important;
            font-family: 'Inter', system-ui, sans-serif !important;
        }

        /* Sidebar Styling (Fixed, Dark, Premium) */
        flux\:sidebar,
        [data-flux-sidebar] {
            background-color: var(--sidebar-bg) !important;
            border-right: none !important;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.2) !important;
            z-index: 50 !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        /* Enforce desktop fixed behavior without breaking mobile transform */
        @media (min-width: 1024px) {

            flux\:sidebar,
            [data-flux-sidebar] {
                height: 100vh !important;
                position: fixed !important;
                left: 0 !important;
                top: 0 !important;
            }
        }

        flux\:sidebar *,
        [data-flux-sidebar] * {
            background-color: transparent !important;
        }

        /* Unstoppable Specificity to Force Sidebar Text to White */
        /* Unstoppable Specificity to Force Sidebar Text to White (Beating Custom Theme's :not selectors) */
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-item][data-flux-sidebar-item],
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-item][data-flux-sidebar-item] div,
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-item][data-flux-sidebar-item] span,
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-item][data-flux-sidebar-item] p,
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-item][data-flux-sidebar-item] svg,
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-profile][data-flux-sidebar-profile],
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-profile][data-flux-sidebar-profile] div,
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-profile][data-flux-sidebar-profile] span,
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-profile][data-flux-sidebar-profile] p,
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-profile][data-flux-sidebar-profile] svg,
        html body [data-flux-sidebar] [data-flux-sidebar-item][data-flux-sidebar-item] div,
        html body [data-flux-sidebar] [data-flux-sidebar-item][data-flux-sidebar-item] span,
        html body [data-flux-sidebar] [data-flux-sidebar-profile][data-flux-sidebar-profile] div,
        html body [data-flux-sidebar] [data-flux-sidebar-profile][data-flux-sidebar-profile] span {
            color: #ffffff !important;
        }

        /* Active Sidebar Item - Pill Style */
        [data-flux-sidebar] [data-flux-sidebar-item][data-current] {
            background-color: var(--accent-blue) !important;
            border-radius: 9999px !important;
            margin: 3px 4px !important;
            width: calc(100% - 8px) !important;
            box-sizing: border-box !important;
            box-shadow: 0 4px 12px rgba(0, 38, 255, 0.3) !important;
        }

        /* Active Text Force White */
        [data-flux-sidebar] [data-flux-sidebar-item][data-current],
        [data-flux-sidebar] [data-flux-sidebar-item][data-current] span,
        [data-flux-sidebar] [data-flux-sidebar-item][data-current] svg,
        [data-flux-sidebar] [data-flux-sidebar-item][data-current] div,
        [data-flux-sidebar] [data-flux-sidebar-item][data-current] * {
            color: #ffffff !important;
        }

        /* Hover Effect for Inactive Items */
        [data-flux-sidebar] [data-flux-sidebar-item]:hover:not([data-current]) {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-radius: 9999px !important;
            margin: 3px 4px !important;
            width: calc(100% - 8px) !important;
            box-sizing: border-box !important;
        }

        /* Message Icon Dot (Make it Red) */
        .message-has-unread > div > div.absolute > div.rounded-full {
            background-color: #ef4444 !important; /* red-500 */
        }
        
        /* Force text color and background for badge */
        .message-has-unread [data-flux-navlist-badge] {
            background-color: #ef4444 !important; /* red-500 */
            color: #ffffff !important;
        }

        /* Also target any badge element on the messages nav item */
        [data-flux-sidebar-item].message-has-unread [data-flux-badge],
        [data-flux-sidebar-item].message-has-unread span[class*="badge"],
        [data-flux-navlist-item].message-has-unread [data-flux-navlist-badge] {
            background-color: #ef4444 !important;
            color: #ffffff !important;
        }

        /* Default System Background (Wavy Blue) applied only to main content */
        html body {
            background-color: var(--main-bg-dark) !important;
            color: var(--text-main-dark);
        }

        /* Force the background on all system pages (dashboard, billing, etc.) */
        main,
        [data-flux-main],
        flux\:main {
            @if(auth()->check() && !empty(auth()->user()->background_image))
                background-image: url("{{ asset('storage/' . auth()->user()->background_image) }}") !important;
                background-size: cover !important;
                background-attachment: fixed !important;
                background-position: center !important;
            @elseif(auth()->check() && !empty(auth()->user()->background_url))
                background-image: url("{{ auth()->user()->background_url }}") !important;
                background-size: cover !important;
                background-attachment: fixed !important;
                background-position: center !important;
            @elseif(auth()->check() && !empty(auth()->user()->background_color))
                background: {{ auth()->user()->background_color }} !important;
                background-image: none !important;
            @else
                background-image: url("{{ asset('images/system_bg.png') }}") !important;
                background-size: cover !important;
                background-attachment: fixed !important;
                background-position: center !important;
            @endif
            min-height: 100vh;
        }

        /* Custom Theme Overrides (Fully implemented for internet-wide support) */
        @if(auth()->check() && (auth()->user()->background_image || auth()->user()->background_url || auth()->user()->background_color || auth()->user()->font_family))
            html body,
            html main {
                @if(!empty(auth()->user()->background_image))
                    background-image: url("{{ asset('storage/' . auth()->user()->background_image) }}") !important;
                    background-size: cover !important;
                    background-attachment: fixed !important;
                    background-position: center !important;
                @elseif(!empty(auth()->user()->background_url))
                    background-image: url("{{ auth()->user()->background_url }}") !important;
                    background-size: cover !important;
                    background-attachment: fixed !important;
                    background-position: center !important;
                @elseif(!empty(auth()->user()->background_color))
                    background: {{ auth()->user()->background_color }} !important;
                    background-image: none !important;
                @endif

                @if(auth()->user()->font_family)
                    font-family: {{ auth()->user()->font_family }} !important;
                @endif
            }
            
            /* Enforce white text across the main content area (allows specific colored badges to keep their colors) */
            html.custom-theme main {
                color: white;
            }

            /* Override Tailwind gray text classes to white in main content */
            html.custom-theme main .text-gray-100,
            html.custom-theme main .text-gray-200,
            html.custom-theme main .text-gray-300,
            html.custom-theme main .text-gray-400,
            html.custom-theme main .text-gray-500,
            html.custom-theme main .text-gray-600,
            html.custom-theme main .text-gray-700 {
                color: white !important;
            }

            /* Import external fonts used in the appearance settings */
            @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Oswald:wght@400;500;600;700&family=Roboto:wght@400;500;700;900&display=swap');



            /* Apply custom text styles to elements in custom theme (strictly excluding the sidebar) */
            @if(auth()->user()->text_stroke_color)
                html.custom-theme main h1:not(form *), 
                html.custom-theme main h2:not(form *), 
                html.custom-theme main h3:not(form *), 
                html.custom-theme main p:not(form *), 
                html.custom-theme main span:not(form *), 
                html.custom-theme main div:not(form *),
                html.custom-theme *:not([data-flux-sidebar]):not([data-flux-sidebar] *):not([data-flux-navbar]):not([data-flux-navbar] *):not(form *) {
                    @if(auth()->user()->text_stroke_color && auth()->user()->text_stroke_width)
                        -webkit-text-stroke: {{ auth()->user()->text_stroke_width }} {{ auth()->user()->text_stroke_color }} !important;
                        paint-order: stroke fill !important;
                    @endif
                }
                
                /* Protect form elements and sidebar from inheriting the stroke */
                html.custom-theme input,
                html.custom-theme select,
                html.custom-theme textarea,
                html.custom-theme label,
                html.custom-theme button,
                [data-flux-sidebar],
                [data-flux-sidebar] * {
                    -webkit-text-stroke: 0px transparent !important;
                    -webkit-text-stroke-width: 0px !important;
                }
            @endif

            /* Ensure font family is applied to main content in custom theme (strictly excluding the sidebar) */
            @if(auth()->user()->font_family)
                html.custom-theme *:not([data-flux-sidebar]):not([data-flux-sidebar] *), 
                html.custom-theme input, 
                html.custom-theme button:not([data-flux-sidebar] *), 
                html.custom-theme select, 
                html.custom-theme textarea {
                    font-family: {{ auth()->user()->font_family }}, 'Inter', sans-serif !important;
                }
            @endif

            @if(auth()->user()->text_size)
                html.custom-theme *:not([data-flux-sidebar]):not([data-flux-sidebar] *):not(form):not(form *) {
                    font-size: {{ auth()->user()->text_size }} !important;
                }
            @endif
            
            @if(auth()->user()->font_weight)
                html.custom-theme *:not([data-flux-sidebar]):not([data-flux-sidebar] *):not(form):not(form *):not(i):not(svg):not(path) {
                    font-weight: {{ auth()->user()->font_weight }} !important;
                }
            @endif
        @endif

        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        /* Scrollbars (Modern Slate) */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }

        /* Ensure main content is shifted correctly */
        main,
        [data-flux-main],
        flux\:main {
            padding-top: 1rem !important;
            /* Mobile padding so it doesn't hide behind mobile header */
        }

        @media (min-width: 1024px) {

            main,
            [data-flux-main],
            flux\:main {
                @if(auth()->check() && auth()->user()->role === 'reader')
                margin-left: 0 !important;
                padding-top: 1rem !important;
                @else
                margin-left: 16rem !important;
                padding-top: 2rem !important;
                @endif
            }

            /* When sidebar is collapsed on desktop, reduce margin */
            [data-flux-sidebar-collapsed-desktop] ~ flux\:main,
            [data-flux-sidebar-collapsed-desktop] ~ [data-flux-main] {
                @if(auth()->check() && auth()->user()->role !== 'reader')
                margin-left: 4rem !important;
                @endif
            }
        }

        /* Hide DWSS text when sidebar is collapsed */
        [data-flux-sidebar-collapsed] .dwss-brand-text,
        [data-flux-sidebar-collapsed-desktop] .dwss-brand-text,
        [data-flux-sidebar-collapsed-mobile] .dwss-brand-text {
            display: none !important;
            opacity: 0 !important;
            width: 0 !important;
            overflow: hidden !important;
        }

        /* Shrink logo when sidebar is collapsed */
        [data-flux-sidebar-collapsed] .dwss-logo,
        [data-flux-sidebar-collapsed-desktop] .dwss-logo,
        [data-flux-sidebar-collapsed-mobile] .dwss-logo {
            width: 2.25rem !important;
            height: 2.25rem !important;
        }
        [data-flux-sidebar-collapsed] .dwss-logo-icon,
        [data-flux-sidebar-collapsed-desktop] .dwss-logo-icon,
        [data-flux-sidebar-collapsed-mobile] .dwss-logo-icon {
            width: 1.75rem !important;
            height: 1.75rem !important;
        }
        [data-flux-sidebar-collapsed] .dwss-logo-container,
        [data-flux-sidebar-collapsed-desktop] .dwss-logo-container,
        [data-flux-sidebar-collapsed-mobile] .dwss-logo-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
            justify-content: center !important;
        }

        [data-flux-sidebar-collapsed] .dwss-header-top,
        [data-flux-sidebar-collapsed-desktop] .dwss-header-top,
        [data-flux-sidebar-collapsed-mobile] .dwss-header-top {
            justify-content: center !important;
        }

        /* =====================================================================
           FIXED SIDEBAR STYLING - IMMUNE TO APPEARANCE SETTINGS
           ===================================================================== */
        /* 1. Neutralize stroke, shadow, and text modifications from appearance settings */
        [data-flux-sidebar],
        [data-flux-sidebar] *,
        html.custom-theme [data-flux-sidebar],
        html.custom-theme [data-flux-sidebar] *,
        html.custom-theme body [data-flux-sidebar],
        html.custom-theme body [data-flux-sidebar] * {
            -webkit-text-stroke: 0px transparent !important;
            -webkit-text-stroke-width: 0px !important;
            paint-order: normal !important;
        }

        /* 2. Fixed Typography: Font Family, Font Weight, and Bigger Font Size */
        [data-flux-sidebar] [data-flux-sidebar-item],
        [data-flux-sidebar] [data-flux-sidebar-item] *,
        [data-flux-sidebar] [data-flux-sidebar-item] span,
        [data-flux-sidebar] [data-flux-sidebar-item] div,
        [data-flux-sidebar] [data-flux-sidebar-item] [data-content],
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-item],
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-item] *,
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-item] span,
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-item] div,
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-item] [data-content],
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-item],
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-item] span,
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-item] div {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            font-size: 1.05rem !important; /* ~17px - clearly bigger & fixed */
            font-weight: 600 !important;
            letter-spacing: 0.015em !important;
            line-height: 1.4 !important;
        }

        /* Active sidebar item font weight */
        [data-flux-sidebar] [data-flux-sidebar-item][data-current],
        [data-flux-sidebar] [data-flux-sidebar-item][data-current] *,
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-item][data-current],
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-item][data-current] * {
            font-weight: 700 !important;
        }

        /* 3. Enlarge sidebar navigation icons */
        [data-flux-sidebar] [data-flux-sidebar-item] svg,
        [data-flux-sidebar] [data-flux-sidebar-item] [data-flux-icon],
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-item] svg,
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-item] [data-flux-icon],
        html.custom-theme body [data-flux-sidebar] [data-flux-sidebar-item] svg {
            width: 1.45rem !important;  /* ~23px */
            height: 1.45rem !important;
            min-width: 1.45rem !important;
            min-height: 1.45rem !important;
            stroke-width: 2px !important;
            flex-shrink: 0 !important;
        }

        /* 4. Sidebar Item Container Spacing & Dimensions */
        [data-flux-sidebar] [data-flux-sidebar-item],
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-item] {
            min-height: 2.75rem !important; /* 44px */
            height: auto !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            gap: 0.75rem !important;
            margin: 3px 4px !important;
            width: calc(100% - 8px) !important;
            border-radius: 9999px !important;
            box-sizing: border-box !important;
        }

        /* 5. Collapsed sidebar alignment */
        [data-flux-sidebar-collapsed-desktop] [data-flux-sidebar-item],
        [data-flux-sidebar-collapsed] [data-flux-sidebar-item] {
            margin-left: auto !important;
            margin-right: auto !important;
            width: 2.5rem !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            justify-content: center !important;
        }

        /* 6. Profile section at the bottom of sidebar */
        [data-flux-sidebar] [data-flux-sidebar-profile],
        [data-flux-sidebar] [data-flux-sidebar-profile] *,
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-profile],
        html.custom-theme [data-flux-sidebar] [data-flux-sidebar-profile] * {
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            font-family: 'Inter', system-ui, sans-serif !important;
            -webkit-text-stroke: 0px transparent !important;
            -webkit-text-stroke-width: 0px !important;
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
        .bg-cyan-600\/80 {
            background-color: var(--accent-blue) !important;
        }

        .text-cyan-400 {
            color: var(--accent-blue) !important;
        }
    </style>
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            vapidPublicKey: '{{ config('webpush.vapid.public_key') }}'
        };
    </script>
    <script src="{{ asset('js/webpush.js') }}"></script>
    <script>
        function shouldBeCustom() {
            let localApp = window.localStorage.getItem('flux.appearance');
            let dbApp = '{{ auth()->user()->appearance ?? "dark" }}';
            
            // Sync logic: If local storage is empty, use DB. If DB is set and different, prioritize DB for first load.
            if (!localApp) {
                window.localStorage.setItem('flux.appearance', dbApp);
                localApp = dbApp;
            }

            // Force all legacy states to custom since we removed the mode selector
            if (localApp !== 'custom') {
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
            if (window.Flux && window.Flux.applyAppearance && !window.Flux.applyAppearance.isPatched) {
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
    @if(auth()->check() && auth()->user()->role !== 'reader')
    @php
        $isConsumer = auth()->user()->role === 'consumer';
    @endphp
    @if($isConsumer)
    <flux:sidebar collapsible collapsed
        class="dark border-e border-[#1e293b] bg-[#0b121c] bg-opacity-65 backdrop-blur-2xl shadow-2xl">
    @else
    <flux:sidebar collapsible
        class="dark border-e border-[#1e293b] bg-[#0b121c] bg-opacity-65 backdrop-blur-2xl shadow-2xl">
    @endif
        <flux:sidebar.header class="relative !flex !flex-col !items-center !h-auto !min-h-0 pt-4 pb-3 px-2">
            <!-- Collapse button positioned at top right -->
            <div class="absolute right-2 top-2 z-10">
                <flux:sidebar.collapse class="text-gray-400 hover:text-white" />
            </div>

            <!-- Brand: Logo Icon centered on TOP of the text -->
            <a href="{{ route('dashboard') }}" wire:navigate class="flex flex-col items-center group w-full text-center dwss-logo-container transition-all duration-300">
                <!-- Logo Icon on the Top -->
                <div class="flex aspect-square size-16 items-center justify-center rounded-full bg-white/10 p-1 shadow-[0_0_25px_rgba(34,211,238,0.35)] border-2 border-cyan-400/50 group-hover:scale-105 transition-all duration-300 dwss-logo shrink-0">
                    <x-app-logo-icon class="size-13 dwss-logo-icon transition-all duration-300" />
                </div>

                <!-- Text Bigger Directly Below Logo -->
                <div class="dwss-brand-text flex items-center justify-center w-full mt-2.5">
                    <span class="text-4xl sm:text-[44px] font-black tracking-widest uppercase drop-shadow-[0_0_18px_rgba(34,211,238,0.9)] inline-block transition-all duration-300 group-hover:scale-105" 
                        style="color: #22d3ee !important; font-family: 'Inter', system-ui, sans-serif !important; -webkit-text-stroke: 0 !important;">
                        D.W.S.S
                    </span>
                </div>
            </a>
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group class="grid">
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    <span>{{ __('Dashboard') }}</span>
                </flux:sidebar.item>

                @if(auth()->user()->role === 'admin')
                    <flux:sidebar.item icon="users" :href="route('customers.index')"
                        :current="request()->routeIs('customers.*')" wire:navigate>
                        <span>{{ __('Consumers') }}</span>
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="list-bullet" :href="route('billing.index')"
                        :current="request()->routeIs('billing.*')" wire:navigate>
                        <span>{{ __('Billing Reports') }}</span>
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="adjustments-horizontal" :href="route('settings.index')"
                        :current="request()->routeIs('settings.*')" wire:navigate>
                        <span>{{ __('Settings') }}</span>
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="key" :href="route('registration-codes.index')"
                        :current="request()->routeIs('registration-codes.*')" wire:navigate>
                        <span>{{ __('Registration Codes') }}</span>
                    </flux:sidebar.item>
                @endif

                @php
                    $unreadCount = \App\Models\Message::where('receiver_id', auth()->id())->whereNull('read_at')->count();
                @endphp
                <flux:sidebar.item icon="chat-bubble-left" :href="route('messages.index')"
                    :current="request()->routeIs('messages.*')" wire:navigate
                    :badge="$unreadCount > 0 ? $unreadCount : null"
                    badge-color="red"
                    class="{{ $unreadCount > 0 ? 'message-has-unread' : '' }}"
                >
                    @if(auth()->user()->role === 'admin')
                        <span>{{ __('Message & Posting') }}</span>
                    @else
                        <span>{{ __('Messages') }}</span>
                    @endif
                </flux:sidebar.item>

                @if(auth()->user()->role === 'consumer')
                    @php
                        $announcementCount = \App\Models\Post::count();
                    @endphp
                    <flux:sidebar.item icon="megaphone" :href="route('consumer.announcements')"
                        :current="request()->routeIs('consumer.announcements')" wire:navigate
                        :badge="$announcementCount > 0 ? $announcementCount : null"
                        badge-color="red"
                    >
                        <span>{{ __('Announcements') }}</span>
                    </flux:sidebar.item>
                @endif

                @if(auth()->user()->role === 'admin')
                    <flux:sidebar.item icon="trash" :href="route('recovery.index')"
                        :current="request()->routeIs('recovery.*')" wire:navigate>
                        <span>{{ __('Recovery / Trash') }}</span>
                    </flux:sidebar.item>

                @endif
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />


        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>
    @endif

    <!-- Global Header -->
    @if(auth()->check() && auth()->user()->role !== 'reader')
    <flux:header class="{{ auth()->user()->role === 'admin' ? 'lg:hidden' : '' }}">
        <flux:sidebar.toggle icon="bars-2" inset="left" />

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
                                @if(auth()->user()->customer)
                                    <flux:text class="truncate">Account Number: {{ auth()->user()->customer->customer_id }}</flux:text>
                                @else
                                    <flux:text class="truncate text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</flux:text>
                                @endif
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
    @endif

    <!-- Historical / Custom System Date Banner -->
    @php
        $activeCustomSystemDate = \App\Models\SystemSetting::get('system_date');
    @endphp
    @if(!empty($activeCustomSystemDate) && auth()->check() && in_array(auth()->user()->role, ['admin', 'staff']))
        <div class="bg-gradient-to-r from-amber-600 via-amber-500 to-yellow-600 text-white px-4 py-2 text-xs font-semibold flex flex-wrap items-center justify-between gap-2 shadow-lg sticky top-0 z-40 backdrop-blur-md border-b border-amber-400/30">
            <div class="flex items-center gap-2">
                <span class="p-1 bg-white/20 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <span>
                    <strong>Historical Record Mode Active:</strong> System date is currently set to <strong>{{ \Carbon\Carbon::parse($activeCustomSystemDate)->format('F d, Y') }}</strong>. All new readings, bills, and calculations will use this date.
                </span>
            </div>
            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('settings.reset-date') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 bg-white text-amber-900 hover:bg-amber-100 rounded-lg font-bold text-[11px] shadow-sm transition-all active:scale-95">
                        Reset to Real-Time
                    </button>
                </form>
                <a href="{{ route('settings.index') }}" class="underline text-white hover:text-white/80 text-[11px] font-medium ml-1">
                    System Settings
                </a>
            </div>
        </div>
    @endif

    {{ $slot }}

    @fluxScripts
</body>

</html>