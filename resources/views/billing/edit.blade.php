<x-layouts::app title="Edit Bill">
    <div
        class="px-6 py-4 bg-transparent min-h-[calc(100vh-4rem)] font-sans text-gray-200 relative z-10 max-w-5xl mx-auto">

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-100 flex items-center gap-2 drop-shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 text-amber-400 drop-shadow-[0_0_8px_rgba(251,191,36,0.8)]" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Bill #{{ $bill->id }}
            </h1>
            <a href="{{ route('billing.show', $bill) }}"
                class="text-gray-400 hover:text-white flex items-center gap-2 transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Statement
            </a>
        </div>

        <div
            class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] border border-[#263548] p-8 mb-8 relative overflow-hidden group">
            <form method="POST" action="{{ route('billing.update', $bill) }}" class="relative z-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2 ml-1">Customer</label>
                            <input type="text" readonly value="{{ $bill->customer->name }} ({{ $bill->customer->customer_id }})" 
                                class="w-full bg-[#1b2636]/40 border border-[#2d4059] text-gray-400 text-sm rounded-xl py-3 px-4 outline-none">
                            <input type="hidden" id="customer_id" name="customer_id" value="{{ $bill->customer_id }}">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="billing_date" class="block text-sm font-medium text-gray-400 mb-2 ml-1">Billing Date</label>
                                <input type="date" id="billing_date" name="billing_date" required
                                    value="{{ old('billing_date', $bill->billing_date->format('Y-m-d')) }}"
                                    class="w-full bg-[#1b2636]/60 border border-[#2d4059] focus:border-emerald-500/50 text-gray-200 text-sm rounded-xl py-3 px-4 outline-none">
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-400 mb-2 ml-1">Due Date</label>
                                <input type="date" id="due_date" name="due_date" required
                                    value="{{ old('due_date', $bill->due_date->format('Y-m-d')) }}"
                                    class="w-full bg-[#1b2636]/60 border border-[#2d4059] focus:border-emerald-500/50 text-gray-200 text-sm rounded-xl py-3 px-4 outline-none">
                            </div>
                        </div>

                        <div class="bg-[#1e293b]/40 border border-amber-500/20 p-5 rounded-2xl relative">
                             <label for="usage_units" class="block text-sm font-medium text-gray-300 mb-2 ml-1">Total Usage (m³) *</label>
                            <input type="number" step="0.01" id="usage_units" name="usage_units" required
                                value="{{ old('usage_units', $bill->usage_units) }}" oninput="calculateCharges()" 
                                class="w-full bg-[#0f1722]/60 border border-[#2d4059] text-gray-100 text-lg font-bold rounded-xl py-3 px-4 outline-none transition-all duration-300">
                             <p id="usage-calculation" class="text-xs mt-3 flex items-center gap-2 min-h-[1.25rem]"></p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="flex flex-col justify-between">
                        <div class="space-y-6 bg-[#0f1722]/40 p-6 rounded-2xl border border-[#263548]">
                             <div class="grid grid-cols-1 gap-5">
                                <div>
                                    <label for="calculated_usage_display" class="block text-xs font-medium text-gray-500 mb-1 ml-1">Calculated Consumption (m³)</label>
                                    <input type="text" id="calculated_usage_display" readonly value="{{ $bill->consumption }}"
                                        class="w-full bg-[#1b2636]/40 border border-[#2d4059] text-gray-300 rounded-xl py-2 px-4 font-mono outline-none">
                                    <input type="hidden" name="consumption" id="consumption" value="{{ $bill->consumption }}">
                                </div>

                                <div>
                                    <label for="base_charge" class="block text-xs font-medium text-gray-500 mb-1 ml-1">Base Charge</label>
                                    <div class="relative text-gray-300 font-medium">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                        <input type="number" step="0.01" id="base_charge" name="base_charge" required
                                            value="{{ old('base_charge', $bill->base_charge) }}" oninput="updateTotal()"
                                            class="w-full bg-[#1b2636]/40 border border-[#2d4059] py-2 pl-7 pr-4 rounded-xl outline-none">
                                    </div>
                                </div>

                                <div>
                                    <label for="usage_charge" class="block text-xs font-medium text-gray-500 mb-1 ml-1">Usage Charge</label>
                                    <div class="relative text-gray-300 font-medium">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">₱</span>
                                        <input type="number" step="0.01" id="usage_charge" name="usage_charge" required
                                            value="{{ old('usage_charge', $bill->usage_charge) }}" oninput="updateTotal()"
                                            class="w-full bg-[#1b2636]/40 border border-[#2d4059] py-2 pl-7 pr-4 rounded-xl outline-none">
                                    </div>
                                </div>

                                <div class="pt-4 mt-4 border-t border-[#263548]">
                                    @if($globalAdditionalChargeTotal > 0)
                                    <div class="mb-4 space-y-1">
                                        <p class="text-[10px] text-gray-400 uppercase font-bold">Applied Additional Charges:</p>
                                        @foreach($bill->applied_additional_charges as $gd)
                                            <div class="flex justify-between text-[11px] text-blue-400 italic">
                                                <span>{{ $gd['name'] }}</span>
                                                <span>+ ₱{{ number_format($gd['amount'], 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif

                                    <label for="total_amount" class="block text-xs font-medium text-emerald-500/80 mb-2 ml-1 uppercase">Total Payable Amount</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-400 font-bold text-xl">₱</span>
                                        <input type="number" step="0.01" id="total_amount" readonly value="{{ $bill->total_amount }}"
                                            class="w-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-3xl font-black rounded-2xl py-4 pl-10 pr-4 outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 mt-8">
                            <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-500 text-white font-bold py-4 rounded-2xl shadow-[0_4px_15px_rgba(217,119,6,0.4)] transition-all flex items-center justify-center gap-2">
                                Save Changes
                            </button>
                            <a href="{{ route('billing.show', $bill) }}" class="px-6 bg-[#1b2636]/60 text-gray-400 rounded-2xl flex items-center border border-[#2d4059]">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const thresholds = {
            @foreach ($thresholds as $type => $vals)
                '{{ $type }}': { green_max: {{ $vals['green_max'] }}, orange_max: {{ $vals['orange_max'] }} },
            @endforeach
        };

        const settings = {
            Regular: { 
                base: {{ $settings['regular_base_charge'] }}, 
                rate: {{ $settings['regular_usage_rate'] }},
                limit: {{ $settings['regular_base_limit'] ?? 10 }}
            },
            Commercial: { 
                base: {{ $settings['commercial_base_charge'] }}, 
                rate: {{ $settings['commercial_usage_rate'] }},
                limit: {{ $settings['commercial_base_limit'] ?? 10 }}
            }
        };

        function calculateCharges() {
            const customerType = '{{ $bill->customer->type }}';
            const presentReadingInput = document.getElementById('usage_units');
            const consumptionDisplay = document.getElementById('calculated_usage_display');
            const baseChargeInput = document.getElementById('base_charge');
            const usageChargeInput = document.getElementById('usage_charge');
            const calculationText = document.getElementById('usage-calculation');

            const usage = parseFloat(presentReadingInput.value) || 0;
            consumptionDisplay.value = usage.toFixed(2);
            document.getElementById('consumption').value = usage.toFixed(2);

            let baseCharge = settings[customerType]?.base || 0;
            let rate = settings[customerType]?.rate || 0;
            let baseLimit = settings[customerType]?.limit || 10;

            const billableUsage = Math.max(usage - baseLimit, 0);
            const usageCharge = billableUsage * rate;

            baseChargeInput.value = baseCharge.toFixed(2);
            usageChargeInput.value = usageCharge.toFixed(2);
            calculationText.textContent = `Usage: ${usage.toFixed(2)}m³ | Calculation: (${usage.toFixed(2)} - ${baseLimit}) × ₱${rate} = ₱${usageCharge.toFixed(2)}`;
            
            updateTotal();
        }

        function updateTotal() {
            const base = parseFloat(document.getElementById('base_charge').value) || 0;
            const usage = parseFloat(document.getElementById('usage_charge').value) || 0;
            const globalAdditionalChargeTotal = {{ $globalAdditionalChargeTotal }};
            
            const total = base + usage + globalAdditionalChargeTotal;
            document.getElementById('total_amount').value = total.toFixed(2);
        }

        window.addEventListener('DOMContentLoaded', calculateCharges);
    </script>
</x-layouts::app>
