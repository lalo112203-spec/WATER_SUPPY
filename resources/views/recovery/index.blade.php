<x-layouts::app title="Recovery / Trash">
    <div class="px-6 py-4 bg-transparent min-h-screen font-sans text-gray-300">
        <h1 class="text-2xl font-bold mb-6 text-gray-200">Recovery / Trash</h1>

        @if(session('success'))
            <div class="py-3 mb-4 text-[#5cb85c] font-bold text-lg drop-shadow-md" role="alert">
                <span class="block sm:inline">✓ {{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col xl:flex-row gap-8">
            <!-- Deleted Customers -->
            <div class="flex-1">
                <h2 class="text-lg font-semibold mb-3 text-red-600">Deleted Customers</h2>
                <div class="bg-[#121a25]/80 backdrop-blur-md rounded shadow-sm overflow-hidden border border-[#263548]">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-red-500 text-white">
                                <th class="px-4 py-3 font-medium">No.</th>
                                <th class="px-4 py-3 font-medium">Name</th>
                                <th class="px-4 py-3 font-medium">Deleted Date</th>
                                <th class="px-4 py-3 font-medium text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($deletedCustomers as $customer)
                            <tr class="hover:bg-[#0f1722]">
                                <td class="px-4 py-3">{{ $customer->customer_id ?? $customer->id }}</td>
                                <td class="px-4 py-3">{{ $customer->name }}</td>
                                <td class="px-4 py-3">{{ $customer->deleted_at->format('M d, Y h:i A') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('recovery.restoreCustomer', $customer->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-[#5cb85c] hover:bg-[#4cae4c] text-white px-3 py-1 rounded text-sm font-medium">
                                                Restore
                                            </button>
                                        </form>
                                        <form action="{{ route('recovery.forceDeleteCustomer', $customer->id) }}" method="POST" class="inline" onsubmit="return confirm('WARNING: This will permanently delete this customer and all their data. This cannot be undone. Proceed?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-200">No deleted customers in trash</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Deleted Bills -->
            <div class="flex-1">
                <h2 class="text-lg font-semibold mb-3 text-red-600">Deleted Bills</h2>
                <div class="bg-[#121a25]/80 backdrop-blur-md rounded shadow-sm overflow-hidden border border-[#263548]">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-red-500 text-white">
                                <th class="px-4 py-3 font-medium">Customer</th>
                                <th class="px-4 py-3 font-medium">Period</th>
                                <th class="px-4 py-3 font-medium">Amount</th>
                                <th class="px-4 py-3 font-medium text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($deletedBills as $bill)
                            <tr class="hover:bg-[#0f1722]">
                                <td class="px-4 py-3">{{ $bill->customer->name ?? 'Unknown' }}</td>
                                <td class="px-4 py-3">{{ $bill->billing_date->format('M Y') }}</td>
                                <td class="px-4 py-3">₱{{ number_format($bill->total_amount, 0) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('recovery.restoreBill', $bill->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-[#5cb85c] hover:bg-[#4cae4c] text-white px-3 py-1 rounded text-sm font-medium">
                                                Restore
                                            </button>
                                        </form>
                                        <form action="{{ route('recovery.forceDeleteBill', $bill->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this bill?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-200">No deleted bills in trash</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-layouts::app>

