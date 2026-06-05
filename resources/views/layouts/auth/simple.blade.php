<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <script>
            // Force dark mode for auth pages
            document.documentElement.classList.add('dark');
            const authObserver = new MutationObserver(() => {
                if (!document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.add('dark');
                }
            });
            authObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        </script>
    </head>
    <body class="min-h-screen bg-gradient-to-br from-cyan-50 to-blue-100 antialiased dark:bg-linear-to-b dark:from-sky-950 dark:to-blue-900 relative">
        <!-- Optional water decoration using CSS background pattern -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-blue-300 via-transparent to-transparent pointer-events-none"></div>
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10 relative z-10">
            <div class="flex w-full max-w-sm flex-col gap-6 bg-[#121a25]/80 backdrop-blur-md dark:bg-zinc-900 p-8 rounded-2xl shadow-2xl border border-blue-100 dark:border-sky-800">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-16 w-16 mb-2 items-center justify-center rounded-full bg-white/10/10 p-0.5 shadow-md border border-white/20">
                        <x-app-logo-icon class="size-14" />
                    </span>
                    <span class="text-2xl font-bold text-white tracking-widest uppercase drop-shadow-[0_0_8px_rgba(6,182,212,0.8)] text-center">D.W.S.S</span>
                    <span class="text-xs font-medium text-cyan-400 -mt-1 tracking-[0.2em] uppercase opacity-80">Dolores Water Supply Service</span>
                </a>
                <div class="flex flex-col gap-6 w-full">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>

