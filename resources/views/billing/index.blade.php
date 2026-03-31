<x-layouts::app title="Billing Reports">
    <div class="px-6 py-4 bg-transparent min-h-screen font-sans text-gray-300">
        
        <h1 class="text-3xl font-bold mb-6 text-gray-200">Billing Reports</h1>
        
        <div class="mb-6">
            <input type="text" placeholder="Search by Consumer No. or Name" class="w-full max-w-lg px-4 py-2 border border-[#263548] rounded focus:outline-none focus:border-blue-400">
        </div>

        <h2 class="text-lg font-semibold mb-3 text-gray-200">Pending Bills</h2>
        
        <div class="bg-[#121a25]/80 backdrop-blur-md rounded-2xl shadow-sm overflow-x-auto mb-4 border border-[#263548] scrollbar-thin scrollbar-thumb-blue-500/30 scrollbar-track-transparent">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-blue-600/90 text-white">
                        <th class="px-4 py-3 font-medium">Period</th>
                        <th class="px-4 py-3 font-medium">No.</th>
                        <th class="px-4 py-3 font-medium">Customer</th>
                        <th class="px-4 py-3 font-medium">Usage</th>
                        <th class="px-4 py-3 font-medium">Bill</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-100">
                    @forelse($pendingBills as $bill)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-4 py-3">{{ $bill->billing_date->format('F Y') }}</td>
                        <td class="px-4 py-3">{{ $bill->customer?->customer_number ?? $bill->customer?->id ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $bill->customer?->name ?? 'Deleted Customer' }}</td>
                        @php
                            $cType = $bill->customer?->type ?? 'Regular';
                            $greenMax = $thresholds[$cType]['green_max'] ?? 12;
                            $orangeMax = $thresholds[$cType]['orange_max'] ?? 14;
                            $bgClass = $bill->usage_units <= $greenMax ? 'bg-emerald-500' : ($bill->usage_units <= $orangeMax ? 'bg-amber-500' : 'bg-red-500');
                        @endphp
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium text-white shadow-sm {{ $bgClass }}">
                                {{ $bill->usage_units }} L
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-300">₱{{ number_format($bill->total_amount, 0) }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('billing.mark-paid', $bill) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded text-sm font-medium shadow-sm transition-transform hover:scale-105">
                                    Mark as Paid
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400 italic">No pending bills</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mb-8">
            {{ $pendingBills->links() }}
        </div>

        <h2 class="text-xl font-bold mb-3 text-gray-200">Payment History (Paid)</h2>
        
        <div class="bg-[#121a25]/80 backdrop-blur-md rounded-2xl shadow-sm overflow-x-auto mb-4 border border-[#263548] scrollbar-thin scrollbar-thumb-blue-500/30 scrollbar-track-transparent">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-blue-600/90 text-white">
                        <th class="px-4 py-3 font-medium">Period</th>
                        <th class="px-4 py-3 font-medium">No.</th>
                        <th class="px-4 py-3 font-medium">Customer</th>
                        <th class="px-4 py-3 font-medium">Usage</th>
                        <th class="px-4 py-3 font-medium">Bill</th>
                        <th class="px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-100">
                    @forelse($paidBills as $bill)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-4 py-3">{{ $bill->billing_date->format('F Y') }}</td>
                        <td class="px-4 py-3">{{ $bill->customer?->customer_number ?? $bill->customer?->id ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $bill->customer?->name ?? 'Deleted Customer' }}</td>
                        @php
                            $cType = $bill->customer?->type ?? 'Regular';
                            $greenMax = $thresholds[$cType]['green_max'] ?? 12;
                            $orangeMax = $thresholds[$cType]['orange_max'] ?? 14;
                            $bgClass = $bill->usage_units <= $greenMax ? 'bg-emerald-500' : ($bill->usage_units <= $orangeMax ? 'bg-amber-500' : 'bg-red-500');
                        @endphp
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium text-white shadow-sm {{ $bgClass }}">
                                {{ $bill->usage_units }} L
                            </span>
                        </td>
                        <td class="px-4 py-3 font-semibold text-gray-300">₱{{ number_format($bill->total_amount, 0) }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('billing.show', $bill) }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline text-sm flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400 italic">No payment history</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>
            {{ $paidBills->links() }}
        </div>
    </div>
</x-layouts::app>

