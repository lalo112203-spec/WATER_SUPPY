<x-layouts::app title="Customers">
    <div class="px-6 py-4 bg-[#f8f9fa] min-h-screen font-sans text-gray-700">
        
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#3498db]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Customers Management
            </h1>
            <a href="{{ route('customers.create') }}" class="bg-[#5cb85c] hover:bg-[#4cae4c] text-white px-4 py-2 rounded font-medium shadow-sm flex items-center gap-2 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Register New Customer
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Stats -->
            <div class="col-span-1 flex flex-col gap-4">
                <div class="bg-white rounded shadow-sm border border-gray-200 p-6 flex flex-col justify-center text-center items-center h-full">
                    <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider mb-2">Total Active Customers</h3>
                    <div class="text-5xl font-bold text-[#3498db] flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#3498db] opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        {{ $activeCustomers }} <span class="text-sm font-normal text-gray-400">/ {{ $totalCustomers }}</span>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="col-span-1 lg:col-span-2">
                <div class="bg-white rounded shadow-sm border border-gray-200 p-4 w-full h-full">
                    <h3 class="text-[#337ab7] font-semibold text-base mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Customer Growth (Flow Trend)
                    </h3>
                    <div style="position: relative; height: 180px;">
                        <canvas id="customerChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Customers Table -->
        <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Customer Directory
        </h2>
        
        <div class="bg-white rounded shadow-sm overflow-hidden border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#42a5f5] text-white">
                        <th class="px-4 py-3 font-medium">Customer ID</th>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Type</th>
                        <th class="px-4 py-3 font-medium hidden md:table-cell">Address</th>
                        <th class="px-4 py-3 font-medium text-right">Manage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-4 py-3 font-semibold text-gray-700">#{{ $customer->customer_id }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-800">{{ $customer->name }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $customer->type === 'Regular' ? 'bg-[#d9edf7] text-[#31708f]' : 'bg-[#fff3cd] text-[#856404]' }}">
                                {{ $customer->type }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden md:table-cell truncate max-w-[200px]" title="{{ $customer->address }}">{{ $customer->address }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('customers.show', $customer) }}" class="p-1 text-[#337ab7] hover:bg-[#ebf0f5] rounded transition duration-150" title="View Customer Profile">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('billing.create', ['customer_id' => $customer->id]) }}" class="p-1 text-[#5cb85c] hover:bg-[#ebf0f5] rounded transition duration-150" title="Add Water Usage / Bill">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-[#d9534f] hover:bg-red-50 rounded transition duration-150" title="Delete Customer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="text-lg font-medium">No customers found</p>
                            <p class="text-sm mt-1">Start by registering your first user to the water system.</p>
                            <a href="{{ route('customers.create') }}" class="inline-block mt-4 bg-[#42a5f5] text-white px-4 py-2 rounded text-sm hover:bg-blue-500 transition">Register Customer</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- Chart.js Setup for Customer Flow Trend -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const customerCtx = document.getElementById('customerChart');
            if (customerCtx) {
                new Chart(customerCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($customerGrowth->pluck('month')->count() ? $customerGrowth->pluck('month') : ['Current']) !!},
                        datasets: [{
                            label: 'New Customers',
                            data: {!! json_encode($customerGrowth->pluck('count')->count() ? $customerGrowth->pluck('count') : [1]) !!},
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.2)',
                            borderWidth: 3,
                            pointBackgroundColor: '#2980b9',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            fill: true,
                            tension: 0.4 // Smooth "flow" curve
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                ticks: { stepSize: 1 },
                                grid: { color: '#f0f0f0' } 
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
</x-layouts::app>
