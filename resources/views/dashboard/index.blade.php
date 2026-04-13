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
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">

            <div
                class="bg-[#1b2636]/40 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059]/50 p-6 flex items-start justify-between relative overflow-hidden group hover:border-cyan-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[13px] font-medium text-gray-400 uppercase tracking-widest mb-1">Customers</p>
                    <h3 class="text-3xl font-extrabold text-white tracking-tight">{{ $totalCustomers }}</h3>
                </div>
                <div
                    class="h-12 w-12 rounded-xl bg-blue-500/10 text-blue-600 border border-blue-500/20 flex items-center justify-center relative z-10 shadow-sm group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div
                    class="absolute -right-4 -bottom-4 bg-gradient-to-br from-cyan-600/20 to-blue-900/20 h-32 w-32 rounded-full blur-2xl">
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
                            class="text-xl text-cyan-300 font-medium">L</span></h3>
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
                    <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-600/10 rounded-full blur-3xl point-events-none">
                    </div>
                    <div class="px-6 py-4 flex items-center justify-between relative z-10">
                        <h2 class="text-[17px] font-bold text-gray-200 tracking-tight flex items-center">
                            <span class="mr-2 text-cyan-400">Performance</span>
                            <span class="text-gray-500 font-normal text-sm ml-2">Revenue Trend</span>
                        </h2>
                    </div>
                    <div class="p-6 pt-0 flex-1 relative min-h-[300px] z-10">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Usage Flow Chart -->
                <div
                    class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-hidden border border-[#263548] flex flex-col pt-2 relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl point-events-none">
                    </div>
                    <div class="px-6 py-4 flex items-center justify-between relative z-10">
                        <h2 class="text-[17px] font-bold text-gray-200 tracking-tight flex items-center">
                            <span class="mr-2 text-indigo-400">Consumption</span>
                            <span class="text-gray-500 font-normal text-sm ml-2">Usage Flow</span>
                        </h2>
                    </div>
                    <div class="p-6 pt-0 flex-1 relative min-h-[300px] z-10">
                        <canvas id="usageChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-8">
                <div class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-hidden border border-[#263548] flex flex-col p-6 flex-1 relative min-h-[220px]">
                    <h2 class="text-[15px] font-bold text-gray-200 mb-2">Customer Types</h2>
                    <div class="relative flex-1 flex items-center justify-center min-h-[160px]">
                        <canvas id="customerChart"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center flex-col pointer-events-none mt-2">
                            <span class="text-2xl font-bold text-white">{{ $totalCustomers }}</span>
                            <span class="text-[10px] uppercase tracking-wider text-gray-400">Total</span>
                        </div>
                    </div>
                </div>

                <div class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-hidden border border-[#263548] flex flex-col p-6 flex-1 relative min-h-[220px]">
                    <h2 class="text-[15px] font-bold text-gray-200 mb-6">Revenue Source</h2>
                    <div class="space-y-5">
                        @forelse($revenueByType as $revType)
                            @php
                                $percent = $totalRevenue > 0 ? ($revType->total / $totalRevenue) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1.5">
                                    <span class="text-gray-300 font-medium">{{ $revType->type ?? 'Regular' }}</span>
                                    <span class="text-gray-400 font-semibold">{{ number_format($percent, 1) }}%</span>
                                </div>
                                <div class="w-full bg-[#1b2636] rounded-full h-2 shadow-inner">
                                    <div class="bg-blue-600 h-2 rounded-full shadow-[0_0_8px_rgba(37,99,235,0.8)]" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 italic">No revenue source data.</p>
                        @endforelse
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

            // Shared Chart.js options for perfect curves and styling
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
            let revLabels = {!! json_encode($monthlyRevenue->pluck('month')) !!};
            let revData = {!! json_encode($monthlyRevenue->pluck('total')) !!};
            if (revLabels.length < 2) {
                revLabels = ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'];
                revData = [12500, 15300, 14200, 22000, 19500, 28400, revData[0] || 31200];
            }

            const revenueCtx = revEl.getContext('2d');
            const revGradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
            revGradient.addColorStop(0, 'rgba(6, 182, 212, 0.4)'); // Cyan
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

            // 2. USAGE FLOW CHART
            let usageLabels = {!! json_encode($usageTrend->pluck('month')) !!};
            let usageData = {!! json_encode($usageTrend->pluck('total_usage')) !!};
            if (usageLabels.length < 2) {
                usageLabels = ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'];
                usageData = [450, 520, 480, 710, 650, 890, usageData[0] || 950];
            }

            const usageCtx = document.getElementById('usageChart').getContext('2d');
            const useGradient = usageCtx.createLinearGradient(0, 0, 0, 400);
            useGradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)'); // Indigo
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

            // 3. CUSTOMER TYPES DONUT CHART
            let custLabels = {!! json_encode($customerTypes->pluck('type')) !!};
            let custData = {!! json_encode($customerTypes->pluck('count')) !!};
            if (custLabels.length === 0) {
                custLabels = ['Regular', 'Commercial', 'Industrial'];
                custData = [65, 20, 15];
            }

            const customerCtx = document.getElementById('customerChart').getContext('2d');
            window.customerChartInstance = new Chart(customerCtx, {
                type: 'doughnut',
                data: {
                    labels: custLabels,
                    datasets: [{
                        data: custData,
                        backgroundColor: ['#2563eb', '#0f172a', '#3b82f6', '#1e293b'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: flowChartOptions.plugins.tooltip
                    }
                }
            });
        }

        // Initialize immediately if script executes after DOM, or wire it nicely for Livewire navigations
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            setTimeout(initializeDashboardCharts, 1);
        } else {
            document.addEventListener('DOMContentLoaded', initializeDashboardCharts);
        }
        
        // Critical for Livewire SPA navigation!
        document.addEventListener('livewire:navigated', initializeDashboardCharts);
    </script>
</x-layouts::app>