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
                    <flux:text>Water Consumption ({{ $bill->consumption ?? 0 }} L)</flux:text>
                    <flux:text class="font-semibold">₱{{ number_format($bill->usage_charge, 2) }}</flux:text>
                </div>
                <div class="flex justify-between">
                    <flux:text>Base Charge</flux:text>
                    <flux:text class="font-semibold">₱{{ number_format($bill->base_charge, 2) }}</flux:text>
                </div>

                @if(!empty($bill->applied_additional_charges))
                    @foreach($bill->applied_additional_charges as $charge)
                        <div class="flex justify-between text-blue-600 dark:text-blue-400">
                            <flux:text>Additional Charge: {{ $charge['name'] }}</flux:text>
                            <flux:text class="font-semibold">+ ₱{{ number_format($charge['amount'], 2) }}</flux:text>
                        </div>
                    @endforeach
                @endif
                
                @if($bill->additional_charge_amount > 0)
                    <div class="flex justify-between text-blue-600 dark:text-blue-400">
                        <flux:text>Additional Charge: {{ $bill->additional_charge_note ?? 'Manual' }}</flux:text>
                        <flux:text class="font-semibold">+ ₱{{ number_format($bill->additional_charge_amount, 2) }}</flux:text>
                    </div>
                @endif
                
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 flex justify-between">
                    <flux:heading size="sm">Total Amount</flux:heading>
                    <flux:heading size="sm" class="text-green-600">₱{{ number_format($bill->total_amount, 2) }}</flux:heading>
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
                <flux:button :href="route('billing.edit', $bill)" variant="ghost">
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
</x-layouts::app>
