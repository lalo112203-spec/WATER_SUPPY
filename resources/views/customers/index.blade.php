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

        @if(session('billing_warning'))
            @php $bWarn = session('billing_warning'); @endphp
            <div class="mb-4 bg-amber-500/10 border border-amber-500/30 text-amber-300 px-5 py-3 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="text-sm">A bill of <strong>₱{{ $bWarn['amount'] }}</strong> already exists for <strong>{{ $bWarn['customer_name'] }}</strong> for <strong>{{ $bWarn['month'] }}</strong>.</span>
                </div>
                <button type="button" onclick="this.closest('.mb-4').remove()" class="text-xs bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 px-3 py-1.5 rounded-lg border border-amber-500/30 transition">Dismiss</button>
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
                        <tr id="row-{{ $customer->id }}" 
                            class="hover:bg-[#1b2636]/60 transition duration-300 cursor-pointer group border-l-2 border-transparent"
                            onclick="toggleDetails('{{ $customer->id }}')">
                            <td class="px-6 py-4 font-medium text-gray-300 flex items-center gap-3">
                                <svg id="chevron-{{ $customer->id }}" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-200 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                {{ $customer->customer_id }}
                            </td>
                            <td class="px-6 py-4 max-w-[160px]">
                                <div class="text-sm font-medium text-gray-200 truncate" title="{{ $customer->name }}">{{ $customer->name }}</div>
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
                            <td class="px-6 py-4 font-bold text-cyan-400 text-sm">
                                {{ number_format($customer->meter_reading ?? 0, 0) }}m³
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
                                        data-id="{{ $customer->id }}"
                                        data-name="{{ $customer->name }}"
                                        data-customer-id="{{ $customer->customer_id }}"
                                        data-type="{{ $customer->type }}"
                                        data-prev-reading="{{ $customer->meter_reading ?? 0 }}"
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
                                            <span class="text-lg text-emerald-400 font-black font-mono">{{ number_format($customer->meter_reading ?? 0, 0) }} m³</span>
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
                                                <span class="text-sm text-gray-200 font-bold font-mono">{{ $customer->customer_id }}</span>
                                            </div>
                                            <div class="flex flex-col gap-1 mt-2">
                                                <span class="text-[10px] uppercase tracking-widest text-white font-bold">Account Password</span>
                                                <span class="text-sm text-rose-300 font-mono tracking-wider">{{ $customer->user->plain_password ?? '********' }}</span>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center justify-center h-full text-center">
                                                <span class="text-[10px] uppercase tracking-widest text-gray-600 font-bold mb-2">No User Account</span>
                                                <form action="{{ route('customers.create-account', $customer) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] bg-cyan-600/20 hover:bg-cyan-600/40 text-cyan-400 border border-cyan-500/30 px-3 py-1 rounded-lg transition">
                                                        Create Account
                                                    </button>
                                                </form>
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
                                        <h4 class="text-[10px] uppercase tracking-widest text-gray-200 font-bold mb-4">Reading & Bill History</h4>
                                        <div class="overflow-x-auto rounded-xl border border-[#2d4059]/30 bg-[#0f1722]/40">
                                            <table class="w-full text-left border-collapse">
                                                <thead>
                                                    <tr class="bg-[#1b2636]/40 text-[#94a3b8] text-[10px] uppercase tracking-wider">
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
                                                        <tr class="text-xs text-gray-300 hover:bg-[#1b2636]/20">
                                                            <td class="px-4 py-2 font-medium">{{ $bill->billing_date->format('F Y') }}</td>
                                                            <td class="px-4 py-2 font-mono">{{ number_format($bill->usage_units, 0) }} m³</td>
                                                            <td class="px-4 py-2 text-center">
                                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                                                    {{ $bill->consumption <= ($customer->type === 'Commercial' ? 49 : 10) ? 'bg-emerald-500/20 text-emerald-400' : ($bill->consumption <= ($customer->type === 'Commercial' ? 50 : 14) ? 'bg-orange-500/20 text-orange-400' : 'bg-rose-500/20 text-rose-400') }}">
                                                                    {{ number_format($bill->consumption, 0) }} m³
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-2 font-bold text-cyan-400">₱{{ number_format($bill->total_amount, 0) }}</td>
                                                            <td class="px-4 py-2">
                                                                <span class="capitalize {{ $bill->status === 'paid' ? 'text-emerald-400' : 'text-rose-400' }}">{{ $bill->status }}</span>
                                                            </td>
                                                            <td class="px-4 py-2 text-right">
                                                                <div class="flex justify-end gap-1">
                                                                    <a href="{{ route('billing.receipt', $bill) }}" target="_blank" class="p-1 text-cyan-400 hover:bg-cyan-500/10 rounded transition" title="View Receipt">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                                        </svg>
                                                                    </a>
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
                                                            <td colspan="6" class="px-4 py-4 text-center text-gray-200 italic text-[10px]">No billing history available.</td>
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
        <div class="p-4 bg-[#121a25] text-gray-200 rounded-xl max-h-[85vh] overflow-y-auto custom-scrollbar">
            <flux:heading size="lg" class="mb-2 !text-white">Quick Add Reading</flux:heading>
            <flux:subheading id="modal-customer-name" class="mb-6 !text-gray-400">Consumer Name</flux:subheading>

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
                            <label for="modal_present_reading" class="block text-sm font-medium text-gray-300">Present Reading (m³)</label>
                            <input type="number" step="1" id="modal_present_reading" name="new_reading" required
                                oninput="calculateQuickCharges()" placeholder="Enter reading..."
                                class="w-full bg-[#0f1722]/80 border border-[#2d4059] focus:border-emerald-500/50 text-emerald-400 placeholder-gray-500 text-2xl font-black rounded-xl py-3 px-4 outline-none transition-all duration-300 shadow-inner">
                        </div>

                        <div id="modal_calc_breakdown" class="text-xs mt-3 min-h-[1.25rem] text-zinc-500"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Base Charge (₱)</label>
                            <input type="number" step="0.01" name="base_charge" id="modal_base_charge" oninput="updateQuickTotal()" class="w-full bg-[#0f1722] border border-[#2d4059] text-gray-200 px-3 py-2 rounded-xl text-sm outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Usage Charge (₱)</label>
                            <input type="number" step="0.01" name="usage_charge" id="modal_usage_charge" oninput="updateQuickTotal()" class="w-full bg-[#0f1722] border border-[#2d4059] text-gray-200 px-3 py-2 rounded-xl text-sm outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <div class="bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/30 flex justify-between items-center px-6">
                        <span class="text-emerald-500 font-bold uppercase tracking-widest text-sm">Total Bill</span>
                        <div class="text-right">
                            <span class="text-emerald-400 font-bold text-3xl">₱<span id="modal_total_display">0</span></span>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <flux:button type="submit" id="modal_submit_btn" variant="primary" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 transition-all font-bold">Generate Bill</flux:button>
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
                        <label class="block text-gray-200 mb-1 text-xs font-medium">Password <span class="text-red-500">*</span></label>
                        <div class="relative w-full md:w-1/2">
                            <input :type="show ? 'text' : 'password'" name="password" id="password" class="w-full px-3 py-2 pr-10 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
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
                        <p class="text-[10px] text-gray-400 mt-1">Minimum 8 characters. Login using Account Number and this password.</p>
                        @if(old('form_type') === 'create') @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror @endif
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
            openQuickBillModal(id, name, customerId, type, prevReading);
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
        window.safeShowModal = safeShowModal;

        let quickCustomerType = 'Regular';
        let quickPrevReading = 0;

        function openQuickBillModal(id, name, customerId, type, prevReading) {
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
                    presentReadingEl.min = prevReadingNum;
                }
                
                const totalDisplayEl = document.getElementById('modal_total_display');
                if (totalDisplayEl) totalDisplayEl.textContent = '0';
                
                const calcBreakdownEl = document.getElementById('modal_calc_breakdown');
                if (calcBreakdownEl) calcBreakdownEl.textContent = '';
                
                const baseChargeEl = document.getElementById('modal_base_charge');
                if (baseChargeEl) baseChargeEl.value = 0;
                
                const usageChargeEl = document.getElementById('modal_usage_charge');
                if (usageChargeEl) usageChargeEl.value = 0;

                const dupWarn = document.getElementById('modal-duplicate-warning');
                if (dupWarn) dupWarn.classList.add('hidden');
                const dupWarnText = document.getElementById('modal-duplicate-warning-text');
                if (dupWarnText) dupWarnText.textContent = '';
                
                const forceBillingEl = document.getElementById('modal_force_billing');
                if (forceBillingEl) forceBillingEl.value = '0';
                
                const forceCb = document.getElementById('modal-force-checkbox');
                if (forceCb) forceCb.checked = false;
                
                const submitBtn = document.getElementById('modal_submit_btn');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                quickCustomerType = type || 'Regular';
                quickPrevReading = prevReadingNum;

                window._quickBillDuplicate = false;
                window._quickBillDuplicateMsg = '';

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
                        const _now = new Date();
                        const readings = data.readings || [];
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

            if (input.value === '') {
                baseInput.value = 0;
                usageInput.value = 0;
                hiddenConsumption.value = 0;
                updateQuickTotal();
                breakdown.textContent = '';
                return;
            }

            const presentReading = parseFloat(input.value) || 0;
            
            const submitBtn = document.getElementById('modal_submit_btn');

            if (presentReading < quickPrevReading) {
                breakdown.textContent = `Invalid: Reading cannot be lower than previous (${quickPrevReading})`;
                breakdown.className = 'text-xs mt-1 text-rose-500 font-bold';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
                baseInput.value = 0;
                usageInput.value = 0;
                hiddenConsumption.value = 0;
                updateQuickTotal();
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            const consumption = Math.max(0, presentReading - quickPrevReading);
            
            hiddenConsumption.value = consumption.toFixed(0);

            let baseCharge = 0;
            let rate = 0;
            let baseLimit = 10;

            let typeKey = quickCustomerType.toLowerCase();
            baseCharge = parseFloat(systemSettings[typeKey + '_base_charge']) || 100;
            rate = parseFloat(systemSettings[typeKey + '_usage_rate']) || 15;
            baseLimit = parseFloat(systemSettings[typeKey + '_base_limit']) || 10;

            const billableUsage = Math.max(consumption - baseLimit, 0);
            const usageCharge = billableUsage * rate;
            const total = baseCharge + usageCharge + globalAdditionalChargeTotal;

            baseInput.value = baseCharge.toFixed(0);
            usageInput.value = usageCharge.toFixed(0);
            
            updateQuickTotal();

            let breakdownText = `Consumption: ${consumption.toFixed(0)}m³ | (${consumption.toFixed(0)} - ${baseLimit}) × ₱${rate} = ₱${usageCharge.toFixed(0)}`;
            if (globalAdditionalChargeTotal > 0) {
                breakdownText += ` + ₱${globalAdditionalChargeTotal.toFixed(0)} (Additional Charges)`;
            }
            breakdown.textContent = breakdownText;
            
            // Color coding breakdown based on total consumption
            breakdown.className = 'text-xs mt-1 transition-colors duration-300';
            if (consumption > 0 && consumption <= 20) breakdown.classList.add('text-emerald-400');
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

    <script>
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

        // Intercept quick-bill-form submit to run duplicate check
        document.addEventListener('DOMContentLoaded', function() {
            const dialog = document.getElementById('dup-bill-dialog');
            if (dialog) {
                dialog.addEventListener('cancel', function(e) {
                    e.preventDefault(); // Prevent Escape key from closing warning
                });
            }

            const quickForm = document.getElementById('quick-bill-form');
            if (quickForm) {
                quickForm.addEventListener('submit', function(e) {
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
    </script>
</x-layouts::app>
