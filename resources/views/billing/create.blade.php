<x-layouts::app title="Create Bill">
    <div class="max-w-4xl">
        <flux:heading class="mb-6">Create Bill</flux:heading>

        <!-- Billing Form -->
        <flux:card class="mb-6">
            <form method="POST" action="{{ route('billing.store') }}">
                @csrf

                <flux:fieldset>
                    <flux:field>
                        <flux:label for="customer_id">Customer</flux:label>
                        <flux:select
                            id="customer_id"
                            name="customer_id"
                            required
                            onchange="loadCustomerHistory(); calculateCharges()"
                        >
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" data-type="{{ $customer->type }}" {{ old('customer_id', request('customer_id')) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->customer_id }}) - {{ $customer->type }}
                                </option>
                            @endforeach
                        </flux:select>
                        @error('customer_id')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <div class="grid grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label for="billing_date">Billing Date</flux:label>
                            <flux:input
                                id="billing_date"
                                name="billing_date"
                                type="date"
                                required
                                value="{{ old('billing_date', now()->format('Y-m-d')) }}"
                            />
                            @error('billing_date')
                                <flux:error>{{ $message }}</flux:error>
                            @enderror
                        </flux:field>

                        <flux:field>
                            <flux:label for="due_date">Due Date</flux:label>
                            <flux:input
                                id="due_date"
                                name="due_date"
                                type="date"
                                required
                                value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}"
                            />
                            @error('due_date')
                                <flux:error>{{ $message }}</flux:error>
                            @enderror
                        </flux:field>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-4">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">💡 Enter the water usage for this month. Charges will be calculated automatically.</p>
                        
                        <flux:field>
                            <flux:label for="usage_units">Water Usage (L) *</flux:label>
                            <flux:input
                                id="usage_units"
                                name="usage_units"
                                type="number"
                                step="0.01"
                                required
                                value="{{ old('usage_units') }}"
                                oninput="calculateCharges()"
                            />
                            <p id="usage-calculation" class="text-xs text-zinc-500 dark:text-zinc-400 mt-1"></p>
                            @error('usage_units')
                                <flux:error>{{ $message }}</flux:error>
                            @enderror
                        </flux:field>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <flux:field>
                            <flux:label for="base_charge">Base Charge (₱)</flux:label>
                            <flux:input
                                id="base_charge"
                                name="base_charge"
                                type="number"
                                step="0.01"
                                required
                                value="{{ old('base_charge', 0) }}"
                                oninput="updateTotal()"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Auto-calculated based on customer type</p>
                        </flux:field>

                        <flux:field>
                            <flux:label for="usage_charge">Usage Charge (₱)</flux:label>
                            <flux:input
                                id="usage_charge"
                                name="usage_charge"
                                type="number"
                                step="0.01"
                                required
                                value="{{ old('usage_charge', 0) }}"
                                oninput="updateTotal()"
                            />
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Auto-calculated from usage</p>
                        </flux:field>

                        <flux:field>
                            <flux:label for="total_amount">Total Bill (₱)</flux:label>
                            <flux:input
                                id="total_amount"
                                type="number"
                                step="0.01"
                                readonly
                                value="0"
                            />
                        </flux:field>
                    </div>
                </flux:fieldset>

                <div class="flex gap-3 mt-6">
                    <flux:button type="submit" variant="primary">Create Bill</flux:button>
                    <flux:button :href="route('billing.index')" variant="ghost" wire:navigate>Cancel</flux:button>
                </div>
            </form>
        </flux:card>

        <!-- Customer History -->
        <flux:card>
            <flux:heading size="md" class="mb-4">Water Reading History</flux:heading>
            <div id="customer-history" class="text-center py-6 text-zinc-500">
                Select a customer to view reading history
            </div>
        </flux:card>
    </div>

    <script>
        // Store customer data for reference
        const customersData = {
            @foreach ($customers as $customer)
                '{{ $customer->id }}': {
                    type: '{{ $customer->type }}',
                    name: '{{ $customer->name }}'
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

            if (!customerId) {
                historyDiv.innerHTML = '<div class="text-center py-6 text-zinc-500">Select a customer to view reading history</div>';
                previousReading = 0;
                calculateCharges();
                return;
            }

            // Fetch customer history via AJAX
            fetch(`/api/customers/${customerId}/readings`)
                .then(response => response.json())
                .then(data => {
                    if (data.readings && data.readings.length > 0) {
                        // Set previous reading for calculation
                        previousReading = data.readings[0].usage_units;
                        
                        let html = `
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-zinc-100 dark:bg-zinc-800">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Date</th>
                                            <th class="px-4 py-2 text-right">Reading (L)</th>
                                            <th class="px-4 py-2 text-right">Usage (L)</th>
                                            <th class="px-4 py-2 text-right">Bill (₱)</th>
                                            <th class="px-4 py-2 text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        `;
                        
                        data.readings.forEach((reading, index) => {
                            const usage = index > 0 ? reading.usage_units - data.readings[index - 1].usage_units : 0;
                            const statusColor = reading.status === 'Paid' ? 'text-green-600' : 'text-orange-600';
                            
                            // Apply color coding to usage column based on thresholds
                            const customerType = customersData[customerId]?.type || 'Regular';
                            const t = thresholds[customerType] || { green_max: 12, orange_max: 14 };
                            let usageColor = 'text-zinc-500';
                            
                            if (usage > 0 && usage <= t.green_max) {
                                usageColor = 'text-green-600 dark:text-green-400 font-semibold';
                            } else if (usage > t.green_max && usage <= t.orange_max) {
                                usageColor = 'text-orange-600 dark:text-orange-400 font-semibold';
                            } else if (usage > t.orange_max) {
                                usageColor = 'text-red-600 dark:text-red-400 font-semibold';
                            }
                            
                            html += `
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-900">
                                    <td class="px-4 py-2">${new Date(reading.billing_date).toLocaleDateString()}</td>
                                    <td class="px-4 py-2 text-right font-mono">${reading.usage_units}</td>
                                    <td class="px-4 py-2 text-right font-mono ${usageColor}">${usage.toFixed(2)}</td>
                                    <td class="px-4 py-2 text-right font-mono">₱${reading.total_amount.toFixed(2)}</td>
                                    <td class="px-4 py-2 text-center"><span class="${statusColor}">${reading.status}</span></td>
                                </tr>
                            `;
                        });

                        html += `</tbody></table></div>`;
                        historyDiv.innerHTML = html;
                    } else {
                        previousReading = 0;
                        historyDiv.innerHTML = '<div class="text-center py-6 text-zinc-500">No reading history yet</div>';
                    }
                    calculateCharges();
                })
                .catch(error => {
                    console.error('Error loading history:', error);
                    historyDiv.innerHTML = '<div class="text-center py-6 text-red-500">Error loading history</div>';
                    previousReading = 0;
                    calculateCharges();
                });
        }

        function calculateCharges() {
            const customerSelect = document.getElementById('customer_id');
            const usageInput = document.getElementById('usage_units');
            const baseChargeInput = document.getElementById('base_charge');
            const usageChargeInput = document.getElementById('usage_charge');
            const calculationText = document.getElementById('usage-calculation');

            if (!customerSelect.value || !usageInput.value) {
                baseChargeInput.value = 0;
                usageChargeInput.value = 0;
                calculationText.textContent = '';
                updateTotal();
                return;
            }

            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            const customerType = selectedOption.getAttribute('data-type');
            
            // The user directly inputs the amount of water used THIS MONTH
            const usage = parseFloat(usageInput.value) || 0;

            // Formula: (usage - 10) * rate + baseCharge
            let baseCharge = 0;
            let rate = 0;

            if (customerType === 'Regular') {
                baseCharge = 100;
                rate = 15;
            } else if (customerType === 'Commercial') {
                baseCharge = 250;
                rate = 25;
            }

            const billableUsage = Math.max(usage - 10, 0);
            const usageCharge = billableUsage * rate;

            baseChargeInput.value = baseCharge.toFixed(2);
            usageChargeInput.value = usageCharge.toFixed(2);

            // Show calculation breakdown and color based on thresholds
            calculationText.textContent = `Usage: ${usage}L | Calculation: (${usage} - 10) × ₱${rate} = ₱${usageCharge.toFixed(2)}`;

            // Determine color class based on usage INCREASE (billable usage)
            // The increase is the metered usage above the 10L threshold
            const waterIncrease = billableUsage;
            const t = thresholds[customerType] || { green_max: 12, orange_max: 14 };
            let colorClass = 'text-zinc-500 dark:text-zinc-400';

            // Color coding based on the increase amount:
            // Green: 1 to green_max (inclusive)
            // Orange: (green_max + 1) to orange_max (inclusive)
            // Red: above orange_max
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
