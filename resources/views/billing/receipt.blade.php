<x-layouts::app title="Receipt">
    <div class="max-w-xl mx-auto py-8">
        <div class="bg-[#121a25]/80 backdrop-blur-md border-t-8 border-blue-500 shadow-lg rounded-lg overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-center pb-6 border-b border-[#263548]">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-200">RECEIPT</h2>
                        <p class="text-sm text-gray-400 mt-1">#{{ str_pad($bill->id, 8, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="text-right">
                        <x-app-logo class="h-8 mb-2 justify-end" />
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter leading-none">DOLORES WATER<br>SERVICES SYSTEM</p>
                    </div>
                </div>

                <div class="flex justify-between items-start py-6 border-b border-[#263548]">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Billed To</p>
                        <p class="font-medium text-gray-200">{{ $bill->customer->name }}</p>
                        <p class="text-sm text-gray-400 mt-1">Customer ID: {{ $bill->customer->customer_id ?? $bill->customer->id }}</p>
                        <p class="text-sm text-gray-400 mt-1">{{ $bill->customer->address }}</p>
                    </div>
                    <div class="text-right">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Billing Period</p>
                            <p class="font-medium text-gray-200">{{ $bill->billing_date->format('F Y') }}</p>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Date Paid</p>
                            <p class="font-medium text-green-600">{{ $bill->paid_date ? $bill->paid_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 py-6 border-b border-[#263548] print:border-zinc-200">
                    <div class="text-center">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1 print:text-zinc-500">Prev. Reading</p>
                        <p class="font-bold text-gray-200 print:text-black">{{ number_format($bill->usage_units - $bill->consumption, 2) }}</p>
                    </div>
                    <div class="text-center border-x border-[#263548] print:border-zinc-200">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1 print:text-zinc-500">Curr. Reading</p>
                        <p class="font-bold text-gray-200 print:text-black">{{ number_format($bill->usage_units, 2) }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1 print:text-zinc-500">Consumption</p>
                        <p class="font-bold text-blue-400 print:text-black">{{ number_format($bill->consumption, 2) }} m³</p>
                    </div>
                </div>

                <div class="py-6">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-4 print:text-zinc-500">Charge Breakdown</p>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <p class="text-gray-400 print:text-zinc-700">Usage Charge ({{ $bill->consumption }} m³)</p>
                            <p class="text-gray-200 font-medium print:text-black">₱{{ number_format($bill->usage_charge, 2) }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-gray-400">Base Charge</p>
                            <p class="text-gray-200 font-medium">₱{{ number_format($bill->base_charge, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-[#0f1722] -mx-8 -mb-8 p-8 border-t border-[#263548] print:bg-white print:border-zinc-200">
                    <div class="flex justify-between items-center">
                        <p class="text-lg text-gray-400 font-semibold uppercase tracking-wider print:text-zinc-700">Total Paid</p>
                        <p class="text-2xl text-green-600 font-bold tracking-tight print:text-black">₱{{ number_format($bill->total_amount, 2) }}</p>
                    </div>
                </div>
                <div class="mt-12 hidden mt-8 text-center print:block">
                    <div class="w-48 border-b border-black mx-auto mb-1"></div>
                    <p class="text-[10px] uppercase text-black font-bold">Authorized Signature</p>
                    <p class="text-[9px] text-zinc-500 mt-8 italic">This serves as your official receipt for water services rendered.</p>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('messages.index') }}" class="text-gray-400 hover:text-gray-300 text-sm mt-4 inline-block font-medium">
                &larr; Back to Dashboard
            </a>
        </div>
    </div>

</x-layouts::app>

