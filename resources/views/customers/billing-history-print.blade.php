<x-layouts::app title="Complete Billing History - {{ $customer->name }}">
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 8mm 10mm;
            }

            html, body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                color: black !important;
                font-size: 10px !important;
            }

            /* Hide non-printable elements */
            nav, header, footer, .sidebar, .no-print, button, a,
            [data-flux-sidebar], [data-flux-navbar], [data-flux-main] > *:not(.max-w-5xl) {
                display: none !important;
            }

            body > *, [data-flux-main] {
                padding: 0 !important;
                margin: 0 !important;
                background: white !important;
            }

            .max-w-5xl {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 auto !important;
                padding: 0 !important;
            }

            .printable-history {
                width: 100% !important;
                margin: 0 !important;
                padding: 12px 16px !important;
                border: 1px solid #111 !important;
                border-top: 5px solid #0284c7 !important;
                border-radius: 2px !important;
                box-shadow: none !important;
                background: white !important;
            }

            h1, h2, h3, h4, p, span, div, td, th {
                color: black !important;
                text-shadow: none !important;
                background: transparent !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            th, td {
                border-bottom: 1px solid #ccc !important;
                padding: 4px 6px !important;
            }

            th {
                background: #f1f5f9 !important;
                font-weight: 700 !important;
            }

            .bg-emerald-500\/10, .bg-emerald-50 { background: #ecfdf5 !important; }
            .bg-rose-500\/10, .bg-rose-50 { background: #fff1f2 !important; }
            .bg-amber-500\/10, .bg-amber-50 { background: #fffbeb !important; }
            .border { border-color: #cbd5e1 !important; }
        }
    </style>

    <div class="max-w-5xl mx-auto py-6 px-4">
        <!-- Action Toolbar (Hidden in Print) -->
        <div class="mb-5 flex justify-between items-center no-print">
            <a href="javascript:history.back()" class="flex items-center gap-2 px-4 py-2 bg-[#1b2636] hover:bg-[#263548] text-gray-300 rounded-xl text-sm font-semibold transition-all border border-[#2d4059] shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>

            <div class="flex items-center gap-3">
                <button type="button" onclick="window.print()" class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-cyan-900/30 transition-all border border-cyan-400/40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Complete History
                </button>
            </div>
        </div>

        <!-- Printable Document Container -->
        <div class="printable-history bg-[#121a25]/90 border border-[#263548] rounded-3xl p-8 shadow-2xl backdrop-blur-md">
            
            <!-- Document Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-[#263548] gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-600 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-cyan-900/40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-white tracking-tight">RURAL WATER SUPPLY & SANITATION SYSTEM</h1>
                        <p class="text-xs text-gray-400 font-medium">Consumer Complete Billing & Payment Ledger</p>
                    </div>
                </div>

                <div class="text-left sm:text-right">
                    <span class="text-[11px] uppercase tracking-wider text-cyan-400 font-bold block">Document Type</span>
                    <span class="text-base font-black text-white">OFFICIAL BILLING STATEMENT HISTORY</span>
                    <p class="text-xs text-gray-400 mt-1">Generated: {{ now()->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            <!-- Consumer Profile Card -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-6 border-b border-[#263548] text-xs">
                <div class="bg-[#0f1722] p-3.5 rounded-xl border border-[#263548]">
                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold block mb-1">Consumer Name</span>
                    <span class="text-sm font-bold text-white block">{{ $customer->name }}</span>
                    <span class="text-[11px] text-gray-400">Account No: <strong class="text-cyan-400 font-mono">{{ $customer->customer_id }}</strong></span>
                </div>

                <div class="bg-[#0f1722] p-3.5 rounded-xl border border-[#263548]">
                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold block mb-1">Service Address</span>
                    <span class="text-xs font-semibold text-gray-200 block">{{ $customer->address ?? 'N/A' }}</span>
                    <span class="text-[11px] text-gray-400">Barangay: {{ $customer->barangay ?? 'N/A' }}</span>
                </div>

                <div class="bg-[#0f1722] p-3.5 rounded-xl border border-[#263548]">
                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold block mb-1">Meter Details</span>
                    <span class="text-xs text-gray-200 block">Meter Post: <strong class="text-white">{{ $customer->meter_post ?? 'N/A' }}</strong></span>
                    <span class="text-xs text-gray-200 block">Type: <strong class="text-cyan-400">{{ $customer->type }}</strong></span>
                    <span class="text-xs text-gray-200 block">Current Reading: <strong class="text-emerald-400 font-mono">{{ number_format($customer->meter_reading ?? 0, 0) }} m³</strong></span>
                </div>

                <div class="bg-[#0f1722] p-3.5 rounded-xl border border-[#263548]">
                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold block mb-1">Account Standing</span>
                    @if($customer->isEligibleForDisconnection())
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                            Disconnection Risk ({{ $customer->unpaid_bills_count }} Unpaid)
                        </span>
                    @elseif($customer->unpaid_bills_count > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            {{ $customer->unpaid_bills_count }} Overdue Bill(s)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            Account Up to Date
                        </span>
                    @endif
                    <p class="text-[10px] text-gray-400 mt-1">Disconnection Policy: {{ $disconnectionThreshold }} unpaid months</p>
                </div>
            </div>

            <!-- Complete Billing Ledger Table -->
            <div class="py-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-bold text-gray-300 uppercase tracking-widest flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Complete Chronological Billing Records ({{ $customer->bills->count() }} Statements)
                    </h3>
                    <span class="text-[11px] text-gray-400">Currency: Philippine Peso (₱)</span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-[#263548]">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-[#0f1722] text-gray-400 uppercase text-[10px] tracking-wider border-b border-[#263548]">
                                <th class="px-3 py-2.5 font-bold">#</th>
                                <th class="px-3 py-2.5 font-bold">Billing Date</th>
                                <th class="px-3 py-2.5 font-bold">Period / Coverage</th>
                                <th class="px-3 py-2.5 font-bold text-right">Prev (m³)</th>
                                <th class="px-3 py-2.5 font-bold text-right">Present (m³)</th>
                                <th class="px-3 py-2.5 font-bold text-right">Usage (m³)</th>
                                <th class="px-3 py-2.5 font-bold text-right">Amount (₱)</th>
                                <th class="px-3 py-2.5 font-bold text-center">Status</th>
                                <th class="px-3 py-2.5 font-bold text-center">OR Number</th>
                                <th class="px-3 py-2.5 font-bold text-center">Payment Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#263548]/40">
                            @forelse($customer->bills as $idx => $bill)
                                <tr class="hover:bg-[#1b2636]/30 transition-colors {{ $bill->status === 'Paid' ? '' : 'bg-rose-500/5' }}">
                                    <td class="px-3 py-2.5 text-gray-400 font-mono text-[11px]">{{ $idx + 1 }}</td>
                                    <td class="px-3 py-2.5 font-semibold text-white whitespace-nowrap">{{ $bill->billing_date->format('M d, Y') }}</td>
                                    <td class="px-3 py-2.5 text-gray-300 whitespace-nowrap">{{ $bill->period ?? $bill->billing_date->format('F Y') }}</td>
                                    <td class="px-3 py-2.5 text-right font-mono text-gray-300">{{ number_format($bill->previous_reading ?? 0, 0) }}</td>
                                    <td class="px-3 py-2.5 text-right font-mono text-gray-200">{{ number_format($bill->usage_units ?? 0, 0) }}</td>
                                    <td class="px-3 py-2.5 text-right font-mono font-bold text-cyan-400">{{ number_format($bill->consumption ?? 0, 0) }}</td>
                                    <td class="px-3 py-2.5 text-right font-mono font-bold {{ $bill->status === 'Paid' ? 'text-emerald-400' : 'text-rose-400' }}">
                                        ₱{{ number_format($bill->total_amount, 0) }}
                                    </td>
                                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                        @if(strtolower($bill->status) === 'paid')
                                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 uppercase">Paid</span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30 uppercase">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2.5 text-center font-mono text-[11px] font-bold text-gray-200 whitespace-nowrap">
                                        {{ $bill->or_number ?: '—' }}
                                    </td>
                                    <td class="px-3 py-2.5 text-center text-gray-400 text-[11px] whitespace-nowrap">
                                        {{ $bill->payment_date ? \Carbon\Carbon::parse($bill->payment_date)->format('M d, Y') : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-8 text-center text-gray-400 italic">No billing records found for this consumer.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ledger Summary & Balance -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-[#263548]">
                <div class="bg-[#0f1722] p-4 rounded-2xl border border-[#263548]">
                    <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold block mb-1">Total Lifetime Billed</span>
                    <span class="text-xl font-black text-white font-mono">₱{{ number_format($totalBilled, 0) }}</span>
                    <p class="text-[11px] text-gray-400 mt-1">{{ $customer->bills->count() }} Total Statements Issued</p>
                </div>

                <div class="bg-[#0f1722] p-4 rounded-2xl border border-[#263548]">
                    <span class="text-[10px] uppercase tracking-wider text-emerald-400 font-bold block mb-1">Total Settled / Paid</span>
                    <span class="text-xl font-black text-emerald-400 font-mono">₱{{ number_format($totalPaid, 0) }}</span>
                    <p class="text-[11px] text-gray-400 mt-1">{{ $customer->bills->where('status', 'Paid')->count() }} Paid Bills</p>
                </div>

                <div class="bg-[#0f1722] p-4 rounded-2xl border {{ $totalUnpaid > 0 ? 'border-rose-500/30 bg-rose-500/5' : 'border-[#263548]' }}">
                    <span class="text-[10px] uppercase tracking-wider {{ $totalUnpaid > 0 ? 'text-rose-400 font-bold' : 'text-gray-400 font-bold' }} block mb-1">
                        Current Outstanding Balance (Arrears)
                    </span>
                    <span class="text-xl font-black font-mono {{ $totalUnpaid > 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                        ₱{{ number_format($totalUnpaid, 0) }}
                    </span>
                    <p class="text-[11px] text-gray-400 mt-1">
                        {{ $customer->bills->where('status', '!=', 'Paid')->count() }} Unpaid Bill(s)
                    </p>
                </div>
            </div>

            <!-- Disconnection Policy Notice Notice Section -->
            @if($customer->isEligibleForDisconnection())
                <div class="mt-6 p-4 rounded-2xl bg-rose-500/10 border-2 border-dashed border-rose-500/40 text-xs">
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <strong class="text-rose-300 font-bold text-sm block">DISCONNECTION NOTICE APPLIED TO THIS ACCOUNT</strong>
                            <p class="text-gray-300 mt-1 leading-relaxed">
                                This account has accumulated <strong>{{ $customer->unpaid_bills_count }} unpaid billing cycles</strong> with a total arrears of <strong class="text-rose-300 font-mono">₱{{ number_format($customer->unpaid_bills_total, 0) }}</strong>. Under local water utility regulations, accounts with <strong>{{ $disconnectionThreshold }} or more consecutive unpaid months</strong> are subject to disconnection of water services without further notice. Please settle immediately.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Certification & Signatures -->
            <div class="grid grid-cols-2 gap-8 pt-10 mt-8 border-t border-[#263548] text-xs">
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-8">Prepared By (Billing / Meter Section):</p>
                    <div class="border-b border-gray-400 w-64 pb-1">
                        <span class="font-bold text-white text-sm">{{ auth()->user()->name ?? 'System Officer' }}</span>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 block">Rural Water District Representative</span>
                </div>

                <div class="text-right flex flex-col items-end">
                    <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-8">Certified & Verified By:</p>
                    <div class="border-b border-gray-400 w-64 pb-1 text-center">
                        <span class="font-bold text-white text-sm">System Administrator</span>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 block">Head of Operations / Revenue</span>
                </div>
            </div>

            <!-- Footer Meta -->
            <div class="mt-8 pt-4 border-t border-[#263548]/40 text-center text-[10px] text-gray-400">
                This document is an official transcript of water utility billing transactions. System Generated on {{ now()->format('F d, Y h:i A') }}.
            </div>
        </div>
    </div>
</x-layouts::app>
