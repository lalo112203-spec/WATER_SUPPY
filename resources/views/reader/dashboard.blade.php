<x-layouts::app title="Meter Reader Dashboard">
    <div class="px-6 py-4 bg-transparent min-h-[calc(100vh-4rem)] font-sans text-gray-200 relative z-10 max-w-6xl mx-auto">
        
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
                                            {{ $customer->type }}
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

                                <form method="POST" action="{{ route('reader.storeReading') }}" class="mt-auto relative z-10 bg-[#0f1722]/50 p-4 rounded-2xl border border-[#263548]">
                                    @csrf
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    
                                    <label class="block text-xs font-bold text-cyan-500 mb-2 uppercase tracking-wider">Input New Reading</label>
                                    
                                    <div class="flex gap-2">
                                        <div class="relative flex-1">
                                            <input type="number" step="1" name="reading" id="reading-{{ $customer->id }}" required placeholder="0"
                                                min="{{ $customer->meter_reading ?? 0 }}"
                                                oninput="calculateBill({{ $customer->id }}, '{{ $customer->type }}', {{ $customer->meter_reading ?? 0 }})"
                                                class="w-full bg-[#1b2636]/60 border border-[#2d4059] focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 text-gray-100 text-lg font-mono rounded-xl py-2 pl-3 pr-8 outline-none transition-all duration-300 placeholder:text-gray-600">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs font-bold">m³</span>
                                        </div>
                                        <button type="submit" id="btn-{{ $customer->id }}" class="bg-cyan-600 hover:bg-cyan-500 text-white p-2 rounded-xl shadow-[0_4px_15px_rgba(6,182,212,0.4)] transition-all duration-300 flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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

    <script>
        const settings = {
            Regular: { 
                base: {{ $settings['regular_base_charge'] ?? 100 }}, 
                rate: {{ $settings['regular_usage_rate'] ?? 15 }},
                limit: {{ $settings['regular_base_limit'] ?? 10 }}
            },
            Commercial: { 
                base: {{ $settings['commercial_base_charge'] ?? 250 }}, 
                rate: {{ $settings['commercial_usage_rate'] ?? 25 }},
                limit: {{ $settings['commercial_base_limit'] ?? 10 }}
            }
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
    </script>
</x-layouts::app>
