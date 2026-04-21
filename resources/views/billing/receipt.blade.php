<x-layouts::app title="Receipt">
    <style>
        @media print {
            nav, .sidebar, .no-print, button, a {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .max-w-xl {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
            .bg-[#121a25]\/80, .bg-[#0f1722] {
                background: white !important;
                backdrop-filter: none !important;
                border: 1px solid #eee !important;
            }
            /* Force black text for all elements in print */
            .text-gray-200, .text-gray-300, .text-gray-400, .text-blue-400, .text-green-600, h2, p, span, div {
                color: black !important;
                text-shadow: none !important;
            }
            .border-[#263548] {
                border-color: #000 !important;
            }
            .shadow-lg {
                box-shadow: none !important;
            }
            .rounded-lg {
                border-radius: 0 !important;
            }
            /* Ensure the logo is visible */
            [class*="bg-white/10"] {
                background-color: transparent !important;
                border: 1px solid #000 !important;
            }
            [class*="drop-shadow"] {
                filter: none !important;
            }
            .printable-receipt {
                border: 2px solid #000 !important;
                padding: 2rem !important;
                margin: 1rem !important;
            }
            /* Add a print date */
            .print-footer::after {
                content: "Printed on: " attr(data-date);
                display: block;
                font-size: 8px;
                text-align: right;
                margin-top: 10px;
                color: #666;
            }
        }
    </style>

    <div class="max-w-xl mx-auto py-8">
        <div class="mb-4 flex justify-end no-print">
            <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </button>
        </div>
        <div class="printable-receipt bg-[#121a25]/80 backdrop-blur-md border-t-8 border-blue-500 shadow-lg rounded-lg overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-center pb-6 border-b border-[#263548]">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-200">{{ strtolower($bill->status) === 'paid' ? 'RECEIPT' : 'BILLING STATEMENT' }}</h2>
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
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">{{ strtolower($bill->status) === 'paid' ? 'Date Paid' : 'Due Date' }}</p>
                            <p class="font-medium {{ strtolower($bill->status) === 'paid' ? 'text-green-600' : 'text-amber-500' }}">
                                {{ strtolower($bill->status) === 'paid' ? $bill->paid_date->format('M d, Y') : $bill->due_date->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 py-6 border-b border-[#263548] print:border-zinc-200">
                    <div class="text-center border-x border-[#263548] print:border-zinc-200 col-span-3">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1 print:text-zinc-500">Total Consumption</p>
                        <p class="font-bold text-blue-400 text-xl print:text-black">{{ number_format($bill->consumption, 2) }} L</p>
                    </div>
                    <!-- Single highlighted consumption row -->
                </div>

                <div class="py-6">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-4 print:text-zinc-500">Charge Breakdown</p>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <p class="text-gray-400 print:text-zinc-700">Usage Charge ({{ $bill->consumption }} L)</p>
                            <p class="text-gray-200 font-medium print:text-black">₱{{ number_format($bill->usage_charge, 2) }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-gray-400">Base Charge</p>
                            <p class="text-gray-200 font-medium">₱{{ number_format($bill->base_charge, 2) }}</p>
                        </div>
                        @if($bill->applied_additional_charges && count($bill->applied_additional_charges) > 0)
                            @foreach($bill->applied_additional_charges as $gCharge)
                            <div class="flex justify-between items-center bg-blue-500/5 -mx-4 px-4 py-2 rounded-lg border border-blue-500/10">
                                <div>
                                    <p class="text-blue-400 font-medium text-sm italic">Additional Charge: {{ $gCharge['name'] }}</p>
                                </div>
                                <p class="text-blue-400 font-bold">+ ₱{{ number_format($gCharge['amount'], 2) }}</p>
                            </div>
                            @endforeach
                        @endif

                        @if($bill->additional_charge_amount > 0)
                        <div class="flex justify-between items-center bg-blue-500/5 -mx-4 px-4 py-2 rounded-lg border border-blue-500/10">
                            <div>
                                <p class="text-blue-400 font-medium text-sm italic">Additional Charge: {{ $bill->additional_charge_note ?? 'Manual' }}</p>
                            </div>
                            <p class="text-blue-400 font-bold">+ ₱{{ number_format($bill->additional_charge_amount, 2) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-[#0f1722] -mx-8 -mb-8 p-8 border-t border-[#263548] print:bg-white print:border-zinc-200">
                    <div class="flex justify-between items-center">
                        <p class="text-lg text-gray-400 font-semibold uppercase tracking-wider print:text-zinc-700">{{ strtolower($bill->status) === 'paid' ? 'Total Paid' : 'Total Amount Due' }}</p>
                        <p class="text-2xl {{ strtolower($bill->status) === 'paid' ? 'text-green-600' : 'text-blue-500' }} font-bold tracking-tight print:text-black">₱{{ number_format($bill->total_amount, 2) }}</p>
                    </div>
                </div>
                <div class="mt-12 hidden mt-8 text-center print:block print-footer" data-date="{{ now()->format('M d, Y H:i') }}">
                    <div class="w-48 border-b border-black mx-auto mb-1"></div>
                    <p class="text-[10px] uppercase text-black font-bold">Authorized Signature</p>
                    <p class="text-[9px] text-zinc-500 mt-8 italic">This serves as your official receipt for water services rendered.</p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col items-center gap-4 no-print">
            <button onclick="window.print()" class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all shadow-lg hover:shadow-blue-500/20 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Official Receipt
            </button>

            <a href="{{ url()->previous() }}" class="text-gray-400 hover:text-gray-300 text-sm font-medium">
                &larr; Back to Previous Page
            </a>
        </div>
    </div>
</x-layouts::app>


