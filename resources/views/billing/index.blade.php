<x-layouts::app title="Billing Reports">
    <div class="px-6 py-4 bg-transparent min-h-screen font-sans text-gray-300">
        
        <h1 class="text-3xl font-bold mb-6 text-gray-200">Billing Reports</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-emerald-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-emerald-500/70 uppercase tracking-widest mb-1">Paid Bills</p>
                    <h3 class="text-3xl font-black text-white tracking-tight">{{ $paidCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-emerald-500/10 transform -rotate-12 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-rose-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-rose-500/70 uppercase tracking-widest mb-1">Pending Bills</p>
                    <h3 class="text-3xl font-black text-white tracking-tight">{{ $pendingCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-rose-500/10 transform rotate-12 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-emerald-400/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-emerald-400/70 uppercase tracking-widest mb-1">Paid Customers</p>
                    <h3 class="text-3xl font-black text-white tracking-tight">{{ $paidCustomersCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-emerald-400/10 transform rotate-6 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-orange-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-orange-500/70 uppercase tracking-widest mb-1">Unpaid Customers</p>
                    <h3 class="text-3xl font-black text-white tracking-tight">{{ $unpaidCustomersCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-orange-500/10 transform -rotate-6 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full max-w-lg">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" placeholder="Search by Consumer No. or Name" class="w-full pl-10 pr-4 py-2 bg-[#121a25]/60 border border-[#263548] rounded-xl focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-all duration-300">
            </div>
            
            <div class="flex items-center gap-4 bg-[#1b2636]/40 p-1 rounded-2xl border border-[#2d4059]/50">
                <div class="px-4 py-2 text-sm">
                    <span class="text-gray-500 uppercase tracking-widest text-[10px] font-bold block">Total Billed</span>
                    <span class="text-xl font-bold text-white tracking-tight">₱{{ number_format($totalBilled, 2) }}</span>
                </div>
            </div>
        </div>

        <h2 class="text-lg font-semibold mb-3 text-gray-200">Pending Bills</h2>
        
        <div class="bg-[#121a25]/80 backdrop-blur-md rounded-2xl shadow-sm overflow-x-auto mb-4 border border-[#263548] scrollbar-thin scrollbar-thumb-blue-500/30 scrollbar-track-transparent">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-blue-600/90 text-white">
                        <th class="px-4 py-3 font-medium">Period</th>
                        <th class="px-4 py-3 font-medium">No.</th>
                        <th class="px-4 py-3 font-medium">Customer</th>
                        <th class="px-4 py-3 font-medium">Usage</th>
                        <th class="px-4 py-3 font-medium">Bill</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-100">
                    @forelse($pendingBills as $bill)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-4 py-3">{{ $bill->billing_date->format('F Y') }}</td>
                        <td class="px-4 py-3">{{ $bill->customer?->customer_number ?? $bill->customer?->id ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $bill->customer?->name ?? 'Deleted Customer' }}</td>
                        @php
                            $cType = $bill->customer?->type ?? 'Regular';
                            $greenMax = $thresholds[$cType]['green_max'] ?? 10;
                            $orangeMax = $thresholds[$cType]['orange_max'] ?? 20;
                            $usage = $bill->consumption ?? 0;
                            $bgClass = $usage <= $greenMax ? 'bg-emerald-500' : ($usage <= $orangeMax ? 'bg-amber-500' : 'bg-red-500');
                        @endphp
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium text-white shadow-sm {{ $bgClass }}">
                                {{ $usage }} L
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-300">₱{{ number_format($bill->total_amount, 0) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <form action="{{ route('billing.mark-paid', $bill) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded text-sm font-medium shadow-sm transition-transform hover:scale-105">
                                        Mark as Paid
                                    </button>
                                </form>

                                <a href="{{ route('billing.show', $bill) }}" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors border border-blue-200" title="View Bill">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                </a>

                                <a href="{{ route('billing.edit', $bill) }}" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors border border-amber-200" title="Edit Bill">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <a href="{{ route('billing.receipt', $bill) }}" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors border border-amber-200" title="Print Bill">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </a>

                                <form action="{{ route('billing.destroy', $bill) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this bill?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors border border-rose-200" title="Delete Bill">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400 italic">No pending bills</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mb-8">
            {{ $pendingBills->links() }}
        </div>

        <h2 class="text-xl font-bold mb-3 text-gray-200">Payment History (Paid)</h2>
        
        <div class="bg-[#121a25]/80 backdrop-blur-md rounded-2xl shadow-sm overflow-x-auto mb-4 border border-[#263548] scrollbar-thin scrollbar-thumb-blue-500/30 scrollbar-track-transparent">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-blue-600/90 text-white">
                        <th class="px-4 py-3 font-medium">Period</th>
                        <th class="px-4 py-3 font-medium">No.</th>
                        <th class="px-4 py-3 font-medium">Customer</th>
                        <th class="px-4 py-3 font-medium">Usage</th>
                        <th class="px-4 py-3 font-medium">Bill</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-100">
                    @forelse($paidBills as $bill)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-4 py-3">{{ $bill->billing_date->format('F Y') }}</td>
                        <td class="px-4 py-3">{{ $bill->customer?->customer_number ?? $bill->customer?->id ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $bill->customer?->name ?? 'Deleted Customer' }}</td>
                        @php
                            $cType = $bill->customer?->type ?? 'Regular';
                            $greenMax = $thresholds[$cType]['green_max'] ?? 10;
                            $orangeMax = $thresholds[$cType]['orange_max'] ?? 20;
                            $usage = $bill->consumption ?? 0;
                            $bgClass = $usage <= $greenMax ? 'bg-emerald-500' : ($usage <= $orangeMax ? 'bg-amber-500' : 'bg-red-500');
                        @endphp
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium text-white shadow-sm {{ $bgClass }}">
                                {{ $usage }} L
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-300">₱{{ number_format($bill->total_amount, 0) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('billing.show', $bill) }}" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors border border-blue-200" title="View Bill">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                </a>

                                <a href="{{ route('billing.receipt', $bill) }}" class="p-1.5 text-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors border border-emerald-200" title="Print Receipt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </a>

                                <form action="{{ route('billing.destroy', $bill) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this bill?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors border border-rose-200" title="Delete Bill">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400 italic">No payment history</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>
            {{ $paidBills->links() }}
        </div>
    </div>
</x-layouts::app>

