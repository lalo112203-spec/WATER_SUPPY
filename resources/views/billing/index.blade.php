<x-layouts::app title="Billing Reports">
    <div class="px-4 sm:px-6 py-4 bg-transparent min-h-screen font-sans text-gray-300">
        
        <h1 class="text-3xl font-bold mb-6 text-gray-200">Billing Reports</h1>

        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-5 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-rose-500/10 border border-rose-500/30 text-rose-300 px-5 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if(session('billing_warning'))
            @php $bWarn = session('billing_warning'); @endphp
            <div class="mb-6 bg-amber-500/10 border border-amber-500/30 text-amber-300 px-5 py-3 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="text-sm">A bill of <strong>₱{{ $bWarn['amount'] }}</strong> already exists for <strong>{{ $bWarn['customer_name'] }}</strong> for <strong>{{ $bWarn['month'] }}</strong>.</span>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    @if(!empty($bWarn['customer_id']) && isset($bWarn['new_reading']))
                        <form method="POST" action="{{ route('billing.store') }}" class="inline">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $bWarn['customer_id'] }}">
                            <input type="hidden" name="new_reading" value="{{ $bWarn['new_reading'] }}">
                            <input type="hidden" name="consumption" value="{{ $bWarn['consumption'] ?? '' }}">
                            <input type="hidden" name="base_charge" value="{{ $bWarn['base_charge'] ?? '' }}">
                            <input type="hidden" name="usage_charge" value="{{ $bWarn['usage_charge'] ?? '' }}">
                            <input type="hidden" name="additional_charge_amount" value="{{ $bWarn['additional_charge_amount'] ?? '' }}">
                            <input type="hidden" name="force_billing" value="1">
                            <button type="submit" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-black font-bold text-xs rounded-xl transition shadow-sm">
                                Proceed Anyway
                            </button>
                        </form>
                    @endif
                    <button type="button" onclick="this.closest('.mb-6').remove()" class="text-xs bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 px-3 py-1.5 rounded-lg border border-amber-500/30 transition">Dismiss</button>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-emerald-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-emerald-300 uppercase tracking-widest mb-1 drop-shadow-sm">Paid Bills</p>
                    <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-md">{{ $paidCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-emerald-400/20 transform -rotate-12 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-rose-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-rose-300 uppercase tracking-widest mb-1 drop-shadow-sm">Pending Bills</p>
                    <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-md">{{ $pendingCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-rose-400/20 transform rotate-12 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-emerald-400/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-emerald-300 uppercase tracking-widest mb-1 drop-shadow-sm">Paid Consumers</p>
                    <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-md">{{ $paidCustomersCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-emerald-400/20 transform rotate-6 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-orange-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-orange-300 uppercase tracking-widest mb-1 drop-shadow-sm">Unpaid Consumers</p>
                    <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-md">{{ $unpaidCustomersCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-orange-400/20 transform -rotate-6 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
            <form action="{{ route('billing.index') }}" method="GET" class="relative w-full max-w-lg group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-200 group-focus-within:text-blue-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Account Number or Name" 
                    class="w-full pl-10 pr-12 py-3 bg-[#121a25]/60 border border-[#263548] rounded-xl focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-all duration-300 text-gray-200">
                @if(request('search'))
                    <a href="{{ route('billing.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-200 hover:text-rose-400 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </form>
            
            <div class="flex items-center gap-4 bg-[#1b2636]/40 p-2 rounded-2xl border border-[#2d4059]/50">
                <div class="px-6 py-1 text-sm">
                    <span class="text-gray-200 uppercase tracking-widest text-[10px] font-bold block">Total Billed</span>
                    <span class="text-2xl font-bold text-white tracking-tight">₱{{ number_format($totalBilled, 0) }}</span>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-semibold text-gray-200">Pending Bills</h2>
            <div class="flex items-center gap-2">
                <button type="button" onclick="safeShowModal('create-bill-modal')" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-sm font-semibold transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Generate Bill
                </button>
                <button type="button" onclick="submitPrintBatch()" class="flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-semibold transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Selected
                </button>
            </div>
        </div>
        
        <form id="printBatchForm" action="{{ route('billing.print-batch') }}" method="POST" target="_blank">
            @csrf
        </form>
        <div class="bg-[#121a25]/80 backdrop-blur-md rounded-2xl shadow-sm overflow-x-auto mb-4 border border-[#263548] scrollbar-thin scrollbar-thumb-blue-500/30 scrollbar-track-transparent">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-blue-600/90 text-white">
                            <th class="px-4 py-3 font-medium w-12">
                                <input type="checkbox" id="selectAllPending" class="rounded border-blue-400 bg-transparent text-blue-500 focus:ring-blue-500" onclick="document.querySelectorAll('.bill-checkbox').forEach(cb => cb.checked = this.checked)">
                            </th>
                        <th class="px-4 py-3 font-medium">Period</th>
                        <th class="px-4 py-3 font-medium">Account Number</th>
                        <th class="px-4 py-3 font-medium">Consumer</th>
                        <th class="px-4 py-3 font-medium">Usage</th>
                        <th class="px-4 py-3 font-medium">Bill</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-100">
                    @forelse($pendingBills as $bill)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-4 py-3">
                            <input type="checkbox" name="bill_ids[]" value="{{ $bill->id }}" class="bill-checkbox rounded border-gray-500 bg-transparent text-blue-500 focus:ring-blue-500" form="printBatchForm">
                        </td>
                        <td class="px-4 py-3">{{ $bill->billing_date->format('F Y') }}</td>
                        <td class="px-4 py-3">{{ str_replace('CUST', '', $bill->customer?->customer_id ?? 'N/A') }}</td>
                        <td class="px-4 py-3">{{ str_replace('Dummy Consumer ', '', $bill->customer?->name ?? 'Deleted Consumer') }}</td>
                        @php
                            $cType = $bill->customer?->type ?? 'Regular';
                            $greenMax = $thresholds[$cType]['green_max'] ?? 10;
                            $orangeMax = $thresholds[$cType]['orange_max'] ?? 20;
                            $usage = $bill->consumption ?? 0;
                            $bgClass = $usage <= $greenMax ? 'bg-emerald-500' : ($usage <= $orangeMax ? 'bg-amber-500' : 'bg-red-500');
                        @endphp
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium text-white shadow-sm {{ $bgClass }}">
                                {{ $usage }} m³
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
                        <td colspan="7" class="px-4 py-6 text-center text-gray-200 italic">No pending bills</td>
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
                        <th class="px-4 py-3 font-medium">Account Number</th>
                        <th class="px-4 py-3 font-medium">Consumer</th>
                        <th class="px-4 py-3 font-medium">Usage</th>
                        <th class="px-4 py-3 font-medium">Bill</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-100">
                    @forelse($paidBills as $bill)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-4 py-3">{{ $bill->billing_date->format('F Y') }}</td>
                        <td class="px-4 py-3">{{ str_replace('CUST', '', $bill->customer?->customer_id ?? 'N/A') }}</td>
                        <td class="px-4 py-3">{{ str_replace('Dummy Consumer ', '', $bill->customer?->name ?? 'Deleted Consumer') }}</td>
                        @php
                            $cType = $bill->customer?->type ?? 'Regular';
                            $greenMax = $thresholds[$cType]['green_max'] ?? 10;
                            $orangeMax = $thresholds[$cType]['orange_max'] ?? 20;
                            $usage = $bill->consumption ?? 0;
                            $bgClass = $usage <= $greenMax ? 'bg-emerald-500' : ($usage <= $orangeMax ? 'bg-amber-500' : 'bg-red-500');
                        @endphp
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium text-white shadow-sm {{ $bgClass }}">
                                {{ $usage }} m³
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-300">₱{{ number_format($bill->total_amount, 0) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
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
                        <td colspan="6" class="px-4 py-6 text-center text-gray-200 italic">No payment history</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>
            {{ $paidBills->links() }}
        </div>
    </div>
    <!-- Create Bill Modal -->
    <flux:modal id="create-bill-modal" name="create-bill-modal" class="md:w-[520px] !bg-[#121a25] !border !border-[#2d4059] !text-gray-200">
        <div class="p-4 bg-[#121a25] text-gray-200 rounded-xl max-h-[85vh] overflow-y-auto custom-scrollbar">
            <flux:heading size="lg" class="mb-2 !text-white">Generate Consumer Bill</flux:heading>
            <flux:subheading class="mb-4 !text-gray-400">Record a new meter reading and calculate charges</flux:subheading>

            <div id="billing-modal-duplicate-warning" class="hidden mb-4 bg-amber-500/10 border border-amber-500/30 text-amber-300 p-3.5 rounded-xl text-xs flex flex-col gap-2">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span id="billing-modal-duplicate-warning-text"></span>
                </div>
                <label class="flex items-center gap-2 cursor-pointer pt-2 border-t border-amber-500/20 text-amber-200 font-semibold">
                    <input type="checkbox" id="billing-modal-force-checkbox" onchange="document.getElementById('billing_modal_force_billing').value = this.checked ? '1' : '0'" class="rounded border-amber-500 bg-[#0f1722] text-amber-500 focus:ring-amber-500">
                    <span>Generate another bill for this month anyway</span>
                </label>
            </div>

            <form action="{{ route('billing.store') }}" method="POST" id="billing-create-form">
                @csrf
                <input type="hidden" name="force_billing" id="billing_modal_force_billing" value="0">
                <input type="hidden" name="consumption" id="billing_modal_consumption_hidden">

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Select Consumer <span class="text-red-500">*</span></label>
                        <select name="customer_id" id="billing_modal_customer_id" required onchange="onBillingCustomerChange()" class="w-full bg-[#0f1722] border border-[#2d4059] text-gray-200 px-3 py-2.5 rounded-xl text-sm outline-none focus:border-emerald-500">
                            <option value="" disabled selected>-- Select an active consumer --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" data-name="{{ $c->name }}" data-type="{{ $c->type }}" data-reading="{{ $c->meter_reading ?? 0 }}">
                                    {{ $c->customer_id }} - {{ $c->name }} ({{ $c->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="bg-[#1b2636]/40 p-4 rounded-xl border border-[#2d4059]/50">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-medium text-gray-300">Previous Reading:</span>
                            <span id="billing_modal_prev_reading" class="font-mono font-bold text-gray-200 text-lg">0</span>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="billing_modal_present_reading" class="block text-sm font-medium text-gray-300">Present Reading (m³) <span class="text-red-500">*</span></label>
                            <input type="number" step="any" id="billing_modal_present_reading" name="new_reading" required
                                oninput="calculateBillingCharges()" placeholder="Enter reading..."
                                class="w-full bg-[#0f1722]/80 border border-[#2d4059] focus:border-emerald-500/50 text-emerald-400 placeholder-gray-500 text-2xl font-black rounded-xl py-3 px-4 outline-none transition-all duration-300 shadow-inner">
                        </div>

                        <div id="billing_modal_calc_breakdown" class="text-xs mt-3 min-h-[1.25rem] text-zinc-500"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Base Charge (₱)</label>
                            <input type="number" step="0.01" name="base_charge" id="billing_modal_base_charge" oninput="updateBillingTotal()" class="w-full bg-[#0f1722] border border-[#2d4059] text-gray-200 px-3 py-2 rounded-xl text-sm outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Usage Charge (₱)</label>
                            <input type="number" step="0.01" name="usage_charge" id="billing_modal_usage_charge" oninput="updateBillingTotal()" class="w-full bg-[#0f1722] border border-[#2d4059] text-gray-200 px-3 py-2 rounded-xl text-sm outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Billing Date</label>
                            <input type="date" name="billing_date" value="{{ now()->format('Y-m-d') }}" class="w-full bg-[#0f1722] border border-[#2d4059] text-gray-200 px-3 py-2 rounded-xl text-sm outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Due Date</label>
                            <input type="date" name="due_date" value="{{ now()->addDays(30)->format('Y-m-d') }}" class="w-full bg-[#0f1722] border border-[#2d4059] text-gray-200 px-3 py-2 rounded-xl text-sm outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <div class="bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/30 flex justify-between items-center px-6">
                        <span class="text-emerald-500 font-bold uppercase tracking-widest text-sm">Total Bill</span>
                        <div class="text-right">
                            <span class="text-emerald-400 font-bold text-3xl">₱<span id="billing_modal_total_display">0</span></span>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" id="billing_modal_submit_btn" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-all font-bold shadow-lg shadow-emerald-600/20 flex items-center justify-center">Generate Bill</button>
                        <flux:modal.close>
                            <flux:button variant="ghost" class="px-6 !border !border-[#2d4059] !text-gray-300 hover:!bg-[#1b2636] hover:!text-white">Cancel</flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            </form>
        </div>
    </flux:modal>

    <script>
        function safeShowModal(name) {
            try {
                if (typeof window.Flux !== 'undefined' && typeof window.Flux.modal === 'function') {
                    window.Flux.modal(name).show();
                }
            } catch (e) {}

            try {
                document.dispatchEvent(new CustomEvent('modal-show', { detail: { name: name } }));
            } catch (e) {}

            try {
                const dialog = document.querySelector(`dialog[data-modal="${name}"], [data-modal="${name}"], #${name}, [name="${name}"]`);
                if (dialog) {
                    if (typeof dialog.showModal === 'function') {
                        if (!dialog.open) dialog.showModal();
                    } else {
                        dialog.classList.remove('hidden');
                        dialog.style.display = 'block';
                    }
                }
            } catch (e) {}
        }

        const billingSettings = {!! json_encode($settings) !!};
        const billingGlobalAdditional = {{ $globalAdditionalChargeTotal ?? 0 }};
        let billingPrevReading = 0;
        let billingCustomerType = 'Regular';
        window._billingDuplicate = false;
        window._billingDuplicateMsg = '';

        function onBillingCustomerChange() {
            const select = document.getElementById('billing_modal_customer_id');
            const opt = select.options[select.selectedIndex];
            if (!opt || !opt.value) return;

            billingPrevReading = parseFloat(opt.getAttribute('data-reading')) || 0;
            billingCustomerType = opt.getAttribute('data-type') || 'Regular';

            document.getElementById('billing_modal_prev_reading').textContent = billingPrevReading.toLocaleString(undefined, { maximumFractionDigits: 0 });
            
            const pr = document.getElementById('billing_modal_present_reading');
            pr.value = '';
            pr.min = billingPrevReading;
            
            document.getElementById('billing_modal_base_charge').value = 0;
            document.getElementById('billing_modal_usage_charge').value = 0;
            document.getElementById('billing_modal_total_display').textContent = '0';
            document.getElementById('billing_modal_calc_breakdown').textContent = '';
            
            const dupWarn = document.getElementById('billing-modal-duplicate-warning');
            if (dupWarn) dupWarn.classList.add('hidden');
            const forceCb = document.getElementById('billing-modal-force-checkbox');
            if (forceCb) forceCb.checked = false;
            document.getElementById('billing_modal_force_billing').value = '0';
            window._billingDuplicate = false;
            window._billingDuplicateMsg = '';

            fetch(`/api/customers/${opt.value}/readings`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                const _now = new Date();
                const readings = data.readings || [];
                const duplicate = readings.find(bill => {
                    const d = new Date(bill.billing_date);
                    return d.getFullYear() === _now.getFullYear() && d.getMonth() === _now.getMonth();
                });
                if (duplicate) {
                    const monthName = new Date(duplicate.billing_date).toLocaleString('en-PH', { month: 'long', year: 'numeric' });
                    const amount = parseFloat(duplicate.total_amount || 0).toLocaleString('en-PH', { maximumFractionDigits: 0 });
                    window._billingDuplicate = true;
                    window._billingDuplicateMsg = `A bill of ₱${amount} was already recorded for ${monthName}. Submitting again will create a second bill for the same month.`;
                    
                    const dupWarn = document.getElementById('billing-modal-duplicate-warning');
                    const dupWarnText = document.getElementById('billing-modal-duplicate-warning-text');
                    if (dupWarn && dupWarnText) {
                        dupWarnText.textContent = window._billingDuplicateMsg;
                        dupWarn.classList.remove('hidden');
                    }
                }
            })
            .catch(() => {});
        }

        function calculateBillingCharges() {
            const input = document.getElementById('billing_modal_present_reading');
            const baseInput = document.getElementById('billing_modal_base_charge');
            const usageInput = document.getElementById('billing_modal_usage_charge');
            const breakdown = document.getElementById('billing_modal_calc_breakdown');
            const hiddenConsumption = document.getElementById('billing_modal_consumption_hidden');
            const submitBtn = document.getElementById('billing_modal_submit_btn');

            if (input.value === '') {
                baseInput.value = 0;
                usageInput.value = 0;
                hiddenConsumption.value = 0;
                updateBillingTotal();
                breakdown.textContent = '';
                return;
            }

            const presentReading = parseFloat(input.value) || 0;

            if (presentReading < billingPrevReading) {
                breakdown.textContent = `Invalid: Reading cannot be lower than previous (${billingPrevReading})`;
                breakdown.className = 'text-xs mt-1 text-rose-500 font-bold';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
                baseInput.value = 0;
                usageInput.value = 0;
                hiddenConsumption.value = 0;
                updateBillingTotal();
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            const consumption = Math.max(0, presentReading - billingPrevReading);
            hiddenConsumption.value = consumption.toFixed(0);

            let typeKey = billingCustomerType.toLowerCase();
            let baseCharge = parseFloat(billingSettings[typeKey + '_base_charge']) || 100;
            let rate = parseFloat(billingSettings[typeKey + '_usage_rate']) || 15;
            let baseLimit = parseFloat(billingSettings[typeKey + '_base_limit']) || 10;

            const billableUsage = Math.max(consumption - baseLimit, 0);
            const usageCharge = billableUsage * rate;

            baseInput.value = baseCharge.toFixed(0);
            usageInput.value = usageCharge.toFixed(0);
            updateBillingTotal();

            let breakdownText = `Consumption: ${consumption.toFixed(0)}m³ | (${consumption.toFixed(0)} - ${baseLimit}) × ₱${rate} = ₱${usageCharge.toFixed(0)}`;
            if (billingGlobalAdditional > 0) {
                breakdownText += ` + ₱${billingGlobalAdditional.toFixed(0)} (Additional Charges)`;
            }
            breakdown.textContent = breakdownText;
            breakdown.className = 'text-xs mt-1 text-emerald-400';
        }

        function updateBillingTotal() {
            const base = parseFloat(document.getElementById('billing_modal_base_charge').value) || 0;
            const usage = parseFloat(document.getElementById('billing_modal_usage_charge').value) || 0;
            const total = base + usage + billingGlobalAdditional;
            document.getElementById('billing_modal_total_display').textContent = total.toLocaleString(undefined, { maximumFractionDigits: 0 });
        }

        const billingCreateForm = document.getElementById('billing-create-form');
        if (billingCreateForm) {
            billingCreateForm.addEventListener('submit', function (e) {
                if (window._billingDuplicate && document.getElementById('billing_modal_force_billing').value !== '1') {
                    const forceCb = document.getElementById('billing-modal-force-checkbox');
                    const dupWarn = document.getElementById('billing-modal-duplicate-warning');
                    if (dupWarn) dupWarn.classList.remove('hidden');
                    if (forceCb && !forceCb.checked) {
                        e.preventDefault();
                        forceCb.focus();
                        alert((window._billingDuplicateMsg || 'A bill already exists for this consumer in this month.') + "\n\nPlease check 'Generate another bill for this month anyway' to confirm.");
                        return false;
                    }
                }
            });
        }

        function submitPrintBatch() {
            const checked = document.querySelectorAll('.bill-checkbox:checked');
            if (checked.length === 0) {
                alert('Please select at least one pending bill to print.');
                return;
            }
            const form = document.getElementById('printBatchForm');
            form.innerHTML = '{{ csrf_field() }}';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'bill_ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });
            form.submit();
        }

        // Debounced search auto-submit
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput && searchInput.form && searchInput.form.action.includes('billing')) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 800);
            });
        }
    </script>
</x-layouts::app>

