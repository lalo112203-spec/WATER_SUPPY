<x-layouts::app title="Receipt">
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 12mm;
            }

            html, body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                color: black !important;
                font-size: 11px !important;
            }

            /* Hide everything except the receipt */
            nav, header, footer, .sidebar, .no-print, button, a,
            [data-flux-sidebar], [data-flux-navbar], [data-flux-main] > *:not(.max-w-xl) {
                display: none !important;
            }

            body > *, [data-flux-main] {
                padding: 0 !important;
                margin: 0 !important;
                background: white !important;
            }

            .max-w-xl {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 auto !important;
                padding: 0 !important;
            }

            .printable-receipt {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                border: 1.5px solid #333 !important;
                border-top: 6px solid #1d4ed8 !important;
                border-radius: 4px !important;
                box-shadow: none !important;
                background: white !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .p-8 {
                padding: 14px 18px !important;
            }

            .py-6 {
                padding-top: 8px !important;
                padding-bottom: 8px !important;
            }

            .pb-6 { padding-bottom: 8px !important; }
            .mt-12, .mt-8 { margin-top: 10px !important; }

            /* Force black text everywhere */
            h2, h3, p, span, div, td, th {
                color: black !important;
                text-shadow: none !important;
                background: transparent !important;
                backdrop-filter: none !important;
            }

            /* Preserve status color hint with border instead */
            .text-green-600 { color: #15803d !important; }
            .text-amber-500 { color: #b45309 !important; }
            .text-blue-400, .text-blue-500 { color: #1d4ed8 !important; }

            .border-\[\#263548\] { border-color: #ccc !important; }
            .bg-\[\#0f1722\] { background: #f8f8f8 !important; }
            .bg-blue-500\/5 { background: #eff6ff !important; }
            .border-blue-500\/10 { border-color: #bfdbfe !important; }

            /* Shrink font sizes */
            .text-3xl { font-size: 18px !important; }
            .text-2xl { font-size: 15px !important; }
            .text-xl  { font-size: 13px !important; }
            .text-lg  { font-size: 12px !important; }
            .text-sm  { font-size: 10px !important; }
            .text-xs  { font-size: 9px !important; }
            .text-\[10px\] { font-size: 8px !important; }
            .text-\[9px\]  { font-size: 8px !important; }

            /* Shrink spacing */
            .space-y-4 > * + * { margin-top: 4px !important; }
            .gap-4 { gap: 8px !important; }

            [class*="drop-shadow"] { filter: none !important; }
            [class*="bg-white\/10"] { background-color: transparent !important; border: 1px solid #999 !important; }
            .shadow-lg, .shadow { box-shadow: none !important; }
            .rounded-lg { border-radius: 2px !important; }

            /* Footer signature line */
            .print-footer::after {
                content: "Printed on: " attr(data-date);
                display: block;
                font-size: 7px;
                text-align: right;
                margin-top: 6px;
                color: #666;
            }

            /* Remove -mx-8 negative margins that break print layout */
            .-mx-8 {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            .-mb-8 { margin-bottom: 0 !important; }
        }
    </style>

    <div class="max-w-xl mx-auto py-8">
        <div class="mb-4 flex justify-between items-center no-print">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('billing.index') }}" class="flex items-center gap-2 px-4 py-2 bg-[#1b2636] hover:bg-[#263548] text-gray-300 rounded-lg text-sm font-semibold transition-all border border-[#2d4059]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
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
                        <p class="text-sm text-gray-200 mt-1">#{{ str_pad($bill->id, 8, '0', STR_PAD_LEFT) }}</p>
                        @if($bill->or_number || strtolower($bill->status) === 'paid')
                            <p class="text-xs text-cyan-400 print:text-zinc-700 mt-0.5 font-mono font-semibold">OR No: {{ $bill->or_number_display }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <x-app-logo class="h-8 mb-2 justify-end" />
                        <p class="text-xs font-bold text-gray-200 uppercase tracking-tighter leading-none">DOLORES WATER<br>SERVICES SYSTEM</p>
                    </div>
                </div>

                <div class="flex justify-between items-start py-6 border-b border-[#263548]">
                    <div>
                        <p class="text-xs text-gray-200 uppercase tracking-wider mb-1">Billed To</p>
                        <p class="font-medium text-gray-200">{{ $bill->customer->name }}</p>
                        <p class="text-sm text-gray-200 mt-1">Account Number: {{ $bill->customer->customer_id ?? $bill->customer->id }}</p>
                        <p class="text-sm text-gray-200 mt-1">Meter Post: {{ $bill->customer->meter_post ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-200 mt-1">{{ $bill->customer->address }}</p>
                    </div>
                    <div class="text-right">
                        <div>
                            <p class="text-xs text-gray-200 uppercase tracking-wider mb-1">Billing Period</p>
                            <p class="font-medium text-gray-200">{{ $bill->billing_date->format('F Y') }}</p>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-200 uppercase tracking-wider mb-1">{{ strtolower($bill->status) === 'paid' ? 'Date Paid' : 'Due Date' }}</p>
                            <p class="font-medium {{ strtolower($bill->status) === 'paid' ? 'text-green-600' : 'text-amber-500' }}">
                                {{ strtolower($bill->status) === 'paid' ? $bill->paid_date->format('M d, Y') : $bill->due_date->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 py-6 border-b border-[#263548] print:border-zinc-200">
                    <div class="text-center border-r border-[#263548] print:border-zinc-200">
                        <p class="text-[10px] text-gray-200 uppercase tracking-widest mb-1 print:text-zinc-500">Previous Reading</p>
                        <p class="font-bold text-gray-200 text-lg print:text-black">{{ $bill->previous_reading !== null ? number_format($bill->previous_reading, 0) : '-' }}</p>
                    </div>
                    <div class="text-center border-r border-[#263548] print:border-zinc-200">
                        <p class="text-[10px] text-gray-200 uppercase tracking-widest mb-1 print:text-zinc-500">New Reading</p>
                        <p class="font-bold text-gray-200 text-lg print:text-black">{{ $bill->new_reading !== null ? number_format($bill->new_reading, 0) : '-' }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-gray-200 uppercase tracking-widest mb-1 print:text-zinc-500">Total Consumption</p>
                        <p class="font-bold text-blue-400 text-xl print:text-black">{{ number_format($bill->consumption, 0) }} m³</p>
                    </div>
                </div>

                <div class="py-6">
                    <p class="text-xs text-gray-200 uppercase tracking-wider mb-4 print:text-zinc-500">Charge Breakdown</p>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <p class="text-gray-200 print:text-zinc-700">Usage Charge ({{ $bill->consumption }} m³)</p>
                            <p class="text-gray-200 font-medium print:text-black">₱{{ number_format($bill->usage_charge, 0) }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-gray-200">Base Charge</p>
                            <p class="text-gray-200 font-medium">₱{{ number_format($bill->base_charge, 0) }}</p>
                        </div>
                        @if($bill->applied_additional_charges && count($bill->applied_additional_charges) > 0)
                            @foreach($bill->applied_additional_charges as $gCharge)
                            <div class="flex justify-between items-center bg-blue-500/5 -mx-4 px-4 py-2 rounded-lg border border-blue-500/10">
                                <div>
                                    <p class="text-blue-400 font-medium text-sm italic">Additional Charge: {{ $gCharge['name'] }}</p>
                                </div>
                                <p class="text-blue-400 font-bold">+ ₱{{ number_format($gCharge['amount'], 0) }}</p>
                            </div>
                            @endforeach
                        @endif

                        @if($bill->additional_charge_amount > 0)
                        <div class="flex justify-between items-center bg-blue-500/5 -mx-4 px-4 py-2 rounded-lg border border-blue-500/10">
                            <div>
                                <p class="text-blue-400 font-medium text-sm italic">Additional Charge: {{ $bill->additional_charge_note ?? 'Manual' }}</p>
                            </div>
                            <p class="text-blue-400 font-bold">+ ₱{{ number_format($bill->additional_charge_amount, 0) }}</p>
                        </div>
                        @endif

                        @php
                            $isPaid = strtolower($bill->status) === 'paid';
                        @endphp

                        @if(!$isPaid)
                            {{-- Arrears: displayed for pending/unpaid bills even if zero --}}
                            @php
                                $otherUnpaidBills = $bill->customer_id ? \App\Models\Bill::where('customer_id', $bill->customer_id)
                                    ->where('id', '!=', $bill->id)
                                    ->whereNotIn('status', ['Paid', 'paid'])
                                    ->orderBy('billing_date', 'asc')
                                    ->get() : collect();
                                $otherUnpaidCount = $otherUnpaidBills->count();
                            @endphp
                            @if($otherUnpaidCount > 0)
                                <div class="pt-2 border-t border-[#263548]/60 print:border-zinc-200">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <div>
                                            <p class="text-gray-200 print:text-zinc-700 font-medium">Unpaid Bill</p>
                                            <p class="text-[10px] text-gray-400 print:text-zinc-500">Unpaid previous bills ({{ $otherUnpaidCount }} {{ Str::plural('bill', $otherUnpaidCount) }})</p>
                                        </div>
                                        <p class="font-bold text-amber-400 print:text-amber-700">₱{{ number_format($bill->arrears, 0) }}</p>
                                    </div>
                                    <div class="space-y-1.5 pl-3 py-1.5 border-l-2 border-amber-500/40 print:border-amber-600/40 bg-amber-500/5 print:bg-zinc-50 rounded-r-lg">
                                        @foreach($otherUnpaidBills as $unpaid)
                                            <div class="flex justify-between items-center text-xs">
                                                <span class="text-gray-300 print:text-zinc-700 flex items-center gap-1.5">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400 print:bg-amber-600 shrink-0"></span>
                                                    <span class="font-medium">{{ $unpaid->billing_date ? $unpaid->billing_date->format('F Y') : 'Prior Bill' }}</span>
                                                </span>
                                                <span class="font-bold text-amber-400 print:text-amber-800">₱{{ number_format($unpaid->total_amount, 0) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="flex justify-between items-center pt-2 border-t border-[#263548]/60 print:border-zinc-200">
                                    <div>
                                        <p class="text-gray-200 print:text-zinc-700 font-medium">Unpaid Bill</p>
                                        <p class="text-[10px] text-gray-400 print:text-zinc-500">No previous unpaid balance</p>
                                    </div>
                                    <p class="font-bold text-gray-200 print:text-black">₱0</p>
                                </div>
                            @endif
                        @endif
                    </div>

                    @php
                        $disconnectionThreshold = (int) \App\Models\SystemSetting::get('disconnection_unpaid_months', 4);
                        $unpaidMonths = $bill->customer ? $bill->customer->unpaid_bills_count : 0;
                    @endphp
                    @if($unpaidMonths >= $disconnectionThreshold && !$isPaid)
                        <div class="mt-4 p-3 rounded-lg bg-rose-500/10 border border-rose-500/30 text-rose-400 print:border-red-600 print:text-red-700 text-xs">
                            <strong class="font-bold">NOTICE OF POSSIBLE DISCONNECTION:</strong> This account has {{ $unpaidMonths }} unpaid monthly bill(s) (policy threshold: {{ $disconnectionThreshold }} months). Please settle your previous bills immediately to prevent disconnection of water services.
                        </div>
                    @endif
                </div>

                <div class="bg-[#0f1722] -mx-8 -mb-8 p-8 border-t border-[#263548] print:bg-white print:border-zinc-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-lg text-gray-200 font-semibold uppercase tracking-wider print:text-zinc-700">{{ $isPaid ? 'Total Paid' : 'Total Amount Due' }}</p>
                            @if(!$isPaid && $bill->arrears > 0)
                                <p class="text-xs text-gray-400 print:text-zinc-500 font-medium">(Current: ₱{{ number_format($bill->total_amount, 0) }} + Unpaid Bill: ₱{{ number_format($bill->arrears, 0) }})</p>
                            @endif
                        </div>
                        <p class="text-2xl {{ $isPaid ? 'text-green-600' : 'text-blue-500' }} font-bold tracking-tight print:text-black">₱{{ number_format($isPaid ? $bill->total_amount : ($bill->total_amount + $bill->arrears), 0) }}</p>
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

            <a href="{{ url()->previous() }}" class="text-gray-200 hover:text-gray-300 text-sm font-medium">
                &larr; Back to Previous Page
            </a>
        </div>
    </div>
</x-layouts::app>


