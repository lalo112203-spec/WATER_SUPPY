<x-layouts::app title="Dashboard">
    <div class="px-6 py-8 bg-transparent min-h-[calc(100vh-4rem)] font-sans text-gray-200 relative z-10">
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-[28px] font-bold text-gray-100 tracking-tight drop-shadow-md">D.W.S.S Dashboard</h1>
                <p class="mt-1 text-[15px] text-gray-400 font-medium">Overview of D.W.S.S metrics and performance.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div
                    class="inline-flex items-center px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-full shadow-sm text-sm font-semibold text-emerald-600 backdrop-blur-sm">
                    <span class="flex h-2 w-2 relative mr-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Status: System Online
                </div>
            </div>
        </div>

        <!-- Revenue Stats -->
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-2">

            <div
                class="bg-[#1b2636]/40 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059]/50 p-6 flex items-start justify-between relative overflow-hidden group hover:border-cyan-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[13px] font-medium text-gray-400 uppercase tracking-widest mb-1">Total Customers</p>
                    <h3 class="text-3xl font-extrabold text-white tracking-tight">{{ $totalCustomers }}</h3>
                </div>
                <div
                    class="h-12 w-12 rounded-xl bg-blue-500/10 text-blue-600 border border-blue-500/20 flex items-center justify-center relative z-10 shadow-sm group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 bg-gradient-to-br from-cyan-600/20 to-blue-900/20 h-32 w-32 rounded-full blur-2xl">
                </div>
            </div>

            <div
                class="bg-[#1b2636]/40 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059]/50 p-6 flex items-start justify-between relative overflow-hidden group hover:border-emerald-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[13px] font-medium text-gray-400 uppercase tracking-widest mb-1">Paid Customers</p>
                    <h3 class="text-3xl font-extrabold text-emerald-400 tracking-tight">{{ $paidCustomersCount }}</h3>
                </div>
                <div
                    class="h-12 w-12 rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 flex items-center justify-center relative z-10 shadow-sm group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 bg-gradient-to-br from-emerald-600/20 to-teal-900/20 h-32 w-32 rounded-full blur-2xl">
                </div>
            </div>

            <div
                class="bg-[#1b2636]/40 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059]/50 p-6 flex items-start justify-between relative overflow-hidden group hover:border-orange-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[13px] font-medium text-gray-400 uppercase tracking-widest mb-1">Unpaid Customers</p>
                    <h3 class="text-3xl font-extrabold text-orange-400 tracking-tight">{{ $unpaidCustomersCount }}</h3>
                </div>
                <div
                    class="h-12 w-12 rounded-xl bg-orange-500/10 text-orange-500 border border-orange-500/20 flex items-center justify-center relative z-10 shadow-sm group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 bg-gradient-to-br from-orange-600/20 to-red-900/20 h-32 w-32 rounded-full blur-2xl">
                </div>
            </div>

            <div
                class="bg-[#1b2636]/40 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059]/50 p-6 flex items-start justify-between relative overflow-hidden group hover:border-blue-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[13px] font-medium text-gray-400 uppercase tracking-widest mb-1">Total Revenue</p>
                    <h3 class="text-3xl font-extrabold text-white tracking-tight">₱{{ number_format($totalRevenue, 2) }}
                    </h3>
                </div>
                <div
                    class="h-12 w-12 rounded-xl bg-blue-900/30 text-blue-400 border border-blue-800/50 flex items-center justify-center relative z-10 shadow-[0_0_15px_rgba(59,130,246,0.3)] group-hover:scale-110 transition-transform">
                    <span class="text-2xl font-bold leading-none flex items-center justify-center translate-y-[1px]">₱</span>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 bg-gradient-to-br from-blue-600/20 to-indigo-900/20 h-32 w-32 rounded-full blur-2xl">
                </div>
            </div>

            <div
                class="bg-[#1b2636]/40 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059]/50 p-6 flex items-start justify-between relative overflow-hidden group hover:border-emerald-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[13px] font-medium text-gray-400 uppercase tracking-widest mb-1">Pending Rev.</p>
                    <h3 class="text-3xl font-extrabold text-emerald-400 tracking-tight">
                        ₱{{ number_format($pendingRevenue, 2) }}</h3>
                </div>
                <div
                    class="h-12 w-12 rounded-xl bg-emerald-900/30 text-emerald-400 border border-emerald-800/50 flex items-center justify-center relative z-10 shadow-[0_0_15px_rgba(16,185,129,0.3)] group-hover:scale-110 transition-transform">
                    <span class="text-2xl font-bold leading-none flex items-center justify-center translate-y-[1px]">₱</span>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 bg-gradient-to-br from-emerald-600/20 to-teal-900/20 h-32 w-32 rounded-full blur-2xl">
                </div>
            </div>

            <div
                class="bg-[#1b2636]/40 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059]/50 p-6 flex items-start justify-between relative overflow-hidden group hover:border-cyan-400/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[13px] font-medium text-gray-400 uppercase tracking-widest mb-1">Total Usage</p>
                    <h3 class="text-3xl font-extrabold text-white tracking-tight">
                        {{ number_format($totalConsumption, 2) }} <span
                            class="text-xl text-cyan-300 font-medium">m³</span></h3>
                </div>
                <div
                    class="h-12 w-12 rounded-xl bg-cyan-900/30 text-cyan-400 border border-cyan-800/50 flex items-center justify-center relative z-10 shadow-[0_0_15px_rgba(6,182,212,0.3)] group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 bg-gradient-to-br from-cyan-600/20 to-blue-900/20 h-32 w-32 rounded-full blur-2xl">
                </div>
            </div>

        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2 flex flex-col gap-8">
                <!-- Revenue Flow Chart -->
                <div
                    class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-hidden border border-[#263548] flex flex-col pt-2 relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-600/10 rounded-full blur-3xl pointer-events-none">
                    </div>
                    <div class="px-6 py-4 flex items-center justify-between relative z-10">
                        <h2 class="text-[17px] font-bold text-gray-200 tracking-tight flex items-center">
                            <span class="mr-2 text-cyan-400">Performance</span>
                            <span class="text-gray-500 font-normal text-sm ml-2">Revenue Trend</span>
                        </h2>
                    </div>
                    <div class="p-6 pt-0 flex-1 relative min-h-[400px] z-10">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Usage Flow Chart -->
                <div
                    class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-hidden border border-[#263548] flex flex-col pt-2 relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none">
                    </div>
                    <div class="px-6 py-4 flex items-center justify-between relative z-10">
                        <h2 class="text-[17px] font-bold text-gray-200 tracking-tight flex items-center">
                            <span class="mr-2 text-indigo-400">Consumption</span>
                            <span class="text-gray-500 font-normal text-sm ml-2">Usage Flow</span>
                        </h2>
                    </div>
                    <div class="p-6 pt-0 flex-1 relative min-h-[400px] z-10">
                        <canvas id="usageChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-8">
                <div class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-hidden border border-[#263548] flex flex-col p-6 flex-1 relative min-h-[220px]">
                    <h2 class="text-[15px] font-bold text-gray-200 mb-2">Customer Types</h2>
                    <div class="relative flex-1 flex items-center justify-center min-h-[160px]">
                        <canvas id="customerChart"></canvas>
                    </div>
                </div>

                <div class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-hidden border border-[#263548] flex flex-col p-6 flex-1 relative min-h-[220px]">
                    <h2 class="text-[15px] font-bold text-gray-200 mb-6">Revenue Source</h2>
                    <div class="relative flex-1 min-h-[160px]">
                        <canvas id="revenueTypeChart"></canvas>
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
            const instances = [
                'revenueChartInstance',
                'usageChartInstance',
                'customerChartInstance',
                'revenueTypeChartInstance'
            ];
            instances.forEach(inst => {
                if (window[inst]) {
                    window[inst].destroy();
                    window[inst] = null;
                }
            });

            // Shared Chart.js options
            const flowChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                    legend: { 
                        display: true, 
                        position: 'top',
                        align: 'end',
                        labels: {
                            color: '#94a3b8',
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { size: 10, weight: '600' },
                            padding: 15
                        }
                    },
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
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    if (label.includes('₱')) {
                                        label += '₱' + context.parsed.y.toLocaleString();
                                    } else {
                                        label += context.parsed.y.toLocaleString() + (context.dataset.unit || '');
                                    }
                                }
                                return label;
                            }
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
                    let paidData = {!! json_encode($monthlyRevenue->pluck('total')) !!};
                    let pendingData = {!! json_encode($monthlyPendingRevenue->pluck('total')) !!};

                    // Ensure labels cover both datasets
                    const allLabels = [...new Set([...revLabels, ...{!! json_encode($monthlyPendingRevenue->pluck('month')) !!}])].sort();

                    const revenueCtx = revEl.getContext('2d');
                    const paidGrad = revenueCtx.createLinearGradient(0, 0, 0, 400);
                    paidGrad.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
                    paidGrad.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

                    const pendGrad = revenueCtx.createLinearGradient(0, 0, 0, 400);
                    pendGrad.addColorStop(0, 'rgba(245, 158, 11, 0.2)');
                    pendGrad.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

                    window.revenueChartInstance = new Chart(revenueCtx, {
                        type: 'line',
                        data: {
                            labels: allLabels,
                            datasets: [
                                {
                                    label: 'Collected (₱)',
                                    data: allLabels.map(l => {
                                        const idx = revLabels.indexOf(l);
                                        return idx !== -1 ? paidData[idx] : 0;
                                    }),
                                    borderColor: '#10b981',
                                    backgroundColor: paidGrad,
                                    borderWidth: 3,
                                    pointBackgroundColor: '#10b981',
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2,
                                    fill: true,
                                    tension: 0.4
                                },
                                {
                                    label: 'Pending (₱)',
                                    data: allLabels.map(l => {
                                        const pendLabels = {!! json_encode($monthlyPendingRevenue->pluck('month')) !!};
                                        const idx = pendLabels.indexOf(l);
                                        return idx !== -1 ? pendingData[idx] : 0;
                                    }),
                                    borderColor: '#f59e0b',
                                    backgroundColor: pendGrad,
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    pointBackgroundColor: '#f59e0b',
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 1,
                                    fill: true,
                                    tension: 0.4
                                }
                            ]
                        },
                        options: flowChartOptions
                    });
                }
            } catch (e) {
                console.error("Revenue chart error:", e);
            };

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
                                label: 'Usage (m³)',
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