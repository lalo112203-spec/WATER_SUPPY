<x-layouts::app title="Bill Details">
    <div class="max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <flux:heading>Bill #{{ $bill->id }}</flux:heading>
            <flux:button :href="route('billing.index')" variant="ghost" wire:navigate>Back</flux:button>
        </div>

        <flux:card class="mb-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Customer</flux:text>
                    <flux:heading size="sm" class="mt-1">{{ $bill->customer->name }}</flux:heading>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">{{ $bill->customer->customer_id }}</flux:text>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">{{ $bill->customer->meter_post ?? 'N/A' }}</flux:text>
                </div>
                <div class="text-right">
                    <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Status</flux:text>
                    <flux:badge
                        :label="$bill->status"
                        :color="$bill->status === 'Paid' ? 'green' : 'orange'"
                        class="mt-1"
                    />
                </div>
            </div>

            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6">
                <div class="grid grid-cols-2 gap-6 mb-4">
                    <div>
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Billing Date</flux:text>
                        <flux:text class="font-semibold mt-1">{{ $bill->billing_date->format('M d, Y') }}</flux:text>
                    </div>
                    <div>
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Due Date</flux:text>
                        <flux:text class="font-semibold mt-1">{{ $bill->due_date->format('M d, Y') }}</flux:text>
                    </div>
                </div>

                @if ($bill->paid_date)
                    <div class="mb-4">
                        <flux:text class="text-zinc-500 dark:text-zinc-400 text-sm">Paid Date</flux:text>
                        <flux:text class="font-semibold mt-1">{{ $bill->paid_date->format('M d, Y') }}</flux:text>
                    </div>
                @endif
            </div>
        </flux:card>

        <flux:card class="mb-6">
            <flux:heading size="sm" class="mb-6">Bill Details</flux:heading>
            
            <div class="space-y-4">
                <div class="flex justify-between">
                    <flux:text>Previous Reading</flux:text>
                    <flux:text class="font-semibold">{{ $bill->previous_reading !== null ? number_format($bill->previous_reading, 0) : '-' }} m³</flux:text>
                </div>
                <div class="flex justify-between">
                    <flux:text>New Reading</flux:text>
                    <flux:text class="font-semibold">{{ $bill->new_reading !== null ? number_format($bill->new_reading, 0) : '-' }} m³</flux:text>
                </div>
                <div class="flex justify-between border-t border-zinc-200 dark:border-zinc-700 pt-3 mt-1">
                    <flux:text>Water Consumption ({{ $bill->consumption ?? 0 }} m³)</flux:text>
                    <flux:text class="font-semibold">₱{{ number_format($bill->usage_charge, 0) }}</flux:text>
                </div>
                <div class="flex justify-between">
                    <flux:text>Base Charge</flux:text>
                    <flux:text class="font-semibold">₱{{ number_format($bill->base_charge, 0) }}</flux:text>
                </div>

                @if(!empty($bill->applied_additional_charges))
                    @foreach($bill->applied_additional_charges as $charge)
                        <div class="flex justify-between text-blue-600 dark:text-blue-400">
                            <flux:text>Additional Charge: {{ $charge['name'] }}</flux:text>
                            <flux:text class="font-semibold">+ ₱{{ number_format($charge['amount'], 0) }}</flux:text>
                        </div>
                    @endforeach
                @endif
                
                @if($bill->additional_charge_amount > 0)
                    <div class="flex justify-between text-blue-600 dark:text-blue-400">
                        <flux:text>Additional Charge: {{ $bill->additional_charge_note ?? 'Manual' }}</flux:text>
                        <flux:text class="font-semibold">+ ₱{{ number_format($bill->additional_charge_amount, 0) }}</flux:text>
                    </div>
                @endif
                
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 flex justify-between">
                    <flux:heading size="sm">Total Amount</flux:heading>
                    <flux:heading size="sm" class="text-green-600">₱{{ number_format($bill->total_amount, 0) }}</flux:heading>
                </div>
            </div>
        </flux:card>

        <div class="flex gap-3">
            @if ($bill->status !== 'Paid')
                <flux:button 
                    onclick="confirm('Mark this bill as paid?') && document.getElementById('mark-paid-form').submit()"
                    variant="primary"
                >
                    Mark as Paid
                </flux:button>
                <form id="mark-paid-form" action="{{ route('billing.mark-paid', $bill) }}" method="POST" style="display: none;">
                    @csrf
                    @method('PATCH')
                </form>
                <flux:button type="button" onclick="window.Flux.modal('edit-bill-modal').show()" variant="ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Statement
                </flux:button>
                <flux:button :href="route('billing.receipt', $bill)" variant="ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Statement
                </flux:button>
            @else
                <flux:button :href="route('billing.receipt', $bill)" variant="primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Receipt
                </flux:button>
            @endif
            <flux:button 
                onclick="confirm('Are you sure?') && document.getElementById('delete-form').submit()"
                variant="danger"
            >
                Delete
            </flux:button>
            <form id="delete-form" action="{{ route('billing.destroy', $bill) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <flux:modal name="edit-bill-modal" class="md:w-[800px] !bg-[#121a25] !border !border-[#2d4059] !text-gray-200">
        <div class="p-4 bg-[#121a25] text-gray-200 rounded-xl max-h-[85vh] overflow-y-auto custom-scrollbar">
            <div class="flex items-center justify-between mb-4 border-b border-[#263548] pb-2">
                <flux:heading size="lg" class="!text-white">Edit Bill #{{ $bill->id }}</flux:heading>
                <flux:modal.close>
                    <button type="button" class="text-gray-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </flux:modal.close>
            </div>
            
            <form method="POST" action="{{ route('billing.update', $bill) }}">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-1">Customer</label>
                            <input type="text" readonly value="{{ $bill->customer->name }} ({{ $bill->customer->customer_id }})" 
                                class="w-full bg-[#1b2636]/40 border border-[#2d4059] text-gray-200 text-sm rounded-xl py-2 px-3 outline-none">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="billing_date" class="block text-sm font-medium text-gray-200 mb-1">Billing Date</label>
                                <input type="date" id="billing_date" name="billing_date" required
                                    value="{{ old('billing_date', $bill->billing_date->format('Y-m-d')) }}"
                                    class="w-full bg-[#1b2636]/60 border border-[#2d4059] focus:border-emerald-500/50 text-gray-200 text-sm rounded-xl py-2 px-3 outline-none">
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-200 mb-1">Due Date</label>
                                <input type="date" id="due_date" name="due_date" required
                                    value="{{ old('due_date', $bill->due_date->format('Y-m-d')) }}"
                                    class="w-full bg-[#1b2636]/60 border border-[#2d4059] focus:border-emerald-500/50 text-gray-200 text-sm rounded-xl py-2 px-3 outline-none">
                            </div>
                        </div>

                        <div class="bg-[#1e293b]/40 border border-amber-500/20 p-4 rounded-2xl relative">
                            <label for="new_reading" class="block text-sm font-medium text-gray-300 mb-1">New Reading (m³) *</label>
                            <input type="number" step="1" id="new_reading" name="new_reading" required
                                min="{{ $bill->previous_reading }}"
                                value="{{ old('new_reading', $bill->new_reading) }}" oninput="calculateCharges()" 
                                class="w-full bg-[#0f1722]/60 border border-[#2d4059] text-gray-100 text-lg font-bold rounded-xl py-2 px-3 outline-none transition-all duration-300">
                            <p id="usage-calculation" class="text-xs mt-2 flex items-center gap-2 min-h-[1rem]"></p>
                        </div>
                    </div>

                    <div class="space-y-4 bg-[#0f1722]/40 p-4 rounded-2xl border border-[#263548]">
                        <div>
                            <label for="calculated_usage_display" class="block text-xs font-medium text-gray-200 mb-1">Calculated Consumption (m³)</label>
                            <input type="text" id="calculated_usage_display" readonly value="{{ $bill->consumption }}"
                                class="w-full bg-[#1b2636]/40 border border-[#2d4059] text-gray-300 rounded-xl py-2 px-3 font-mono outline-none">
                            <input type="hidden" name="consumption" id="consumption" value="{{ $bill->consumption }}">
                        </div>

                        <div>
                            <label for="base_charge" class="block text-xs font-medium text-gray-200 mb-1">Base Charge</label>
                            <div class="relative text-gray-300 font-medium">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-200">₱</span>
                                <input type="number" step="0.01" id="base_charge" name="base_charge" required
                                    value="{{ old('base_charge', $bill->base_charge) }}" oninput="updateTotal()"
                                    class="w-full bg-[#1b2636]/40 border border-[#2d4059] py-2 pl-7 pr-3 rounded-xl outline-none">
                            </div>
                        </div>

                        <div>
                            <label for="usage_charge" class="block text-xs font-medium text-gray-200 mb-1">Usage Charge</label>
                            <div class="relative text-gray-300 font-medium">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-200">₱</span>
                                <input type="number" step="0.01" id="usage_charge" name="usage_charge" required
                                    value="{{ old('usage_charge', $bill->usage_charge) }}" oninput="updateTotal()"
                                    class="w-full bg-[#1b2636]/40 border border-[#2d4059] py-2 pl-7 pr-3 rounded-xl outline-none">
                            </div>
                        </div>

                        <div class="pt-3 mt-3 border-t border-[#263548]">
                            @if($globalAdditionalChargeTotal > 0)
                            <div class="mb-3 space-y-1">
                                <p class="text-[10px] text-gray-200 uppercase font-bold">Applied Additional Charges:</p>
                                @foreach($bill->applied_additional_charges as $gd)
                                    <div class="flex justify-between text-[11px] text-blue-400 italic">
                                        <span>{{ $gd['name'] }}</span>
                                        <span>+ ₱{{ number_format($gd['amount'], 0) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @endif

                            <label for="total_amount" class="block text-xs font-medium text-emerald-500/80 mb-1 uppercase">Total Payable Amount</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-emerald-400 font-bold text-lg">₱</span>
                                <input type="number" step="0.01" id="total_amount" readonly value="{{ $bill->total_amount }}"
                                    class="w-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-2xl font-black rounded-xl py-2 pl-8 pr-3 outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-[#263548]">
                    <flux:modal.close>
                        <flux:button variant="ghost" class="px-6 !border !border-[#2d4059] !text-gray-300 hover:!bg-[#1b2636] hover:!text-white">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" id="edit_bill_btn" variant="primary" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 transition-all font-bold text-white">Save Changes</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
    
    <script>
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

        const previousReading = {{ $bill->previous_reading ?? 0 }};

        function calculateCharges() {
            const customerType = '{{ $bill->customer->type }}';
            const presentReadingInput = document.getElementById('new_reading');
            const consumptionDisplay = document.getElementById('calculated_usage_display');
            const baseChargeInput = document.getElementById('base_charge');
            const usageChargeInput = document.getElementById('usage_charge');
            const calculationText = document.getElementById('usage-calculation');

            const presentReading = parseFloat(presentReadingInput.value) || 0;
            
            if (presentReading < previousReading) {
                calculationText.textContent = `Invalid: Reading cannot be lower than previous (${previousReading})`;
                calculationText.className = 'text-xs mt-1 text-rose-500 font-bold';
                const submitBtn = document.getElementById('edit_bill_btn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
                baseChargeInput.value = 0;
                usageChargeInput.value = 0;
                consumptionDisplay.value = '0.00';
                updateTotal();
                return;
            }
            
            const submitBtn = document.getElementById('edit_bill_btn');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
            const usage = presentReading - previousReading;
            
            consumptionDisplay.value = usage.toFixed(0);
            document.getElementById('consumption').value = usage.toFixed(0);

            let baseCharge = settings[customerType]?.base || 0;
            let rate = settings[customerType]?.rate || 0;
            let baseLimit = settings[customerType]?.limit || 10;

            const billableUsage = Math.max(usage - baseLimit, 0);
            const usageCharge = billableUsage * rate;

            baseChargeInput.value = baseCharge.toFixed(0);
            usageChargeInput.value = usageCharge.toFixed(0);
            calculationText.textContent = `Usage: ${usage.toFixed(0)}m³ | Calculation: (${usage.toFixed(0)} - ${baseLimit}) × ₱${rate} = ₱${usageCharge.toFixed(0)}`;
            
            updateTotal();
        }

        function updateTotal() {
            const base = parseFloat(document.getElementById('base_charge').value) || 0;
            const usage = parseFloat(document.getElementById('usage_charge').value) || 0;
            const globalAdditionalChargeTotal = {{ $globalAdditionalChargeTotal }};
            
            const total = base + usage + globalAdditionalChargeTotal;
            document.getElementById('total_amount').value = total.toFixed(0);
        }
        
        @if($errors->any())
            window.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => window.Flux.modal('edit-bill-modal').show(), 100);
            });
        @endif
    </script>
</x-layouts::app>
