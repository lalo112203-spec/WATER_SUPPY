<x-layouts::app title="Consumers">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }
        select:invalid {
            color: #9ca3af !important;
        }
        @keyframes dupDialogIn {
            from { opacity: 0; transform: scale(0.92) translateY(12px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .dup-dialog-panel { animation: dupDialogIn 0.22s cubic-bezier(0.34,1.4,0.64,1) both; }
    </style>
    <div class="px-4 py-2 bg-transparent min-h-[calc(100vh-4rem)] font-sans text-gray-200 relative z-10">

        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-4 gap-4 flex-wrap">
            <div class="flex items-center gap-4">
                <h1 class="text-2xl font-bold flex items-center gap-3 drop-shadow-sm whitespace-nowrap">
                    <div class="p-2 bg-blue-600/10 rounded-xl border border-blue-600/20 shadow-[0_0_15px_rgba(37,99,235,0.1)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span>Consumers</span>
                </h1>
            </div>

            <div class="flex-1 flex justify-end w-full">
                <form action="{{ route('customers.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <!-- Column 1: Print & Search -->
                    <div class="flex flex-col gap-3 w-full sm:w-auto">
                        <a href="{{ route('customers.report') }}" target="_blank"
                            class="w-full bg-emerald-600 hover:bg-emerald-500 text-white border border-emerald-500/30 px-5 py-2.5 rounded-2xl font-bold shadow-sm flex items-center justify-center gap-2 transition-all duration-300 hover:scale-[1.02] active:scale-95 whitespace-nowrap group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print Monthly Report
                        </a>

                        <div class="relative flex items-center bg-[#1b2636]/60 backdrop-blur-md border border-[#2d4059]/50 rounded-2xl overflow-hidden group focus-within:border-blue-500/50 focus-within:ring-1 focus-within:ring-blue-500/30 transition-all duration-300 w-full h-11 shadow-inner">
                            <div class="absolute left-0 pl-4 pr-1 flex items-center justify-center text-gray-200 group-focus-within:text-blue-500 transition-colors duration-300 min-w-[40px] pointer-events-none z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search person..." onchange="this.form.submit()"
                                class="w-full h-full bg-transparent border-none text-[14px] pl-12 pr-10 outline-none text-gray-100 placeholder-gray-500 relative z-20">
                            <button type="submit" class="hidden"></button>
                            @if (request('search'))
                                <div class="absolute right-0 h-full px-4 flex items-center justify-center z-30">
                                    <a href="{{ route('customers.index', request()->except('search')) }}" class="text-gray-200 hover:text-rose-400 transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Column 2: Register & Barangay -->
                    <div class="flex flex-col gap-3 w-full sm:w-auto">
                        <button type="button" onclick="window.Flux.modal('create-customer-modal').show()"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold shadow-[0_4px_15px_rgba(37,99,235,0.3)] flex items-center justify-center gap-2 transition-all duration-300 hover:scale-[1.02] active:scale-95 whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Register New Consumer
                        </button>

                        <div class="relative flex items-center bg-[#1b2636]/60 backdrop-blur-md border border-[#2d4059]/50 rounded-2xl overflow-hidden group focus-within:border-blue-500/50 focus-within:ring-1 focus-within:ring-blue-500/30 transition-all duration-300 w-full h-11 shadow-inner">
                            <div class="absolute left-0 pl-4 pr-1 flex items-center justify-center text-gray-200 group-focus-within:text-blue-500 transition-colors duration-300 min-w-[40px] pointer-events-none z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <select name="barangay" onchange="this.form.submit()"
                                class="w-full h-full bg-transparent border-none text-[14px] pl-12 pr-10 outline-none appearance-none text-gray-100 font-medium cursor-pointer relative z-20">
                                <option value="" class="bg-[#0f1722] text-gray-100">All Barangays</option>
                                @foreach($barangays as $brgy)
                                    <option value="{{ $brgy }}" {{ request('barangay') == $brgy ? 'selected' : '' }} class="bg-[#0f1722] text-gray-100">{{ $brgy }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-0 px-4 h-full flex items-center justify-center text-gray-200 pointer-events-none z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-5 py-3 rounded-2xl flex items-center justify-between shadow-sm">
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
            <div class="mb-4 bg-rose-500/10 border border-rose-500/30 text-rose-300 px-5 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif



        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <!-- Stats -->
            <div class="col-span-1 flex flex-col gap-4">
                <div
                    class="bg-[#1b2636]/40 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059]/50 p-6 flex flex-col justify-center text-center items-center h-full relative overflow-hidden group hover:border-cyan-500/50 transition-all">
                    <div
                        class="absolute -right-4 -bottom-4 bg-gradient-to-br from-cyan-600/20 to-blue-900/20 h-32 w-32 rounded-full blur-2xl">
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-gray-200 text-sm font-medium uppercase tracking-wider mb-2 drop-shadow-sm">Total Active
                            Consumers</h3>
                        <div
                            class="text-blue-600 dark:text-blue-400 flex items-center justify-center gap-3 drop-shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500/30" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <div class="flex items-baseline gap-1">
                                <span class="text-5xl font-bold text-white drop-shadow-md">{{ $activeCustomers }}</span>
                                <span class="text-sm font-normal text-gray-300">/ {{ $totalCustomers }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="col-span-1">
                <div
                    class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-hidden border border-[#263548] p-4 w-full h-full relative">
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-cyan-600/10 rounded-full blur-3xl point-events-none">
                    </div>
                    <h3
                        class="text-gray-100 font-semibold text-base mb-4 border-b border-white/10 pb-2 flex items-center gap-2 relative z-10 drop-shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Consumer Growth Trend
                    </h3>
                    <div class="p-4 pt-0 flex-1 relative min-h-[160px] z-10">
                        <canvas id="customerChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Customers Table -->
        <h2 class="text-lg font-semibold flex items-center gap-2 text-gray-200 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-200" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Consumer Directory
        </h2>

        <div
            class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-x-auto border border-[#263548] scrollbar-thin scrollbar-thumb-cyan-500/30 scrollbar-track-transparent">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#0f1722] text-[#94a3b8] uppercase text-[10px] tracking-wider">
                        <th class="px-6 py-3 font-semibold border-b border-[#263548]">Account Number</th>
                        <th class="px-6 py-3 font-semibold border-b border-[#263548]">Name</th>
                        <th class="px-6 py-3 font-semibold border-b border-[#263548]">Type</th>
                        <th class="px-6 py-3 font-semibold border-b border-[#263548]">Meter Post</th>
                        <th class="px-6 py-3 font-semibold hidden lg:table-cell border-b border-[#263548]">Address</th>
                        <th class="px-6 py-3 font-semibold border-b border-[#263548]">Usage</th>
                        <th class="px-6 py-3 font-semibold text-right border-b border-[#263548]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#263548]">
                    @php $lastBarangay = null; @endphp
                    @forelse(($Consumers ?? $customers) as $customer)
                        {{-- Only show barangay separator if we are NOT filtering by a specific barangay --}}
                        @if(!request('barangay') && $customer->barangay !== $lastBarangay)
                            <tr class="bg-[#1b2636]/60 text-[#94a3b8] uppercase text-[10px] tracking-widest">
                                <td colspan="7" class="px-6 py-2 font-bold border-y border-[#263548]/50">
                                    <div class="flex items-center gap-2">
                                        <div class="h-1.5 w-1.5 rounded-full bg-cyan-500"></div>
                                        {{ $customer->barangay ?? 'No Barangay Set' }}
                                    </div>
                                </td>
                            </tr>
                            @php $lastBarangay = $customer->barangay; @endphp
                        @endif
                        @php
                            $latestBill = $customer->bills->sortByDesc('id')->first();
                        @endphp
                        <tr id="row-{{ $customer->id }}" 
                            class="hover:bg-[#1b2636]/60 transition duration-300 cursor-pointer group border-l-2 border-transparent"
                            onclick="toggleDetails('{{ $customer->id }}')">
                            <td class="px-6 py-4 font-medium text-gray-300 flex items-center gap-3">
                                <svg id="chevron-{{ $customer->id }}" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-200 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                {{ $customer->customer_id }}
                            </td>
                            <td class="px-6 py-4 max-w-[180px]">
                                <div class="text-sm font-medium text-gray-200 truncate flex items-center gap-1.5" title="{{ $customer->name }}">
                                    <span>{{ $customer->name }}</span>
                                    @if($customer->isEligibleForDisconnection())
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40" title="Disconnection Risk: {{ $customer->unpaid_bills_count }} unpaid months">
                                            Overdue
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $customer->type === 'Regular' ? 'bg-cyan-900/40 text-cyan-300 border-cyan-700/50' : 'bg-orange-900/40 text-orange-300 border-orange-700/50' }}">
                                    {{ $customer->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-200">{{ $customer->meter_post ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-200 hidden lg:table-cell max-w-[200px]"
                                title="{{ $customer->address }}"><div class="truncate">{{ $customer->address }}</div></td>
                            <td class="px-6 py-4 font-bold text-cyan-400 text-sm" onclick="event.stopPropagation()">
                                <div class="inline-flex items-center gap-1.5 group/edit cursor-pointer hover:text-amber-300 transition-colors"
                                    onclick="triggerEditReadingFromRow('{{ $customer->id }}')"
                                    title="Click to edit meter reading">
                                    <span id="usage-val-{{ $customer->id }}">{{ number_format($customer->meter_reading ?? 0, 0) }}m³</span>
                                    <span class="p-1 rounded bg-amber-500/10 text-amber-400 group-hover/edit:bg-amber-500/20 transition-all opacity-80 group-hover/edit:opacity-100" title="Edit Meter Reading">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                <div class="flex justify-end gap-1">
                                    {{-- Removed standalone show link as requested --}}
                                    <button type="button" 
                                        onclick="toggleDetails('{{ $customer->id }}'); event.stopPropagation();"
                                        class="p-2 text-cyan-400 bg-cyan-900/20 hover:bg-cyan-600/30 rounded-lg transition duration-300 border border-cyan-700/30 shadow-sm"
                                        title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button type="button" 
                                        id="quick-bill-btn-{{ $customer->id }}"
                                        data-id="{{ $customer->id }}"
                                        data-name="{{ $customer->name }}"
                                        data-customer-id="{{ $customer->customer_id }}"
                                        data-type="{{ $customer->type }}"
                                        data-prev-reading="{{ $customer->meter_reading ?? 0 }}"
                                        data-bills-count="{{ $customer->bills->count() }}"
                                        data-is-first="{{ $customer->isFirstReading() ? 'true' : 'false' }}"
                                        onclick="handleQuickBill(this, event); return false;"
                                        class="p-2 text-emerald-400 bg-emerald-900/20 hover:bg-emerald-600/30 rounded-lg transition duration-300 border border-emerald-700/30 shadow-sm"
                                        title="Quick Add Reading">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 pointer-events-none" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                    <button type="button" 
                                        id="edit-reading-btn-{{ $customer->id }}"
                                        data-id="{{ $customer->id }}"
                                        data-name="{{ $customer->name }}"
                                        data-customer-id="{{ $customer->customer_id }}"
                                        data-type="{{ $customer->type }}"
                                        data-reading="{{ $customer->meter_reading ?? 0 }}"
                                        data-latest-bill-id="{{ $latestBill?->id ?? '' }}"
                                        data-latest-bill-prev="{{ $latestBill?->previous_reading ?? 0 }}"
                                        data-latest-bill-reading="{{ $latestBill?->new_reading ?? ($customer->meter_reading ?? 0) }}"
                                        data-latest-bill-period="{{ $latestBill ? $latestBill->billing_date->format('F Y') : 'Initial Reading' }}"
                                        onclick="handleEditCustomerReading(this, event)"
                                        class="p-2 text-amber-400 bg-amber-900/20 hover:bg-amber-600/30 rounded-lg transition duration-300 border border-amber-700/30 shadow-sm"
                                        title="Edit Meter Reading">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button type="button" 
                                        data-id="{{ $customer->id }}"
                                        data-customer-id="{{ $customer->customer_id }}"
                                        data-name="{{ $customer->name }}"
                                        data-type-id="{{ $customer->customer_type_id }}"
                                        data-phone="{{ $customer->phone_number }}"
                                        data-meter-post="{{ $customer->meter_post }}"
                                        data-barangay="{{ $customer->barangay }}"
                                        onclick="handleEditCustomer(this, event)"
                                        class="p-2 text-blue-400 bg-blue-900/20 hover:bg-blue-600/30 rounded-lg transition duration-300 border border-blue-700/30 shadow-sm"
                                        title="Edit Consumer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this consumer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="event.stopPropagation()"
                                            class="p-2 text-rose-400 bg-rose-900/20 hover:bg-rose-600/30 rounded-lg transition duration-300 border border-rose-700/30 shadow-sm"
                                            title="Delete Consumer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr id="details-{{ $customer->id }}" class="hidden bg-[#0a1018]/50 overflow-hidden transition-all duration-300">
                            <td colspan="7" class="px-8 py-8 border-b border-[#263548]">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 animate-fadeIn">
                                    <!-- Primary Details -->
                                    <div class="space-y-4">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-[10px] uppercase tracking-widest text-cyan-500/70 font-bold">Account Status</span>
                                            <span class="flex items-center gap-2">
                                                <span class="h-2 w-2 rounded-full {{ ($customer->status ?? 'active') === 'active' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                                                <span class="text-sm capitalize font-medium {{ ($customer->status ?? 'active') === 'active' ? 'text-emerald-300' : 'text-rose-300' }}">{{ $customer->status ?? 'active' }}</span>
                                            </span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <span class="text-[10px] uppercase tracking-widest text-white font-bold">Consumer Type</span>
                                            <span class="text-sm text-white font-medium">{{ $customer->type }}</span>
                                        </div>
                                    </div>

                                    <!-- Usage Details -->
                                    <div class="space-y-4">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-[10px] uppercase tracking-widest text-emerald-500/70 font-bold">Current Reading</span>
                                            <span class="text-lg text-emerald-400 font-black font-mono" id="detail-reading-{{ $customer->id }}">{{ number_format($customer->meter_reading ?? 0, 0) }} m³</span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <span class="text-[10px] uppercase tracking-widest text-white font-bold">Total Lifetime Usage</span>
                                            <span class="text-sm text-white font-medium">{{ number_format($customer->bills->sum('consumption'), 0) }} m³</span>
                                        </div>
                                    </div>

                                    <!-- User Account Details -->
                                    <div class="space-y-4 bg-[#1b2636]/30 p-4 rounded-2xl border border-[#2d4059]/30">
                                        @if($customer->user)
                                            <div class="flex flex-col gap-1">
                                                <span class="text-[10px] uppercase tracking-widest text-cyan-400 font-bold flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    Login Username
                                                </span>
                                                <span class="text-sm text-gray-200 font-bold font-mono">{{ $customer->user->username ?? $customer->customer_id }}</span>
                                            </div>
                                            <div class="flex flex-col gap-1 mt-2">
                                                <span class="text-[10px] uppercase tracking-widest text-white font-bold">Account Password</span>
                                                <span class="text-sm text-rose-300 font-mono tracking-wider">{{ $customer->user->plain_password ?? '********' }}</span>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center justify-center h-full text-center">
                                                <span class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">No User Account</span>
                                                <button type="button" 
                                                    onclick="openCreateConsumerAccountModal('{{ $customer->id }}', '{{ $customer->customer_id }}', '{{ addslashes($customer->name) }}')"
                                                    class="text-[10px] bg-cyan-600/20 hover:bg-cyan-600/40 text-cyan-400 border border-cyan-500/30 px-3 py-1.5 rounded-lg transition font-semibold flex items-center gap-1 shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                    </svg>
                                                    Create Account
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- System Meta -->
                                    <div class="space-y-4">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-[10px] uppercase tracking-widest text-gray-200 font-bold">Full Address</span>
                                            <span class="text-sm text-gray-200 leading-relaxed">{{ $customer->address }}</span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <span class="text-[10px] uppercase tracking-widest text-gray-200 font-bold">Registration Date</span>
                                            <span class="text-sm text-gray-200">{{ $customer->created_at->format('F d, Y') }} <span class="text-[10px] text-gray-600 block">{{ $customer->created_at->diffForHumans() }}</span></span>
                                        </div>
                                    </div>

                                    <div class="col-span-1 md:col-span-4 pt-6 border-t border-[#263548]/30 mt-4">
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
                                            <h4 class="text-[10px] uppercase tracking-widest text-gray-200 font-bold">Reading & Bill History</h4>
                                            
                                            <div class="flex items-center gap-2 flex-wrap" onclick="event.stopPropagation()">
                                                @if($customer->unpaid_bills_count > 0)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40" title="Total accumulated arrears from unpaid bills">
                                                        Arrears: ₱{{ number_format($customer->unpaid_bills_total, 0) }} ({{ $customer->unpaid_bills_count }} unpaid)
                                                    </span>
                                                @endif
                                                @if($customer->isEligibleForDisconnection())
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                        Disconnection Alert ({{ $customer->unpaid_bills_count }} unpaid)
                                                    </span>
                                                    <form action="{{ route('customers.send-disconnection-notice', $customer) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-xs font-bold transition shadow-sm flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                            </svg>
                                                            Send Notice
                                                        </button>
                                                    </form>
                                                @endif

                                                <form id="printCustomerReceiptsBatchForm-{{ $customer->id }}" action="{{ route('billing.print-batch') }}" method="POST" target="_blank" class="hidden">
                                                    @csrf
                                                </form>

                                                <button type="button" onclick="submitCustomerReceiptsBatch({{ $customer->id }})" class="px-3 py-1 bg-blue-600/30 hover:bg-blue-600/50 text-blue-300 border border-blue-500/40 rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm" title="Print selected bills">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                    </svg>
                                                    Print Selected
                                                </button>
                                            </div>
                                        </div>
                                        <div class="overflow-x-auto rounded-xl border border-[#2d4059]/30 bg-[#0f1722]/40">
                                            <table class="w-full text-left border-collapse">
                                                <thead>
                                                    <tr class="bg-[#1b2636]/40 text-[#94a3b8] text-[10px] uppercase tracking-wider">
                                                        <th class="px-3 py-2 w-10 text-center">
                                                            <input type="checkbox" title="Select all bills" class="rounded border-gray-600 bg-[#0f1722] text-cyan-500 focus:ring-cyan-500 cursor-pointer" onclick="toggleCustomerBills({{ $customer->id }}, this.checked)">
                                                        </th>
                                                        <th class="px-4 py-2 font-semibold">Period</th>
                                                        <th class="px-4 py-2 font-semibold">Reading</th>
                                                        <th class="px-4 py-2 font-semibold text-center">Usage</th>
                                                        <th class="px-4 py-2 font-semibold">Bill</th>
                                                        <th class="px-4 py-2 font-semibold">Status</th>
                                                        <th class="px-4 py-2 font-semibold text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-[#263548]/30">
                                                    @forelse($customer->bills->sortByDesc('billing_date') as $bill)
                                                        <tr id="cust-bill-row-{{ $bill->id }}" class="text-xs text-gray-300 hover:bg-[#1b2636]/20">
                                                            <td class="px-3 py-2 text-center align-middle">
                                                                <input type="checkbox" value="{{ $bill->id }}" class="customer-bill-cb-{{ $customer->id }} rounded border-gray-600 bg-[#0f1722] text-cyan-500 focus:ring-cyan-500 cursor-pointer" title="Select bill">
                                                            </td>
                                                            <td class="px-4 py-2 font-medium">{{ $bill->billing_date->format('F Y') }}</td>
                                                            <td class="px-4 py-2 font-mono" id="bill-reading-cell-{{ $bill->id }}">{{ number_format($bill->usage_units, 0) }} m³</td>
                                                            <td class="px-4 py-2 text-center">
                                                                <span id="bill-consumption-badge-{{ $bill->id }}" class="px-2 py-0.5 rounded text-[10px] font-bold 
                                                                    {{ $bill->consumption <= ($customer->type === 'Commercial' ? 49 : 10) ? 'bg-emerald-500/20 text-emerald-400' : ($bill->consumption <= ($customer->type === 'Commercial' ? 50 : 14) ? 'bg-orange-500/20 text-orange-400' : 'bg-rose-500/20 text-rose-400') }}">
                                                                    {{ number_format($bill->consumption, 0) }} m³
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-2 font-bold text-cyan-400" id="bill-total-cell-{{ $bill->id }}">₱{{ number_format($bill->total_amount, 0) }}</td>
                                                            <td class="px-4 py-2">
                                                                <span class="capitalize {{ strtolower($bill->status) === 'paid' ? 'text-emerald-400' : 'text-rose-400' }}">{{ strtolower($bill->status) === 'paid' ? 'Paid' : 'Unpaid' }}</span>
                                                            </td>
                                                            <td class="px-4 py-2 text-right">
                                                                <div class="flex justify-end gap-1">
                                                                    <a href="{{ route('billing.receipt', $bill) }}" class="p-1 text-cyan-400 hover:bg-cyan-500/10 rounded transition" title="Print Receipt">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                                        </svg>
                                                                    </a>
                                                                    @php
                                                                        $isFirstBill = $customer->bills->sortBy('billing_date')->first()?->id === $bill->id;
                                                                    @endphp
                                                                    <button type="button" 
                                                                        onclick="openEditBillDirect({{ $bill->id }}, {{ $bill->previous_reading ?? 0 }}, {{ $bill->new_reading ?? $bill->usage_units }}, '{{ $bill->billing_date->format('F Y') }}', {{ $customer->id }}, '{{ addslashes($customer->name) }}', '{{ $customer->type }}', '{{ $customer->customer_id }}', {{ $isFirstBill ? 'true' : 'false' }})"
                                                                        class="p-1 text-amber-400 hover:bg-amber-500/10 rounded transition" title="Edit Reading for this Bill">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                        </svg>
                                                                    </button>
                                                                    <form action="{{ route('billing.destroy', $bill) }}" method="POST" onsubmit="return confirm('Delete this record?');">
                                                                        @csrf @method('DELETE')
                                                                        <button type="submit" class="p-1 text-rose-400 hover:bg-rose-500/10 rounded transition" title="Delete">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                            </svg>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="px-4 py-4 text-center text-gray-200 italic text-[10px]">No billing history available.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="col-span-1 md:col-span-4 pt-4 border-t border-[#263548]/30 mt-2 flex justify-between items-center">
                                        <div class="flex gap-4">
                                            @if($customer->user)
                                                <a href="{{ route('messages.index', ['select_user' => $customer->user->id]) }}" class="text-[10px] bg-indigo-600/20 hover:bg-indigo-600/40 text-indigo-400 border border-indigo-500/30 px-3 py-1.5 rounded-lg transition uppercase tracking-widest font-bold">
                                                    Message Consumer
                                                </a>
                                            @endif
                                        </div>
                                        <button type="button" onclick="toggleDetails('{{ $customer->id }}')" class="text-gray-200 hover:text-gray-300 transition text-[10px] uppercase tracking-widest font-bold px-4 py-2">
                                            Close Details
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-[#263548] mb-3"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                @if(request('search'))
                                    <p class="text-lg font-medium text-gray-300">No results found for "{{ request('search') }}"
                                    </p>
                                    <p class="text-sm mt-1 text-gray-200">Try adjusting your search terms or ID.</p>
                                    <a href="{{ route('customers.index') }}"
                                        class="inline-block mt-4 text-cyan-400 hover:text-cyan-300 transition text-sm font-medium">Clear
                                        search</a>
                                @else
                                    <p class="text-lg font-medium text-gray-300">No Consumers found</p>
                                    <p class="text-sm mt-1 text-gray-200">Start by registering your first user to the water
                                        system.</p>
                                    <button type="button" onclick="window.Flux.modal('create-customer-modal').show()"
                                        class="inline-block mt-4 bg-cyan-600/80 border border-cyan-400/50 text-white px-5 py-2 rounded-xl text-sm hover:bg-cyan-500 transition shadow-[0_0_15px_rgba(6,182,212,0.3)] backdrop-blur-sm">Register
                                        Consumer</button>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ ($Consumers ?? $customers)->links() }}
        </div>
    </div>

    <!-- Quick Bill Modal -->
    <flux:modal id="quick-bill-modal" name="quick-bill-modal" class="md:w-[500px] !bg-[#121a25] !border !border-[#2d4059] !text-gray-200">
        <style>
            #quick-bill-modal input[type=number]::-webkit-inner-spin-button,
            #quick-bill-modal input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none !important;
                margin: 0 !important;
            }
            #quick-bill-modal input[type=number] {
                -moz-appearance: textfield !important;
            }
        </style>
        <div class="p-4 bg-[#121a25] text-gray-200 rounded-xl max-h-[85vh] overflow-y-auto custom-scrollbar">
            <flux:heading size="lg" class="mb-2 !text-white">Quick Add Reading</flux:heading>
            <flux:subheading id="modal-customer-name" class="mb-4 !text-gray-400">Consumer Name</flux:subheading>
            <form action="{{ route('billing.store') }}" method="POST" id="quick-bill-form">
                @csrf
                <input type="hidden" name="customer_id" id="modal_customer_id">
                <input type="hidden" name="billing_date" value="{{ now()->format('Y-m-d') }}">
                <input type="hidden" name="due_date" value="{{ now()->addDays(30)->format('Y-m-d') }}">
                <input type="hidden" name="consumption" id="modal_consumption_hidden">
                <input type="hidden" name="force_billing" id="modal_force_billing" value="0">

                <div class="space-y-4 sm:space-y-6">
                    <div class="bg-[#1b2636]/40 p-4 rounded-xl border border-[#2d4059]/50">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm font-medium text-gray-200">Previous Reading:</span>
                            <span id="modal_prev_reading" class="font-mono font-bold text-gray-200 text-lg">0</span>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="modal_present_reading" class="block text-sm font-medium text-gray-300">Present Reading (m³) <span class="text-red-500">*</span></label>
                            <input type="number" step="any" id="modal_present_reading" name="new_reading" required
                                oninput="calculateQuickCharges()" placeholder="Enter reading..."
                                class="w-full border border-[#2d4059] focus:border-emerald-500/50 text-2xl font-black rounded-xl py-3 px-4 outline-none transition-all duration-300 shadow-inner [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                style="background-color: #0f1722 !important; color: #34d399 !important; -moz-appearance: textfield;">
                        </div>

                        <!-- Same or Lower Reading Warning Alert -->
                        <div id="quick_reading_warning_box" class="hidden mt-3 p-3 bg-amber-500/15 border border-amber-500/40 rounded-xl text-amber-300 text-xs transition-all duration-300"></div>

                        <div id="modal_calc_breakdown" class="text-xs mt-3 min-h-[1.25rem] text-zinc-500"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1">Base Charge (₱)</label>
                            <input type="number" step="0.01" name="base_charge" id="modal_base_charge" readonly
                                class="w-full border border-[#2d4059] px-3 py-2 rounded-xl text-sm outline-none cursor-not-allowed font-bold"
                                style="background-color: #1b2636 !important; color: #e2e8f0 !important;">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1">Usage Charge (₱)</label>
                            <input type="number" step="0.01" name="usage_charge" id="modal_usage_charge" readonly
                                class="w-full border border-[#2d4059] px-3 py-2 rounded-xl text-sm outline-none cursor-not-allowed font-bold"
                                style="background-color: #1b2636 !important; color: #e2e8f0 !important;">
                        </div>
                    </div>

                    <div class="bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/30 flex justify-between items-center px-6">
                        <span class="text-emerald-500 font-bold uppercase tracking-widest text-sm">Total Bill</span>
                        <div class="text-right">
                            <span class="text-emerald-400 font-bold text-3xl">₱<span id="modal_total_display">0</span></span>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" id="modal_submit_btn" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-all font-bold shadow-lg shadow-emerald-600/20 flex items-center justify-center">Generate Bill</button>
                        <flux:modal.close>
                            <flux:button variant="ghost" class="px-6 !border !border-[#2d4059] !text-gray-300 hover:!bg-[#1b2636] hover:!text-white">Cancel</flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            </form>
        </div>
    </flux:modal>
    <!-- Create Customer Modal -->
    <flux:modal name="create-customer-modal" class="md:w-[800px] !bg-[#121a25] !border !border-[#2d4059] !text-gray-200">
        <div class="p-4 bg-[#121a25] text-gray-200 rounded-xl max-h-[85vh] overflow-y-auto custom-scrollbar">
            <div class="flex items-center justify-between mb-4 border-b border-[#263548] pb-2">
                <flux:heading size="lg" class="!text-white">Register New Consumer</flux:heading>
            </div>
            <form method="POST" action="{{ route('customers.store') }}">
                @csrf
                <input type="hidden" name="form_type" value="create">
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 items-end">
                    <div class="md:col-span-1">
                        <label class="block text-gray-200 mb-1 text-[10px] font-medium">Account No. (Leave blank to auto-gen)</label>
                        <input type="text" name="customer_id" value="{{ old('customer_id', old('form_type') === 'create' ? '' : ($nextId ?? '')) }}" placeholder="Auto-generate" class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                        @if(old('form_type') === 'create') @error('customer_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-gray-200 mb-2 text-xs font-bold uppercase tracking-wider text-cyan-500">Consumer Name</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-gray-200 mb-1 text-[10px] font-medium">Surname <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('form_type') === 'create' ? old('last_name') : '' }}" required class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                                @if(old('form_type') === 'create') @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                            </div>
                            <div>
                                <label class="block text-gray-200 mb-1 text-[10px] font-medium">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('form_type') === 'create' ? old('first_name') : '' }}" required class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                                @if(old('form_type') === 'create') @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                            </div>
                            <div>
                                <label class="block text-gray-200 mb-1 text-[10px] font-medium">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('form_type') === 'create' ? old('middle_name') : '' }}" class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                                @if(old('form_type') === 'create') @error('middle_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-200 mb-1 text-xs font-medium">Consumer Type <span class="text-red-500">*</span></label>
                        <select name="customer_type_id" onchange="handleCustomerTypeColor(this)" required class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                            <option value="" disabled selected hidden>Select Type</option>
                            @foreach($customerTypes as $type)
                                <option value="{{ $type->id }}" class="text-gray-200 bg-[#0f151e]" {{ (old('form_type') === 'create' && old('customer_type_id') == $type->id) ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @if(old('form_type') === 'create') @error('customer_type_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                    </div>
                    <div>
                        <label class="block text-gray-200 mb-1 text-xs font-medium">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('form_type') === 'create' ? old('phone_number') : '' }}" placeholder="e.g. 09123456789" class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                        @if(old('form_type') === 'create') @error('phone_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                    </div>
                </div>

                <div class="mb-4">
                    <div class="flex flex-col md:flex-row gap-4 items-start">
                        <div class="w-full md:w-1/2">
                            <label class="block text-gray-200 mb-1 text-xs font-medium">Meter Post <span class="text-red-500">*</span></label>
                            <input type="text" name="meter_post" value="{{ old('form_type') === 'create' ? old('meter_post') : '' }}" required placeholder="Meter Post" class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                            @if(old('form_type') === 'create') @error('meter_post') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                        </div>
                        <div class="w-full md:w-1/2">
                            <label class="block text-gray-200 mb-1 text-xs font-medium">Barangay <span class="text-red-500">*</span></label>
                            <input type="text" name="barangay" list="barangays_list" value="{{ old('form_type') === 'create' ? old('barangay') : '' }}" required placeholder="Barangay e.g. BRGY 1" class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                            @if(old('form_type') === 'create') @error('barangay') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                        </div>
                    </div>
                </div>

                <div class="mb-4 bg-[#1a2432]/50 p-4 border border-[#263548] rounded">
                    <label class="flex items-center space-x-3 text-gray-300 font-medium mb-3 cursor-pointer">
                        <input type="checkbox" name="create_account" id="create_account" value="1" class="rounded text-[#42a5f5] focus:ring-[#42a5f5] bg-[#0f151e] border-[#263548] w-5 h-5 cursor-pointer" {{ (old('form_type') === 'create' && old('create_account')) ? 'checked' : '' }}>
                        <span class="text-sm">Also Create Login Account for this Consumer</span>
                    </label>
                    <div id="password_field" style="{{ (old('form_type') === 'create' && old('create_account')) ? 'display: block;' : 'display: none;' }}" x-data="{ show: false }">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-200 mb-1 text-xs font-medium">Username (Optional)</label>
                                <input type="text" name="username" id="create_modal_username" value="{{ old('username') }}" placeholder="Defaults to Account No." class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm font-mono">
                                <p class="text-[10px] text-gray-400 mt-1">Leave blank to use Account Number as username.</p>
                                @if(old('form_type') === 'create') @error('username') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-gray-200 text-xs font-medium">Password <span class="text-red-500">*</span></label>
                                    <button type="button" onclick="generateCreateModalPassword()" class="text-[10px] text-cyan-400 hover:text-cyan-300 font-semibold underline">Auto-generate</button>
                                </div>
                                <div class="relative w-full">
                                    <input :type="show ? 'text' : 'password'" name="password" id="create_modal_password" class="w-full px-3 py-2 pr-10 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm font-mono">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition-colors focus:outline-none">
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 0110.665 4.937c-1.274 4.057-5.064 7-9.542 7-1.07 0-2.1-.17-3.064-.486m-2.868-2.868A8.966 8.966 0 013 12c.5-1.278 1.258-2.42 2.215-3.375" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">Minimum 6 characters. Login using Username or Account Number.</p>
                                @if(old('form_type') === 'create') @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-[#263548]">
                    <flux:modal.close>
                        <flux:button variant="ghost" class="px-6 !border !border-[#2d4059] !text-gray-300 hover:!bg-[#1b2636] hover:!text-white">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 transition-all font-bold text-white">Save Consumer</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Edit Customer Modal -->
    <flux:modal id="edit-customer-modal" name="edit-customer-modal" class="md:w-[800px] !bg-[#121a25] !border !border-[#2d4059] !text-gray-200">
        <div class="p-4 bg-[#121a25] text-gray-200 rounded-xl max-h-[85vh] overflow-y-auto custom-scrollbar">
            <div class="flex items-center justify-between mb-4 border-b border-[#263548] pb-2">
                <flux:heading size="lg" class="!text-white">Edit Consumer</flux:heading>
            </div>
            <form method="POST" id="edit-customer-form" action="{{ old('form_type') === 'edit' && old('customer_db_id') ? route('customers.update', old('customer_db_id')) : '' }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_type" value="edit">
                <input type="hidden" name="customer_db_id" id="edit_customer_db_id" value="{{ old('customer_db_id') }}">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 items-end">
                    <div class="md:col-span-1">
                        <label class="block text-gray-200 mb-1 text-[10px] font-medium">Account Number</label>
                        <input type="text" name="customer_id" id="edit_customer_id" value="{{ old('form_type') === 'edit' ? old('customer_id') : '' }}" required class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                        @if(old('form_type') === 'edit') @error('customer_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-gray-200 mb-2 text-xs font-bold uppercase tracking-wider text-cyan-500">Consumer Name</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-gray-200 mb-1 text-[10px] font-medium">Surname <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" id="edit_last_name" value="{{ old('form_type') === 'edit' ? old('last_name') : '' }}" required class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                                @if(old('form_type') === 'edit') @error('last_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                            </div>
                            <div>
                                <label class="block text-gray-200 mb-1 text-[10px] font-medium">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" id="edit_first_name" value="{{ old('form_type') === 'edit' ? old('first_name') : '' }}" required class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                                @if(old('form_type') === 'edit') @error('first_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                            </div>
                            <div>
                                <label class="block text-gray-200 mb-1 text-[10px] font-medium">Middle Name</label>
                                <input type="text" name="middle_name" id="edit_middle_name" value="{{ old('form_type') === 'edit' ? old('middle_name') : '' }}" class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                                @if(old('form_type') === 'edit') @error('middle_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-200 mb-1 text-xs font-medium">Consumer Type <span class="text-red-500">*</span></label>
                        <select name="customer_type_id" id="edit_customer_type_id" onchange="handleCustomerTypeColor(this)" required class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                            <option value="" disabled selected hidden>Select Type</option>
                            @foreach($customerTypes as $type)
                                <option value="{{ $type->id }}" class="text-gray-200 bg-[#0f151e]" {{ (old('form_type') === 'edit' && old('customer_type_id') == $type->id) ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @if(old('form_type') === 'edit') @error('customer_type_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                    </div>
                    <div>
                        <label class="block text-gray-200 mb-1 text-xs font-medium">Phone Number</label>
                        <input type="text" name="phone_number" id="edit_phone_number" value="{{ old('form_type') === 'edit' ? old('phone_number') : '' }}" placeholder="e.g. 09123456789" class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                        @if(old('form_type') === 'edit') @error('phone_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                    </div>
                </div>

                <div class="mb-4 flex flex-col md:flex-row gap-4 items-start">
                    <div class="w-full md:w-1/2">
                        <label class="block text-gray-200 mb-1 text-xs font-medium">Meter Post <span class="text-red-500">*</span></label>
                        <input type="text" name="meter_post" id="edit_meter_post" value="{{ old('form_type') === 'edit' ? old('meter_post') : '' }}" required class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                        @if(old('form_type') === 'edit') @error('meter_post') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="block text-gray-200 mb-1 text-xs font-medium">Barangay <span class="text-red-500">*</span></label>
                        <input type="text" name="barangay" id="edit_barangay" list="barangays_list" value="{{ old('form_type') === 'edit' ? old('barangay') : '' }}" required class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                        @if(old('form_type') === 'edit') @error('barangay') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-[#263548]">
                    <flux:modal.close>
                        <flux:button variant="ghost" class="px-6 !border !border-[#2d4059] !text-gray-300 hover:!bg-[#1b2636] hover:!text-white">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 transition-all font-bold text-white">Update Consumer</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" data-navigate-track></script>
    <script>
        function openEditCustomerModal(id, customer) {
            document.getElementById('edit_customer_db_id').value = id;
            document.getElementById('edit_customer_id').value = customer.customer_id || '';
            
            let parts = (customer.name || '').split(',');
            document.getElementById('edit_last_name').value = parts[0] ? parts[0].trim() : '';
            let firstMiddle = parts.length > 1 ? parts[1].trim().split(' ') : [];
            document.getElementById('edit_first_name').value = firstMiddle[0] || '';
            document.getElementById('edit_middle_name').value = firstMiddle.slice(1).join(' ') || '';
            
            const typeSelect = document.getElementById('edit_customer_type_id');
            if (typeSelect) {
                typeSelect.value = customer.customer_type_id || '';
                handleCustomerTypeColor(typeSelect);
            }
            document.getElementById('edit_phone_number').value = customer.phone_number || '';
            document.getElementById('edit_meter_post').value = customer.meter_post || '';
            document.getElementById('edit_barangay').value = customer.barangay || '';
            
            document.getElementById('edit-customer-form').action = `/customers/${id}`;
            safeShowModal('edit-customer-modal');
        }

        function safeShowModal(name) {
            // 1. Try Flux API
            try {
                if (typeof window.Flux !== 'undefined' && typeof window.Flux.modal === 'function') {
                    window.Flux.modal(name).show();
                }
            } catch (e) {
                console.warn("window.Flux.modal error:", e);
            }

            // 2. Dispatch modal-show event on document (Flux listener)
            try {
                document.dispatchEvent(new CustomEvent('modal-show', { detail: { name: name } }));
            } catch (e) {}

            // 3. Direct HTML5 dialog fallback
            try {
                const dialog = document.querySelector(`dialog[data-modal="${name}"], [data-modal="${name}"], #${name}, [name="${name}"]`);
                if (dialog) {
                    if (typeof dialog.showModal === 'function') {
                        if (!dialog.open) {
                            dialog.showModal();
                        }
                    } else {
                        dialog.classList.remove('hidden');
                        dialog.style.display = 'block';
                    }
                }
            } catch (e) {
                console.warn("Direct showModal fallback error:", e);
            }
        }

        function initCustomerPage() {
            const createAcc = document.getElementById('create_account');
            if (createAcc && !createAcc._listenerAttached) {
                createAcc._listenerAttached = true;
                createAcc.addEventListener('change', function () {
                    const passField = document.getElementById('password_field');
                    const passInput = document.getElementById('password');
                    if (this.checked) {
                        passField.style.display = 'block';
                        passInput.required = true;
                    } else {
                        passField.style.display = 'none';
                        passInput.required = false;
                    }
                });
            }

            @if($errors->any())
                @if(old('form_type') === 'create')
                    setTimeout(() => safeShowModal('create-customer-modal'), 100);
                @elseif(old('form_type') === 'edit')
                    setTimeout(() => safeShowModal('edit-customer-modal'), 100);
                @endif
            @endif
        }

        document.addEventListener('DOMContentLoaded', initCustomerPage);
        document.addEventListener('livewire:navigated', initCustomerPage);

        function handleQuickBill(btn, event) {
            if (event) event.stopPropagation();
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const customerId = btn.getAttribute('data-customer-id');
            const type = btn.getAttribute('data-type');
            const prevReading = btn.getAttribute('data-prev-reading');
            const isFirst = btn.getAttribute('data-is-first') === 'true' || parseInt(btn.getAttribute('data-bills-count') || '0') === 0 || parseFloat(prevReading || 0) === 0;
            openQuickBillModal(id, name, customerId, type, prevReading, isFirst);
        }

        function handleEditCustomer(btn, event) {
            if (event) event.stopPropagation();
            const id = btn.getAttribute('data-id');
            const customer = {
                id: id,
                customer_id: btn.getAttribute('data-customer-id') || '',
                name: btn.getAttribute('data-name') || '',
                customer_type_id: btn.getAttribute('data-type-id') || '',
                phone_number: btn.getAttribute('data-phone') || '',
                meter_post: btn.getAttribute('data-meter-post') || '',
                barangay: btn.getAttribute('data-barangay') || ''
            };
            openEditCustomerModal(id, customer);
        }

        // ===== Edit Meter Reading Feature =====
        function triggerEditReadingFromRow(id) {
            const btn = document.getElementById('edit-reading-btn-' + id);
            if (btn) {
                handleEditCustomerReading(btn, window.event);
            }
        }

        function handleEditCustomerReading(btn, event) {
            if (event) event.stopPropagation();
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const customerId = btn.getAttribute('data-customer-id');
            const type = btn.getAttribute('data-type');
            const reading = parseFloat(btn.getAttribute('data-reading')) || 0;
            const latestBillId = btn.getAttribute('data-latest-bill-id');
            const latestBillPrev = parseFloat(btn.getAttribute('data-latest-bill-prev')) || 0;
            const latestBillReading = parseFloat(btn.getAttribute('data-latest-bill-reading')) || reading;
            const period = btn.getAttribute('data-latest-bill-period') || 'Current Period';

            if (latestBillId) {
                openAdminEditBillModal(latestBillId, latestBillPrev, latestBillReading, period, id, name, type, customerId);
            } else {
                openAdminEditCustomerModal(id, reading, name, type, customerId);
            }
        }

        function openEditBillDirect(billId, prevReading, currentReading, period, id, name, type, customerId, isFirstBill = false) {
            openAdminEditBillModal(billId, prevReading, currentReading, period, id, name, type, customerId, isFirstBill);
        }

        function openAdminEditCustomerModal(id, currentReading, name, type, customerAcctId) {
            document.getElementById('admin-edit-bill-id').value = '';
            document.getElementById('admin-edit-customer-id').value = id;
            document.getElementById('admin-edit-customer-type').value = type || 'Regular';
            document.getElementById('admin-edit-prev-reading').value = 0;
            document.getElementById('admin-edit-prev-display').textContent = '0 m³ (Base)';

            document.getElementById('admin-edit-reading-title').textContent = 'Edit Initial Reading';
            document.getElementById('admin-edit-reading-subtitle').textContent = `${name || 'Consumer'} (${customerAcctId || id}) • Initial Reading (No Bills Yet)`;
            window._adminEditIsFirstBill = true;

            const inputEl = document.getElementById('admin-edit-new-reading-input');
            inputEl.value = Math.round(currentReading);
            inputEl.min = 0;

            document.getElementById('admin-edit-error-msg').classList.add('hidden');
            document.getElementById('admin-edit-calc-preview').classList.add('hidden');

            recalcAdminEditReading();

            const modal = document.getElementById('editCustomerReadingModalAdmin');
            if (modal && modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => inputEl.focus(), 60);
        }

        function openAdminEditBillModal(billId, prevReading, currentReading, period, id, name, type, customerAcctId, isFirstBill = false) {
            document.getElementById('admin-edit-bill-id').value = billId;
            document.getElementById('admin-edit-customer-id').value = id;
            document.getElementById('admin-edit-customer-type').value = type || 'Regular';
            document.getElementById('admin-edit-prev-reading').value = prevReading;
            document.getElementById('admin-edit-prev-display').textContent = Math.round(prevReading) + ' m³';
            window._adminEditIsFirstBill = !!isFirstBill;

            document.getElementById('admin-edit-reading-title').textContent = 'Edit Meter Reading';
            document.getElementById('admin-edit-reading-subtitle').textContent = `${name || 'Consumer'} (${customerAcctId || id}) • ${period || 'Current Period'}`;

            const inputEl = document.getElementById('admin-edit-new-reading-input');
            inputEl.value = Math.round(currentReading);
            inputEl.min = 0;

            document.getElementById('admin-edit-error-msg').classList.add('hidden');
            document.getElementById('admin-edit-calc-preview').classList.add('hidden');

            recalcAdminEditReading();

            const modal = document.getElementById('editCustomerReadingModalAdmin');
            if (modal && modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => inputEl.focus(), 60);
        }

        function closeAdminEditReadingModal() {
            const modal = document.getElementById('editCustomerReadingModalAdmin');
            if (modal) modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function recalcAdminEditReading() {
            const prev = parseFloat(document.getElementById('admin-edit-prev-reading').value) || 0;
            const newVal = parseFloat(document.getElementById('admin-edit-new-reading-input').value);
            const errEl = document.getElementById('admin-edit-error-msg');
            const previewEl = document.getElementById('admin-edit-calc-preview');
            const saveBtn = document.getElementById('btn-save-admin-reading');

            if (isNaN(newVal) || newVal < 0) {
                errEl.classList.add('hidden');
                previewEl.classList.add('hidden');
                saveBtn.disabled = true;
                saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            errEl.classList.add('hidden');
            const usage = Math.max(0, newVal - prev);
            const custType = (document.getElementById('admin-edit-customer-type')?.value || 'Regular').toLowerCase();

            const baseCharge = parseFloat(systemSettings[custType + '_base_charge']) || 100;
            const rate = parseFloat(systemSettings[custType + '_usage_rate']) || 15;
            const baseLimit = parseFloat(systemSettings[custType + '_base_limit']) || 10;
            const isFirstBill = !!window._adminEditIsFirstBill;

            const billableUsage = Math.max(usage - baseLimit, 0);
            const usageCharge = isFirstBill ? 0 : (billableUsage * rate);
            const total = baseCharge + usageCharge + (typeof globalAdditionalChargeTotal !== 'undefined' ? globalAdditionalChargeTotal : 0);

            let noteText = '';
            if (isFirstBill) {
                noteText = '<span class="text-amber-400 font-bold ml-1">(First Reading: Base Charge Only)</span>';
            } else if (newVal <= prev) {
                noteText = '<span class="text-amber-400 font-bold ml-1">(Reading ≤ Previous: Base Charge Only)</span>';
            }

            previewEl.innerHTML = `
                <div class="flex justify-between items-center text-xs">
                    <span>Recalculated Usage: <strong>${Math.round(usage)} m³</strong> ${noteText}</span>
                    <span class="text-cyan-300 font-bold">New Bill Total: <strong>₱${Math.round(total).toLocaleString('en-PH')}</strong></span>
                </div>
            `;
            previewEl.classList.remove('hidden');
            saveBtn.disabled = false;
            saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        function saveAdminEditReading() {
            const billId = document.getElementById('admin-edit-bill-id').value;
            const customerId = document.getElementById('admin-edit-customer-id').value;
            const prevReading = parseFloat(document.getElementById('admin-edit-prev-reading').value) || 0;
            const newReading = parseFloat(document.getElementById('admin-edit-new-reading-input').value);

            if (isNaN(newReading) || newReading < 0) {
                recalcAdminEditReading();
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const saveBtn = document.getElementById('btn-save-admin-reading');
            saveBtn.disabled = true;
            saveBtn.innerHTML = `
                <svg class="animate-spin h-4 w-4 text-gray-950 inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Saving...</span>
            `;

            const url = billId ? `/reader/bills/${billId}` : `/reader/customers/${customerId}/reading`;
            const payload = billId ? {
                new_reading: newReading,
                previous_reading: prevReading
            } : {
                reading: newReading
            };

            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
            .then(async r => {
                const data = await r.json().catch(() => ({}));
                if (!r.ok) {
                    throw new Error(data.message || `Server error (${r.status})`);
                }
                return data;
            })
            .then(data => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Save Reading</span>
                `;

                if (data.success) {
                    closeAdminEditReadingModal();

                    const updatedReading = data.customer_meter_reading !== undefined ? data.customer_meter_reading : (data.meter_reading !== undefined ? data.meter_reading : newReading);

                    // 1. Update Usage column cell
                    const usageCell = document.getElementById('usage-val-' + customerId);
                    if (usageCell) {
                        usageCell.textContent = Math.round(updatedReading) + 'm³';
                    }

                    // 2. Update Details row current reading display if present
                    const detailReading = document.getElementById('detail-reading-' + customerId);
                    if (detailReading) {
                        detailReading.textContent = Math.round(updatedReading) + ' m³';
                    }

                    // 3. Update Edit Reading button data attributes
                    const editBtn = document.getElementById('edit-reading-btn-' + customerId);
                    if (editBtn) {
                        editBtn.setAttribute('data-reading', updatedReading);
                        if (billId) {
                            editBtn.setAttribute('data-latest-bill-reading', updatedReading);
                        }
                    }

                    // 4. Update Quick Bill button previous reading
                    const quickBtn = document.getElementById('quick-bill-btn-' + customerId);
                    if (quickBtn) {
                        quickBtn.setAttribute('data-prev-reading', updatedReading);
                    }

                    // 5. Update Bill History row if editing a specific bill
                    if (billId) {
                        const billReadingCell = document.getElementById('bill-reading-cell-' + billId);
                        if (billReadingCell) {
                            billReadingCell.textContent = Math.round(newReading) + ' m³';
                        }
                        const billTotalCell = document.getElementById('bill-total-cell-' + billId);
                        if (billTotalCell && data.bill && data.bill.total_amount !== undefined) {
                            billTotalCell.textContent = '₱' + Math.round(data.bill.total_amount).toLocaleString('en-PH');
                        }
                        const billConsumptionBadge = document.getElementById('bill-consumption-badge-' + billId);
                        if (billConsumptionBadge && data.bill && data.bill.consumption !== undefined) {
                            billConsumptionBadge.textContent = Math.round(data.bill.consumption) + ' m³';
                        }
                    }

                    showAdminToastNotification(data.message || 'Meter reading updated successfully!');
                } else {
                    alert(data.message || 'Failed to update meter reading.');
                }
            })
            .catch(err => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Save Reading</span>
                `;
                alert(err.message || 'An error occurred while saving the meter reading.');
            });
        }

        function showAdminToastNotification(msg) {
            let toast = document.getElementById('admin-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'admin-toast';
                toast.className = 'fixed top-6 right-6 z-[9999999] px-5 py-3.5 rounded-2xl bg-emerald-600/95 text-white font-bold text-sm shadow-[0_10px_30px_rgba(16,185,129,0.5)] border border-emerald-400/30 backdrop-blur-md flex items-center gap-3 transition-all duration-300 pointer-events-none opacity-0 -translate-y-4';
                toast.innerHTML = `
                    <div class="p-1 rounded-lg bg-emerald-700/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span id="admin-toast-msg">${msg}</span>
                `;
                document.body.appendChild(toast);
            } else {
                const msgEl = document.getElementById('admin-toast-msg');
                if (msgEl) msgEl.textContent = msg;
            }

            toast.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-4');
            toast.classList.add('opacity-100', 'translate-y-0');

            if (window._adminToastTimeout) clearTimeout(window._adminToastTimeout);
            window._adminToastTimeout = setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'pointer-events-none', '-translate-y-4');
            }, 3500);
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeAdminEditReadingModal();
            }
        });

        function toggleDetails(id) {
            const detailsRow = document.getElementById('details-' + id);
            const mainRow = document.getElementById('row-' + id);
            const chevron = document.getElementById('chevron-' + id);
            
            if (!detailsRow) return;

            if (detailsRow.classList.contains('hidden')) {
                detailsRow.classList.remove('hidden');
                if (mainRow) mainRow.classList.add('bg-[#1b2636]/60', 'border-cyan-500/30');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            } else {
                detailsRow.classList.add('hidden');
                if (mainRow) mainRow.classList.remove('bg-[#1b2636]/60', 'border-cyan-500/30');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        window.openEditCustomerModal = openEditCustomerModal;
        window.openQuickBillModal = openQuickBillModal;
        window.toggleDetails = toggleDetails;
        window.handleQuickBill = handleQuickBill;
        window.handleEditCustomer = handleEditCustomer;
        window.triggerEditReadingFromRow = triggerEditReadingFromRow;
        window.handleEditCustomerReading = handleEditCustomerReading;
        window.openEditBillDirect = openEditBillDirect;
        window.openAdminEditCustomerModal = openAdminEditCustomerModal;
        window.openAdminEditBillModal = openAdminEditBillModal;
        window.closeAdminEditReadingModal = closeAdminEditReadingModal;
        window.recalcAdminEditReading = recalcAdminEditReading;
        window.saveAdminEditReading = saveAdminEditReading;
        window.safeShowModal = safeShowModal;

        let quickCustomerType = 'Regular';
        let quickPrevReading = 0;
        let quickIsFirstReading = false;

        function openQuickBillModal(id, name, customerId, type, prevReading, isFirst = false) {
            try {
                const idEl = document.getElementById('modal_customer_id');
                if (idEl) idEl.value = id || '';
                
                const nameEl = document.getElementById('modal-customer-name');
                if (nameEl) nameEl.textContent = `${name || ''} (${customerId || ''}) - ${type || ''}`;
                
                const prevReadingNum = parseFloat(prevReading) || 0;
                const prevReadingEl = document.getElementById('modal_prev_reading');
                if (prevReadingEl) prevReadingEl.textContent = prevReadingNum.toLocaleString(undefined, { maximumFractionDigits: 0 });
                
                const presentReadingEl = document.getElementById('modal_present_reading');
                if (presentReadingEl) {
                    presentReadingEl.value = '';
                    presentReadingEl.min = 0;
                }
                
                const totalDisplayEl = document.getElementById('modal_total_display');
                if (totalDisplayEl) totalDisplayEl.textContent = '0';
                
                const calcBreakdownEl = document.getElementById('modal_calc_breakdown');
                if (calcBreakdownEl) calcBreakdownEl.textContent = '';
                
                const baseChargeEl = document.getElementById('modal_base_charge');
                if (baseChargeEl) baseChargeEl.value = 0;
                
                const usageChargeEl = document.getElementById('modal_usage_charge');
                if (usageChargeEl) usageChargeEl.value = 0;

                const forceBillingEl = document.getElementById('modal_force_billing');
                if (forceBillingEl) forceBillingEl.value = '0';
                
                const submitBtn = document.getElementById('modal_submit_btn');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                quickCustomerType = type || 'Regular';
                quickPrevReading = prevReadingNum;
                quickIsFirstReading = (isFirst === true || isFirst === 'true') || prevReadingNum === 0;

                window._quickBillDuplicate = false;
                window._quickBillDuplicateMsg = '';
                window._quickReadingWarningConfirmed = false;
                const quickWarningBox = document.getElementById('quick_reading_warning_box');
                if (quickWarningBox) { quickWarningBox.innerHTML = ''; quickWarningBox.classList.add('hidden'); }

                // Trigger modal show
                safeShowModal('quick-bill-modal');

                setTimeout(() => {
                    const pr = document.getElementById('modal_present_reading');
                    if (pr) pr.focus();
                }, 100);

                if (id) {
                    fetch(`/api/customers/${id}/readings`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        const _now = new Date('{{ now()->format('Y-m-d\TH:i:s') }}');
                        const readings = data.readings || [];
                        quickIsFirstReading = readings.length === 0 || quickPrevReading === 0;
                        if (document.getElementById('modal_present_reading')?.value !== '') {
                            calculateQuickCharges();
                        }
                        const duplicate = readings.find(bill => {
                            const d = new Date(bill.billing_date);
                            return d.getFullYear() === _now.getFullYear() && d.getMonth() === _now.getMonth();
                        });
                        if (duplicate) {
                            const monthName = new Date(duplicate.billing_date).toLocaleString('en-PH', { month: 'long', year: 'numeric' });
                            const amount = parseFloat(duplicate.total_amount || 0).toLocaleString('en-PH', { maximumFractionDigits: 0 });
                            window._quickBillDuplicate = true;
                            window._quickBillDuplicateMsg = `A bill of ₱${amount} was already recorded for ${monthName}. Submitting again will create a second bill for the same month.`;
                        }
                    })
                    .catch(() => {});
                }
            } catch (err) {
                console.error("openQuickBillModal error:", err);
                safeShowModal('quick-bill-modal');
            }
        }

        window.openQuickBillModal = openQuickBillModal;

        const systemSettings = {!! json_encode($settings) !!};
        const globalAdditionalChargeTotal = {{ $globalAdditionalChargeTotal ?? 0 }};

        function calculateQuickCharges() {
            const input = document.getElementById('modal_present_reading');
            const baseInput = document.getElementById('modal_base_charge');
            const usageInput = document.getElementById('modal_usage_charge');
            const totalDisplay = document.getElementById('modal_total_display');
            const breakdown = document.getElementById('modal_calc_breakdown');
            const hiddenConsumption = document.getElementById('modal_consumption_hidden');
            const warningBox = document.getElementById('quick_reading_warning_box');

            if (input.value === '') {
                baseInput.value = 0;
                usageInput.value = 0;
                hiddenConsumption.value = 0;
                updateQuickTotal();
                breakdown.textContent = '';
                if (warningBox) { warningBox.innerHTML = ''; warningBox.classList.add('hidden'); }
                return;
            }

            const presentReading = parseFloat(input.value) || 0;
            const submitBtn = document.getElementById('modal_submit_btn');
            const isFirst = quickIsFirstReading || quickPrevReading === 0;
            const cannotProceed = !isFirst && presentReading <= quickPrevReading;

            if (submitBtn) {
                submitBtn.disabled = cannotProceed;
                if (cannotProceed) {
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
            const consumption = Math.max(0, presentReading - quickPrevReading);
            
            hiddenConsumption.value = consumption.toFixed(0);

            let baseCharge = 0;
            let rate = 0;
            let baseLimit = 10;

            let typeKey = (quickCustomerType || 'Regular').toLowerCase();
            baseCharge = parseFloat(systemSettings[typeKey + '_base_charge']) || 100;
            rate = parseFloat(systemSettings[typeKey + '_usage_rate']) || 15;
            baseLimit = parseFloat(systemSettings[typeKey + '_base_limit']) || 10;

            const billableUsage = Math.max(consumption - baseLimit, 0);
            const usageCharge = isFirst ? 0 : (billableUsage * rate);
            const total = baseCharge + usageCharge + globalAdditionalChargeTotal;

            baseInput.value = baseCharge.toFixed(0);
            usageInput.value = usageCharge.toFixed(0);
            
            updateQuickTotal();

            // Real-time Warning Banner in Quick Bill Modal
            if (warningBox) {
                if (!isFirst && presentReading === quickPrevReading) {
                    warningBox.innerHTML = `
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <p class="font-bold text-amber-200">Cannot Proceed: Meter Reading Unchanged</p>
                                <p class="text-amber-300/90 text-[11px] mt-0.5">Present reading (${presentReading.toFixed(0)} m³) is equal to previous reading (${quickPrevReading.toFixed(0)} m³). You cannot proceed with generating this bill — reading must be greater than previous.</p>
                            </div>
                        </div>
                    `;
                    warningBox.classList.remove('hidden');
                } else if (!isFirst && presentReading < quickPrevReading) {
                    warningBox.innerHTML = `
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <p class="font-bold text-rose-200">Cannot Proceed: Reading Lower Than Previous</p>
                                <p class="text-rose-300/90 text-[11px] mt-0.5">Present reading (${presentReading.toFixed(0)} m³) is lower than previous reading (${quickPrevReading.toFixed(0)} m³). You cannot proceed with generating this bill — reading must be greater than previous.</p>
                            </div>
                        </div>
                    `;
                    warningBox.classList.remove('hidden');
                } else {
                    warningBox.innerHTML = '';
                    warningBox.classList.add('hidden');
                }
            }

            let breakdownText;
            if (isFirst) {
                breakdownText = `First Reading: Consumption ${consumption.toFixed(0)}m³ recorded | Usage charge waived (₱0) • Base charge only: ₱${baseCharge.toFixed(0)}`;
                breakdown.className = 'text-xs mt-1 text-emerald-400';
            } else if (presentReading <= quickPrevReading) {
                breakdownText = `Invalid Reading: Present reading must be greater than previous reading (${quickPrevReading.toFixed(0)} m³). Cannot generate bill.`;
                breakdown.className = 'text-xs mt-1 text-rose-400 font-semibold';
            } else {
                breakdownText = `Consumption: ${consumption.toFixed(0)}m³ | (${consumption.toFixed(0)} - ${baseLimit}) × ₱${rate} = ₱${usageCharge.toFixed(0)}`;
                if (globalAdditionalChargeTotal > 0) {
                    breakdownText += ` + ₱${globalAdditionalChargeTotal.toFixed(0)} (Additional Charges)`;
                }
                breakdown.className = 'text-xs mt-1 transition-colors duration-300';
                if (consumption > 0 && consumption <= 20) breakdown.classList.add('text-emerald-400');
                else if (consumption > 20 && consumption <= 30) breakdown.classList.add('text-orange-400');
                else if (consumption > 30) breakdown.classList.add('text-rose-400');
                else breakdown.classList.add('text-zinc-500');
            }
            breakdown.textContent = breakdownText;
            
            // Color coding breakdown based on total consumption
            breakdown.className = 'text-xs mt-1 transition-colors duration-300';
            if (isFirst || presentReading <= quickPrevReading) {
                breakdown.classList.add('text-emerald-400');
            } else if (consumption > 0 && consumption <= 20) breakdown.classList.add('text-emerald-400');
            else if (consumption > 20 && consumption <= 30) breakdown.classList.add('text-orange-400');
            else if (consumption > 30) breakdown.classList.add('text-rose-400');
            else breakdown.classList.add('text-zinc-500');
        }

        function updateQuickTotal() {
            const base = parseFloat(document.getElementById('modal_base_charge').value) || 0;
            const usage = parseFloat(document.getElementById('modal_usage_charge').value) || 0;
            const total = base + usage + globalAdditionalChargeTotal;
            document.getElementById('modal_total_display').textContent = total.toLocaleString(undefined, { maximumFractionDigits: 0 });
        }

        function initializeCustomerChart() {
            try {
                if (typeof window.Chart === 'undefined') {
                    setTimeout(initializeCustomerChart, 50);
                    return;
                }

                const customerCtxEl = document.getElementById('customerChart');
                if (!customerCtxEl) return;

                if (window.customerFlowChartInstance) window.customerFlowChartInstance.destroy();

                const customerCtx = customerCtxEl.getContext('2d');
                
                let labels = {!! json_encode($customerGrowth->pluck('month')) !!};
                let data = {!! json_encode($customerGrowth->pluck('count')) !!};

                window.customerFlowChartInstance = new Chart(customerCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Consumers',
                            data: data,
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#06b6d4',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 34, 0.95)',
                                titleColor: '#e2e8f0',
                                bodyColor: '#cbd5e1',
                                borderColor: '#1e293b',
                                borderWidth: 1,
                                padding: 12
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, color: '#64748b' },
                                grid: { color: 'rgba(148, 163, 184, 0.05)', drawBorder: false },
                                border: { display: false }
                            },
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: { color: '#64748b' }
                            }
                        }
                    }
                });
            } catch (e) {
                console.error("Customer chart error:", e);
            }
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(initializeCustomerChart, 1);
        } else {
            document.addEventListener('DOMContentLoaded', initializeCustomerChart);
        }
        
        document.addEventListener('livewire:navigated', initializeCustomerChart);

        // Debounced search auto-submit
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput && searchInput.form && searchInput.form.action.includes('Consumers')) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 800);
            });
        }

        // Toggle password field in create customer modal
        const createAccountCheckbox = document.getElementById('create_account');
        const passwordField = document.getElementById('password_field');
        if (createAccountCheckbox && passwordField) {
            createAccountCheckbox.addEventListener('change', function() {
                passwordField.style.display = this.checked ? 'block' : 'none';
                if (this.checked) {
                    document.getElementById('password').setAttribute('required', 'required');
                } else {
                    document.getElementById('password').removeAttribute('required');
                }
            });
        }


        function handleCustomerTypeColor(select) {
            if (!select.value || select.value === "") {
                select.style.color = '#9ca3af'; // Tailwind gray-400 placeholder
            } else {
                select.style.color = '#e5e7eb'; // Tailwind gray-200 text
            }
        }

        // Initialize colors on load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('select[name="customer_type_id"]').forEach(select => {
                handleCustomerTypeColor(select);
            });
        });
    </script>

    {{-- ===== Duplicate Bill Confirm Dialog ===== --}}
    <dialog id="dup-bill-dialog" class="fixed inset-0 z-[999999] p-4 m-auto bg-transparent border-none outline-none max-w-lg w-full items-center justify-center backdrop:bg-black/85 backdrop:backdrop-blur-md" style="display:none; color: #ffffff !important;">
        <div class="dup-dialog-panel relative bg-[#0f172a] border-2 border-amber-400 shadow-[0_0_100px_rgba(245,158,11,0.5),0_30px_60px_rgba(0,0,0,0.95)] w-full overflow-hidden rounded-2xl z-10" style="background-color: #0f172a !important; color: #ffffff !important;">
            {{-- Top accent bar --}}
            <div class="h-1.5 w-full bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500"></div>
            <div class="p-6 sm:p-7">
                {{-- Icon + Title --}}
                <div class="flex items-start gap-4 mb-5">
                    <div class="shrink-0 rounded-2xl shadow-lg" style="background-color: rgba(245, 158, 11, 0.25) !important; border: 2px solid rgba(251, 191, 36, 0.5) !important; padding: 0.75rem !important;">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #fbbf24 !important; stroke: #fbbf24 !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 style="color: #ffffff !important; font-weight: 900 !important; font-size: 1.25rem !important; margin: 0 !important; line-height: 1.2 !important;">Duplicate Bill Warning</h3>
                        <p style="color: #fbbf24 !important; font-weight: 400 !important; font-size: 0.875rem !important; margin-top: 0.25rem !important; margin-bottom: 0 !important;">A bill already exists for this month</p>
                    </div>
                </div>

                {{-- Message --}}
                <div style="background-color: #1e1b18 !important; border: 2px solid #f59e0b !important; padding: 1rem !important; border-radius: 0.75rem !important; margin-bottom: 1.25rem !important; box-shadow: inset 0 2px 4px rgba(0,0,0,0.5) !important;">
                    <p id="dup-dialog-msg" style="color: #ffffff !important; font-weight: 400 !important; font-size: 1rem !important; line-height: 1.5 !important; margin: 0 !important;"></p>
                </div>

                {{-- Question --}}
                <p style="color: #ffffff !important; font-weight: 400 !important; font-size: 0.95rem !important; margin-bottom: 1.5rem !important;">Do you still want to generate a new bill for the same month?</p>

                {{-- Buttons --}}
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="hideDupDialog()"
                        style="background-color: #334155 !important; color: #ffffff !important; border: 2px solid #64748b !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 700 !important; font-size: 0.875rem !important; cursor: pointer !important;">
                        Cancel
                    </button>
                    <button type="button" id="dup-dialog-confirm"
                        style="background-color: #fbbf24 !important; color: #0f172a !important; border: none !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 900 !important; font-size: 0.875rem !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important; gap: 0.5rem !important; box-shadow: 0 4px 20px rgba(251, 191, 36, 0.5) !important;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #0f172a !important; stroke: #0f172a !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Submit Anyway
                    </button>
                </div>
            </div>
        </div>
    </dialog>

    {{-- ===== Reading Same/Lower Warning Dialog (Quick Bill) ===== --}}
    <dialog id="quick-reading-warning-dialog" class="fixed inset-0 z-[999999] p-4 m-auto bg-transparent border-none outline-none max-w-lg w-full items-center justify-center backdrop:bg-black/85 backdrop:backdrop-blur-md" style="display:none; color: #ffffff !important;">
        <div class="dup-dialog-panel relative bg-[#0f172a] border-2 border-amber-400 shadow-[0_0_100px_rgba(245,158,11,0.5),0_30px_60px_rgba(0,0,0,0.95)] w-full overflow-hidden rounded-2xl z-10" style="background-color: #0f172a !important; color: #ffffff !important;">
            <div class="h-1.5 w-full bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500"></div>
            <div class="p-6 sm:p-7">
                <div class="flex items-start gap-4 mb-5">
                    <div class="shrink-0 rounded-2xl shadow-lg" style="background-color: rgba(245, 158, 11, 0.25) !important; border: 2px solid rgba(251, 191, 36, 0.5) !important; padding: 0.75rem !important;">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #fbbf24 !important; stroke: #fbbf24 !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="quick-reading-dialog-title" style="color: #ffffff !important; font-weight: 900 !important; font-size: 1.25rem !important; margin: 0 !important; line-height: 1.2 !important;">Reading Verification Warning</h3>
                        <p id="quick-reading-dialog-subtitle" style="color: #fbbf24 !important; font-weight: 400 !important; font-size: 0.875rem !important; margin-top: 0.25rem !important; margin-bottom: 0 !important;">Reading is less than or equal to previous reading</p>
                    </div>
                </div>
                <div style="background-color: #1e1b18 !important; border: 2px solid #f59e0b !important; padding: 1rem !important; border-radius: 0.75rem !important; margin-bottom: 1.25rem !important; box-shadow: inset 0 2px 4px rgba(0,0,0,0.5) !important;">
                    <p id="quick-reading-dialog-msg" style="color: #ffffff !important; font-weight: 400 !important; font-size: 0.95rem !important; line-height: 1.5 !important; margin: 0 !important; white-space: pre-line;"></p>
                </div>
                <p style="color: #fca5a5 !important; font-weight: 500 !important; font-size: 0.95rem !important; margin-bottom: 1.5rem !important;">You cannot proceed. Present reading must be greater than previous reading.</p>
                <div class="flex justify-end">
                    <button type="button" onclick="hideQuickReadingWarningDialog()" style="background-color: #fbbf24 !important; color: #0f172a !important; border: none !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 900 !important; font-size: 0.875rem !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important; gap: 0.5rem !important; box-shadow: 0 4px 20px rgba(251, 191, 36, 0.4) !important;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #0f172a !important; stroke: #0f172a !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Go Back & Correct Reading
                    </button>
                </div>
            </div>
        </div>
    </dialog>

    {{-- ===== Admin Edit Meter Reading Modal ===== --}}
    <div id="editCustomerReadingModalAdmin" class="fixed inset-0 flex items-center justify-center p-4 hidden" style="z-index: 9999999 !important;" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" style="z-index: 1 !important;" onclick="closeAdminEditReadingModal()"></div>
        <div class="relative bg-[#0f1722] border-2 border-amber-400/50 rounded-3xl shadow-[0_25px_80px_rgba(0,0,0,0.95),0_0_50px_rgba(245,158,11,0.25)] w-full max-w-md p-6 overflow-hidden text-gray-200" style="animation: adminModalPop 0.2s cubic-bezier(0.34, 1.3, 0.64, 1) both; z-index: 2 !important;">
            {{-- Top Accent Bar --}}
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-500 via-orange-400 to-amber-500"></div>

            <div class="flex items-center justify-between pb-4 border-b border-[#263548] mb-5 mt-1">
                <div class="flex items-center gap-2.5">
                    <div class="p-2.5 bg-amber-500/20 text-amber-400 rounded-2xl border border-amber-500/30 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-black text-lg" id="admin-edit-reading-title">Edit Meter Reading</h3>
                        <p class="text-xs text-amber-400 font-medium" id="admin-edit-reading-subtitle">Consumer Info</p>
                    </div>
                </div>
                <button type="button" onclick="closeAdminEditReadingModal()" class="text-gray-400 hover:text-rose-400 p-1.5 rounded-xl hover:bg-[#1b2636] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <input type="hidden" id="admin-edit-bill-id">
            <input type="hidden" id="admin-edit-customer-id">
            <input type="hidden" id="admin-edit-customer-type">
            <input type="hidden" id="admin-edit-prev-reading">

            <div class="space-y-4">
                <div class="bg-[#121a25]/90 p-4 rounded-2xl border border-[#263548] flex justify-between items-center shadow-inner">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Previous Reading</span>
                    <span class="font-mono text-cyan-400 font-bold text-base" id="admin-edit-prev-display">0 m³</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-amber-400 mb-2 uppercase tracking-wider">New Meter Reading (m³)</label>
                    <div class="relative">
                        <input type="number" step="1" id="admin-edit-new-reading-input"
                            class="w-full bg-[#1b2636]/90 border border-[#2d4059] focus:border-amber-400 text-white text-2xl font-mono font-black rounded-2xl py-3 pl-4 pr-12 outline-none transition shadow-inner"
                            oninput="recalcAdminEditReading()" placeholder="0">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold font-mono">m³</span>
                    </div>
                </div>

                <div id="admin-edit-calc-preview" class="p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl hidden"></div>
                <div id="admin-edit-error-msg" class="text-xs text-rose-400 font-mono font-medium p-3 bg-rose-500/10 border border-rose-500/20 rounded-2xl hidden"></div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-[#263548]">
                <button type="button" onclick="closeAdminEditReadingModal()" class="px-4 py-2.5 bg-[#1b2636] hover:bg-[#263548] text-gray-300 rounded-xl text-xs font-semibold transition border border-[#2d4059]">
                    Cancel
                </button>
                <button type="button" id="btn-save-admin-reading" onclick="saveAdminEditReading()" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-gray-950 rounded-xl text-xs font-black transition shadow-lg shadow-amber-500/30 flex items-center gap-1.5 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Save Reading</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Create Consumer Account Modal -->
    <div id="createConsumerAccountModal" class="fixed inset-0 flex items-center justify-center p-4 hidden" style="z-index: 9999999 !important;" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" style="z-index: 1 !important;" onclick="closeCreateConsumerAccountModal()"></div>
        <div class="relative bg-[#0f1722] border-2 border-cyan-500/50 rounded-3xl shadow-[0_25px_80px_rgba(0,0,0,0.95),0_0_50px_rgba(6,182,212,0.25)] w-full max-w-md p-6 overflow-hidden text-gray-200" style="animation: adminModalPop 0.2s cubic-bezier(0.34, 1.3, 0.64, 1) both; z-index: 2 !important;">
            {{-- Top Accent Bar --}}
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-cyan-500 via-blue-500 to-cyan-500"></div>

            <div class="flex items-center justify-between pb-4 border-b border-[#263548] mb-5 mt-1">
                <div class="flex items-center gap-2.5">
                    <div class="p-2.5 bg-cyan-500/20 text-cyan-400 rounded-2xl border border-cyan-500/30 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-black text-lg">Create Consumer Account</h3>
                        <p class="text-xs text-cyan-400 font-medium" id="create-account-consumer-info">Consumer Info</p>
                    </div>
                </div>
                <button type="button" onclick="closeCreateConsumerAccountModal()" class="text-gray-400 hover:text-rose-400 p-1.5 rounded-xl hover:bg-[#1b2636] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="create-consumer-account-form" method="POST" action="">
                @csrf
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-gray-200 uppercase tracking-wider">
                                Username <span class="text-red-400">*</span>
                            </label>
                            <span class="text-[10px] text-gray-400">Custom or Account No.</span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="username" id="create_account_username" required
                                class="w-full bg-[#1b2636]/90 border border-[#2d4059] focus:border-cyan-400 text-white text-sm rounded-2xl py-2.5 pl-10 pr-4 outline-none transition shadow-inner font-mono">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-gray-200 uppercase tracking-wider">
                                Password <span class="text-red-400">*</span>
                            </label>
                            <button type="button" onclick="generateRandomAccountPassword()" class="text-[11px] text-cyan-400 hover:text-cyan-300 font-semibold flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Auto-generate
                            </button>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="create_account_password" required minlength="6"
                                class="w-full bg-[#1b2636]/90 border border-[#2d4059] focus:border-cyan-400 text-white text-sm rounded-2xl py-2.5 pl-10 pr-10 outline-none transition shadow-inner font-mono">
                            <button type="button" onclick="toggleAccountPasswordVisibility()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition-colors focus:outline-none">
                                <svg id="create_account_eye_icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">Minimum 6 characters. Consumer will use this to sign in.</p>
                    </div>

                    <div class="p-3 bg-cyan-950/40 border border-cyan-800/40 rounded-2xl flex items-start gap-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-cyan-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-cyan-200/90 leading-relaxed">
                            Once created, the consumer can log into the Consumer Portal using either their <strong>Username</strong> or <strong>Account Number</strong> along with this password.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-[#263548]">
                    <button type="button" onclick="closeCreateConsumerAccountModal()" class="px-4 py-2.5 bg-[#1b2636] hover:bg-[#263548] text-gray-300 rounded-xl text-xs font-semibold transition border border-[#2d4059]">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white rounded-xl text-xs font-black transition shadow-lg shadow-cyan-600/30 flex items-center gap-1.5 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Create Account</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes adminModalPop {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>

    <script>
        // ===== Reading Same/Lower Warning Dialog Helpers =====
        function showQuickReadingWarningDialog(title, subtitle, message) {
            document.getElementById('quick-reading-dialog-title').textContent = title;
            document.getElementById('quick-reading-dialog-subtitle').textContent = subtitle;
            document.getElementById('quick-reading-dialog-msg').innerText = message;
            const dialog = document.getElementById('quick-reading-warning-dialog');
            if (dialog.parentElement !== document.body) {
                document.body.appendChild(dialog);
            }
            dialog.style.display = 'flex';
            if (typeof dialog.showModal === 'function') {
                try { dialog.showModal(); } catch (e) {}
            }
            document.body.style.overflow = 'hidden';
        }

        function hideQuickReadingWarningDialog() {
            const dialog = document.getElementById('quick-reading-warning-dialog');
            if (dialog) {
                if (typeof dialog.close === 'function') {
                    try { dialog.close(); } catch (e) {}
                }
                dialog.style.display = 'none';
            }
            document.body.style.overflow = '';
        }

        // ===== Duplicate Bill Dialog Helpers =====
        function showDupDialog(message, onConfirm) {
            document.getElementById('dup-dialog-msg').textContent = message;
            const dialog = document.getElementById('dup-bill-dialog');
            
            if (dialog.parentElement !== document.body) {
                document.body.appendChild(dialog);
            }

            dialog.style.display = 'flex';
            if (typeof dialog.showModal === 'function') {
                try { dialog.showModal(); } catch (e) {}
            }
            document.body.style.overflow = 'hidden';

            document.getElementById('dup-dialog-confirm').onclick = function() {
                hideDupDialog();
                onConfirm();
            };
        }

        function hideDupDialog() {
            const dialog = document.getElementById('dup-bill-dialog');
            if (dialog) {
                if (typeof dialog.close === 'function') {
                    try { dialog.close(); } catch (e) {}
                }
                dialog.style.display = 'none';
            }
            document.body.style.overflow = '';
        }

        // Intercept quick-bill-form submit to run reading check and duplicate check
        document.addEventListener('DOMContentLoaded', function() {
            const dupDialog = document.getElementById('dup-bill-dialog');
            if (dupDialog) {
                dupDialog.addEventListener('cancel', function(e) {
                    e.preventDefault(); // Prevent Escape key from closing warning
                });
            }

            const readingDialog = document.getElementById('quick-reading-warning-dialog');
            if (readingDialog) {
                readingDialog.addEventListener('cancel', function(e) {
                    e.preventDefault();
                });
            }

            const quickForm = document.getElementById('quick-bill-form');
            if (quickForm) {
                quickForm.addEventListener('submit', function(e) {
                    const prInput = document.getElementById('modal_present_reading');
                    const presentReading = parseFloat(prInput ? prInput.value : 0) || 0;
                    const isFirst = quickIsFirstReading || quickPrevReading === 0;

                    // 1. Reading Same or Lower Check (Cannot Proceed)
                    if (!isFirst && presentReading <= quickPrevReading) {
                        e.preventDefault();
                        const isEqual = presentReading === quickPrevReading;
                        const title = isEqual ? 'Cannot Proceed: Unchanged Reading' : 'Cannot Proceed: Lower Reading';
                        const subtitle = isEqual ? 'Present reading is equal to previous reading' : 'Present reading is lower than previous reading';
                        const msg = isEqual
                            ? `The present reading (${presentReading} m³) is equal to the previous reading (${quickPrevReading} m³).\n\nYou cannot proceed with generating this bill because the present reading must be greater than the previous reading.`
                            : `The present reading (${presentReading} m³) is lower than the previous reading (${quickPrevReading} m³).\n\nYou cannot proceed with generating this bill because the present reading must be greater than the previous reading.`;

                        showQuickReadingWarningDialog(title, subtitle, msg);
                        return;
                    }

                    // 2. Duplicate Bill Check
                    if (document.getElementById('modal_force_billing').value === '1') return; // already confirmed
                    if (!window._quickBillDuplicate) return; // no duplicate found
                    e.preventDefault();
                    showDupDialog(window._quickBillDuplicateMsg, function() {
                        document.getElementById('modal_force_billing').value = '1';
                        quickForm.submit();
                    });
                });
            }
        });

        function toggleCustomerBills(customerId, checked) {
            document.querySelectorAll('.customer-bill-cb-' + customerId).forEach(cb => {
                cb.checked = checked;
            });
        }

        function submitCustomerReceiptsBatch(customerId) {
            const checked = document.querySelectorAll('.customer-bill-cb-' + customerId + ':checked');
            if (checked.length === 0) {
                alert('Please select at least one bill to print receipts.');
                return;
            }
            const form = document.getElementById('printCustomerReceiptsBatchForm-' + customerId);
            if (!form) return;
            form.querySelectorAll('input[name="bill_ids[]"]').forEach(el => el.remove());
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'bill_ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });
            form.submit();
        }

        // ===== Create Consumer Account Modal Functions =====
        function openCreateConsumerAccountModal(customerId, customerAcctNo, customerName) {
            const form = document.getElementById('create-consumer-account-form');
            if (form) {
                form.action = `/customers/${customerId}/create-account`;
            }

            const infoEl = document.getElementById('create-account-consumer-info');
            if (infoEl) {
                infoEl.textContent = `${customerName} (Acct #${customerAcctNo})`;
            }

            const usernameInput = document.getElementById('create_account_username');
            if (usernameInput) {
                usernameInput.value = customerAcctNo;
            }
            
            generateRandomAccountPassword();

            const modal = document.getElementById('createConsumerAccountModal');
            if (modal) {
                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            setTimeout(() => {
                const u = document.getElementById('create_account_username');
                if (u) u.focus();
            }, 60);
        }

        function closeCreateConsumerAccountModal() {
            const modal = document.getElementById('createConsumerAccountModal');
            if (modal) {
                modal.classList.add('hidden');
            }
            document.body.style.overflow = '';
        }

        function toggleAccountPasswordVisibility() {
            const input = document.getElementById('create_account_password');
            const icon = document.getElementById('create_account_eye_icon');
            if (!input || !icon) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 0110.665 4.937c-1.274 4.057-5.064 7-9.542 7-1.07 0-2.1-.17-3.064-.486m-2.868-2.868A8.966 8.966 0 013 12c.5-1.278 1.258-2.42 2.215-3.375" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />`;
            } else {
                input.type = 'password';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        }

        function generateRandomAccountPassword() {
            const chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            let pwd = '';
            for (let i = 0; i < 8; i++) {
                pwd += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            const input = document.getElementById('create_account_password');
            if (input) input.value = pwd;
        }

        function generateCreateModalPassword() {
            const chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            let pwd = '';
            for (let i = 0; i < 8; i++) {
                pwd += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            const input = document.getElementById('create_modal_password');
            if (input) input.value = pwd;
        }
    </script>
</x-layouts::app>
