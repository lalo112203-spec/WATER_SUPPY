<x-layouts::app title="Dashboard">
    <div class="flex flex-col py-4 sm:py-8 px-1 sm:px-6 lg:px-8 w-full font-sans min-h-[calc(100vh-4rem)]">
        
        <!-- Header -->
        <div class="mb-6 sm:mb-8 px-2 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-[24px] sm:text-[28px] font-bold text-gray-100 tracking-tight flex items-center">
                    Welcome back, <span class="text-blue-600 ml-2">{{ auth()->user()->name }}</span>
                </h1>
                <p class="mt-1 sm:mt-2 text-[14px] sm:text-[15px] text-gray-200 font-medium">View your billing history and latest announcements.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 mx-2 animate-fade-in-down flex items-center bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl shadow-sm">
                <svg class="h-6 w-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[15px] font-semibold">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 mx-2 animate-fade-in-down flex items-center bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl shadow-sm">
                <svg class="h-6 w-6 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[15px] font-semibold">{{ session('error') }}</span>
            </div>
        @endif



        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 flex-1 mb-8 mx-0 sm:mx-2 items-start">
            
            <!-- Estimate Reading Section -->
            <div class="bg-[#121a25]/80 backdrop-blur-md shadow-[0_4px_20px_rgb(0,0,0,0.03)] rounded-2xl sm:rounded-3xl overflow-hidden border border-[#263548] p-5 h-full flex flex-col">
                <h2 class="text-lg font-bold text-gray-100 tracking-tight mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Estimate Next Bill
                </h2>
                <div class="flex flex-col gap-3 flex-1 justify-center">
                    <div>
                        <label for="reading_estimate" class="block text-[13px] font-medium text-gray-300 mb-1.5">Current Meter Reading (Previous: {{ $customer->meter_reading ?? 0 }})</label>
                        <input type="number" id="reading_estimate" class="block w-full px-3 py-2 text-sm bg-[#0f1722] border border-[#263548] rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50" placeholder="Enter reading" min="{{ $customer->meter_reading ?? 0 }}" onkeypress="if(event.key === 'Enter') calculateEstimate()">
                    </div>
                    <button type="button" onclick="calculateEstimate()" class="w-full px-4 py-2 mt-1 text-sm bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 transition-all border border-blue-500">
                        View Estimate
                    </button>
                    <p class="mt-1 text-[11px] text-rose-500 hidden font-bold" id="estimate_error_text"></p>
                    <p class="mt-1 text-[11px] text-gray-400">Calculate how much your next bill will be based on your reading.</p>
                </div>
            </div>

            <!-- Usage Chart Section -->
            <div class="bg-[#0f172a]/60 backdrop-blur-md rounded-2xl sm:rounded-3xl shadow-xl overflow-hidden border border-white/20 flex flex-col p-5 relative min-h-[220px] h-full">
                <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-600/10 rounded-full blur-2xl pointer-events-none"></div>
                <h2 class="text-lg font-bold text-white tracking-tight flex items-center mb-2 z-10">
                    <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Historical Usage
                </h2>
                <div class="relative flex-1 z-10 w-full h-full min-h-[140px]">
                    <canvas id="consumerUsageChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 flex-1 mb-8 mx-0 sm:mx-2 items-start">
            <div class="bg-[#121a25]/80 backdrop-blur-md shadow-[0_4px_20px_rgb(0,0,0,0.03)] rounded-2xl sm:rounded-3xl overflow-hidden border border-[#263548]">
                <div class="p-3 sm:p-6 border-b border-[#263548] flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-100 tracking-tight flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Billing Overview
                    </h2>
                </div>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-full">
                        <thead class="bg-[#0f1722]/80 text-[10px] sm:text-[12px]">
                            <tr>
                                <th class="px-1 sm:px-6 py-3 font-bold text-gray-200 uppercase tracking-widest border-b border-[#263548]">Date</th>
                                <th class="px-1 sm:px-6 py-3 font-bold text-gray-200 uppercase tracking-widest border-b border-[#263548]">Usage</th>
                                <th class="px-1 sm:px-6 py-3 font-bold text-gray-200 uppercase tracking-widest border-b border-[#263548]">Total</th>
                                <th class="px-1 sm:px-6 py-3 font-bold text-gray-200 uppercase tracking-widest border-b border-[#263548]">Status</th>
                                <th class="px-1 sm:px-6 py-3 font-bold text-gray-200 uppercase tracking-widest border-b border-[#263548] text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @if(isset($customer) && $customer->bills->count() > 0)
                                @foreach($customer->bills as $bill)
                                    <tr class="hover:bg-blue-50/30 transition-colors">
                                        <td class="px-1 sm:px-6 py-4 whitespace-nowrap text-[11px] sm:text-[15px] font-semibold text-gray-200 tracking-tight">{{ \Carbon\Carbon::parse($bill->billing_date)->format('M d, y') }}</td>
                                        <td class="px-1 sm:px-6 py-4 whitespace-nowrap text-[11px] sm:text-[15px] font-medium text-gray-200">{{ $bill->usage_units }} <span class="text-[9px] sm:text-xs">m³</span></td>
                                        <td class="px-1 sm:px-6 py-4 whitespace-nowrap text-[11px] sm:text-[15px] font-bold text-gray-100">₱{{ number_format($bill->total_amount, 0) }}</td>
                                        <td class="px-1 sm:px-6 py-4 whitespace-nowrap">
                                            @if(strtolower($bill->status) === 'paid')
                                                <span class="px-1.5 sm:px-3 py-1 inline-flex text-[9px] sm:text-xs font-bold rounded-full bg-green-100 text-green-700 shadow-sm border border-green-200 uppercase tracking-wide">Paid</span>
                                            @else
                                                <span class="px-1.5 sm:px-3 py-1 inline-flex text-[9px] sm:text-xs font-bold rounded-full bg-red-100 text-red-700 shadow-sm border border-red-200 uppercase tracking-wide">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-1 sm:px-6 py-4 whitespace-nowrap text-right">
                                            @if(strtolower($bill->status) !== 'paid')
                                                <a href="{{ route('billing.receipt', $bill->id) }}" class="text-amber-700 font-bold text-[9px] sm:text-xs uppercase tracking-wide border border-amber-200 bg-amber-50 hover:bg-amber-100 hover:border-amber-300 rounded-lg px-1.5 sm:px-3 py-1 sm:py-1.5 inline-flex items-center text-center shadow-sm transition-all ml-auto w-max">
                                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="hidden sm:inline">Statement</span>
                                                    <span class="sm:hidden">View</span>
                                                </a>
                                            @else
                                                <a href="{{ route('billing.receipt', $bill->id) }}" class="text-green-700 font-bold text-[9px] sm:text-xs uppercase tracking-wide border border-green-200 bg-green-50 hover:bg-green-100 hover:border-green-300 rounded-lg px-1.5 sm:px-3 py-1 sm:py-1.5 inline-flex items-center text-center shadow-sm transition-all ml-auto w-max">
                                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-1 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="hidden sm:inline">Receipt</span>
                                                    <span class="sm:hidden">Print</span>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#0f1722] mb-3 border border-[#263548]">
                                            <svg class="w-6 h-6 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-[15px] font-medium text-gray-200">No billing records found.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.4);
        border-radius: 10px;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Fullscreen specific tweaks */
    :fullscreen main {
        padding: 2rem !important;
        background-color: #020617 !important;
    }
