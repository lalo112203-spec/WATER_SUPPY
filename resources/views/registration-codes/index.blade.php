<x-layouts::app title="Registration Codes">
    <div class="px-6 py-4 bg-transparent min-h-[calc(100vh-4rem)] font-sans text-gray-200 relative z-10">

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-100 flex items-center gap-2 drop-shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 text-cyan-400 drop-shadow-[0_0_8px_rgba(6,182,212,0.8)]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    Registration Codes
                </h1>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-4">
                <form action="{{ route('registration-codes.index') }}" method="GET" class="relative group w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 group-focus-within:text-cyan-400 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search code or user..." 
                        class="w-full pl-9 pr-10 py-2 bg-[#1b2636]/60 border border-[#2d4059]/50 rounded-xl focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 transition-all duration-300 text-sm text-gray-200">
                    @if(request('search'))
                        <a href="{{ route('registration-codes.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-rose-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </form>

                <form action="{{ route('registration-codes.store') }}" method="POST" class="w-full md:w-auto">
                    @csrf
                    <button type="submit"
                        class="bg-cyan-600/80 hover:bg-cyan-500 border border-cyan-400/50 text-white px-4 py-2 rounded-xl font-medium shadow-[0_0_15px_rgba(6,182,212,0.3)] flex items-center justify-center gap-2 transition duration-300 backdrop-blur-sm w-full md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Generate New Code
                    </button>
                </form>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-900/40 border border-emerald-500/50 text-emerald-200 rounded-2xl backdrop-blur-md">
                {{ session('status') }}
            </div>
        @endif

        <div
            class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-x-auto border border-[#263548] scrollbar-thin scrollbar-thumb-cyan-500/30 scrollbar-track-transparent">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#0f1722] text-[#94a3b8] uppercase text-xs tracking-wider">
                        <th class="px-6 py-4 font-semibold border-b border-[#263548]">Registration Code</th>
                        <th class="px-6 py-4 font-semibold border-b border-[#263548]">Status</th>
                        <th class="px-6 py-4 font-semibold border-b border-[#263548]">Used By (Customer)</th>
                        <th class="px-6 py-4 font-semibold border-b border-[#263548]">Created At</th>
                        <th class="px-6 py-4 font-semibold text-right border-b border-[#263548]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#263548]">
                    @forelse($codes as $code)
                        <tr class="hover:bg-[#1b2636]/60 transition duration-300 {{ $code->is_used ? 'opacity-50 grayscale-[0.5]' : '' }}">
                            <td class="px-6 py-4 font-bold text-xl {{ $code->is_used ? 'text-gray-500' : 'text-cyan-400' }} tracking-widest font-mono">
                                {{ $code->code }}
                            </td>
                            <td class="px-6 py-4">
                                @if($code->is_used)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-900/40 text-rose-300 border border-rose-700/50">
                                        Used
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-900/40 text-emerald-300 border border-emerald-700/50">
                                        Available
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                @if($code->user)
                                    <div class="flex items-center gap-2">
                                        <div class="font-medium">{{ $code->user->name }}</div>
                                        <span class="text-xs text-gray-500">Account Number: {{ $code->user->customer_id ?? $code->user->id }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-500 italic">Not used yet</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ $code->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('registration-codes.destroy', $code) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this code?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-rose-400 bg-rose-900/20 hover:bg-rose-600/30 rounded-lg transition duration-300 border border-rose-700/30 shadow-sm"
                                        title="Delete Code">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-[#263548] mb-3"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                <p class="text-lg font-medium text-gray-300">No registration codes generated</p>
                                <p class="text-sm mt-1 text-gray-500">Generate codes to allow new customers to register.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $codes->links() }}
        </div>
    </div>
    <script>
        // Debounced search auto-submit
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput && searchInput.form && searchInput.form.action.includes('registration-codes')) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 800);
            });
        }
    </script>
</x-layouts::app>
