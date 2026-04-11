<x-layouts::app title="Customers">
    <div class="px-6 py-4 bg-transparent min-h-[calc(100vh-4rem)] font-sans text-gray-200 relative z-10">

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-100 flex items-center gap-2 drop-shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 text-cyan-400 drop-shadow-[0_0_8px_rgba(6,182,212,0.8)]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Customers
                </h1>

                <form action="{{ route('customers.index') }}" method="GET" class="ml-4 w-full md:w-64">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-focus-within:text-cyan-400 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search person..."
                            class="w-full bg-[#1b2636]/40 backdrop-blur-md border border-[#2d4059]/50 focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/30 text-gray-200 text-sm rounded-xl py-2 pl-10 pr-10 outline-none transition-all duration-300"
                            autofocus>
                        @if (request('search'))
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <a href="{{ route('customers.index') }}" class="text-gray-500 hover:text-rose-400 transition-colors duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <a href="{{ route('customers.create') }}"
                class="bg-cyan-600/80 hover:bg-cyan-500 border border-cyan-400/50 text-white px-4 py-2 rounded-xl font-medium shadow-[0_0_15px_rgba(6,182,212,0.3)] flex items-center gap-2 transition duration-300 backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Register New Customer
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Stats -->
            <div class="col-span-1 flex flex-col gap-4">
                <div
                    class="bg-[#1b2636]/40 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059]/50 p-6 flex flex-col justify-center text-center items-center h-full relative overflow-hidden group hover:border-cyan-500/50 transition-all">
                    <div
                        class="absolute -right-4 -bottom-4 bg-gradient-to-br from-cyan-600/20 to-blue-900/20 h-32 w-32 rounded-full blur-2xl">
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-gray-400 text-sm font-medium uppercase tracking-wider mb-2">Total Active
                            Customers</h3>
                        <div
                            class="text-5xl font-bold text-cyan-400 flex items-center justify-center gap-3 drop-shadow-[0_0_8px_rgba(6,182,212,0.5)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-cyan-500/50" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            {{ $activeCustomers }} <span class="text-sm font-normal text-gray-500">/
                                {{ $totalCustomers }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="col-span-1 lg:col-span-2">
                <div
                    class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-hidden border border-[#263548] p-4 w-full h-full relative">
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-cyan-600/10 rounded-full blur-3xl point-events-none">
                    </div>
                    <h3
                        class="text-gray-200 font-semibold text-base mb-4 border-b border-[#263548] pb-2 flex items-center gap-2 relative z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Customer Growth (Flow Trend)
                    </h3>
                    <div style="position: relative; height: 180px; z-index: 10;">
                        <canvas id="customerChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Customers Table -->
        <h2 class="text-lg font-semibold flex items-center gap-2 text-gray-200 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Customer Directory
        </h2>

        <div
            class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-x-auto border border-[#263548] scrollbar-thin scrollbar-thumb-cyan-500/30 scrollbar-track-transparent">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-[#0f1722] text-[#94a3b8] uppercase text-xs tracking-wider">
                        <th class="px-6 py-4 font-semibold border-b border-[#263548]">Customer ID</th>
                        <th class="px-6 py-4 font-semibold border-b border-[#263548]">Name</th>
                        <th class="px-6 py-4 font-semibold border-b border-[#263548]">Type</th>
                        <th class="px-6 py-4 font-semibold hidden md:table-cell border-b border-[#263548]">Address</th>
                        <th class="px-6 py-4 font-semibold border-b border-[#263548]">Total Usage</th>
                        <th class="px-6 py-4 font-semibold text-right border-b border-[#263548]">Manage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#263548]">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-[#1b2636]/60 transition duration-300">
                            <td class="px-6 py-4 font-semibold text-gray-300">{{ $customer->customer_id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-200">{{ $customer->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold shadow-sm {{ $customer->type === 'Regular' ? 'bg-cyan-900/40 text-cyan-300 border border-cyan-700/50' : 'bg-orange-900/40 text-orange-300 border border-orange-700/50' }}">
                                    {{ $customer->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400 hidden md:table-cell truncate max-w-[200px]"
                                title="{{ $customer->address }}">{{ $customer->address }}</td>
                            <td class="px-6 py-4 font-bold text-cyan-400">
                                {{ number_format($customer->bills->sum('consumption'), 2) }} L
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('customers.show', $customer) }}"
                                        class="p-2 text-cyan-400 bg-cyan-900/20 hover:bg-cyan-600/30 rounded-lg transition duration-300 border border-cyan-700/30 shadow-sm"
                                        title="View Customer Profile">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <button type="button" 
                                        onclick="openQuickBillModal('{{ $customer->id }}', '{{ $customer->name }}', '{{ $customer->customer_id }}', '{{ $customer->type }}', {{ $customer->meter_reading ?? 0 }})"
                                        class="p-2 text-emerald-400 bg-emerald-900/20 hover:bg-emerald-600/30 rounded-lg transition duration-300 border border-emerald-700/30 shadow-sm"
                                        title="Quick Add Reading">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-rose-400 bg-rose-900/20 hover:bg-rose-600/30 rounded-lg transition duration-300 border border-rose-700/30 shadow-sm"
                                            title="Delete Customer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-[#263548] mb-3"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                @if(request('search'))
                                    <p class="text-lg font-medium text-gray-300">No results found for "{{ request('search') }}"
                                    </p>
                                    <p class="text-sm mt-1 text-gray-500">Try adjusting your search terms or ID.</p>
                                    <a href="{{ route('customers.index') }}"
                                        class="inline-block mt-4 text-cyan-400 hover:text-cyan-300 transition text-sm font-medium">Clear
                                        search</a>
                                @else
                                    <p class="text-lg font-medium text-gray-300">No customers found</p>
                                    <p class="text-sm mt-1 text-gray-500">Start by registering your first user to the water
                                        system.</p>
                                    <a href="{{ route('customers.create') }}"
                                        class="inline-block mt-4 bg-cyan-600/80 border border-cyan-400/50 text-white px-5 py-2 rounded-xl text-sm hover:bg-cyan-500 transition shadow-[0_0_15px_rgba(6,182,212,0.3)] backdrop-blur-sm">Register
                                        Customer</a>
                                @endif
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

    <!-- Quick Bill Modal -->
    <flux:modal name="quick-bill-modal" class="md:w-[500px]">
        <div class="p-4">
            <flux:heading size="lg" class="mb-2">Quick Add Reading</flux:heading>
            <flux:subheading id="modal-customer-name" class="mb-6">Customer Name</flux:subheading>

            <form action="{{ route('billing.store') }}" method="POST" id="quick-bill-form">
                @csrf
                <input type="hidden" name="customer_id" id="modal_customer_id">
                <input type="hidden" name="billing_date" value="{{ now()->format('Y-m-d') }}">
                <input type="hidden" name="due_date" value="{{ now()->addDays(30)->format('Y-m-d') }}">
                <input type="hidden" name="consumption" id="modal_consumption_hidden">

                <div class="space-y-6">
                    <div class="bg-[#1b2636]/40 p-4 rounded-xl border border-[#2d4059]/50">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm font-medium text-gray-400">Previous Reading:</span>
                            <span id="modal_prev_reading" class="font-mono font-bold text-gray-200 text-lg">0.00</span>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="modal_present_reading" class="block text-sm font-medium text-gray-300">Present Reading (L)</label>
                            <input type="number" step="0.01" id="modal_present_reading" name="usage_units" required
                                oninput="calculateQuickCharges()" placeholder="Enter reading..."
                                class="w-full bg-[#0f1722]/60 border border-[#2d4059] focus:border-emerald-500/50 text-emerald-400 text-2xl font-black rounded-xl py-3 px-4 outline-none transition-all duration-300">
                        </div>

                        <div id="modal_calc_breakdown" class="text-xs mt-3 min-h-[1.25rem] text-zinc-500"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label>Base Charge</flux:label>
                            <flux:input name="base_charge" id="modal_base_charge" type="number" step="0.01" oninput="updateQuickTotal()" class="text-gray-200" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Usage Charge</flux:label>
                            <flux:input name="usage_charge" id="modal_usage_charge" type="number" step="0.01" oninput="updateQuickTotal()" class="text-gray-200" />
                        </flux:field>
                    </div>

                    <div class="bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/30 flex justify-between items-center px-6">
                        <span class="text-emerald-500 font-bold uppercase tracking-widest text-sm">Total Bill</span>
                        <div class="text-right">
                            <span class="text-emerald-400 font-bold text-3xl">₱<span id="modal_total_display">0.00</span></span>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <flux:button type="submit" variant="primary" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 transition-all font-bold">Generate Bill</flux:button>
                        <flux:modal.close>
                            <flux:button variant="ghost" class="px-6 border border-[#2d4059] text-gray-400">Cancel</flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            </form>
        </div>
    </flux:modal>
    <script>
        let quickCustomerType = 'Regular';
        let quickPrevReading = 0;

        function openQuickBillModal(id, name, customerId, type, prevReading) {
            document.getElementById('modal_customer_id').value = id;
            document.getElementById('modal-customer-name').textContent = `${name} (${customerId}) - ${type}`;
            document.getElementById('modal_prev_reading').textContent = parseFloat(prevReading).toLocaleString(undefined, { minimumFractionDigits: 2 });
            document.getElementById('modal_present_reading').value = '';
            document.getElementById('modal_total_display').textContent = '0.00';
            document.getElementById('modal_calc_breakdown').textContent = '';
            document.getElementById('modal_base_charge').value = 0;
            document.getElementById('modal_usage_charge').value = 0;
            
            quickCustomerType = type;
            quickPrevReading = parseFloat(prevReading);
            
            window.Flux.modal('quick-bill-modal').show();
            
            // Focus input after modal is shown
            setTimeout(() => {
                document.getElementById('modal_present_reading').focus();
            }, 100);
        }

        function calculateQuickCharges() {
            const input = document.getElementById('modal_present_reading');
            const baseInput = document.getElementById('modal_base_charge');
            const usageInput = document.getElementById('modal_usage_charge');
            const totalDisplay = document.getElementById('modal_total_display');
            const breakdown = document.getElementById('modal_calc_breakdown');
            const hiddenConsumption = document.getElementById('modal_consumption_hidden');

            const presentReading = parseFloat(input.value) || 0;
            const consumption = Math.abs(quickPrevReading - presentReading);
            
            hiddenConsumption.value = consumption.toFixed(2);

            let baseCharge = quickCustomerType === 'Commercial' ? 250 : 100;
            let rate = quickCustomerType === 'Commercial' ? 25 : 15;

            const billableUsage = Math.max(consumption - 10, 0);
            const usageCharge = billableUsage * rate;
            const total = baseCharge + usageCharge;

            baseInput.value = baseCharge.toFixed(2);
            usageInput.value = usageCharge.toFixed(2);
            
            updateQuickTotal();

            breakdown.textContent = `Consumption: ${consumption.toFixed(2)}L | (${consumption.toFixed(2)} - 10) × ₱${rate} = ₱${usageCharge.toFixed(2)}`;
            
            // Color coding breakdown based on total consumption
            breakdown.className = 'text-xs mt-1 transition-colors duration-300';
            if (consumption > 0 && consumption <= 20) breakdown.classList.add('text-emerald-400');
            else if (consumption > 20 && consumption <= 30) breakdown.classList.add('text-orange-400');
            else if (consumption > 30) breakdown.classList.add('text-rose-400');
            else breakdown.classList.add('text-zinc-500');
        }

        function updateQuickTotal() {
            const base = parseFloat(document.getElementById('modal_base_charge').value) || 0;
            const usage = parseFloat(document.getElementById('modal_usage_charge').value) || 0;
            const total = base + usage;
            document.getElementById('modal_total_display').textContent = total.toLocaleString(undefined, { minimumFractionDigits: 2 });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const customerCtx = document.getElementById('customerChart');
            if (customerCtx) {
                // Gradient
                const custGradient = customerCtx.getContext('2d').createLinearGradient(0, 0, 0, 200);
                custGradient.addColorStop(0, 'rgba(6, 182, 212, 0.4)');
                custGradient.addColorStop(1, 'rgba(6, 182, 212, 0.0)');

                new Chart(customerCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($customerGrowth->pluck('month')->count() ? $customerGrowth->pluck('month') : ['Current']) !!},
                        datasets: [{
                            label: 'New Customers',
                            data: {!! json_encode($customerGrowth->pluck('count')->count() ? $customerGrowth->pluck('count') : [1]) !!},
                            borderColor: '#06b6d4',
                            backgroundColor: custGradient,
                            borderWidth: 3,
                            pointBackgroundColor: '#06b6d4',
                            pointBorderColor: '#164e63',
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
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(15, 23, 34, 0.95)',
                                titleColor: '#e2e8f0',
                                bodyColor: '#cbd5e1',
                                borderColor: '#1e293b',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, color: '#64748b' },
                                grid: { color: '#1e293b', drawBorder: false },
                                border: { display: false }
                            },
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: { color: '#64748b' }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-layouts::app>