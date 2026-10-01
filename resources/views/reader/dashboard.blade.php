<x-layouts::app title="Meter Reader Dashboard">
    <div class="px-4 sm:px-6 py-4 bg-transparent min-h-[calc(100vh-4rem)] font-sans text-gray-200 relative z-10 max-w-6xl mx-auto">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-100 flex items-center gap-3 drop-shadow-md">
                    <div class="bg-cyan-500/20 p-2 rounded-xl text-cyan-400 border border-cyan-500/30 shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    Meter Reading Terminal
                </h1>
                <p class="text-white/80 mt-2 text-sm font-medium drop-shadow">Enter the current meter readings for consumers below.</p>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm bg-[#1b2636]/60 hover:bg-rose-500/20 hover:text-rose-400 text-gray-300 hover:border-rose-500/50 rounded-xl px-4 py-2 transition-all duration-300 border border-[#2d4059] flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-6 py-4 rounded-2xl flex items-center justify-between shadow-[0_0_20px_rgba(16,185,129,0.1)]">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif


        @if($errors->any())
            <div class="mb-6 bg-rose-500/10 border border-rose-500/30 text-rose-400 px-6 py-4 rounded-2xl flex items-start gap-3 shadow-[0_0_20px_rgba(244,63,94,0.1)]">
                <svg class="w-6 h-6 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <span class="font-medium">Failed to submit reading.</span>
                    <ul class="list-disc list-inside mt-1 text-sm opacity-90">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="mb-8 space-y-4">
            {{-- Search & Barangay Dropdown Bar --}}
            <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                {{-- Search Bar --}}
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput" oninput="filterConsumers()" placeholder="Search by Name, Account No, or Brgy..."
                        class="w-full border border-[#263548] focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 text-gray-200 text-sm rounded-2xl py-3.5 pl-12 pr-4 outline-none transition-all duration-300 shadow-[0_8px_30px_rgb(0,0,0,0.4)]"
                        style="background-color: #121a25 !important; color: #f3f4f6 !important;">
                </div>

                {{-- Barangay Dropdown Selector --}}
                <div class="relative md:w-72 shrink-0">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyan-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <select id="barangaySelect" onchange="selectBarangay(this.value)"
                        class="w-full border border-[#263548] focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 text-gray-100 text-sm font-semibold rounded-2xl py-3.5 pl-10 pr-9 outline-none transition-all duration-300 shadow-[0_8px_30px_rgb(0,0,0,0.4)] appearance-none cursor-pointer"
                        style="background-color: #121a25 !important; color: #f3f4f6 !important;">
                        <option value="all">📍 All Barangays ({{ $groupedCustomers->flatten(1)->count() }})</option>
                        @foreach($groupedCustomers as $barangay => $group)
                            <option value="{{ $barangay }}">📍 {{ $barangay }} ({{ $group->count() }})</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Interactive Barangay Buttons / Quick Filter Pills --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-2 pt-1 scrollbar-thin scrollbar-thumb-cyan-500/30">
                <span class="text-xs text-cyan-400 font-bold uppercase tracking-wider whitespace-nowrap shrink-0 flex items-center gap-1.5 mr-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Choose Barangay:
                </span>

                <button type="button" 
                    id="brgy-btn-all"
                    onclick="selectBarangay('all')" 
                    data-brgy="all"
                    class="brgy-filter-btn px-4 py-2 rounded-xl text-xs font-black transition-all duration-200 whitespace-nowrap shrink-0 flex items-center gap-2 bg-cyan-500 text-gray-950 shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                    All Barangays
                    <span class="brgy-count-badge px-1.5 py-0.5 rounded-md text-[10px] font-mono bg-black/20 text-gray-950 font-bold">
                        {{ $groupedCustomers->flatten(1)->count() }}
                    </span>
                </button>

                @foreach($groupedCustomers as $barangay => $group)
                    <button type="button" 
                        id="brgy-btn-{{ \Illuminate\Support\Str::slug($barangay) }}"
                        onclick="selectBarangay('{{ addslashes($barangay) }}')" 
                        data-brgy="{{ $barangay }}"
                        class="brgy-filter-btn px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-200 whitespace-nowrap shrink-0 flex items-center gap-2 bg-[#121a25]/80 hover:bg-[#1b2636] text-gray-300 hover:text-white border border-[#263548]">
                        {{ $barangay }}
                        <span class="brgy-count-badge px-1.5 py-0.5 rounded-md text-[10px] font-mono bg-[#1b2636] text-gray-400 border border-[#2d4059]">
                            {{ $group->count() }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <div id="consumerGroups">
            @forelse($groupedCustomers as $barangay => $group)
                <div class="brgy-group mb-12" data-barangay="{{ $barangay }}">
                    <div class="flex items-center gap-3 mb-6 border-b border-[#263548] pb-3">
                        <div class="bg-cyan-400/30 p-2 rounded-lg text-cyan-300 border border-cyan-400/40 shadow-[0_0_10px_rgba(34,211,238,0.3)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-100">{{ $barangay }}</h2>
                        <span class="bg-[#1b2636] text-white/70 text-xs px-3 py-1 rounded-lg border border-[#2d4059] ml-2 shadow-inner">
                            {{ $group->count() }} Consumers
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($group as $customer)
                            @php
                                $latestBill = $customer->bills->sortByDesc('id')->first();
                                $latestBillPeriod = $latestBill ? ($latestBill->billing_date ? $latestBill->billing_date->format('F Y') : now()->format('F Y')) : '';
                                $latestBillPrev = $latestBill ? (float)$latestBill->previous_reading : 0;
                                $latestBillReading = $latestBill ? (float)$latestBill->new_reading : (float)($customer->meter_reading ?? 0);
                            @endphp
                            <div class="consumer-card bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] border border-[#263548] p-6 relative overflow-hidden group hover:border-cyan-500/30 hover:shadow-[0_8px_30px_rgba(6,182,212,0.15)] transition-all duration-500 flex flex-col justify-between h-full"
                                id="consumer-card-{{ $customer->id }}"
                                data-customer-id="{{ $customer->id }}"
                                data-customer-name="{{ $customer->name }}"
                                data-customer-acct="{{ $customer->customer_id }}"
                                data-customer-type="{{ $customer->customerType ? $customer->customerType->name : $customer->type }}"
                                data-meter-reading="{{ $customer->meter_reading ?? 0 }}"
                                data-latest-bill-id="{{ $latestBill ? $latestBill->id : '' }}"
                                data-latest-bill-prev="{{ $latestBillPrev }}"
                                data-latest-bill-reading="{{ $latestBillReading }}"
                                data-latest-bill-period="{{ $latestBillPeriod }}">
                                
                                <div class="absolute -right-10 -top-10 bg-cyan-600/5 h-32 w-32 rounded-full blur-3xl pointer-events-none group-hover:bg-cyan-600/15 transition-all duration-700"></div>

                                <div class="mb-6 z-10">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="bg-gray-800/50 p-2 rounded-xl border border-[#2d4059] flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <h2 class="text-lg font-bold text-gray-100 truncate consumer-name" title="{{ $customer->name }}">
                                            {{ $customer->name }}
                                        </h2>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 mt-3">
                                        <span class="bg-[#1b2636] text-white/80 text-xs font-mono px-3 py-1 rounded-lg border border-[#2d4059] consumer-account">
                                            {{ $customer->customer_id }}
                                        </span>
                                        <span class="bg-[#1b2636] text-white/80 text-xs px-3 py-1 rounded-lg border border-[#2d4059]">
                                            {{ $customer->customerType ? $customer->customerType->name : $customer->type }}
                                        </span>
                                        @if($customer->barangay)
                                        <span class="bg-indigo-500/10 text-indigo-400 text-xs px-3 py-1 rounded-lg border border-indigo-500/20 consumer-brgy flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $customer->barangay }}
                                        </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t border-[#263548]">
                                        <div>
                                            <p class="text-xs text-white/70 font-medium mb-1">Previous Reading / Current System Value</p>
                                            <p class="text-xl font-mono text-white font-bold" id="card-reading-val-{{ $customer->id }}">{{ number_format($customer->meter_reading ?? 0, 0) }} <span class="text-xs text-white/60">m³</span></p>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('reader.storeReading') }}" class="mt-auto relative z-10 bg-[#0f1722]/50 p-4 rounded-2xl border border-[#263548] reader-reading-form" data-customer-id="{{ $customer->id }}" data-customer-name="{{ $customer->name }}">
                                    @csrf
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    <input type="hidden" name="force_billing" value="0" class="force-billing-input">
                                    
                                    <label class="block text-xs font-bold text-cyan-500 mb-2 uppercase tracking-wider">Input New Reading</label>
                                    
                                    <div class="flex gap-2 items-center">
                                        @php
                                            $isFirstReading = ($customer->bills_count ?? $customer->bills->count()) === 0;
                                        @endphp
                                        <div class="relative flex-1">
                                            <input type="number" step="1" name="reading" id="reading-{{ $customer->id }}" required placeholder="0"
                                                min="0"
                                                oninput="calculateBill({{ $customer->id }}, '{{ $customer->customerType ? $customer->customerType->name : $customer->type }}', {{ $customer->meter_reading ?? 0 }}, {{ $isFirstReading ? 'true' : 'false' }})"
                                                class="w-full border border-[#2d4059] focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 text-gray-100 text-lg font-mono rounded-xl py-2 pl-3 pr-8 outline-none transition-all duration-300 placeholder:text-gray-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                style="background-color: #1b2636 !important; color: #f3f4f6 !important; -moz-appearance: textfield;">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold pointer-events-none">m³</span>
                                        </div>
                                        <button type="submit" id="btn-{{ $customer->id }}" class="bg-cyan-600 hover:bg-cyan-500 text-white p-2 rounded-xl shadow-[0_4px_15px_rgba(6,182,212,0.4)] transition-all duration-300 flex items-center justify-center shrink-0" title="Submit New Reading">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button type="button"
                                            onclick="openEditFromCard({{ $customer->id }})"
                                            id="btn-edit-row-{{ $customer->id }}"
                                            class="bg-amber-500/20 hover:bg-amber-500/30 text-amber-400 border border-amber-500/30 p-2 rounded-xl transition-all duration-300 flex items-center justify-center shrink-0"
                                            title="Edit Meter Reading">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button type="button"
                                            onclick="openBillHistory({{ $customer->id }}, {{ Js::from($customer->name) }}, {{ Js::from($customer->customer_id) }}); event.preventDefault();"
                                            class="bg-violet-600/20 hover:bg-violet-500/30 text-violet-400 border border-violet-500/30 p-2 rounded-xl transition-all duration-300 flex items-center justify-center shrink-0"
                                            title="View Bill History">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p id="calc-{{ $customer->id }}" class="text-xs text-cyan-400 mt-2 font-mono h-4"></p>
                                </form>
                            </div>
                        @endforeach

                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 bg-[#121a25]/80 backdrop-blur-md rounded-3xl border border-[#263548]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <p class="text-xl font-medium text-gray-400">No active consumers found.</p>
                </div>
            @endforelse

            <div id="noResultsNotice" class="text-center py-16 px-4 bg-[#121a25]/60 rounded-3xl border border-[#263548] text-gray-400" style="display: none;">
                <svg class="h-12 w-12 mx-auto mb-3 text-cyan-400 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-base font-bold text-gray-200">No Consumers Found</p>
                <p class="text-xs text-gray-400 mt-1">No consumers match the selected barangay or search criteria.</p>
            </div>
        </div>
    </div>

    {{-- ===== Bill History Modal ===== --}}
    <div id="billHistoryModal" class="fixed inset-0 flex items-center justify-center p-4 hidden" style="z-index: 999990 !important;" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" style="z-index: 1 !important;" onclick="closeBillHistory()"></div>

        {{-- Panel --}}
        <div class="relative bg-[#0f1722] border border-[#263548] rounded-3xl shadow-[0_25px_60px_rgba(0,0,0,0.8)] w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden" style="animation: bhSlideUp 0.25s cubic-bezier(0.34,1.56,0.64,1) both; z-index: 2 !important;">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-[#263548] bg-[#121a25]/80 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-violet-500/20 rounded-xl border border-violet-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-base" id="bh-consumer-name">Bill History</h3>
                        <p class="text-xs text-gray-400" id="bh-consumer-id"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="printBhCustomerHistory()" class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow transition" title="Print Complete Billing History">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print History
                    </button>
                    <button onclick="closeBillHistory()" class="text-gray-500 hover:text-rose-400 transition-colors p-1.5 rounded-lg hover:bg-rose-500/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Summary bar --}}
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 px-6 py-3 bg-[#0a1018]/60 border-b border-[#263548] text-xs shrink-0">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-violet-400"></span>
                    <span class="text-gray-400">Total: <span id="bh-total-count" class="text-white font-bold">–</span></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    <span class="text-gray-400">Paid: <span id="bh-paid-count" class="text-emerald-400 font-bold">–</span></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-rose-400 animate-pulse"></span>
                    <span class="text-gray-400">Unpaid: <span id="bh-pending-count" class="text-rose-400 font-bold">–</span></span>
                </div>
                <div class="flex items-center gap-2 ml-auto">
                    <span class="text-gray-400">Total Billed: <span id="bh-total-amount" class="text-cyan-400 font-bold">–</span></span>
                </div>
            </div>

            {{-- Body --}}
            <div class="flex-1 overflow-y-auto">
                {{-- Loading --}}
                <div id="bh-loading" class="flex flex-col items-center justify-center py-16 gap-4">
                    <div class="w-10 h-10 border-4 border-violet-500/30 border-t-violet-500 rounded-full animate-spin"></div>
                    <p class="text-sm text-gray-400">Loading bill history…</p>
                </div>

                {{-- Empty --}}
                <div id="bh-empty" class="hidden flex flex-col items-center justify-center py-16 gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-400 text-sm">No billing records found for this consumer.</p>
                </div>

                {{-- Table --}}
                <div id="bh-table-wrap" class="hidden overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-[#0a1018] text-[#94a3b8] uppercase text-[10px] tracking-wider sticky top-0 z-10">
                                <th class="px-5 py-3 font-semibold border-b border-[#263548]">Period</th>
                                <th class="px-5 py-3 font-semibold border-b border-[#263548]">Reading</th>
                                <th class="px-5 py-3 font-semibold text-center border-b border-[#263548]">Usage</th>
                                <th class="px-5 py-3 font-semibold border-b border-[#263548]">Amount</th>
                                <th class="px-5 py-3 font-semibold border-b border-[#263548]">Status</th>
                                <th class="px-5 py-3 font-semibold text-right border-b border-[#263548]">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bh-tbody" class="divide-y divide-[#263548]"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Edit Reading Modal (Item 2) ===== --}}
    <div id="editReadingModal" class="fixed inset-0 flex items-center justify-center p-4 hidden" style="z-index: 9999999 !important;" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" style="z-index: 1 !important;" onclick="closeEditReadingModal()"></div>
        <div class="relative bg-[#0f1722] border-2 border-cyan-500/40 rounded-3xl shadow-[0_25px_80px_rgba(0,0,0,0.95),0_0_50px_rgba(6,182,212,0.25)] w-full max-w-md p-6 overflow-hidden" style="animation: bhSlideUp 0.2s ease both; z-index: 2 !important;">
            <div class="flex items-center justify-between pb-4 border-b border-[#263548] mb-5">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 bg-amber-500/20 text-amber-400 rounded-xl border border-amber-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-base" id="edit-modal-title">Edit Meter Reading</h3>
                        <p class="text-xs text-gray-400" id="edit-period-label">Period</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditReadingModal()" class="text-gray-400 hover:text-rose-400 p-1 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <input type="hidden" id="edit-bill-id">
            <input type="hidden" id="edit-customer-id">
            <input type="hidden" id="edit-customer-type">
            <input type="hidden" id="edit-prev-reading">

            <div class="space-y-4">
                <div class="bg-[#121a25]/80 p-4 rounded-2xl border border-[#263548]">
                    <div class="flex justify-between items-center text-xs text-gray-400 mb-1">
                        <span>Previous Reading</span>
                        <span class="font-mono text-white font-bold text-sm" id="edit-prev-reading-display">0 m³</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-cyan-400 mb-2 uppercase tracking-wider">New Meter Reading (m³)</label>
                    <div class="relative">
                        <input type="number" step="1" id="edit-new-reading-input"
                            class="w-full border border-[#2d4059] focus:border-cyan-500 text-gray-100 text-lg font-mono rounded-xl py-2.5 pl-3 pr-10 outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            style="background-color: #1b2636 !important; color: #f3f4f6 !important; -moz-appearance: textfield;"
                            oninput="recalcEditBill()">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold pointer-events-none">m³</span>
                    </div>
                </div>

                <div id="edit-calc-preview" class="text-xs text-emerald-400 font-mono font-medium p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl hidden"></div>
                <div id="edit-error-msg" class="text-xs text-rose-400 font-mono font-medium p-3 bg-rose-500/10 border border-rose-500/20 rounded-xl hidden"></div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-[#263548]">
                <button type="button" onclick="closeEditReadingModal()" class="px-4 py-2 bg-[#1b2636] hover:bg-[#263548] text-gray-300 rounded-xl text-xs font-semibold transition border border-[#2d4059]">
                    Cancel
                </button>
                <button type="button" id="btn-save-edit-bill" onclick="saveEditBill()" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-cyan-600/30">
                    Update Reading
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes bhSlideUp {
            from { opacity: 0; transform: translateY(24px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>

    <script>
        const settings = {
            @foreach(\App\Models\CustomerType::all() as $type)
            "{{ $type->name }}": { 
                base: {{ $type->base_charge }}, 
                rate: {{ $type->usage_rate }},
                limit: {{ $type->base_limit }}
            },
            @endforeach
        };
        const globalAdditionalChargeTotal = {{ $globalAdditionalChargeTotal ?? 0 }};

        // ===== Barangay Filter & Consumer Search =====
        let currentSelectedBarangay = 'all';

        function selectBarangay(brgy) {
            currentSelectedBarangay = (brgy || 'all').trim();

            // Sync Dropdown
            const selectEl = document.getElementById('barangaySelect');
            if (selectEl && selectEl.value !== currentSelectedBarangay) {
                selectEl.value = currentSelectedBarangay;
            }

            // Sync Buttons
            const selectedLower = currentSelectedBarangay.toLowerCase();
            document.querySelectorAll('.brgy-filter-btn').forEach(btn => {
                const btnBrgy = (btn.getAttribute('data-brgy') || btn.dataset.brgy || '').trim().toLowerCase();
                const isMatch = (selectedLower === 'all' && btnBrgy === 'all') || (selectedLower === btnBrgy);
                const badge = btn.querySelector('.brgy-count-badge');

                if (isMatch) {
                    btn.className = 'brgy-filter-btn px-4 py-2 rounded-xl text-xs font-black transition-all duration-200 whitespace-nowrap shrink-0 flex items-center gap-2 bg-cyan-500 text-gray-950 shadow-[0_0_15px_rgba(6,182,212,0.4)]';
                    if (badge) badge.className = 'brgy-count-badge px-1.5 py-0.5 rounded-md text-[10px] font-mono bg-black/20 text-gray-950 font-bold';
                } else {
                    btn.className = 'brgy-filter-btn px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-200 whitespace-nowrap shrink-0 flex items-center gap-2 bg-[#121a25]/80 hover:bg-[#1b2636] text-gray-300 hover:text-white border border-[#263548]';
                    if (badge) badge.className = 'brgy-count-badge px-1.5 py-0.5 rounded-md text-[10px] font-mono bg-[#1b2636] text-gray-400 border border-[#2d4059]';
                }
            });

            filterConsumers();
        }

        function filterConsumers() {
            const searchVal = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
            const groups = document.querySelectorAll('.brgy-group');
            const selectedLower = (currentSelectedBarangay || 'all').trim().toLowerCase();
            let totalVisible = 0;

            groups.forEach(group => {
                const brgyAttr = (group.getAttribute('data-barangay') || group.dataset.barangay || '').trim().toLowerCase();
                const matchesBrgy = (selectedLower === 'all' || brgyAttr === selectedLower);

                if (!matchesBrgy) {
                    group.style.display = 'none';
                    return;
                }

                const cards = group.querySelectorAll('.consumer-card');
                let visibleCardsInGroup = 0;

                cards.forEach(card => {
                    const name = (card.querySelector('.consumer-name')?.textContent || '').toLowerCase();
                    const account = (card.querySelector('.consumer-account')?.textContent || '').toLowerCase();
                    const brgy = (card.querySelector('.consumer-brgy')?.textContent || '').toLowerCase();

                    const matchesSearch = !searchVal || name.includes(searchVal) || account.includes(searchVal) || brgy.includes(searchVal);

                    if (matchesSearch) {
                        card.style.display = '';
                        visibleCardsInGroup++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (visibleCardsInGroup > 0) {
                    group.style.display = '';
                    totalVisible += visibleCardsInGroup;
                } else {
                    group.style.display = 'none';
                }
            });

            const emptyNotice = document.getElementById('noResultsNotice');
            if (emptyNotice) {
                emptyNotice.style.display = (totalVisible === 0) ? '' : 'none';
            }
        }

        function calculateBill(customerId, type, previousReading, isFirstReading = false) {
            const input = document.getElementById('reading-' + customerId).value;
            const calcEl = document.getElementById('calc-' + customerId);
            const submitBtn = document.getElementById('btn-' + customerId);
            
            if (!input) {
                calcEl.textContent = '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
                return;
            }

            const currentReading = parseFloat(input);
            
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            const usage = Math.max(0, currentReading - previousReading);

            let baseCharge = settings[type]?.base || 0;
            let rate = settings[type]?.rate || 0;
            let baseLimit = settings[type]?.limit || 10;

            const billableUsage = Math.max(usage - baseLimit, 0);
            const usageCharge = isFirstReading ? 0 : (billableUsage * rate);
            
            const total = baseCharge + usageCharge + globalAdditionalChargeTotal;

            if (isFirstReading) {
                calcEl.textContent = `Usage: ${usage}m³ (First Reading: Base Charge Only) | Total: ₱${total.toFixed(2)}`;
            } else if (currentReading === previousReading) {
                calcEl.textContent = `Usage: 0m³ (Same as previous reading) | Total: ₱${total.toFixed(2)}`;
            } else if (currentReading < previousReading) {
                calcEl.textContent = `Usage: 0m³ (Reading ${currentReading}m³ ≤ Previous ${previousReading}m³) | Total: ₱${total.toFixed(2)}`;
            } else {
                calcEl.textContent = `Usage: ${usage}m³ | Total Bill: ₱${total.toFixed(2)}`;
            }
            calcEl.className = 'text-xs text-emerald-400 mt-2 font-mono font-bold h-4';
        }

        // ===== Bill History Modal Logic =====
        let currentBhCustomerId = null;

        function openBillHistory(customerId, customerName, customerAcctId) {
            currentBhCustomerId = customerId;
            document.getElementById('bh-consumer-name').textContent = customerName;
            document.getElementById('bh-consumer-id').textContent = 'Acct # ' + customerAcctId;

            // Reset states
            document.getElementById('bh-loading').classList.remove('hidden');
            document.getElementById('bh-empty').classList.add('hidden');
            document.getElementById('bh-table-wrap').classList.add('hidden');
            document.getElementById('bh-tbody').innerHTML = '';
            ['bh-total-count','bh-paid-count','bh-pending-count','bh-total-amount'].forEach(id => {
                document.getElementById(id).textContent = '–';
            });

            // Show modal
            const bhModal = document.getElementById('billHistoryModal');
            if (bhModal && bhModal.parentElement !== document.body) {
                document.body.appendChild(bhModal);
            }
            bhModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Fetch bill history
            fetch(`/reader/customers/${customerId}/bills`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('bh-loading').classList.add('hidden');
                const bills = data.bills || [];

                if (bills.length === 0) {
                    document.getElementById('bh-empty').classList.remove('hidden');
                    return;
                }

                // Stats
                const paid    = bills.filter(b => b.status === 'Paid').length;
                const pending = bills.length - paid;
                const totalAmt = bills.reduce((s, b) => s + parseFloat(b.total_amount || 0), 0);
                document.getElementById('bh-total-count').textContent   = bills.length;
                document.getElementById('bh-paid-count').textContent    = paid;
                document.getElementById('bh-pending-count').textContent = pending;
                document.getElementById('bh-total-amount').textContent  = '₱' + totalAmt.toLocaleString('en-PH', { maximumFractionDigits: 0 });

                // Build rows
                const tbody = document.getElementById('bh-tbody');
                bills.forEach(bill => {
                    const date  = new Date(bill.billing_date);
                    const month = date.toLocaleString('en-PH', { month: 'long', year: 'numeric' });
                    const isPaid = bill.status === 'Paid';
                    const isFirstBill = bills.length > 0 && bills[bills.length - 1].id === bill.id;
                    const consumption = bill.consumption || 0;

                    let usageBadge;
                    if (consumption <= 10) {
                        usageBadge = 'bg-emerald-500/20 text-emerald-400';
                    } else if (consumption <= 20) {
                        usageBadge = 'bg-orange-500/20 text-orange-400';
                    } else {
                        usageBadge = 'bg-rose-500/20 text-rose-400';
                    }

                    const tr = document.createElement('tr');
                    tr.id = 'bh-row-' + bill.id;
                    tr.className = 'hover:bg-[#1b2636]/30 transition duration-150';
                    tr.innerHTML = `
                        <td class="px-5 py-3 font-medium text-gray-300 whitespace-nowrap">${month}</td>
                        <td class="px-5 py-3 font-mono text-xs whitespace-nowrap">
                            <span class="text-gray-500">${bill.previous_reading ?? 0}</span>
                            <span class="text-gray-600 mx-1">→</span>
                            <span class="text-gray-200">${bill.new_reading ?? 0} m³</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold ${usageBadge}">${consumption} m³</span>
                        </td>
                        <td class="px-5 py-3 font-bold text-cyan-400 whitespace-nowrap">₱${parseFloat(bill.total_amount || 0).toLocaleString('en-PH', { maximumFractionDigits: 0 })}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold ${isPaid ? 'text-emerald-400' : 'text-rose-400'}">
                                <span class="h-1.5 w-1.5 rounded-full ${isPaid ? 'bg-emerald-400' : 'bg-rose-400 animate-pulse'}"></span>
                                ${isPaid ? 'Paid' : 'Unpaid'}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex justify-end gap-1.5">
                                <a href="/reader/bills/${bill.id}/receipt" target="_blank"
                                   class="p-1.5 text-cyan-400 bg-cyan-500/10 hover:bg-cyan-500/20 rounded-lg border border-cyan-500/20 transition"
                                   title="View Receipt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </a>
                                <button type="button" onclick="openEditBillModal(${bill.id}, ${bill.previous_reading ?? 0}, ${bill.new_reading ?? 0}, '${month}', currentBhCustomerId, ${JSON.stringify(customerName || '')}, ${JSON.stringify(data.customer?.type || '')}, ${isFirstBill})"
                                   class="p-1.5 text-amber-400 bg-amber-500/10 hover:bg-amber-500/20 rounded-lg border border-amber-500/20 transition"
                                   title="Edit Reading">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button type="button" onclick="deleteBillRow(${bill.id})"
                                   class="p-1.5 text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 rounded-lg border border-rose-500/20 transition"
                                   title="Delete Bill">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                document.getElementById('bh-table-wrap').classList.remove('hidden');
            })
            .catch(err => {
                document.getElementById('bh-loading').classList.add('hidden');
                document.getElementById('bh-empty').classList.remove('hidden');
                console.error('Failed to load bill history:', err);
            });
        }

        function closeBillHistory() {
            document.getElementById('billHistoryModal').classList.add('hidden');
            document.body.style.overflow = '';
            currentBhCustomerId = null;
        }

        function deleteBillRow(billId) {
            if (!confirm('Delete this bill? The customer\'s meter reading will be reverted to the previous value.')) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            fetch(`/reader/bills/${billId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById('bh-row-' + billId);
                    if (row) {
                        row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(20px)';
                        setTimeout(() => {
                            row.remove();
                            const tbody = document.getElementById('bh-tbody');
                            if (!tbody.children.length) {
                                document.getElementById('bh-table-wrap').classList.add('hidden');
                                document.getElementById('bh-empty').classList.remove('hidden');
                                ['bh-total-count','bh-paid-count','bh-pending-count','bh-total-amount'].forEach(id => {
                                    document.getElementById(id).textContent = '0';
                                });
                                return;
                            }
                            // Recalculate stats from remaining rows
                            const rows = Array.from(tbody.querySelectorAll('tr'));
                            let paid = 0, total = 0;
                            rows.forEach(r => {
                                const statusEl = r.querySelector('td:nth-child(5) span');
                                if (statusEl && statusEl.textContent.trim() === 'Paid') paid++;
                                const amtEl = r.querySelector('td:nth-child(4)');
                                if (amtEl) total += parseFloat(amtEl.textContent.replace(/[^\d.]/g, '')) || 0;
                            });
                            document.getElementById('bh-total-count').textContent   = rows.length;
                            document.getElementById('bh-paid-count').textContent    = paid;
                            document.getElementById('bh-pending-count').textContent = rows.length - paid;
                            document.getElementById('bh-total-amount').textContent  = '₱' + total.toLocaleString('en-PH', { maximumFractionDigits: 0 });
                        }, 300);
                    }
                } else {
                    alert('Failed to delete bill. Please try again.');
                }
            })
            .catch(() => alert('Network error. Please try again.'));
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') { 
                closeBillHistory(); 
                closeEditReadingModal();
            }
        });

        function printBhCustomerHistory() {
            if (!currentBhCustomerId) return;
            window.open(`/customers/${currentBhCustomerId}/billing-history/print`, '_blank');
        }

        let currentEditingBillId = null;

        function openEditFromCard(customerId) {
            const card = document.getElementById(`consumer-card-${customerId}`) || document.querySelector(`.consumer-card[data-customer-id="${customerId}"]`);
            if (!card) return;

            const customerName = card.dataset.customerName || 'Consumer';
            const customerType = card.dataset.customerType || '';
            const currentMeterReading = parseFloat(card.dataset.meterReading || 0);
            const billId = card.dataset.latestBillId;
            const prevReading = parseFloat(card.dataset.latestBillPrev || 0);
            const billReading = parseFloat(card.dataset.latestBillReading || currentMeterReading);
            const period = card.dataset.latestBillPeriod || 'Current Period';

            if (billId) {
                openEditBillModal(billId, prevReading, billReading, period, customerId, customerName, customerType);
            } else {
                openEditCustomerModal(customerId, currentMeterReading, customerName, customerType);
            }
        }

        function openEditCustomerModal(customerId, currentReading, customerName, customerType) {
            currentEditingBillId = null;
            document.getElementById('edit-bill-id').value = '';
            document.getElementById('edit-customer-id').value = customerId;
            document.getElementById('edit-customer-type').value = customerType || '';
            document.getElementById('edit-prev-reading').value = 0;
            document.getElementById('edit-prev-reading-display').textContent = '0 m³ (Base)';
            
            document.getElementById('edit-modal-title').textContent = `Edit Initial Reading – ${customerName}`;
            document.getElementById('edit-period-label').textContent = 'Initial Reading (No Bills Generated Yet)';
            
            const inputEl = document.getElementById('edit-new-reading-input');
            inputEl.value = currentReading;
            inputEl.min = 0;
            
            document.getElementById('edit-error-msg').classList.add('hidden');
            document.getElementById('edit-calc-preview').classList.add('hidden');
            
            recalcEditBill();
            
            const editModal = document.getElementById('editReadingModal');
            if (editModal && editModal.parentElement !== document.body) {
                document.body.appendChild(editModal);
            }
            editModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => inputEl.focus(), 60);
        }

        function openEditBillModal(billId, prevReading, currentReading, period, customerId = null, customerName = null, customerType = null, isFirstBill = false) {
            currentEditingBillId = billId;
            document.getElementById('edit-bill-id').value = billId;
            document.getElementById('edit-customer-id').value = customerId || '';
            document.getElementById('edit-customer-type').value = customerType || '';
            document.getElementById('edit-prev-reading').value = prevReading;
            document.getElementById('edit-prev-reading-display').textContent = Math.round(prevReading) + ' m³';
            window._editIsFirstBill = !!isFirstBill;
            
            const titleEl = document.getElementById('edit-modal-title');
            if (customerName) {
                titleEl.textContent = `Edit Reading – ${customerName}`;
            } else {
                titleEl.textContent = 'Edit Meter Reading';
            }
            
            document.getElementById('edit-period-label').textContent = 'Period: ' + (period || 'Current');
            
            const inputEl = document.getElementById('edit-new-reading-input');
            inputEl.value = currentReading;
            inputEl.min = prevReading;
            
            document.getElementById('edit-error-msg').classList.add('hidden');
            document.getElementById('edit-calc-preview').classList.add('hidden');
            
            recalcEditBill();
            
            const editModal = document.getElementById('editReadingModal');
            if (editModal && editModal.parentElement !== document.body) {
                document.body.appendChild(editModal);
            }
            editModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => inputEl.focus(), 60);
        }

        function closeEditReadingModal() {
            document.getElementById('editReadingModal').classList.add('hidden');
            document.body.style.overflow = '';
            currentEditingBillId = null;
        }

        function recalcEditBill() {
            const prev = parseFloat(document.getElementById('edit-prev-reading').value) || 0;
            const newVal = parseFloat(document.getElementById('edit-new-reading-input').value);
            const errEl = document.getElementById('edit-error-msg');
            const previewEl = document.getElementById('edit-calc-preview');
            const saveBtn = document.getElementById('btn-save-edit-bill');

            if (isNaN(newVal) || newVal < 0) {
                errEl.classList.add('hidden');
                previewEl.classList.add('hidden');
                saveBtn.disabled = true;
                saveBtn.classList.add('opacity-50');
                return;
            }

            errEl.classList.add('hidden');
            const usage = Math.max(0, newVal - prev);
            
            const custType = document.getElementById('edit-customer-type')?.value;
            const typeConfig = custType && settings[custType] ? settings[custType] : null;
            const isFirstBill = window._editIsFirstBill;
            if (typeConfig) {
                const billableUsage = Math.max(usage - typeConfig.limit, 0);
                const usageCharge = isFirstBill ? 0 : (billableUsage * typeConfig.rate);
                const total = typeConfig.base + usageCharge + globalAdditionalChargeTotal;
                let note = '';
                if (isFirstBill) {
                    note = '<span class="text-amber-400 font-bold ml-1">(First Reading: Base Charge Only)</span>';
                } else if (newVal <= prev) {
                    note = '<span class="text-amber-400 font-bold ml-1">(Reading ≤ Previous: Base Charge Only)</span>';
                }
                previewEl.innerHTML = `<span>Recalculated Usage: <strong>${usage} m³</strong> ${note}</span><span class="block mt-1 text-cyan-300">New Bill Total: <strong>₱${Math.round(total).toLocaleString('en-PH')}</strong></span>`;
            } else {
                previewEl.textContent = `Recalculated Usage: ${usage} m³`;
            }
            previewEl.classList.remove('hidden');
            saveBtn.disabled = false;
            saveBtn.classList.remove('opacity-50');
        }

        function saveEditBill() {
            const billId = document.getElementById('edit-bill-id').value;
            const customerId = document.getElementById('edit-customer-id').value;
            const prevReading = parseFloat(document.getElementById('edit-prev-reading').value) || 0;
            const newReading = parseFloat(document.getElementById('edit-new-reading-input').value);

            if (isNaN(newReading) || newReading < 0) {
                recalcEditBill();
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            const saveBtn = document.getElementById('btn-save-edit-bill');
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';

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
                saveBtn.textContent = 'Update Reading';

                if (data.success) {
                    closeEditReadingModal();

                    const updatedReading = data.customer_meter_reading !== undefined ? data.customer_meter_reading : (data.meter_reading !== undefined ? data.meter_reading : newReading);

                    // 1. UPDATE THE CARD IN THE FRONT!
                    const resolvedCustId = customerId || (data.bill ? data.bill.customer_id : null);
                    if (resolvedCustId) {
                        updateConsumerCardFront(resolvedCustId, updatedReading, billId, data.bill);
                    }

                    // 2. UPDATE TABLE ROW IN BILL HISTORY IF OPEN
                    if (billId) {
                        const row = document.getElementById('bh-row-' + billId);
                        if (row && data.bill) {
                            const readingTd = row.querySelector('td:nth-child(2)');
                            if (readingTd) {
                                readingTd.innerHTML = `
                                    <span class="text-gray-500">${Math.round(data.bill.previous_reading)}</span>
                                    <span class="text-gray-600 mx-1">→</span>
                                    <span class="text-gray-200">${Math.round(data.bill.new_reading)} m³</span>
                                `;
                            }
                            const usageTd = row.querySelector('td:nth-child(3) span');
                            if (usageTd) {
                                usageTd.textContent = `${Math.round(data.bill.consumption)} m³`;
                            }
                            const amtTd = row.querySelector('td:nth-child(4)');
                            if (amtTd) {
                                amtTd.textContent = '₱' + Math.round(parseFloat(data.bill.total_amount || 0)).toLocaleString('en-PH');
                            }
                        }

                        // Recalculate stats
                        const tbody = document.getElementById('bh-tbody');
                        if (tbody && tbody.children.length > 0) {
                            const rows = Array.from(tbody.querySelectorAll('tr'));
                            let total = 0;
                            rows.forEach(r => {
                                const amtEl = r.querySelector('td:nth-child(4)');
                                if (amtEl) total += parseFloat(amtEl.textContent.replace(/[^\d.]/g, '')) || 0;
                            });
                            document.getElementById('bh-total-amount').textContent = '₱' + Math.round(total).toLocaleString('en-PH');
                        }
                    }

                    showToastNotification(data.message || 'Reading updated successfully!');
                } else {
                    alert(data.message || 'Failed to update reading.');
                }
            })
            .catch(err => {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Update Reading';
                alert(err.message || 'Failed to update reading. Please try again.');
            });
        }

        function updateConsumerCardFront(customerId, newReading, billId, billData) {
            const card = document.getElementById(`consumer-card-${customerId}`) || document.querySelector(`.consumer-card[data-customer-id="${customerId}"]`);
            if (!card) return;

            const readingNum = Math.round(parseFloat(newReading) || 0);

            // 1. Update text display of "Previous Reading / Current System Value"
            const valEl = document.getElementById(`card-reading-val-${customerId}`);
            if (valEl) {
                valEl.innerHTML = `${readingNum.toLocaleString('en-PH')} <span class="text-xs text-white/60">m³</span>`;
            }

            // 2. Update input element min value and calculateBill attribute
            const inputEl = document.getElementById(`reading-${customerId}`);
            if (inputEl) {
                inputEl.min = readingNum;
                const custType = card.dataset.customerType || '';
                inputEl.setAttribute('oninput', `calculateBill(${customerId}, '${custType}', ${readingNum})`);
                if (inputEl.value && parseFloat(inputEl.value) < readingNum) {
                    inputEl.value = '';
                    const calcEl = document.getElementById(`calc-${customerId}`);
                    if (calcEl) calcEl.textContent = '';
                }
            }

            // 3. Update dataset attributes on card
            card.dataset.meterReading = readingNum;
            if (billId && billData) {
                card.dataset.latestBillId = billId;
                card.dataset.latestBillReading = readingNum;
                card.dataset.latestBillPrev = billData.previous_reading;
            }

            // 4. Subtle pulse highlight animation to indicate update
            card.classList.add('ring-2', 'ring-amber-400/50');
            setTimeout(() => card.classList.remove('ring-2', 'ring-amber-400/50'), 1500);
        }

        function showToastNotification(msg) {
            let toast = document.getElementById('reader-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'reader-toast';
                toast.className = 'fixed top-6 right-6 z-[999999] px-5 py-3.5 rounded-2xl bg-emerald-600/95 text-white font-bold text-sm shadow-[0_10px_30px_rgba(16,185,129,0.5)] border border-emerald-400/30 backdrop-blur-md flex items-center gap-3 transition-all duration-300 pointer-events-none opacity-0 -translate-y-4';
                toast.innerHTML = `
                    <div class="p-1 bg-white/20 rounded-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span id="reader-toast-msg">${msg}</span>
                `;
                document.body.appendChild(toast);
            } else {
                const msgEl = document.getElementById('reader-toast-msg');
                if (msgEl) msgEl.textContent = msg;
            }

            toast.classList.remove('opacity-0', 'pointer-events-none', '-translate-y-4');
            toast.classList.add('opacity-100', 'translate-y-0');

            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'pointer-events-none', '-translate-y-4');
            }, 3500);
        }

        // ===== Duplicate Bill Popup Dialog =====
        function showDupDialog(message, onConfirm, onEdit) {
            document.getElementById('reader-dup-dialog-msg').textContent = message;
            const dialog = document.getElementById('reader-dup-bill-dialog');

            if (dialog.parentElement !== document.body) {
                document.body.appendChild(dialog);
            }

            const editBtn = document.getElementById('reader-dup-dialog-edit');
            if (editBtn) {
                if (onEdit) {
                    editBtn.style.display = 'inline-flex';
                    editBtn.onclick = function() {
                        hideDupDialog();
                        onEdit();
                    };
                } else {
                    editBtn.style.display = 'none';
                }
            }

            dialog.style.display = 'flex';
            if (typeof dialog.showModal === 'function') {
                try { dialog.showModal(); } catch (e) {}
            }
            document.body.style.overflow = 'hidden';

            const confirmBtn = document.getElementById('reader-dup-dialog-confirm');
            if (confirmBtn) {
                if (onConfirm) {
                    confirmBtn.style.display = 'inline-flex';
                    confirmBtn.onclick = function() {
                        hideDupDialog();
                        onConfirm();
                    };
                } else {
                    confirmBtn.style.display = 'none';
                }
            }
        }

        function hideDupDialog() {
            const dialog = document.getElementById('reader-dup-bill-dialog');
            if (dialog) {
                if (typeof dialog.close === 'function') {
                    try { dialog.close(); } catch (e) {}
                }
                dialog.style.display = 'none';
            }
            document.body.style.overflow = '';
        }

        // Attach submit interceptors to all reader forms
        document.addEventListener('DOMContentLoaded', function() {
            // Support URL param ?barangay=...
            const urlParams = new URLSearchParams(window.location.search);
            const brgyParam = urlParams.get('barangay');
            if (brgyParam) {
                selectBarangay(brgyParam);
            }

            const dialog = document.getElementById('reader-dup-bill-dialog');
            if (dialog) {
                dialog.addEventListener('cancel', function(e) {
                    e.preventDefault(); // Prevent Escape key from closing warning
                });
            }

            document.querySelectorAll('.reader-reading-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    const forceInput = form.querySelector('.force-billing-input');
                    if (forceInput && forceInput.value === '1') return; // already confirmed
                    
                    const customerId = form.dataset.customerId;
                    const customerName = form.dataset.customerName;
                    
                    e.preventDefault();
                    
                    // AJAX check for existing bill this month
                    fetch(`/reader/customers/${customerId}/bills`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        const bills = data.bills || [];
                        const now = new Date();
                        const duplicate = bills.find(b => {
                            const d = new Date(b.billing_date);
                            return d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth();
                        });

                        if (duplicate) {
                            const monthName = new Date(duplicate.billing_date).toLocaleString('en-PH', { month: 'long', year: 'numeric' });
                            const amount = parseFloat(duplicate.total_amount || 0).toLocaleString('en-PH', { maximumFractionDigits: 0 });
                            showDupDialog(
                                `${customerName} already has a bill of ₱${amount} for ${monthName}. Submitting again is not allowed because a bill already exists for this month.`,
                                null,
                                null
                            );
                        } else {
                            form.submit(); // no duplicate, submit normally
                        }
                    })
                    .catch(function() {
                        form.submit(); // on error, let server handle it
                    });
                });
            });
        });

    </script>

    {{-- ===== Duplicate Bill Confirm Dialog (Reader) ===== --}}
    <dialog id="reader-dup-bill-dialog" class="fixed inset-0 z-[999999] p-4 m-auto bg-transparent border-none outline-none max-w-lg w-full items-center justify-center backdrop:bg-black/85 backdrop:backdrop-blur-md" style="display:none; color: #ffffff !important;">
        <div class="relative bg-[#0f172a] border-2 border-amber-400 rounded-2xl shadow-[0_0_100px_rgba(245,158,11,0.5),0_30px_60px_rgba(0,0,0,0.95)] w-full max-w-lg overflow-hidden z-10"
            style="animation: dupDialogIn 0.22s cubic-bezier(0.34,1.4,0.64,1) both; background-color: #0f172a !important; color: #ffffff !important;">
            <div class="h-1.5 w-full bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500"></div>
            <div class="p-6 sm:p-7">
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
                <div style="background-color: #1e1b18 !important; border: 2px solid #f59e0b !important; padding: 1rem !important; border-radius: 0.75rem !important; margin-bottom: 1.25rem !important; box-shadow: inset 0 2px 4px rgba(0,0,0,0.5) !important;">
                    <p id="reader-dup-dialog-msg" style="color: #ffffff !important; font-weight: 400 !important; font-size: 1rem !important; line-height: 1.5 !important; margin: 0 !important;"></p>
                </div>
                <p style="color: #ffffff !important; font-weight: 400 !important; font-size: 0.95rem !important; margin-bottom: 1.5rem !important;">A bill has already been created for this consumer for this month. New submissions are not permitted.</p>
                <div class="flex flex-wrap gap-3 justify-end items-center">
                    <button type="button" onclick="hideDupDialog()"
                        style="background-color: #334155 !important; color: #ffffff !important; border: 2px solid #64748b !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 700 !important; font-size: 0.875rem !important; cursor: pointer !important;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </dialog>

    <style>
        @keyframes dupDialogIn {
            from { opacity: 0; transform: scale(0.92) translateY(12px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
</x-layouts::app>
