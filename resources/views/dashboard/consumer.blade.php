<x-layouts::app title="Dashboard">
    <div class="flex flex-col py-4 sm:py-8 px-1 sm:px-6 lg:px-8 w-full font-sans min-h-[calc(100vh-4rem)]">
        
        <!-- Header -->
        <div class="mb-6 sm:mb-8 px-2 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-[24px] sm:text-[28px] font-bold text-gray-100 tracking-tight flex items-center">
                    Welcome back, <span class="text-blue-600 ml-2">{{ auth()->user()->name }}</span>
                </h1>
                <p class="mt-1 sm:mt-2 text-[14px] sm:text-[15px] text-gray-200 font-medium">View your billing history and latest announcements.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 mx-2 animate-fade-in-down flex items-center bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl shadow-sm">
                <svg class="h-6 w-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[15px] font-semibold">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 mx-2 animate-fade-in-down flex items-center bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl shadow-sm">
                <svg class="h-6 w-6 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[15px] font-semibold">{{ session('error') }}</span>
            </div>
        @endif



        <div class="grid grid-cols-1 gap-6 sm:gap-8 flex-1 mb-8 mx-0 sm:mx-2 items-start">
            
            <!-- Payment History Section -->
            <div class="bg-[#121a25]/80 backdrop-blur-md shadow-[0_4px_20px_rgb(0,0,0,0.03)] rounded-2xl sm:rounded-3xl overflow-hidden border border-[#263548]">
                <div class="p-3 sm:p-6 border-b border-[#263548] flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-100 tracking-tight flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Billing Overview
                    </h2>
                </div>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-full">
                        <thead class="bg-[#0f1722]/80 text-[10px] sm:text-[12px]">
                            <tr>
                                <th class="px-1 sm:px-6 py-3 font-bold text-gray-200 uppercase tracking-widest border-b border-[#263548]">Date</th>
                                <th class="px-1 sm:px-6 py-3 font-bold text-gray-200 uppercase tracking-widest border-b border-[#263548]">Usage</th>
                                <th class="px-1 sm:px-6 py-3 font-bold text-gray-200 uppercase tracking-widest border-b border-[#263548]">Total</th>
                                <th class="px-1 sm:px-6 py-3 font-bold text-gray-200 uppercase tracking-widest border-b border-[#263548]">Status</th>
                                <th class="px-1 sm:px-6 py-3 font-bold text-gray-200 uppercase tracking-widest border-b border-[#263548] text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @if(isset($customer) && $customer->bills->count() > 0)
                                @foreach($customer->bills as $bill)
                                    <tr class="hover:bg-blue-50/30 transition-colors">
                                        <td class="px-1 sm:px-6 py-4 whitespace-nowrap text-[11px] sm:text-[15px] font-semibold text-gray-200 tracking-tight">{{ \Carbon\Carbon::parse($bill->billing_date)->format('M d, y') }}</td>
                                        <td class="px-1 sm:px-6 py-4 whitespace-nowrap text-[11px] sm:text-[15px] font-medium text-gray-200">{{ $bill->usage_units }} <span class="text-[9px] sm:text-xs">m³</span></td>
                                        <td class="px-1 sm:px-6 py-4 whitespace-nowrap text-[11px] sm:text-[15px] font-bold text-gray-100">₱{{ number_format($bill->total_amount, 0) }}</td>
                                        <td class="px-1 sm:px-6 py-4 whitespace-nowrap">
                                            @if(strtolower($bill->status) === 'paid')
                                                <span class="px-1.5 sm:px-3 py-1 inline-flex text-[9px] sm:text-xs font-bold rounded-full bg-green-100 text-green-700 shadow-sm border border-green-200 uppercase tracking-wide">Paid</span>
                                            @else
                                                <span class="px-1.5 sm:px-3 py-1 inline-flex text-[9px] sm:text-xs font-bold rounded-full bg-red-100 text-red-700 shadow-sm border border-red-200 uppercase tracking-wide">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-1 sm:px-6 py-4 whitespace-nowrap text-right">
                                            @if(strtolower($bill->status) !== 'paid')
                                                <a href="{{ route('billing.receipt', $bill->id) }}" class="text-amber-700 font-bold text-[9px] sm:text-xs uppercase tracking-wide border border-amber-200 bg-amber-50 hover:bg-amber-100 hover:border-amber-300 rounded-lg px-1.5 sm:px-3 py-1 sm:py-1.5 inline-flex items-center text-center shadow-sm transition-all ml-auto w-max">
                                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="hidden sm:inline">Statement</span>
                                                    <span class="sm:hidden">View</span>
                                                </a>
                                            @else
                                                <a href="{{ route('billing.receipt', $bill->id) }}" class="text-green-700 font-bold text-[9px] sm:text-xs uppercase tracking-wide border border-green-200 bg-green-50 hover:bg-green-100 hover:border-green-300 rounded-lg px-1.5 sm:px-3 py-1 sm:py-1.5 inline-flex items-center text-center shadow-sm transition-all ml-auto w-max">
                                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="hidden sm:inline">Receipt</span>
                                                    <span class="sm:hidden">Print</span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#0f1722] mb-3 border border-[#263548]">
                                            <svg class="w-6 h-6 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-[15px] font-medium text-gray-200">No billing records found.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.4);
        border-radius: 10px;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Fullscreen specific tweaks */
    :fullscreen main {
        padding: 2rem !important;
        background-color: #020617 !important;
    }
</style>

<script>
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
            });
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }

    // Auto-fullscreen attempt on user interaction if they just logged in
    @if(session('success') && str_contains(session('success'), 'logged in'))
    document.addEventListener('click', function autoFS() {
        toggleFullscreen();
        document.removeEventListener('click', autoFS);
    }, { once: true });
    @endif
</script>
</x-layouts::app>

