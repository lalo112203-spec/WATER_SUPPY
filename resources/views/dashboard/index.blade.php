<x-layouts::app title="Dashboard">
    <div class="px-6 py-8 bg-transparent min-h-[calc(100vh-4rem)] font-sans text-gray-200 relative z-10">
        <!-- Hero Glow Background -->
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between relative z-20">
            <div>
                <h1 class="text-[32px] font-black text-white tracking-tight leading-none mb-2 drop-shadow-2xl">
                    Dolores <span class="text-blue-500">Dashboard</span>
                </h1>
                <p class="text-[15px] text-gray-400 font-medium tracking-wide">Real-time infrastructure analytics & performance metrics.</p>
            </div>
            <div class="mt-6 md:mt-0">
                <div class="inline-flex items-center px-5 py-2.5 bg-emerald-500/5 border border-emerald-500/20 rounded-2xl shadow-xl backdrop-blur-xl group hover:border-emerald-500/40 transition-all duration-500">
                    <span class="flex h-2.5 w-2.5 relative mr-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.8)]"></span>
                    </span>
                    <span class="text-sm font-bold text-emerald-400/90 tracking-wider uppercase">System Live</span>
                </div>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 gap-6 mb-10 md:grid-cols-2 lg:grid-cols-3 relative z-20">
            @php
                $cards = [
                    ['Total Customers', $totalCustomers, 'users', 'from-blue-600/20 to-blue-900/20', 'blue-500', 'blue'],
                    ['Paid Customers', $paidCustomersCount, 'check-circle', 'from-emerald-600/20 to-teal-900/20', 'emerald-400', 'emerald'],
                    ['Unpaid Customers', $unpaidCustomersCount, 'exclamation-triangle', 'from-orange-600/20 to-red-900/20', 'orange-400', 'orange'],
                    ['Total Revenue', '₱' . number_format($totalRevenue, 2), 'currency-peso', 'from-cyan-600/20 to-blue-900/20', 'cyan-400', 'cyan'],
                    ['Pending Revenue', '₱' . number_format($pendingRevenue, 2), 'banknotes', 'from-indigo-600/20 to-purple-900/20', 'indigo-400', 'indigo'],
                    ['Total Consumption', number_format($totalConsumption, 2) . ' L', 'beaker', 'from-sky-600/20 to-blue-950/20', 'sky-400', 'sky'],
                ];
            @endphp

            @foreach($cards as $card)
            <div class="group relative bg-[#0f172a]/40 backdrop-blur-2xl rounded-[32px] border border-white/5 p-8 transition-all duration-500 hover:scale-[1.02] hover:bg-[#0f172a]/60 hover:border-{{ $card[4] }}/30 overflow-hidden shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br {{ $card[3] }} opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-[13px] font-black text-gray-400 uppercase tracking-[0.2em] opacity-80">{{ $card[0] }}</span>
                        <div class="h-12 w-12 rounded-2xl bg-{{ $card[5] }}-500/10 border border-{{ $card[5] }}-500/20 flex items-center justify-center text-{{ $card[5] }}-400 shadow-inner group-hover:shadow-{{ $card[5] }}-500/20 group-hover:scale-110 transition-all duration-500">
                             @if($card[2] == 'users')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                             @elseif($card[2] == 'check-circle')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                             @elseif($card[2] == 'exclamation-triangle')
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                             @elseif($card[2] == 'currency-peso' || $card[2] == 'banknotes')
                                <span class="text-2xl font-black">₱</span>
                             @else
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.644.322a6 6 0 01-3.86.517l-2.387-.477a2 2 0 00-1.022.547l-1.16 1.16a2 2 0 00.442 3.322l.926.471l13.626-6.81l-.926-.47a2 2 0 00-1.742.067l-.644.322a2 2 0 01-1.288.172l-2.387-.477z" /></svg>
                             @endif
                        </div>
                    </div>
                    <h3 class="text-[34px] font-black text-{{ $card[4] }} tracking-tight group-hover:translate-x-1 transition-transform duration-500">{{ $card[1] }}</h3>
                </div>
                
                <!-- Decorative Blur -->
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-{{ $card[5] }}-600/20 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
            </div>
            @endforeach
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10 relative z-20">
            <!-- Left Chart Column -->
            <div class="lg:col-span-2 flex flex-col gap-8">
                <!-- Revenue Trend -->
                <div class="bg-[#0f172a]/60 backdrop-blur-2xl rounded-[40px] border border-white/5 overflow-hidden shadow-2xl flex flex-col relative group">
                    <div class="absolute top-0 right-0 w-80 h-80 bg-blue-600/5 rounded-full blur-[100px] pointer-events-none group-hover:bg-blue-600/10 transition-all duration-700"></div>
                    <div class="px-10 py-8 flex items-center justify-between border-b border-white/5">
                        <div>
                            <h2 class="text-xl font-black text-white tracking-tight">Revenue Stream</h2>
                            <p class="text-sm text-gray-500 font-medium tracking-wide">Monthly aggregated collection metrics</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        </div>
                    </div>
                    <div class="p-10 pt-4 flex-1 min-h-[350px]">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Usage Trend -->
                <div class="bg-[#0f172a]/60 backdrop-blur-2xl rounded-[40px] border border-white/5 overflow-hidden shadow-2xl flex flex-col relative group">
                    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-600/5 rounded-full blur-[100px] pointer-events-none group-hover:bg-indigo-600/10 transition-all duration-700"></div>
                    <div class="px-10 py-8 flex items-center justify-between border-b border-white/5">
                        <div>
                            <h2 class="text-xl font-black text-white tracking-tight">Consumption Flow</h2>
                            <p class="text-sm text-gray-500 font-medium tracking-wide">Infrastructure usage volume over time</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                             <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.644.322a6 6 0 01-3.86.517l-2.387-.477a2 2 0 00-1.022.547l-1.16 1.16a2 2 0 00.442 3.322l.926.471l13.626-6.81l-.926-.47a2 2 0 00-1.742.067l-.644.322a2 2 0 01-1.288.172l-2.387-.477z" /></svg>
                        </div>
                    </div>
                    <div class="p-10 pt-4 flex-1 min-h-[350px]">
                        <canvas id="usageChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="flex flex-col gap-8">
                <!-- Customer Segments -->
                <div class="bg-[#0f172a]/60 backdrop-blur-2xl rounded-[40px] border border-white/5 p-8 shadow-2xl flex-1 relative group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    <h2 class="text-lg font-black text-white mb-8 tracking-tight flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                        Customer Segments
                    </h2>
                    <div class="relative h-[250px] flex items-center justify-center">
                        <canvas id="customerChart"></canvas>
                    </div>
                </div>

                <!-- Revenue distribution -->
                <div class="bg-[#0f172a]/60 backdrop-blur-2xl rounded-[40px] border border-white/5 p-8 shadow-2xl flex-1 relative group overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-b from-indigo-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    <h2 class="text-lg font-black text-white mb-8 tracking-tight flex items-center">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3"></span>
                        Revenue Sources
                    </h2>
                    <div class="relative h-[250px]">
                        <canvas id="revenueTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js" data-navigate-track></script>
    <script>
        function initializeDashboardCharts() {
            if (typeof window.Chart === 'undefined') {
                setTimeout(initializeDashboardCharts, 50);
                return;
            }

            const revEl = document.getElementById('revenueChart');
            if (!revEl) return;

            // Destroy previous instances to avoid rendering issues on Livewire navigation
            if (window.revenueChartInstance) window.revenueChartInstance.destroy();
            if (window.usageChartInstance) window.usageChartInstance.destroy();
            if (window.customerChartInstance) window.customerChartInstance.destroy();

            if (typeof Chart === 'undefined') {
                setTimeout(initializeDashboardCharts, 50);
                return;
            }

            // Destroy previous instances to avoid rendering issues on Livewire navigation
            if (window.revenueChartInstance) window.revenueChartInstance.destroy();
            if (window.usageChartInstance) window.usageChartInstance.destroy();
            if (window.customerChartInstance) window.customerChartInstance.destroy();
            if (window.revenueTypeChartInstance) window.revenueTypeChartInstance.destroy();

            // Shared Chart.js options
            const flowChartOptions = {
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
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.05)', drawBorder: false },
                        border: { display: false },
                        ticks: { font: { family: "'Inter', sans-serif" }, color: '#64748b' }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { family: "'Inter', sans-serif" }, color: '#64748b' }
                    }
                }
            };

            // 1. REVENUE FLOW CHART
            try {
                const revEl = document.getElementById('revenueChart');
                if (revEl) {
                    let revLabels = {!! json_encode($monthlyRevenue->pluck('month')) !!};
                    let revData = {!! json_encode($monthlyRevenue->pluck('total')) !!};

                    const revenueCtx = revEl.getContext('2d');
                    const revGradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
                    revGradient.addColorStop(0, 'rgba(6, 182, 212, 0.4)');
                    revGradient.addColorStop(1, 'rgba(6, 182, 212, 0.0)');

                    window.revenueChartInstance = new Chart(revenueCtx, {
                        type: 'line',
                        data: {
                            labels: revLabels,
                            datasets: [{
                                label: 'Revenue (₱)',
                                data: revData,
                                borderColor: '#06b6d4',
                                backgroundColor: revGradient,
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
                        options: flowChartOptions
                    });
                }
            } catch (e) {
                console.error("Revenue chart error:", e);
            }

            // 2. USAGE FLOW CHART
            try {
                const usageEl = document.getElementById('usageChart');
                if (usageEl) {
                    let usageLabels = {!! json_encode($usageTrend->pluck('month')) !!};
                    let usageData = {!! json_encode($usageTrend->pluck('total_usage')) !!};

                    const usageCtx = usageEl.getContext('2d');
                    const useGradient = usageCtx.createLinearGradient(0, 0, 0, 400);
                    useGradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
                    useGradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

                    window.usageChartInstance = new Chart(usageCtx, {
                        type: 'line',
                        data: {
                            labels: usageLabels,
                            datasets: [{
                                label: 'Usage (L)',
                                data: usageData,
                                borderColor: '#6366f1',
                                backgroundColor: useGradient,
                                borderWidth: 3,
                                pointBackgroundColor: '#6366f1',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: flowChartOptions
                    });
                }
            } catch (e) {
                console.error("Usage chart error:", e);
            }

            // 3. CUSTOMER TYPES PIE CHART
            try {
                const customerEl = document.getElementById('customerChart');
                if (customerEl) {
                    let custLabels = {!! json_encode($customerTypes->pluck('type')) !!};
                    let custData = {!! json_encode($customerTypes->pluck('count')) !!};

                    const customerCtx = customerEl.getContext('2d');
                    window.customerChartInstance = new Chart(customerCtx, {
                        type: 'pie',
                        data: {
                            labels: custLabels,
                            datasets: [{
                                data: custData,
                                backgroundColor: [
                                    'rgba(6, 182, 212, 0.8)',
                                    'rgba(249, 115, 22, 0.8)',
                                    'rgba(59, 130, 246, 0.8)',
                                    'rgba(16, 185, 129, 0.8)'
                                ],
                                borderColor: '#121a25',
                                borderWidth: 4,
                                hoverOffset: 15
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            layout: { padding: 10 },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: {
                                        color: '#94a3b8',
                                        usePointStyle: true,
                                        padding: 20,
                                        font: { size: 11, weight: '600' }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                    titleColor: '#ffffff',
                                    bodyColor: '#cbd5e1',
                                    borderColor: '#334155',
                                    borderWidth: 1,
                                    padding: 12,
                                    displayColors: true
                                }
                            }
                        }
                    });
                }
            } catch (e) {
                console.error("Customer types chart error:", e);
            }

            // 4. REVENUE BY TYPE HORIZONTAL BAR CHART
            try {
                const revTypeEl = document.getElementById('revenueTypeChart');
                if (revTypeEl) {
                    let revTypeLabels = {!! json_encode($revenueByType->pluck('type')) !!};
                    let revTypeData = {!! json_encode($revenueByType->pluck('total')) !!};

                    const revTypeCtx = revTypeEl.getContext('2d');
                    window.revenueTypeChartInstance = new Chart(revTypeCtx, {
                        type: 'bar',
                        data: {
                            labels: revTypeLabels,
                            datasets: [{
                                label: 'Revenue (₱)',
                                data: revTypeData,
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.8)',
                                    'rgba(16, 185, 129, 0.8)',
                                    'rgba(6, 182, 212, 0.8)',
                                    'rgba(249, 115, 22, 0.8)'
                                ],
                                borderRadius: 8,
                                borderWidth: 0,
                                barThickness: 25
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                    titleColor: '#ffffff',
                                    bodyColor: '#cbd5e1',
                                    borderColor: '#334155',
                                    borderWidth: 1,
                                    padding: 12,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) label += ': ';
                                            if (context.parsed.x !== null) {
                                                label += '₱' + context.parsed.x.toLocaleString();
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(148, 163, 184, 0.05)', drawBorder: false },
                                    border: { display: false },
                                    ticks: { 
                                        color: '#94a3b8',
                                        font: { size: 10 },
                                        callback: function(value) { return '₱' + value.toLocaleString(); }
                                    }
                                },
                                y: {
                                    grid: { display: false },
                                    border: { display: false },
                                    ticks: { color: '#f1f5f9', font: { size: 12, weight: 'bold' } }
                                }
                            }
                        }
                    });
                }
            } catch (e) {
                console.error("Revenue type chart error:", e);
            }
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(initializeDashboardCharts, 1);
        } else {
            document.addEventListener('DOMContentLoaded', initializeDashboardCharts);
        }
        document.addEventListener('livewire:navigated', initializeDashboardCharts);
    </script>
</x-layouts::app>