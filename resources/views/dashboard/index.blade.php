<x-layouts::app title="Dashboard">
    <div class="px-6 py-8 bg-transparent min-h-[calc(100vh-4rem)] font-sans text-gray-200 relative z-10">
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-[28px] font-bold text-gray-100 tracking-tight drop-shadow-md">System Dashboard</h1>
                <p class="mt-1 text-[15px] text-gray-400 font-medium">Overview of your water system metrics and performance.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="inline-flex items-center px-4 py-2 bg-[#091522]/80 border border-cyan-800/60 rounded-xl shadow-[0_0_15px_rgba(6,182,212,0.2)] text-sm font-semibold text-cyan-400 backdrop-blur-sm">
                    <span class="flex h-2 w-2 relative mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-300"></span>
                    </span>
                    Status: System Online
                </div>
            </div>
        </div>
        
        <!-- Revenue Stats -->
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
            
            <div class="bg-gradient-to-br from-[#1b2636] to-[#0f1722] rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059] p-6 flex items-start justify-between relative overflow-hidden group hover:border-cyan-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[13px] font-medium text-gray-400 uppercase tracking-widest mb-1">Customers</p>
                    <h3 class="text-3xl font-extrabold text-white tracking-tight">{{ $totalCustomers }}</h3>
                    <p class="mt-2 text-xl font-medium text-cyan-300">₱1,250.00</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-cyan-900/30 text-cyan-400 border border-cyan-800/50 flex items-center justify-center relative z-10 shadow-[0_0_15px_rgba(6,182,212,0.3)] group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="absolute -right-4 -bottom-4 bg-gradient-to-br from-cyan-600/20 to-blue-900/20 h-32 w-32 rounded-full blur-2xl"></div>
            </div>

            <div class="bg-gradient-to-br from-[#1b2636] to-[#0f1722] rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059] p-6 flex items-start justify-between relative overflow-hidden group hover:border-blue-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[13px] font-medium text-gray-400 uppercase tracking-widest mb-1">Total Revenue</p>
                    <h3 class="text-3xl font-extrabold text-white tracking-tight">₱{{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div class="h-12 w-12 rounded-xl bg-blue-900/30 text-blue-400 border border-blue-800/50 flex items-center justify-center relative z-10 shadow-[0_0_15px_rgba(59,130,246,0.3)] group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="absolute -right-4 -bottom-4 bg-gradient-to-br from-blue-600/20 to-indigo-900/20 h-32 w-32 rounded-full blur-2xl"></div>
            </div>

            <div class="bg-gradient-to-br from-[#1b2636] to-[#0f1722] rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059] p-6 flex items-start justify-between relative overflow-hidden group hover:border-emerald-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[13px] font-medium text-gray-400 uppercase tracking-widest mb-1">Pending Rev.</p>
                    <h3 class="text-3xl font-extrabold text-emerald-400 tracking-tight">₱{{ number_format($pendingRevenue, 2) }}</h3>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-900/30 text-emerald-400 border border-emerald-800/50 flex items-center justify-center relative z-10 shadow-[0_0_15px_rgba(16,185,129,0.3)] group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="absolute -right-4 -bottom-4 bg-gradient-to-br from-emerald-600/20 to-teal-900/20 h-32 w-32 rounded-full blur-2xl"></div>
            </div>

            <div class="bg-gradient-to-br from-[#1b2636] to-[#0f1722] rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.5)] border border-[#2d4059] p-6 flex items-start justify-between relative overflow-hidden group hover:border-cyan-400/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[13px] font-medium text-gray-400 uppercase tracking-widest mb-1">Total Usage</p>
                    <h3 class="text-3xl font-extrabold text-white tracking-tight">{{ number_format($totalConsumption, 2) }} <span class="text-xl text-cyan-300 font-medium">L</span></h3>
                    <p class="mt-2 text-xl font-medium text-gray-400">₱600.00</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-cyan-900/30 text-cyan-400 border border-cyan-800/50 flex items-center justify-center relative z-10 shadow-[0_0_15px_rgba(6,182,212,0.3)] group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="absolute -right-4 -bottom-4 bg-gradient-to-br from-cyan-600/20 to-blue-900/20 h-32 w-32 rounded-full blur-2xl"></div>
            </div>
            
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-hidden border border-[#263548] flex flex-col pt-2 relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-600/10 rounded-full blur-3xl point-events-none"></div>
                <div class="px-6 py-4 flex items-center justify-between relative z-10">
                    <h2 class="text-[17px] font-bold text-gray-200 tracking-tight flex items-center">
                        <span class="mr-2 text-cyan-400">Performance Trends</span>
                        <span class="text-gray-500 font-normal text-sm ml-2">Revenue Trend</span>
                    </h2>
                </div>
                <div class="p-6 pt-0 flex-1 relative min-h-[300px] z-10">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-[#121a25]/80 backdrop-blur-md rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.6)] overflow-hidden border border-[#263548] flex flex-col pt-2 relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl point-events-none"></div>
                <div class="px-6 py-4 flex items-center justify-between relative z-10">
                    <h2 class="text-[17px] font-bold text-gray-200 tracking-tight flex items-center">
                        Usage Trend
                    </h2>
                </div>
                <div class="p-6 pt-0 flex-1 relative min-h-[300px] z-10">
                    <canvas id="usageChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // Monthly Revenue Chart - Styled as a curved line (flow) chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            
            // Gradient
            const revGradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
            revGradient.addColorStop(0, 'rgba(6, 182, 212, 0.5)');
            revGradient.addColorStop(1, 'rgba(6, 182, 212, 0.0)');

            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyRevenue->pluck('month')->count() ? $monthlyRevenue->pluck('month') : ['Current']) !!},
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: {!! json_encode($monthlyRevenue->pluck('total')->count() ? $monthlyRevenue->pluck('total') : [0]) !!},
                        borderColor: '#06b6d4',
                        backgroundColor: revGradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#06b6d4',
                        pointBorderColor: '#164e63',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4 // This makes it a smooth "flow" curve
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
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#1e293b', drawBorder: false },
                            border: { display: false },
                            ticks: { font: { family: "'Inter', sans-serif" }, color: '#64748b' }
                        },
                        x: { 
                            grid: { display: false },
                            border: { display: false },
                            ticks: { font: { family: "'Inter', sans-serif" }, color: '#64748b' }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });

            // Usage Trend Chart - Styled as a curved line (flow) chart
            const usageCtx = document.getElementById('usageChart').getContext('2d');
            
            // Gradient
            const useGradient = usageCtx.createLinearGradient(0, 0, 0, 400);
            useGradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
            useGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(usageCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($usageTrend->pluck('month')->count() ? $usageTrend->pluck('month') : ['Current']) !!},
                    datasets: [{
                        label: 'Usage (L)',
                        data: {!! json_encode($usageTrend->pluck('total_usage')->count() ? $usageTrend->pluck('total_usage') : [0]) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: useGradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#1e3a8a',
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
                            backgroundColor: 'rgba(15, 23, 34, 0.95)',
                            titleColor: '#e2e8f0',
                            bodyColor: '#cbd5e1',
                            borderColor: '#1e293b',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#1e293b', drawBorder: false },
                            border: { display: false },
                            ticks: { font: { family: "'Inter', sans-serif" }, color: '#64748b' }
                        },
                        x: { 
                            grid: { display: false },
                            border: { display: false },
                            ticks: { font: { family: "'Inter', sans-serif" }, color: '#64748b' }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        });
    </script>
</x-layouts::app>