</style>

<script>
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
            });
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }

    // Auto-fullscreen attempt on user interaction if they just logged in
    @if(session('success') && str_contains(session('success'), 'logged in'))
    document.addEventListener('click', function autoFS() {
        toggleFullscreen();
        document.removeEventListener('click', autoFS);
    }, { once: true });
    @endif

    // Estimator Logic
    const estimatorSettings = {
        Regular: { 
            base: {{ $settings['regular_base_charge'] ?? 100 }}, 
            rate: {{ $settings['regular_usage_rate'] ?? 15 }},
            limit: {{ $settings['regular_base_limit'] ?? 10 }}
        },
        Commercial: { 
            base: {{ $settings['commercial_base_charge'] ?? 250 }}, 
            rate: {{ $settings['commercial_usage_rate'] ?? 25 }},
            limit: {{ $settings['commercial_base_limit'] ?? 10 }}
        }
    };
    
    const customerType = '{{ $customer->type }}';
    const previousReading = {{ $customer->meter_reading ?? 0 }};
    const globalAdditionalChargeTotal = {{ $globalAdditionalChargeTotal ?? 0 }};
    
    function calculateEstimate() {
        const input = document.getElementById('reading_estimate');
        const errorText = document.getElementById('estimate_error_text');
        const currentReading = parseFloat(input.value);
        
        if (isNaN(currentReading)) {
            errorText.textContent = "Please enter a valid reading.";
            errorText.classList.remove('hidden');
            return;
        }
        
        if (currentReading < previousReading) {
            errorText.textContent = `Reading cannot be lower than your previous reading (${previousReading}).`;
            errorText.classList.remove('hidden');
            return;
        }
        
        errorText.classList.add('hidden');
        
        const usage = currentReading - previousReading;
        const typeSettings = estimatorSettings[customerType] || estimatorSettings['Regular'];
        
        const billableUsage = Math.max(usage - typeSettings.limit, 0);
        const usageCharge = billableUsage * typeSettings.rate;
        const total = typeSettings.base + usageCharge + globalAdditionalChargeTotal;
        
        // Populate modal
        document.getElementById('modal_est_prev').textContent = previousReading.toLocaleString();
        document.getElementById('modal_est_new').textContent = currentReading.toLocaleString();
        document.getElementById('modal_est_usage').textContent = usage.toLocaleString() + ' m³';
        
        document.getElementById('modal_est_usage_charge_lbl').textContent = `Usage Charge (${usage} m³)`;
        document.getElementById('modal_est_usage_charge').textContent = '₱' + usageCharge.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('modal_est_base_charge').textContent = '₱' + typeSettings.base.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        const additionalChargesContainer = document.getElementById('modal_est_additional_charges');
        additionalChargesContainer.innerHTML = '';
        @if(isset($globalAdditionalCharges) && count($globalAdditionalCharges) > 0)
            @foreach($globalAdditionalCharges as $charge)
            additionalChargesContainer.innerHTML += `
                <div class="flex justify-between items-center bg-blue-500/5 -mx-4 px-4 py-2 rounded-lg border border-blue-500/10">
                    <div>
                        <p class="text-blue-400 font-medium text-sm italic">Additional Charge: {{ $charge['name'] }}</p>
                    </div>
                    <p class="text-blue-400 font-bold">+ ₱{{ number_format($charge['amount'], 2) }}</p>
                </div>
            `;
            @endforeach
        @endif
        
        document.getElementById('modal_est_total').textContent = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        // Open modal
        window.Flux.modal('estimate-receipt-modal').show();
    }
    // Initialize Chart
    const initChart = () => {
        if (typeof window.Chart === 'undefined') {
            setTimeout(initChart, 50);
            return;
        }
        
        const usageCtx = document.getElementById('consumerUsageChart');
        if (!usageCtx) return;
        
        // Ensure oldest bills are first for the chart (left to right)
        @php
            $chartBills = isset($customer) && $customer->bills ? $customer->bills->reverse()->values() : collect();
            $labels = $chartBills->map(fn($b) => \Carbon\Carbon::parse($b->billing_date)->format('M Y'))->toArray();
            $data = $chartBills->map(fn($b) => $b->consumption)->toArray();
        @endphp
        
        const labels = {!! json_encode($labels) !!};
        const data = {!! json_encode($data) !!};
        
        if (labels.length === 0) {
            // No data placeholder or just empty
            return;
        }

        const gradient = usageCtx.getContext('2d').createLinearGradient(0, 0, 0, 150);
        gradient.addColorStop(0, 'rgba(6, 182, 212, 0.5)'); // Cyan 500
        gradient.addColorStop(1, 'rgba(6, 182, 212, 0.0)');

        new Chart(usageCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Usage (m³)',
                    data: data,
                    borderColor: '#06b6d4', // Cyan 500
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#06b6d4',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
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
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#ffffff',
                        bodyColor: '#cbd5e1',
                        borderColor: '#334155',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' m³';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                        border: { display: false },
                        ticks: { font: { family: "'Inter', sans-serif", size: 10 }, color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { family: "'Inter', sans-serif", size: 10 }, color: '#94a3b8', maxRotation: 45, minRotation: 45 }
                    }
                }
            }
        });
    };

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(initChart, 1);
    } else {
        document.addEventListener('DOMContentLoaded', initChart);
    }
    document.addEventListener('livewire:navigated', initChart);

    // Smart Auto-Refresh: Listen for new bills generated by admin
    const checkNewBills = () => {
        fetch(`/api/check-new-bills/{{ $customer->id }}`)
            .then(res => res.json())
            .then(data => {
                if (data.has_new_bill) {
                    // Reload the page to show the new bill and notification
                    window.location.reload();
                }
            })
            .catch(err => console.error('Error checking for new bills:', err));
    };

    // Poll every 5 seconds
    setInterval(checkNewBills, 5000);
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js" data-navigate-track></script>

