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

        @if(session('billing_warning'))
            @php $rWarn = session('billing_warning'); @endphp
            <div class="mb-6 bg-amber-500/10 border border-amber-500/30 text-amber-300 px-6 py-4 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-[0_0_20px_rgba(245,158,11,0.1)]">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <span class="font-bold">Duplicate Bill Warning:</span>
                        <p class="text-sm text-amber-200/90 mt-0.5">A bill of ₱{{ $rWarn['amount'] }} was already generated for <strong>{{ $rWarn['customer_name'] }}</strong> in {{ $rWarn['month'] }}.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('reader.storeReading') }}" class="flex items-center gap-2 shrink-0">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $rWarn['customer_id'] }}">
                    <input type="hidden" name="reading" value="{{ $rWarn['prefill_reading'] ?? '' }}">
                    <input type="hidden" name="force_billing" value="1">
                    <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-black font-bold text-xs rounded-xl transition">
                        Proceed Anyway
                    </button>
                    <button type="button" onclick="this.closest('.mb-6').remove()" class="px-3 py-2 bg-transparent text-gray-400 hover:text-white text-xs">
                        Dismiss
                    </button>
                </form>
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

        <div class="mb-6 relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" id="searchInput" onkeyup="filterConsumers()" placeholder="Search by Name, Account No, or Brgy..." class="w-full bg-[#121a25]/80 backdrop-blur-md border border-[#263548] focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 text-gray-200 text-sm rounded-2xl py-4 pl-12 pr-4 outline-none transition-all duration-300 shadow-[0_8px_30px_rgb(0,0,0,0.4)]">
        </div>

        <div id="consumerGroups">
            @forelse($groupedCustomers as $barangay => $group)
                <div class="brgy-group mb-12">
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
                            <div class="consumer-card bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] border border-[#263548] p-6 relative overflow-hidden group hover:border-cyan-500/30 hover:shadow-[0_8px_30px_rgba(6,182,212,0.15)] transition-all duration-500 flex flex-col justify-between h-full">
                                
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
                                        <p class="text-xs text-white/70 font-medium mb-1">Previous Reading / Current System Value</p>
                                        <p class="text-xl font-mono text-white font-bold">{{ number_format($customer->meter_reading ?? 0, 0) }} <span class="text-xs text-white/60">m³</span></p>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('reader.storeReading') }}" class="mt-auto relative z-10 bg-[#0f1722]/50 p-4 rounded-2xl border border-[#263548] reader-reading-form" data-customer-id="{{ $customer->id }}" data-customer-name="{{ $customer->name }}">
                                    @csrf
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    <input type="hidden" name="force_billing" value="0" class="force-billing-input">
                                    
                                    <label class="block text-xs font-bold text-cyan-500 mb-2 uppercase tracking-wider">Input New Reading</label>
                                    
                                    <div class="flex gap-2">
                                        <div class="relative flex-1">
                                            <input type="number" step="1" name="reading" id="reading-{{ $customer->id }}" required placeholder="0"
                                                min="{{ $customer->meter_reading ?? 0 }}"
                                                oninput="calculateBill({{ $customer->id }}, '{{ $customer->customerType ? $customer->customerType->name : $customer->type }}', {{ $customer->meter_reading ?? 0 }})"
                                                class="w-full bg-[#1b2636]/60 border border-[#2d4059] focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 text-gray-100 text-lg font-mono rounded-xl py-2 pl-3 pr-8 outline-none transition-all duration-300 placeholder:text-gray-600">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs font-bold">m³</span>
                                        </div>
                                        <button type="submit" id="btn-{{ $customer->id }}" class="bg-cyan-600 hover:bg-cyan-500 text-white p-2 rounded-xl shadow-[0_4px_15px_rgba(6,182,212,0.4)] transition-all duration-300 flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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
        </div>
    </div>

    {{-- ===== Bill History Modal ===== --}}
    <div id="billHistoryModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeBillHistory()"></div>

        {{-- Panel --}}
        <div class="relative bg-[#0f1722] border border-[#263548] rounded-3xl shadow-[0_25px_60px_rgba(0,0,0,0.8)] w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden" style="animation: bhSlideUp 0.25s cubic-bezier(0.34,1.56,0.64,1) both;">
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
                <button onclick="closeBillHistory()" class="text-gray-500 hover:text-rose-400 transition-colors p-1.5 rounded-lg hover:bg-rose-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
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
                    <span class="text-gray-400">Pending: <span id="bh-pending-count" class="text-rose-400 font-bold">–</span></span>
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

        function calculateBill(customerId, type, previousReading) {
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
            
            if (currentReading < previousReading) {
                calcEl.textContent = `Invalid: Reading cannot be lower than previous (${previousReading})`;
                calcEl.className = 'text-xs text-rose-500 mt-2 font-mono font-bold h-4';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
                return;
            } else {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
            const usage = currentReading - previousReading;

            let baseCharge = settings[type]?.base || 0;
            let rate = settings[type]?.rate || 0;
            let baseLimit = settings[type]?.limit || 10;

            const billableUsage = Math.max(usage - baseLimit, 0);
            const usageCharge = billableUsage * rate;
            
            const total = baseCharge + usageCharge + globalAdditionalChargeTotal;

            calcEl.textContent = `Usage: ${usage}m³ | Total Bill: ₱${total.toFixed(2)}`;
            calcEl.className = 'text-xs text-emerald-400 mt-2 font-mono font-bold h-4';
        }

        function filterConsumers() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const groups = document.querySelectorAll('.brgy-group');

            groups.forEach(group => {
                const cards = group.querySelectorAll('.consumer-card');
                let groupHasVisible = false;

                cards.forEach(card => {
                    const name = card.querySelector('.consumer-name').textContent.toLowerCase();
                    const account = card.querySelector('.consumer-account').textContent.toLowerCase();
                    const brgyEl = card.querySelector('.consumer-brgy');
                    const brgy = brgyEl ? brgyEl.textContent.toLowerCase() : '';
                    
                    if (name.includes(input) || account.includes(input) || brgy.includes(input)) {
                        card.style.display = '';
                        groupHasVisible = true;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (groupHasVisible) {
                    group.style.display = '';
                } else {
                    group.style.display = 'none';
                }
            });
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
            document.getElementById('billHistoryModal').classList.remove('hidden');
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
                                ${bill.status}
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
            if (e.key === 'Escape') { closeBillHistory(); }
        });

        // ===== Duplicate Bill Popup Dialog =====
        function showDupDialog(message, onConfirm) {
            document.getElementById('reader-dup-dialog-msg').textContent = message;
            const dialog = document.getElementById('reader-dup-bill-dialog');

            if (dialog.parentElement !== document.body) {
                document.body.appendChild(dialog);
            }

            dialog.style.display = 'flex';
            if (typeof dialog.showModal === 'function') {
                try { dialog.showModal(); } catch (e) {}
            }
            document.body.style.overflow = 'hidden';

            document.getElementById('reader-dup-dialog-confirm').onclick = function() {
                hideDupDialog();
                onConfirm();
            };
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
                                `${customerName} already has a bill of ₱${amount} for ${monthName}. Submitting again will create a second bill for the same month.`,
                                function() {
                                    if (forceInput) forceInput.value = '1';
                                    form.submit();
                                }
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
                <p style="color: #ffffff !important; font-weight: 400 !important; font-size: 0.95rem !important; margin-bottom: 1.5rem !important;">Do you still want to generate a new bill for the same month?</p>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="hideDupDialog()"
                        style="background-color: #334155 !important; color: #ffffff !important; border: 2px solid #64748b !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 700 !important; font-size: 0.875rem !important; cursor: pointer !important;">
                        Cancel
                    </button>
                    <button type="button" id="reader-dup-dialog-confirm"
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

    <style>
        @keyframes dupDialogIn {
            from { opacity: 0; transform: scale(0.92) translateY(12px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
</x-layouts::app>
