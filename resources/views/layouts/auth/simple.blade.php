<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-br from-cyan-50 to-blue-100 antialiased dark:bg-linear-to-b dark:from-sky-950 dark:to-blue-900 relative">
        <!-- Optional water decoration using CSS background pattern -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-blue-300 via-transparent to-transparent pointer-events-none"></div>
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10 relative z-10">
            <div class="flex w-full max-w-sm flex-col gap-6 bg-white dark:bg-zinc-900 p-8 rounded-2xl shadow-2xl border border-blue-100 dark:border-sky-800">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-12 w-12 mb-2 items-center justify-center rounded-xl bg-blue-500 text-white shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S12 3 12 3s-4.5 3.97-4.5 9 2.015 9 4.5 9Z" />
                        </svg>
                    </span>
                    <span class="text-xl font-bold text-blue-900 dark:text-blue-100">Water System</span>
                </a>
                <div class="flex flex-col gap-6 w-full">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