<!-- Estimate Receipt Modal -->
<flux:modal name="estimate-receipt-modal" class="md:w-[500px] !bg-white !p-0 overflow-hidden">
    <div class="printable-receipt border-t-8 border-blue-500 bg-white">
        <div class="p-8">
            <div class="flex justify-between items-center pb-6 border-b border-zinc-200">
                <div>
                    <h2 class="text-2xl font-bold text-black uppercase">Estimated Bill</h2>
                    <p class="text-sm text-zinc-500 mt-1">Not an official receipt</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-black uppercase tracking-tighter leading-none">DOLORES WATER<br>SERVICES SYSTEM</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 py-6 border-b border-zinc-200">
                <div class="text-center border-r border-zinc-200">
                    <p class="text-[10px] text-zinc-500 uppercase tracking-widest mb-1">Previous Reading</p>
                    <p class="font-bold text-black text-lg" id="modal_est_prev">-</p>
                </div>
                <div class="text-center border-r border-zinc-200">
                    <p class="text-[10px] text-zinc-500 uppercase tracking-widest mb-1">New Reading</p>
                    <p class="font-bold text-black text-lg" id="modal_est_new">-</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] text-zinc-500 uppercase tracking-widest mb-1">Total Consumption</p>
                    <p class="font-bold text-blue-600 text-xl" id="modal_est_usage">-</p>
                </div>
            </div>

            <div class="py-6">
                <p class="text-xs text-zinc-500 uppercase tracking-wider mb-4">Estimated Breakdown</p>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <p class="text-zinc-700" id="modal_est_usage_charge_lbl">Usage Charge</p>
                        <p class="text-black font-medium" id="modal_est_usage_charge">-</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-zinc-700">Base Charge</p>
                        <p class="text-black font-medium" id="modal_est_base_charge">-</p>
                    </div>
                    <div id="modal_est_additional_charges" class="space-y-4"></div>
                </div>
            </div>

            <div class="bg-zinc-50 -mx-8 -mb-8 p-8 border-t border-zinc-200">
                <div class="flex justify-between items-center">
                    <p class="text-lg text-zinc-700 font-semibold uppercase tracking-wider">Estimated Total</p>
                    <p class="text-2xl text-blue-600 font-bold tracking-tight" id="modal_est_total">-</p>
                </div>
            </div>
            
        </div>
    </div>
</flux:modal>
</x-layouts::app>

