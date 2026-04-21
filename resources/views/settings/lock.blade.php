<x-layouts::app title="Access Restricted">
    <div class="min-h-[calc(100vh-8rem)] flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-2xl border border-[#263548] p-10 relative overflow-hidden">
             <div class="absolute -right-20 -top-20 bg-blue-600/10 h-64 w-64 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col items-center text-center relative z-10 transition-all duration-500">
                <div class="w-20 h-20 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-6 ring-1 ring-blue-500/30 shadow-[0_0_20px_rgba(59,130,246,0.2)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <h1 class="text-2xl font-black text-white mb-2 uppercase tracking-tight">System Locked</h1>
                <p class="text-gray-400 text-sm mb-8 leading-relaxed">
                    You are entering a sensitive configuration area. To proceed, please verify your identity with your administrator password.
                </p>

                <form method="POST" action="{{ route('settings.authorize') }}" class="w-full space-y-5">
                    @csrf
                    <div class="space-y-1">
                        <input name="password" type="password" required autofocus
                            placeholder="Enter Master Password"
                            class="w-full bg-[#1b2636]/60 border border-[#2d4059] focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 text-white text-center py-4 px-4 rounded-2xl outline-none transition-all placeholder:text-gray-600 font-mono tracking-widest">
                        
                        @if ($errors->any())
                            <p class="text-rose-500 text-xs mt-2 font-bold animate-pulse">
                                {{ $errors->first() }}
                            </p>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center justify-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                        Unlock System
                    </button>
                </form>

                <div class="mt-10 pt-6 border-t border-[#263548] w-full">
                    <a href="{{ route('dashboard') }}" class="text-xs text-gray-500 hover:text-white transition-colors">
                        &larr; Return to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
