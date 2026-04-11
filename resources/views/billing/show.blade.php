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
                    <flux:text>Water Consumption ({{ $bill->consumption ?? 0 }} m³)</flux:text>
                    <flux:text class="font-semibold">₱{{ number_format($bill->usage_charge, 2) }}</flux:text>
                </div>
                <div class="flex justify-between">
                    <flux:text>Base Charge</flux:text>
                    <flux:text class="font-semibold">₱{{ number_format($bill->base_charge, 2) }}</flux:text>
                </div>
                
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
