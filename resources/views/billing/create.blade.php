<x-layouts::app title="Create Bill">
    <div
        class="px-6 py-4 bg-transparent min-h-[calc(100vh-4rem)] font-sans text-gray-200 relative z-10 max-w-5xl mx-auto">

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-100 flex items-center gap-2 drop-shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 text-emerald-400 drop-shadow-[0_0_8px_rgba(16,185,129,0.8)]" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Generate Customer Bill
            </h1>
            <a href="{{ route('billing.index') }}"
                class="text-gray-400 hover:text-white flex items-center gap-2 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Billing Directory
            </a>
        </div>

        <!-- Billing Form Card -->
        <div
            class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] border border-[#263548] p-8 mb-8 relative overflow-hidden group">
            <div
                class="absolute -right-10 -top-10 bg-emerald-600/10 h-40 w-40 rounded-full blur-3xl pointer-events-none group-hover:bg-emerald-600/20 transition-all duration-700">
            </div>

            <form method="POST" action="{{ route('billing.store') }}" class="relative z-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-400 mb-2 ml-1">Select
                                Customer</label>
                            <div class="relative group">
                                <select id="customer_id" name="customer_id" required
                                    onchange="loadCustomerHistory(); calculateCharges()"
                                    class="w-full bg-[#1b2636]/60 backdrop-blur-md border border-[#2d4059] focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30 text-gray-200 text-sm rounded-xl py-3 pl-4 pr-10 outline-none transition-all duration-300 appearance-none">
                                    <option value="">Choose a customer...</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" data-type="{{ $customer->type }}" {{ old('customer_id', request('customer_id')) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->customer_id }}) - {{ $customer->type }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-500 group-hover:text-emerald-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('customer_id') <p class="text-rose-400 text-xs mt-2 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="billing_date"
                                    class="block text-sm font-medium text-gray-400 mb-2 ml-1">Billing Date</label>
                                <input type="date" id="billing_date" name="billing_date" required
                                    value="{{ old('billing_date', now()->format('Y-m-d')) }}"
                                    class="w-full bg-[#1b2636]/60 backdrop-blur-md border border-[#2d4059] focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30 text-gray-200 text-sm rounded-xl py-3 px-4 outline-none transition-all duration-300">
                                @error('billing_date') <p class="text-rose-400 text-xs mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-400 mb-2 ml-1">Due
                                    Date</label>
                                <input type="date" id="due_date" name="due_date" required
                                    value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}"
                                    class="w-full bg-[#1b2636]/60 backdrop-blur-md border border-[#2d4059] focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30 text-gray-200 text-sm rounded-xl py-3 px-4 outline-none transition-all duration-300">
                                @error('due_date') <p class="text-rose-400 text-xs mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-[#1e293b]/40 border border-emerald-500/20 p-5 rounded-2xl relative">
                            <div class="flex items-start gap-3 mb-4">
                                <div class="bg-emerald-500/20 p-2 rounded-lg text-emerald-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-400 leading-relaxed mb-2">Previous Reading: <span id="prev-reading-display" class="font-mono font-bold text-gray-200">0.00</span></p>
                                    <p class="text-xs text-gray-400 leading-relaxed">Enter the current water meter reading.
                                        The system will automatically calculate the consumption and charges.</p>
                                </div>
                            </div>

                            <label for="usage_units" class="block text-sm font-medium text-gray-300 mb-2 ml-1">Present Reading (L) *</label>
                            <input type="number" step="0.01" id="usage_units" name="usage_units" required
                                value="{{ old('usage_units') }}" oninput="calculateCharges()" placeholder="e.g. 100.00"
                                class="w-full bg-[#0f1722]/60 border border-[#2d4059] focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30 text-gray-100 text-lg font-bold rounded-xl py-3 px-4 shadow-inner outline-none transition-all duration-300 placeholder:text-gray-600">
                            <p id="usage-calculation" class="text-xs mt-3 flex items-center gap-2 min-h-[1.25rem]"></p>
                            @error('usage_units') <p class="text-rose-400 text-xs mt-2 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="flex flex-col justify-between">
                        <div class="space-y-6 bg-[#0f1722]/40 p-6 rounded-2xl border border-[#263548]">
                            <h3
                                class="text-sm font-semibold text-gray-400 uppercase tracking-widest flex items-center gap-2 mb-4 border-b border-[#263548] pb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Charge Summary
                            </h3>

                            <div class="grid grid-cols-1 gap-5">
                                <div>
                                    <label for="calculated_usage_display"
                                        class="block text-xs font-medium text-gray-500 mb-1 ml-1">Calculated Consumption (L)</label>
                                    <div class="relative">
                                        <input type="text" id="calculated_usage_display" readonly value="0.00"
                                            class="w-full bg-[#1b2636]/40 border border-[#2d4059] text-gray-300 rounded-xl py-2 px-4 font-mono outline-none transition-all">
                                        <input type="hidden" name="consumption" id="consumption" value="0">
                                    </div>
                                </div>

                                <div>
                                    <label for="base_charge"
                                        class="block text-xs font-medium text-gray-500 mb-1 ml-1">Base Charge</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                        <input type="number" step="0.01" id="base_charge" name="base_charge" required
                                            value="{{ old('base_charge', 0) }}" oninput="updateTotal()"
                                            class="w-full bg-[#1b2636]/40 border border-[#2d4059] focus:border-emerald-500/50 text-gray-300 rounded-xl py-2 pl-7 pr-4 outline-none transition-all">
                                    </div>
                                </div>

                                <div>
                                    <label for="usage_charge"
                                        class="block text-xs font-medium text-gray-500 mb-1 ml-1">Usage Charge</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                        <input type="number" step="0.01" id="usage_charge" name="usage_charge" required
                                            value="{{ old('usage_charge', 0) }}" oninput="updateTotal()"
                                            class="w-full bg-[#1b2636]/40 border border-[#2d4059] focus:border-emerald-500/50 text-gray-300 rounded-xl py-2 pl-7 pr-4 outline-none transition-all">
                                    </div>
                                </div>

                                <div class="pt-4 mt-4 border-t border-[#263548]">
                                    <label for="total_amount"
                                        class="block text-xs font-medium text-emerald-500/80 mb-2 ml-1 uppercase tracking-tighter">Total
                                        Payable Amount</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-400 font-bold text-xl">₱</span>
                                        <input type="number" step="0.01" id="total_amount" readonly value="0"
                                            class="w-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-3xl font-black rounded-2xl py-4 pl-10 pr-4 outline-none shadow-[0_0_20px_rgba(16,185,129,0.1)]">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 mt-8">
                            <button type="submit"
                                class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-4 rounded-2xl shadow-[0_4px_15px_rgba(5,150,105,0.4)] transition-all duration-300 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Create Bill
                            </button>
                            <a href="{{ route('billing.index') }}"
                                class="px-6 bg-[#1b2636]/60 hover:bg-[#2d4059]/60 text-gray-400 hover:text-white rounded-2xl flex items-center justify-center transition-all duration-300 border border-[#2d4059]">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Customer History Card -->
        <div
            class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] border border-[#263548] p-6 relative overflow-hidden transition-all duration-500">
            <h2 class="text-lg font-semibold mb-6 flex items-center gap-2 text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Consumption & Billing History
            </h2>

            <div id="customer-history" class="text-center py-12 text-gray-500 transition-all duration-300">
                <div class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#263548] mb-4 opacity-50" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-xl font-medium">Select a customer above</p>
                    <p class="text-sm mt-1 text-gray-600">History will automatically appear once a user is selected.</p>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Store customer data for reference
        const customersData = {
            @foreach ($customers as $customer)
                        '{{ $customer->id }}': {
                    type: '{{ $customer->type }}',
                    name: '{{ $customer->name }}',
                    initial_reading: {{ $customer->meter_reading ?? 0 }}
                },
            @endforeach
        };

        // thresholds provided by server
        const thresholds = {
            @foreach ($thresholds as $type => $vals)
                '{{ $type }}': { green_max: {{ $vals['green_max'] }}, orange_max: {{ $vals['orange_max'] }} },
            @endforeach
        };

        // Store previous reading
        let previousReading = 0;

        function loadCustomerHistory() {
            const customerId = document.getElementById('customer_id').value;
            const historyDiv = document.getElementById('customer-history');
            const prevReadingDisplay = document.getElementById('prev-reading-display');

            if (!customerId) {
                historyDiv.innerHTML = '<div class="text-center py-6 text-zinc-500">Select a customer to view reading history</div>';
                previousReading = 0;
                prevReadingDisplay.textContent = '0.00';
                calculateCharges();
                return;
            }

            // Fetch customer history via AJAX
            fetch(`/api/customers/${customerId}/readings`)
                .then(response => response.json())
                .then(data => {
                    // Set previous reading from latest bill or falling back to customer's initial reading
                    if (data.readings && data.readings.length > 0) {
                        previousReading = data.readings[0].usage_units;
                    } else {
                        previousReading = customersData[customerId]?.initial_reading || 0;
                    }
                    
                    prevReadingDisplay.textContent = previousReading.toLocaleString(undefined, { minimumFractionDigits: 2 });

                    if (data.readings && data.readings.length > 0) {
                        let html = `
                            <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-emerald-500/30">
                                <table class="w-full text-left border-collapse min-w-[600px]">
                                    <thead>
                                        <tr class="bg-[#0f1722]/60 text-[#94a3b8] uppercase text-xs tracking-wider">
                                            <th class="px-6 py-4 font-semibold border-b border-[#263548]">Billing Date</th>
                                            <th class="px-6 py-4 font-semibold text-right border-b border-[#263548]">Reading (L)</th>
                                            <th class="px-6 py-4 font-semibold text-right border-b border-[#263548]">Usage (L)</th>
                                            <th class="px-6 py-4 font-semibold text-right border-b border-[#263548]">Bill (₱)</th>
                                            <th class="px-6 py-4 font-semibold text-center border-b border-[#263548]">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#263548]">
                        `;

                        data.readings.forEach((reading, index) => {
                            // Calculate usage as difference from PREVIOUS bill in time.
                            // Since readings are DESC by date, the previous chronological reading is at index + 1
                            let readingUsage = 0;
                            if (index < data.readings.length - 1) {
                                readingUsage = Math.abs(data.readings[index+1].usage_units - reading.usage_units);
                            } else {
                                // For the oldest bill, usage is difference from initial customer record reading
                                readingUsage = Math.abs((customersData[customerId]?.initial_reading || 0) - reading.usage_units);
                            }
                            
                            const statusColor = reading.status === 'Paid' ? 'bg-emerald-900/40 text-emerald-300 border-emerald-700/50' : 'bg-orange-900/40 text-orange-300 border-orange-700/50';

                            // Apply color coding to usage column based on thresholds
                            const customerType = data.customer?.type || 'Regular';
                            const t = thresholds[customerType] || { green_max: 12, orange_max: 14 };
                            let usageColor = 'text-gray-400';

                            if (readingUsage > 0 && readingUsage <= t.green_max) {
                                usageColor = 'text-emerald-400 font-bold';
                            } else if (readingUsage > t.green_max && readingUsage <= t.orange_max) {
                                usageColor = 'text-orange-400 font-bold';
                            } else if (readingUsage > t.orange_max) {
                                usageColor = 'text-rose-400 font-bold';
                            }

                            html += `
                                <tr class="hover:bg-[#1b2636]/60 transition duration-300">
                                    <td class="px-6 py-4 text-gray-300 font-medium">${new Date(reading.billing_date).toLocaleDateString()}</td>
                                    <td class="px-6 py-4 text-right font-mono text-gray-300">${reading.usage_units.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    <td class="px-6 py-4 text-right font-mono ${usageColor}">${readingUsage.toFixed(2)}</td>
                                    <td class="px-6 py-4 text-right font-mono font-bold text-gray-200">₱${reading.total_amount.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold border ${statusColor}">
                                            ${reading.status}
                                        </span>
                                    </td>
                                </tr>
                            `;
                        });

                        html += `</tbody></table></div>`;
                        historyDiv.innerHTML = html;
                    } else {
                        historyDiv.innerHTML = `
                            <div class="flex flex-col items-center py-6">
                                <p class="text-gray-400 font-medium">No reading history yet</p>
                                <p class="text-xs text-gray-600">Using initial reading: ${previousReading.toFixed(2)}</p>
                            </div>
                        `;
                    }
                    calculateCharges();
                })
                .catch(error => {
                    console.error('Error loading history:', error);
                    historyDiv.innerHTML = '<div class="text-center py-12 text-rose-500 font-medium">Failed to load customer history</div>';
                    previousReading = customersData[customerId]?.initial_reading || 0;
                    prevReadingDisplay.textContent = previousReading.toLocaleString(undefined, { minimumFractionDigits: 2 });
                    calculateCharges();
                });
        }

        function calculateCharges() {
            const customerSelect = document.getElementById('customer_id');
            const presentReadingInput = document.getElementById('usage_units'); // this field stores the reading
            const baseChargeInput = document.getElementById('base_charge');
            const usageChargeInput = document.getElementById('usage_charge');
            const calculationText = document.getElementById('usage-calculation');
            const consumptionDisplay = document.getElementById('calculated_usage_display');

            if (!customerSelect.value || !presentReadingInput.value) {
                baseChargeInput.value = 0;
                usageChargeInput.value = 0;
                calculationText.textContent = '';
                consumptionDisplay.value = '0.00';
                updateTotal();
                return;
            }

            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            const customerType = selectedOption.getAttribute('data-type');

            // Formula: Previous Reading - Present Reading = Usage
            const presentReading = parseFloat(presentReadingInput.value) || 0;
            // The user formula uses (previous - present)
            const usage = Math.abs(previousReading - presentReading);
            
            consumptionDisplay.value = usage.toFixed(2);
            document.getElementById('consumption').value = usage.toFixed(2);

            // Tiered Charge Formula from User:
            // Regular: (usage - 10) * 15 + 100
            // Commercial: (usage - 10) * 25 + 250
            
            let baseCharge = 0;
            let rate = 0;

            if (customerType === 'Regular') {
                baseCharge = 100;
                rate = 15;
            } else if (customerType === 'Commercial') {
                baseCharge = 250;
                rate = 25;
            }

            // Calculation as per "result - 10 = result x rate = result + base = total"
            // We use Math.max to avoid negative charges if usage is < 10
            const billableUsage = Math.max(usage - 10, 0);
            const usageCharge = billableUsage * rate;

            baseChargeInput.value = baseCharge.toFixed(2);
            usageChargeInput.value = usageCharge.toFixed(2);

            // Show calculation breakdown
            calculationText.textContent = `Usage: ${usage.toFixed(2)}L | Calculation: (${usage.toFixed(2)} - 10) × ₱${rate} = ₱${usageCharge.toFixed(2)}`;

            // Determine color class based on total usage
            const waterIncrease = usage;
            const t = thresholds[customerType] || { green_max: 12, orange_max: 14 };
            let colorClass = 'text-zinc-500 dark:text-zinc-400';

            if (waterIncrease > 0 && waterIncrease <= t.green_max) {
                colorClass = 'text-green-600 dark:text-green-400';
            } else if (waterIncrease > t.green_max && waterIncrease <= t.orange_max) {
                colorClass = 'text-orange-600 dark:text-orange-400';
            } else if (waterIncrease > t.orange_max) {
                colorClass = 'text-red-600 dark:text-red-400';
            }

            calculationText.className = `text-xs mt-1 ${colorClass}`;

            updateTotal();
        }

        function updateTotal() {
            const baseCharge = parseFloat(document.getElementById('base_charge').value) || 0;
            const usageCharge = parseFloat(document.getElementById('usage_charge').value) || 0;
            const totalAmount = baseCharge + usageCharge;
            document.getElementById('total_amount').value = totalAmount.toFixed(2);
        }

        // Load history on page load if customer is pre-selected
        window.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('customer_id').value) {
                loadCustomerHistory();
                calculateCharges();
            }
        });
    </script>
</x-layouts::app>