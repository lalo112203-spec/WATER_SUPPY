<x-layouts::app title="Receipt">
    <div class="max-w-xl mx-auto py-8">
        <div class="bg-white border-t-8 border-blue-500 shadow-lg rounded-lg overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-center pb-6 border-b border-gray-100">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">RECEIPT</h2>
                        <p class="text-sm text-gray-500 mt-1">#{{ str_pad($bill->id, 8, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="text-right">
                        <x-app-logo class="h-8 mb-2 justify-end" />
                        <p class="text-sm font-semibold text-gray-600">Water System Comm.</p>
                    </div>
                </div>

                <div class="flex justify-between items-start py-6 border-b border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Billed To</p>
                        <p class="font-medium text-gray-800">{{ $bill->customer->name }}</p>
                        <p class="text-sm text-gray-600 mt-1">Customer ID: {{ $bill->customer->customer_id ?? $bill->customer->id }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $bill->customer->address }}</p>
                    </div>
                    <div class="text-right">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Billing Period</p>
                            <p class="font-medium text-gray-800">{{ $bill->billing_date->format('F Y') }}</p>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Date Paid</p>
                            <p class="font-medium text-green-600">{{ $bill->paid_date ? $bill->paid_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="py-6">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-4">Payment Details</p>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <p class="text-gray-600">Water Usage ({{ $bill->usage_units }} m³)</p>
                            <p class="text-gray-800 font-medium">₱{{ number_format($bill->usage_charge, 2) }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-gray-600">Base Charge</p>
                            <p class="text-gray-800 font-medium">₱{{ number_format($bill->base_charge, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 -mx-8 -mb-8 p-8 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <p class="text-lg text-gray-600 font-semibold uppercase tracking-wider">Total Paid</p>
                        <p class="text-2xl text-green-600 font-bold tracking-tight">₱{{ number_format($bill->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center print:hidden">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm flex items-center justify-center mx-auto transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Receipt
            </button>
            <a href="{{ route('messages.index') }}" class="text-gray-500 hover:text-gray-700 text-sm mt-4 inline-block font-medium">
                &larr; Back to Dashboard
            </a>
        </div>
    </div>
    
    <style type="text/css" media="print">
        @page { size: auto; margin: 0; }
        body { background: white; margin: 2cm; }
        header, nav, aside.flux-sidebar { display: none !important; }
        .flex-1 { margin: 0 !important; padding: 0 !important; }
        main { background: white !important; height: auto !important; }
    </style>
</x-layouts::app>
