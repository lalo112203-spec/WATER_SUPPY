<x-layouts::app title="Billing Reports">
    <div class="px-4 sm:px-6 py-4 bg-transparent min-h-screen font-sans text-gray-300">
        
        <h1 class="text-3xl font-bold mb-6 text-gray-200">Billing Reports</h1>

        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-5 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-rose-500/10 border border-rose-500/30 text-rose-300 px-5 py-3 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif



        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-emerald-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-emerald-300 uppercase tracking-widest mb-1 drop-shadow-sm">Paid Bills</p>
                    <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-md">{{ $paidCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-emerald-400/20 transform -rotate-12 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-rose-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-rose-300 uppercase tracking-widest mb-1 drop-shadow-sm">Unpaid Bills</p>
                    <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-md">{{ $pendingCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-rose-400/20 transform rotate-12 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-emerald-400/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-emerald-300 uppercase tracking-widest mb-1 drop-shadow-sm">Paid Consumers</p>
                    <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-md">{{ $paidCustomersCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-emerald-400/20 transform rotate-6 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-[#1b2636]/40 backdrop-blur-md rounded-2xl border border-[#2d4059]/50 p-6 relative overflow-hidden group hover:border-orange-500/50 transition-all">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-orange-300 uppercase tracking-widest mb-1 drop-shadow-sm">Unpaid Consumers</p>
                    <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-md">{{ $unpaidCustomersCount }}</h3>
                </div>
                <div class="absolute -right-3 -bottom-3 text-orange-400/20 transform -rotate-6 group-hover:rotate-0 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="mb-8 flex flex-col lg:flex-row gap-4 items-stretch lg:items-center justify-between">
            <form action="{{ route('billing.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap lg:flex-nowrap gap-3 flex-1 max-w-5xl">
                <div class="relative flex-1 min-w-[200px] group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Account # or Name" 
                        class="w-full pl-10 pr-10 py-2.5 bg-[#121a25]/60 border border-[#263548] rounded-xl focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-all text-gray-200 text-sm">
                    @if(request('search'))
                        <a href="{{ route('billing.index', request()->except('search')) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>

                {{-- Unified Month & Year Combobox (App Calendar Style) --}}
                @php
                    $displayPeriod = 'Month & Year';
                    if ($selectedMonth && $selectedYear && isset($allMonths[$selectedMonth])) {
                        $displayPeriod = $allMonths[$selectedMonth] . ' ' . $selectedYear;
                    } elseif ($selectedMonth && isset($allMonths[$selectedMonth])) {
                        $displayPeriod = $allMonths[$selectedMonth] . ' (All Years)';
                    } elseif ($selectedYear) {
                        $displayPeriod = 'All Months, ' . $selectedYear;
                    }
                @endphp
                <div class="relative w-full sm:w-52" 
                    x-data="{
                        open: false,
                        tempMonth: '{{ $selectedMonth ?? 'all' }}',
                        tempYear: '{{ $selectedYear ?? 'all' }}',
                        monthNames: {
                            '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr',
                            '05': 'May', '06': 'Jun', '07': 'Jul', '08': 'Aug',
                            '09': 'Sep', '10': 'Oct', '11': 'Nov', '12': 'Dec'
                        },
                        fullMonthNames: {
                            '01': 'January', '02': 'February', '03': 'March', '04': 'April',
                            '05': 'May', '06': 'June', '07': 'July', '08': 'August',
                            '09': 'September', '10': 'October', '11': 'November', '12': 'December'
                        },
                        prevYear() {
                            let yr = (this.tempYear === 'all' || !this.tempYear) ? {{ now()->year }} : parseInt(this.tempYear);
                            if (yr > 1500) {
                                this.tempYear = String(yr - 1);
                            } else {
                                this.tempYear = '1500';
                            }
                        },
                        nextYear() {
                            let yr = (this.tempYear === 'all' || !this.tempYear) ? {{ now()->year }} : parseInt(this.tempYear);
                            this.tempYear = String(yr + 1);
                        },
                        toggleOpen() {
                            this.open = !this.open;
                            if (this.open && (this.tempYear === 'all' || !this.tempYear)) {
                                this.tempYear = '{{ now()->year }}';
                            }
                        },
                        apply() {
                            $el.closest('form').submit();
                        },
                        clearAndApply() {
                            this.tempMonth = 'all';
                            this.tempYear = 'all';
                            this.apply();
                        }
                    }"
                    @keydown.escape.window="open = false"
                >
                    <input type="hidden" name="month" :value="tempMonth" value="{{ $selectedMonth ?? 'all' }}">
                    <input type="hidden" name="year" :value="tempYear" value="{{ $selectedYear ?? 'all' }}">

                    <button type="button" 
                        @click="toggleOpen()" 
                        class="w-full py-2.5 px-3 bg-[#121a25]/80 hover:bg-[#162232] border border-[#263548] hover:border-blue-500/50 rounded-xl focus:outline-none focus:border-blue-500 text-gray-200 text-sm cursor-pointer flex items-center justify-between gap-2 transition-all shadow-sm group"
                        :class="open ? 'border-blue-500 ring-1 ring-blue-500/30' : ''">
                        <div class="flex items-center gap-2 truncate">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate font-medium text-xs sm:text-sm text-gray-200">
                                {{ $displayPeriod }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            @if(($selectedMonth && $selectedMonth !== 'all') || ($selectedYear && $selectedYear !== 'all'))
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            @endif
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-gray-300 transition-transform duration-200" :class="open ? 'rotate-180 text-blue-400' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    {{-- Calendar Style Dropdown Panel (Compact ~280px) --}}
                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                        @click.outside="open = false" 
                        class="absolute left-0 mt-2 bg-[#0d1522] border border-[#263548] rounded-2xl shadow-2xl p-3 z-50 text-gray-200 backdrop-blur-xl"
                        style="display: none; width: 280px;">
                        
                        {{-- App Calendar Year Header with Nav Arrows --}}
                        <div class="flex items-center justify-between pb-2 mb-2 border-b border-[#263548]/70">
                            <button type="button" @click="prevYear()" title="Previous Year (min 1500)" class="p-1 rounded-lg text-gray-400 hover:text-white hover:bg-[#1a2536] transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <div class="flex items-center justify-center flex-1 px-2">
                                <input type="number" min="1500" max="3000" 
                                    placeholder="{{ now()->year }}"
                                    title="Type any continuous year from 1500 upwards"
                                    :value="tempYear === 'all' ? '{{ now()->year }}' : tempYear" 
                                    @input="tempYear = $event.target.value ? String(Math.max(1500, parseInt($event.target.value))) : '{{ now()->year }}'"
                                    class="w-24 bg-[#141e2c] text-sm font-bold text-white border border-[#263548] rounded-lg py-1 px-2 text-center focus:outline-none focus:border-blue-500 hover:border-blue-500/50 transition">
                            </div>

                            <button type="button" @click="nextYear()" title="Next Year" class="p-1 rounded-lg text-gray-400 hover:text-white hover:bg-[#1a2536] transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>

                        {{-- Quick Filter Toggles: All Years & All Months --}}
                        <div class="grid grid-cols-2 gap-1.5 mb-2">
                            <button type="button" 
                                @click="tempYear = (tempYear === 'all' ? '{{ now()->year }}' : 'all')" 
                                style="height: 28px;"
                                class="rounded-lg text-[11px] font-semibold transition-all flex items-center justify-center border"
                                :class="tempYear === 'all' 
                                    ? 'bg-blue-600 text-white font-bold shadow-sm shadow-blue-500/30 border-blue-500' 
                                    : 'bg-[#141e2c] hover:bg-[#1f2d42] text-gray-300 border-[#263548]/50'">
                                All Years
                            </button>
                            <button type="button" 
                                @click="tempMonth = 'all'" 
                                style="height: 28px;"
                                class="rounded-lg text-[11px] font-semibold transition-all flex items-center justify-center border"
                                :class="tempMonth === 'all' 
                                    ? 'bg-blue-600 text-white font-bold shadow-sm shadow-blue-500/30 border-blue-500' 
                                    : 'bg-[#141e2c] hover:bg-[#1f2d42] text-gray-300 border-[#263548]/50'">
                                All Months
                            </button>
                        </div>

                        {{-- 4x3 Month Grid (like iOS / Android Calendar) --}}
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 5px;">
                            @foreach($allMonths as $num => $name)
                                <button type="button" 
                                    @click="tempMonth = '{{ $num }}'"
                                    style="height: 32px;"
                                    class="rounded-lg text-xs font-medium transition-all flex items-center justify-center"
                                    :class="tempMonth === '{{ $num }}' 
                                        ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-500/40 ring-1 ring-blue-400' 
                                        : 'bg-[#141e2c] hover:bg-[#1f2d42] text-gray-300 border border-[#263548]/50'">
                                    {{ substr($name, 0, 3) }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Footer Action Bar --}}
                        <div class="flex items-center justify-between pt-2.5 mt-2.5 border-t border-[#263548]/70">
                            <button type="button" @click="clearAndApply()" class="text-[11px] text-gray-400 hover:text-rose-400 transition font-medium">
                                Clear
                            </button>
                            <div class="flex items-center gap-1.5">
                                <button type="button" @click="open = false" class="px-2.5 py-1 text-gray-300 hover:bg-[#1b2636] rounded-lg text-xs font-medium transition border border-[#2d4059]">
                                    Cancel
                                </button>
                                <button type="button" @click="apply()" class="px-3.5 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold transition shadow-md shadow-blue-600/30">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="w-full sm:w-40">
                    <select name="status" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-[#121a25]/80 border border-[#263548] rounded-xl focus:outline-none focus:border-blue-500 text-gray-200 text-sm cursor-pointer">
                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All Status</option>
                        <option value="unpaid" {{ in_array(request('status'), ['unpaid', 'pending']) ? 'selected' : '' }}>Unpaid Only</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid Only</option>
                    </select>
                </div>

                {{-- Barangay Filter --}}
                <div class="w-full sm:w-44">
                    <select name="barangay" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-[#121a25]/80 border border-[#263548] rounded-xl focus:outline-none focus:border-blue-500 text-gray-200 text-sm cursor-pointer">
                        <option value="all" {{ !request('barangay') || request('barangay') === 'all' ? 'selected' : '' }}>All Barangays</option>
                        @foreach($availableBarangays as $brgy)
                            <option value="{{ $brgy }}" {{ request('barangay') === $brgy ? 'selected' : '' }}>{{ $brgy }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Meter Post Filter --}}
                <div class="w-full sm:w-44">
                    <select name="meter_post" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-[#121a25]/80 border border-[#263548] rounded-xl focus:outline-none focus:border-blue-500 text-gray-200 text-sm cursor-pointer">
                        <option value="all" {{ !request('meter_post') || request('meter_post') === 'all' ? 'selected' : '' }}>All Meter Posts</option>
                        @foreach($availableMeterPosts as $post)
                            <option value="{{ $post }}" {{ request('meter_post') === $post ? 'selected' : '' }}>Post: {{ $post }}</option>
                        @endforeach
                    </select>
                </div>

                @if($selectedMonth || $selectedYear || (request('status') && request('status') !== 'all') || (request('barangay') && request('barangay') !== 'all') || (request('meter_post') && request('meter_post') !== 'all') || request('search'))
                    <a href="{{ route('billing.index') }}" class="px-3 py-2.5 bg-[#1b2636] hover:bg-[#263548] text-gray-300 rounded-xl text-xs font-semibold flex items-center justify-center gap-1 border border-[#2d4059] transition">
                        Reset
                    </a>
                @endif
            </form>
            
            <div class="flex items-center gap-4 bg-[#1b2636]/40 p-2 rounded-2xl border border-[#2d4059]/50 shrink-0">
                <div class="px-6 py-1 text-sm">
                    <span class="text-gray-200 uppercase tracking-widest text-[10px] font-bold block">Total Billed</span>
                    <span class="text-2xl font-bold text-white tracking-tight">₱{{ number_format($totalBilled, 0) }}</span>
                </div>
            </div>
        </div>

        <form id="printBatchForm" action="{{ route('billing.print-batch') }}" method="POST" target="_blank">
            @csrf
        </form>

        {{-- ===== Unified Billing Records by Month ===== --}}
        <div class="mb-10 bg-[#121a25]/80 backdrop-blur-md rounded-2xl shadow-sm border border-[#263548] p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-[#263548]">
                <div>
                    <h2 class="text-xl font-bold text-gray-100 flex items-center gap-2.5">
                        <div class="p-2 bg-blue-500/20 text-blue-400 rounded-xl border border-blue-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        List of Consumers by Billing Month
                        @if($selectedMonth && isset($allMonths[$selectedMonth]))
                            <span class="text-xs bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 px-2.5 py-1 rounded-lg font-mono">Month: {{ $allMonths[$selectedMonth] }}</span>
                        @elseif($selectedMonth)
                            <span class="text-xs bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 px-2.5 py-1 rounded-lg font-mono">Month: {{ $selectedMonth }}</span>
                        @endif
                        @if($selectedYear)
                            <span class="text-xs bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-2.5 py-1 rounded-lg font-mono">Year: {{ $selectedYear }}</span>
                        @endif
                        @if(request('status') && request('status') !== 'all')
                            <span class="text-xs bg-amber-500/20 text-amber-300 border border-amber-500/30 px-2.5 py-1 rounded-lg capitalize">Status: {{ request('status') === 'pending' ? 'unpaid' : request('status') }}</span>
                        @endif
                    </h2>
                    <p class="text-xs text-gray-400 mt-1">Displays consumers for each month when filtering or sorting billing records with complete payment verification and batch printing.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" onclick="submitPrintBatch()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-semibold transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Selected
                    </button>
                    <div class="text-xs text-gray-400 font-mono hidden md:block">
                        Showing <span class="text-white font-bold">{{ $monthlyBillingRecords->count() }}</span> Month Group(s)
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                @forelse($monthlyBillingRecords as $monthLabel => $monthBills)
                    @php $monthSlug = \Illuminate\Support\Str::slug($monthLabel); @endphp
                    <div class="bg-[#0f1722]/90 rounded-2xl border border-[#263548] overflow-hidden">
                        {{-- Month Group Header Banner (from Image 2) --}}
                        <div class="px-5 py-3.5 bg-[#141d2b]/90 border-b border-[#263548] flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <h3 class="text-base font-bold text-blue-500 tracking-wide">
                                    {{ $monthLabel }}
                                </h3>
                                <span class="text-xs text-gray-200 font-semibold bg-[#0f1722] px-3 py-1 rounded-lg border border-[#2d4059]">
                                    {{ $monthBills->count() }} Consumers
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-xs font-semibold">
                                <span class="text-emerald-400 font-bold">{{ $monthBills->where('status', 'Paid')->count() }} Paid</span>
                                <span class="text-rose-400 font-bold">{{ $monthBills->where('status', '!=', 'Paid')->count() }} Unpaid</span>
                                <span class="text-white font-bold text-sm">Total: ₱{{ number_format($monthBills->sum('total_amount'), 0) }}</span>
                            </div>
                        </div>

                        {{-- Table with Blue Header (from Image 1) --}}
                        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-blue-500/30 scrollbar-track-transparent">
                            <table class="w-full text-left border-collapse min-w-[1000px] text-xs">
                                <thead>
                                    <tr class="bg-blue-600 text-white font-semibold text-xs tracking-wider">
                                        <th class="px-4 py-3.5 w-12 text-center whitespace-nowrap">
                                            <input type="checkbox" class="rounded border-blue-400 bg-transparent text-blue-500 focus:ring-blue-500" 
                                                title="Select all in {{ $monthLabel }}"
                                                onclick="document.querySelectorAll('.month-cb-{{ $monthSlug }}').forEach(cb => cb.checked = this.checked)">
                                        </th>
                                        <th class="px-4 py-3.5 whitespace-nowrap">Account Number</th>
                                        <th class="px-4 py-3.5 whitespace-nowrap">Consumer</th>
                                        <th class="px-4 py-3.5 whitespace-nowrap">Barangay</th>
                                        <th class="px-4 py-3.5 whitespace-nowrap">Meter Post</th>
                                        <th class="px-4 py-3.5 whitespace-nowrap">Type</th>
                                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Usage</th>
                                        <th class="px-4 py-3.5 whitespace-nowrap">Bill</th>
                                        <th class="px-4 py-3.5 whitespace-nowrap">OR Number</th>
                                        <th class="px-4 py-3.5 whitespace-nowrap">Status</th>
                                        <th class="px-4 py-3.5 text-right whitespace-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#263548]/40">
                                    @foreach($monthBills as $mb)
                                        @php
                                             $cType = $mb->customer?->type ?? 'Regular';
                                             $greenMax = $thresholds[$cType]['green_max'] ?? 10;
                                             $orangeMax = $thresholds[$cType]['orange_max'] ?? 20;
                                             $usage = $mb->consumption ?? 0;
                                             $bgClass = $usage <= $greenMax ? 'bg-emerald-500' : ($usage <= $orangeMax ? 'bg-amber-500' : 'bg-red-500');
                                             $isPaid = strtolower($mb->status) === 'paid';
                                        @endphp
                                        <tr class="hover:bg-[#1b2636]/30 transition-colors">
                                            <td class="px-4 py-3.5 align-middle text-center whitespace-nowrap w-12">
                                                <input type="checkbox" name="bill_ids[]" value="{{ $mb->id }}" 
                                                    class="bill-checkbox month-cb-{{ $monthSlug }} rounded border-gray-500 bg-transparent text-blue-500 focus:ring-blue-500" 
                                                    form="printBatchForm">
                                            </td>
                                            <td class="px-4 py-3.5 align-middle whitespace-nowrap font-mono text-gray-300">{{ str_replace('CUST', '', $mb->customer?->customer_id ?? 'N/A') }}</td>
                                            <td class="px-4 py-3.5 align-middle whitespace-nowrap font-medium text-gray-200">{{ $mb->customer?->name ?? 'Deleted Consumer' }}</td>
                                            <td class="px-4 py-3.5 align-middle whitespace-nowrap text-gray-300">{{ $mb->customer?->barangay ?? 'N/A' }}</td>
                                            <td class="px-4 py-3.5 align-middle whitespace-nowrap font-mono text-cyan-400 font-semibold">{{ $mb->customer?->meter_post ?? 'N/A' }}</td>
                                            <td class="px-4 py-3.5 align-middle whitespace-nowrap text-gray-400">{{ $cType }}</td>
                                            <td class="px-4 py-3.5 align-middle whitespace-nowrap text-center">
                                                <span class="inline-flex items-center justify-center px-3 py-1 rounded text-xs font-bold text-white shadow-sm whitespace-nowrap {{ $bgClass }}">
                                                    {{ number_format($usage, 0) }} m³
                                                </span>
                                            </td>
                                            <td class="px-4 py-3.5 align-middle whitespace-nowrap font-semibold text-gray-200">₱{{ number_format($mb->total_amount, 0) }}</td>
                                            <td class="px-4 py-3.5 align-middle whitespace-nowrap font-mono text-xs">
                                                @if($isPaid)
                                                    <span class="text-blue-400 font-semibold tracking-wide whitespace-nowrap">{{ $mb->or_number_display }}</span>
                                                @else
                                                    <span class="text-gray-500">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3.5 align-middle whitespace-nowrap">
                                                @if($isPaid)
                                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 whitespace-nowrap">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Paid
                                                    </span>
                                                @else
                                                    <span class="text-xs font-semibold text-rose-400 whitespace-nowrap">Unpaid</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3.5 align-middle whitespace-nowrap text-right">
                                                <div class="inline-flex items-center justify-end gap-1.5 whitespace-nowrap">
                                                    @if(!$isPaid)
                                                        {{-- Payment verification modal trigger --}}
                                                        <button type="button" 
                                                            onclick="openPaymentVerificationModal({{ $mb->id }}, '{{ addslashes($mb->customer?->name ?? 'Consumer') }}', '{{ $mb->customer?->customer_id ?? 'N/A' }}', '{{ $mb->billing_date->format('F Y') }}', {{ $mb->total_amount }}, '{{ $mb->or_number_display }}')" 
                                                            class="bg-[#00c853] hover:bg-[#00b048] text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-transform hover:scale-105 inline-flex items-center gap-1.5 whitespace-nowrap">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Mark as Paid
                                                        </button>
                                                    @endif

                                                    <a href="{{ route('billing.receipt', $mb) }}" class="p-1.5 text-amber-400/90 hover:text-amber-300 hover:bg-amber-500/10 rounded-lg transition border border-amber-500/30 inline-flex items-center justify-center" title="Print Receipt">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>

                                                    <form action="{{ route('billing.destroy', $mb) }}" method="POST" class="inline-flex items-center m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this bill?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 text-rose-400/90 hover:text-rose-300 hover:bg-rose-500/10 rounded-lg transition border border-rose-500/30 inline-flex items-center justify-center" title="Delete Bill">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400 text-sm italic bg-[#0f1722]/50 rounded-2xl border border-dashed border-[#263548]">
                        No billing records found for the selected filter or sorting criteria.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Create Bill Modal -->
    <flux:modal id="create-bill-modal" name="create-bill-modal" class="md:w-[520px] !bg-[#121a25] !border !border-[#2d4059] !text-gray-200">
        <div class="p-4 bg-[#121a25] text-gray-200 rounded-xl max-h-[85vh] overflow-y-auto custom-scrollbar">
            <flux:heading size="lg" class="mb-2 !text-white">Generate Consumer Bill</flux:heading>
            <flux:subheading class="mb-4 !text-gray-400">Record a new meter reading and calculate charges</flux:subheading>

            <form action="{{ route('billing.store') }}" method="POST" id="billing-create-form">
                @csrf
                <input type="hidden" name="force_billing" id="billing_modal_force_billing" value="0">
                <input type="hidden" name="consumption" id="billing_modal_consumption_hidden">

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Select Consumer <span class="text-red-500">*</span></label>
                        <select name="customer_id" id="billing_modal_customer_id" required onchange="onBillingCustomerChange()" class="w-full bg-[#0f1722] border border-[#2d4059] text-gray-200 px-3 py-2.5 rounded-xl text-sm outline-none focus:border-emerald-500">
                            <option value="" disabled selected>-- Select an active consumer --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" data-name="{{ $c->name }}" data-type="{{ $c->type }}" data-reading="{{ $c->meter_reading ?? 0 }}" data-bills-count="{{ $c->bills_count ?? 0 }}">
                                    {{ $c->customer_id }} - {{ $c->name }} ({{ $c->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="bg-[#1b2636]/40 p-4 rounded-xl border border-[#2d4059]/50">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-medium text-gray-300">Previous Reading:</span>
                            <span id="billing_modal_prev_reading" class="font-mono font-bold text-gray-200 text-lg">0</span>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="billing_modal_present_reading" class="block text-sm font-medium text-gray-300">Present Reading (m³) <span class="text-red-500">*</span></label>
                            <input type="number" step="any" id="billing_modal_present_reading" name="new_reading" required
                                oninput="calculateBillingCharges()" placeholder="Enter reading..."
                                class="w-full border border-[#2d4059] focus:border-emerald-500/50 text-2xl font-black rounded-xl py-3 px-4 outline-none transition-all duration-300 shadow-inner [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                style="background-color: #0f1722 !important; color: #34d399 !important; -moz-appearance: textfield;">
                        </div>

                        <!-- Same or Lower Reading Warning Alert -->
                        <div id="billing_reading_warning_box" class="hidden mt-3 p-3 bg-amber-500/15 border border-amber-500/40 rounded-xl text-amber-300 text-xs transition-all duration-300"></div>

                        <div id="billing_modal_calc_breakdown" class="text-xs mt-3 min-h-[1.25rem] text-zinc-500"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1">Base Charge (₱)</label>
                            <input type="number" step="0.01" name="base_charge" id="billing_modal_base_charge" readonly
                                class="w-full border border-[#2d4059] px-3 py-2 rounded-xl text-sm outline-none cursor-not-allowed font-bold"
                                style="background-color: #1b2636 !important; color: #e2e8f0 !important;">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1">Usage Charge (₱)</label>
                            <input type="number" step="0.01" name="usage_charge" id="billing_modal_usage_charge" readonly
                                class="w-full border border-[#2d4059] px-3 py-2 rounded-xl text-sm outline-none cursor-not-allowed font-bold"
                                style="background-color: #1b2636 !important; color: #e2e8f0 !important;">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Billing Date</label>
                            <input type="date" name="billing_date" value="{{ now()->format('Y-m-d') }}" class="w-full bg-[#0f1722] border border-[#2d4059] text-gray-200 px-3 py-2 rounded-xl text-sm outline-none focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-1">Due Date</label>
                            <input type="date" name="due_date" value="{{ now()->addDays(30)->format('Y-m-d') }}" class="w-full bg-[#0f1722] border border-[#2d4059] text-gray-200 px-3 py-2 rounded-xl text-sm outline-none focus:border-emerald-500">
                        </div>
                    </div>

                    <div class="bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/30 flex justify-between items-center px-6">
                        <span class="text-emerald-500 font-bold uppercase tracking-widest text-sm">Total Bill</span>
                        <div class="text-right">
                            <span class="text-emerald-400 font-bold text-3xl">₱<span id="billing_modal_total_display">0</span></span>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" id="billing_modal_submit_btn" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-all font-bold shadow-lg shadow-emerald-600/20 flex items-center justify-center">Generate Bill</button>
                        <flux:modal.close>
                            <flux:button variant="ghost" class="px-6 !border !border-[#2d4059] !text-gray-300 hover:!bg-[#1b2636] hover:!text-white">Cancel</flux:button>
                        </flux:modal.close>
                    </div>
                </div>
            </form>
        </div>
    </flux:modal>

    <script>
        function safeShowModal(name) {
            try {
                if (typeof window.Flux !== 'undefined' && typeof window.Flux.modal === 'function') {
                    window.Flux.modal(name).show();
                }
            } catch (e) {}

            try {
                document.dispatchEvent(new CustomEvent('modal-show', { detail: { name: name } }));
            } catch (e) {}

            try {
                const dialog = document.querySelector(`dialog[data-modal="${name}"], [data-modal="${name}"], #${name}, [name="${name}"]`);
                if (dialog) {
                    if (typeof dialog.showModal === 'function') {
                        if (!dialog.open) dialog.showModal();
                    } else {
                        dialog.classList.remove('hidden');
                        dialog.style.display = 'block';
                    }
                }
            } catch (e) {}
        }

        const billingSettings = {!! json_encode($settings) !!};
        const billingGlobalAdditional = {{ $globalAdditionalChargeTotal ?? 0 }};
        let billingPrevReading = 0;
        let billingCustomerType = 'Regular';
        let billingIsFirstReading = false;
        window._billingDuplicate = false;
        window._billingDuplicateMsg = '';

        function onBillingCustomerChange() {
            const select = document.getElementById('billing_modal_customer_id');
            const opt = select.options[select.selectedIndex];
            if (!opt || !opt.value) return;

            billingPrevReading = parseFloat(opt.getAttribute('data-reading')) || 0;
            billingCustomerType = opt.getAttribute('data-type') || 'Regular';
            billingIsFirstReading = parseInt(opt.getAttribute('data-bills-count') || '0') === 0 || billingPrevReading === 0;
            window._billingReadingWarningConfirmed = false;
            const wb = document.getElementById('billing_reading_warning_box');
            if (wb) { wb.innerHTML = ''; wb.classList.add('hidden'); }

            document.getElementById('billing_modal_prev_reading').textContent = billingPrevReading.toLocaleString(undefined, { maximumFractionDigits: 0 });
            
            const pr = document.getElementById('billing_modal_present_reading');
            pr.value = '';
            pr.min = 0;
            
            document.getElementById('billing_modal_base_charge').value = 0;
            document.getElementById('billing_modal_usage_charge').value = 0;
            document.getElementById('billing_modal_total_display').textContent = '0';
            document.getElementById('billing_modal_calc_breakdown').textContent = '';
            
            document.getElementById('billing_modal_force_billing').value = '0';
            window._billingDuplicate = false;
            window._billingDuplicateMsg = '';

            fetch(`/api/customers/${opt.value}/readings`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                const _now = new Date('{{ now()->format('Y-m-d\TH:i:s') }}');
                const readings = data.readings || [];
                billingIsFirstReading = readings.length === 0 || billingPrevReading === 0;
                if (pr.value !== '') {
                    calculateBillingCharges();
                }
                const duplicate = readings.find(bill => {
                    const d = new Date(bill.billing_date);
                    return d.getFullYear() === _now.getFullYear() && d.getMonth() === _now.getMonth();
                });
                if (duplicate) {
                    const monthName = new Date(duplicate.billing_date).toLocaleString('en-PH', { month: 'long', year: 'numeric' });
                    const amount = parseFloat(duplicate.total_amount || 0).toLocaleString('en-PH', { maximumFractionDigits: 0 });
                    window._billingDuplicate = true;
                    window._billingDuplicateMsg = `A bill of ₱${amount} was already recorded for ${monthName}. Submitting again will create a second bill for the same month.`;
                }
            })
            .catch(() => {});
        }

        function calculateBillingCharges() {
            const input = document.getElementById('billing_modal_present_reading');
            const baseInput = document.getElementById('billing_modal_base_charge');
            const usageInput = document.getElementById('billing_modal_usage_charge');
            const breakdown = document.getElementById('billing_modal_calc_breakdown');
            const hiddenConsumption = document.getElementById('billing_modal_consumption_hidden');
            const submitBtn = document.getElementById('billing_modal_submit_btn');
            const warningBox = document.getElementById('billing_reading_warning_box');

            if (input.value === '') {
                baseInput.value = 0;
                usageInput.value = 0;
                hiddenConsumption.value = 0;
                updateBillingTotal();
                breakdown.textContent = '';
                if (warningBox) { warningBox.innerHTML = ''; warningBox.classList.add('hidden'); }
                return;
            }

            const presentReading = parseFloat(input.value) || 0;
            const isFirst = billingIsFirstReading || billingPrevReading === 0;
            const cannotProceed = !isFirst && presentReading <= billingPrevReading;
            if (submitBtn) {
                submitBtn.disabled = cannotProceed;
                if (cannotProceed) {
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            const consumption = Math.max(0, presentReading - billingPrevReading);
            hiddenConsumption.value = consumption.toFixed(0);

            let typeKey = (billingCustomerType || 'Regular').toLowerCase();
            let baseCharge = parseFloat(billingSettings[typeKey + '_base_charge']) || 100;
            let rate = parseFloat(billingSettings[typeKey + '_usage_rate']) || 15;
            let baseLimit = parseFloat(billingSettings[typeKey + '_base_limit']) || 10;

            const billableUsage = Math.max(consumption - baseLimit, 0);
            const usageCharge = isFirst ? 0 : (billableUsage * rate);

            baseInput.value = baseCharge.toFixed(0);
            usageInput.value = usageCharge.toFixed(0);
            updateBillingTotal();

            // Real-time Warning Banner in Modal
            if (warningBox) {
                if (!isFirst && presentReading === billingPrevReading) {
                    warningBox.innerHTML = `
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <p class="font-bold text-amber-200">Cannot Proceed: Meter Reading Unchanged</p>
                                <p class="text-amber-300/90 text-[11px] mt-0.5">Present reading (${presentReading.toFixed(0)} m³) is equal to previous reading (${billingPrevReading.toFixed(0)} m³). You cannot proceed with generating this bill — reading must be greater than previous.</p>
                            </div>
                        </div>
                    `;
                    warningBox.classList.remove('hidden');
                } else if (!isFirst && presentReading < billingPrevReading) {
                    warningBox.innerHTML = `
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <p class="font-bold text-rose-200">Cannot Proceed: Reading Lower Than Previous</p>
                                <p class="text-rose-300/90 text-[11px] mt-0.5">Present reading (${presentReading.toFixed(0)} m³) is lower than previous reading (${billingPrevReading.toFixed(0)} m³). You cannot proceed with generating this bill — reading must be greater than previous.</p>
                            </div>
                        </div>
                    `;
                    warningBox.classList.remove('hidden');
                } else {
                    warningBox.innerHTML = '';
                    warningBox.classList.add('hidden');
                }
            }

            let breakdownText;
            if (isFirst) {
                breakdownText = `First Reading: Consumption ${consumption.toFixed(0)}m³ recorded | Usage charge waived (₱0) • Base charge only: ₱${baseCharge.toFixed(0)}`;
                breakdown.className = 'text-xs mt-1 text-emerald-400';
            } else if (presentReading <= billingPrevReading) {
                breakdownText = `Invalid Reading: Present reading must be greater than previous reading (${billingPrevReading.toFixed(0)} m³). Cannot generate bill.`;
                breakdown.className = 'text-xs mt-1 text-rose-400 font-semibold';
            } else {
                breakdownText = `Consumption: ${consumption.toFixed(0)}m³ | (${consumption.toFixed(0)} - ${baseLimit}) × ₱${rate} = ₱${usageCharge.toFixed(0)}`;
                if (billingGlobalAdditional > 0) {
                    breakdownText += ` + ₱${billingGlobalAdditional.toFixed(0)} (Additional Charges)`;
                }
                breakdown.className = 'text-xs mt-1 text-emerald-400';
            }
            breakdown.textContent = breakdownText;
        }

        function updateBillingTotal() {
            const base = parseFloat(document.getElementById('billing_modal_base_charge').value) || 0;
            const usage = parseFloat(document.getElementById('billing_modal_usage_charge').value) || 0;
            const total = base + usage + billingGlobalAdditional;
            document.getElementById('billing_modal_total_display').textContent = total.toLocaleString(undefined, { maximumFractionDigits: 0 });
        }

        function showBillingReadingWarningDialog(title, subtitle, message) {
            document.getElementById('billing-reading-dialog-title').textContent = title;
            document.getElementById('billing-reading-dialog-subtitle').textContent = subtitle;
            document.getElementById('billing-reading-dialog-msg').innerText = message;
            const dialog = document.getElementById('billing-reading-warning-dialog');
            if (dialog.parentElement !== document.body) {
                document.body.appendChild(dialog);
            }
            dialog.style.display = 'flex';
            if (typeof dialog.showModal === 'function') {
                try { dialog.showModal(); } catch (e) {}
            }
            document.body.style.overflow = 'hidden';
        }

        function hideBillingReadingWarningDialog() {
            const dialog = document.getElementById('billing-reading-warning-dialog');
            if (dialog) {
                if (typeof dialog.close === 'function') {
                    try { dialog.close(); } catch (e) {}
                }
                dialog.style.display = 'none';
            }
            document.body.style.overflow = '';
        }

        function showBillingDupDialog(message, onConfirm) {
            document.getElementById('billing-dup-dialog-msg').textContent = message;
            const dialog = document.getElementById('billing-dup-bill-dialog');
            if (dialog.parentElement !== document.body) {
                document.body.appendChild(dialog);
            }
            dialog.style.display = 'flex';
            if (typeof dialog.showModal === 'function') {
                try { dialog.showModal(); } catch (e) {}
            }
            document.body.style.overflow = 'hidden';
            document.getElementById('billing-dup-dialog-confirm').onclick = function() {
                hideBillingDupDialog();
                onConfirm();
            };
        }

        function hideBillingDupDialog() {
            const dialog = document.getElementById('billing-dup-bill-dialog');
            if (dialog) {
                if (typeof dialog.close === 'function') {
                    try { dialog.close(); } catch (e) {}
                }
                dialog.style.display = 'none';
            }
            document.body.style.overflow = '';
        }

        const billingCreateForm = document.getElementById('billing-create-form');
        if (billingCreateForm) {
            billingCreateForm.addEventListener('submit', function (e) {
                const prInput = document.getElementById('billing_modal_present_reading');
                const presentReading = parseFloat(prInput ? prInput.value : 0) || 0;
                const isFirst = billingIsFirstReading || billingPrevReading === 0;

                // 1. Reading Same or Lower Check (Cannot Proceed)
                if (!isFirst && presentReading <= billingPrevReading) {
                    e.preventDefault();
                    const isEqual = presentReading === billingPrevReading;
                    const title = isEqual ? 'Cannot Proceed: Unchanged Reading' : 'Cannot Proceed: Lower Reading';
                    const subtitle = isEqual ? 'Present reading is equal to previous reading' : 'Present reading is lower than previous reading';
                    const msg = isEqual
                        ? `The present reading (${presentReading} m³) is equal to the previous reading (${billingPrevReading} m³).\n\nYou cannot proceed with generating this bill because the present reading must be greater than the previous reading.`
                        : `The present reading (${presentReading} m³) is lower than the previous reading (${billingPrevReading} m³).\n\nYou cannot proceed with generating this bill because the present reading must be greater than the previous reading.`;

                    showBillingReadingWarningDialog(title, subtitle, msg);
                    return;
                }

                // 2. Duplicate Bill Check
                if (window._billingDuplicate && document.getElementById('billing_modal_force_billing').value !== '1') {
                    e.preventDefault();
                    showBillingDupDialog(window._billingDuplicateMsg || 'A bill already exists for this consumer in this month.', function() {
                        document.getElementById('billing_modal_force_billing').value = '1';
                        billingCreateForm.submit();
                    });
                }
            });
        }

        function submitPrintBatch() {
            const checked = document.querySelectorAll('.bill-checkbox:checked');
            if (checked.length === 0) {
                alert('Please select at least one bill to print.');
                return;
            }
            const form = document.getElementById('printBatchForm');
            form.innerHTML = '{{ csrf_field() }}';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'bill_ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });
            form.submit();
        }

        // Debounced search auto-submit
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput && searchInput.form && searchInput.form.action.includes('billing')) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 800);
            });
        }

        // ===== Payment Verification Modal Logic (Item 4) =====
        let currentVerificationExpectedAmount = 0;

        function openPaymentVerificationModal(billId, customerName, accountNo, period, expectedAmount, defaultOr) {
            currentVerificationExpectedAmount = parseFloat(expectedAmount);
            document.getElementById('pv-consumer-name').textContent = customerName;
            document.getElementById('pv-account-no').textContent = accountNo;
            document.getElementById('pv-period').textContent = period;
            const formattedExpected = Number.isInteger(currentVerificationExpectedAmount) 
                ? currentVerificationExpectedAmount.toLocaleString('en-PH') 
                : currentVerificationExpectedAmount.toLocaleString('en-PH', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
            document.getElementById('pv-expected-amount').textContent = '₱' + formattedExpected;
            document.getElementById('pv_or_number').value = defaultOr || ('OR-' + String(billId).padStart(6, '0'));
            document.getElementById('pv_payment_amount').value = '';
            
            const form = document.getElementById('paymentVerificationForm');
            form.action = `/billing/${billId}/mark-paid`;

            document.getElementById('paymentVerificationModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('pv_payment_amount').focus();
            }, 100);
        }

        function closePaymentVerificationModal() {
            document.getElementById('paymentVerificationModal').classList.add('hidden');
        }

        function handlePaymentVerificationSubmit(event) {
            const entered = parseFloat(document.getElementById('pv_payment_amount').value);
            const expected = currentVerificationExpectedAmount;

            if (isNaN(entered) || Math.abs(entered - expected) > 0.01) {
                event.preventDefault();
                showPaymentIncorrectDialog(entered, expected);
                return false;
            }
            return true;
        }

        function showPaymentIncorrectDialog(entered, expected) {
            const formattedEntered = isNaN(entered) ? '₱0' : '₱' + (Number.isInteger(entered) ? entered.toLocaleString('en-PH') : entered.toLocaleString('en-PH', { minimumFractionDigits: 0, maximumFractionDigits: 2 }));
            const formattedExpected = '₱' + (Number.isInteger(expected) ? expected.toLocaleString('en-PH') : expected.toLocaleString('en-PH', { minimumFractionDigits: 0, maximumFractionDigits: 2 }));
            
            document.getElementById('payment-incorrect-message').innerHTML = `
                The entered payment amount <strong class="text-rose-400 font-mono font-bold">${formattedEntered}</strong> is incorrect.<br><br>
                It does not match the required bill amount of <strong class="text-emerald-400 font-mono font-bold">${formattedExpected}</strong>.<br><br>
                Please enter the exact payment amount to confirm payment.
            `;
            document.getElementById('paymentIncorrectDialog').classList.remove('hidden');
        }

        function closePaymentIncorrectDialog() {
            document.getElementById('paymentIncorrectDialog').classList.add('hidden');
            const amtInput = document.getElementById('pv_payment_amount');
            if (amtInput) {
                amtInput.focus();
                amtInput.select();
            }
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closePaymentVerificationModal();
                closePaymentIncorrectDialog();
            }
        });
    </script>

    {{-- ===== Payment Verification Modal (Item 4) ===== --}}
    <div id="paymentVerificationModal" class="fixed inset-0 z-[80] flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" onclick="closePaymentVerificationModal()"></div>
        <div class="relative bg-[#0f1722] border border-[#263548] rounded-3xl shadow-[0_25px_60px_rgba(0,0,0,0.9)] w-full max-w-md p-6 overflow-hidden z-10" style="animation: bhSlideUp 0.25s cubic-bezier(0.34,1.56,0.64,1) both;">
            <div class="flex items-center justify-between pb-4 border-b border-[#263548] mb-5">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-emerald-500/20 text-emerald-400 rounded-xl border border-emerald-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-base">Payment Verification</h3>
                        <p class="text-xs text-gray-400">Verify details and enter payment amount</p>
                    </div>
                </div>
                <button type="button" onclick="closePaymentVerificationModal()" class="text-gray-400 hover:text-rose-400 p-1 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="paymentVerificationForm" method="POST" action="" onsubmit="handlePaymentVerificationSubmit(event)">
                @csrf
                @method('PATCH')

                <div class="space-y-4 mb-6">
                    <div class="bg-[#121a25]/90 p-4 rounded-2xl border border-[#263548] space-y-2 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Consumer</span>
                            <span class="font-bold text-white text-sm" id="pv-consumer-name">Consumer Name</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Account #</span>
                            <span class="font-mono text-gray-300" id="pv-account-no">N/A</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Billing Period</span>
                            <span class="font-medium text-gray-200" id="pv-period">October 2026</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-[#263548]">
                            <span class="text-gray-300 font-semibold uppercase tracking-wider text-[11px]">Total Bill Amount</span>
                            <span class="text-lg font-bold text-emerald-400 font-mono" id="pv-expected-amount">₱0.00</span>
                        </div>
                    </div>

                    {{-- OR Number input (Item 3) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-300 mb-1.5 uppercase tracking-wider">Official Receipt (OR) Number</label>
                        <input type="text" name="or_number" id="pv_or_number" required placeholder="OR-000001"
                            class="w-full bg-[#1b2636]/80 border border-[#2d4059] focus:border-cyan-500 text-gray-100 text-sm font-mono rounded-xl py-2.5 px-3.5 outline-none">
                    </div>

                    {{-- Payment verification amount input (Item 4) --}}
                    <div>
                        <label class="block text-xs font-bold text-cyan-400 mb-1.5 uppercase tracking-wider">
                            Enter Payment Amount (₱) <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-base">₱</span>
                            <input type="number" step="0.01" name="payment_amount" id="pv_payment_amount" required placeholder="0.00"
                                class="w-full bg-[#1b2636]/80 border border-[#2d4059] focus:border-cyan-500 text-gray-100 text-lg font-mono rounded-xl py-2.5 pl-8 pr-3 outline-none">
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">Please enter the exact payment amount received to confirm payment.</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#263548]">
                    <button type="button" onclick="closePaymentVerificationModal()" class="px-4 py-2.5 bg-[#1b2636] hover:bg-[#263548] text-gray-300 rounded-xl text-xs font-semibold transition border border-[#2d4059]">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-emerald-600/30 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Pop-up Modal for Incorrect Payment Amount (Item 4) ===== --}}
    <div id="paymentIncorrectDialog" class="fixed inset-0 z-[90] flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" onclick="closePaymentIncorrectDialog()"></div>
        <div class="relative bg-[#0f172a] border-2 border-rose-500 rounded-3xl shadow-[0_0_80px_rgba(244,63,94,0.5)] w-full max-w-md p-6 overflow-hidden z-10" style="animation: bhSlideUp 0.2s ease both;">
            <div class="flex items-start gap-4 mb-4">
                <div class="p-3 bg-rose-500/20 text-rose-400 rounded-2xl border border-rose-500/30 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-black text-lg">Incorrect Payment Amount</h3>
                    <p class="text-rose-400 text-xs font-semibold mt-0.5">Verification Error</p>
                </div>
            </div>

            <div class="bg-rose-950/40 border border-rose-500/30 rounded-2xl p-4 mb-5 text-gray-200 text-sm">
                <p id="payment-incorrect-message">The entered payment amount does not match the total bill amount.</p>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="closePaymentIncorrectDialog()" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-rose-600/30">
                    OK, Correct Amount
                </button>
            </div>
        </div>
    </div>

    {{-- ===== Duplicate Bill Confirm Dialog (Billing) ===== --}}
    <dialog id="billing-dup-bill-dialog" class="fixed inset-0 z-[999999] p-4 m-auto bg-transparent border-none outline-none max-w-lg w-full items-center justify-center backdrop:bg-black/85 backdrop:backdrop-blur-md" style="display:none; color: #ffffff !important;">
        <div class="dup-dialog-panel relative bg-[#0f172a] border-2 border-amber-400 shadow-[0_0_100px_rgba(245,158,11,0.5),0_30px_60px_rgba(0,0,0,0.95)] w-full overflow-hidden rounded-2xl z-10" style="background-color: #0f172a !important; color: #ffffff !important;">
            <div class="h-1.5 w-full bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500"></div>
            <div class="p-6 sm:p-7">
                <div class="flex items-start gap-4 mb-5">
                    <div class="shrink-0 rounded-2xl shadow-lg" style="background-color: rgba(245, 158, 11, 0.25) !important; border: 2px solid rgba(251, 191, 36, 0.5) !important; padding: 0.75rem !important;">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #fbbf24 !important; stroke: #fbbf24 !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 style="color: #ffffff !important; font-weight: 900 !important; font-size: 1.25rem !important; margin: 0 !important; line-height: 1.2 !important;">Duplicate Bill Warning</h3>
                        <p style="color: #fbbf24 !important; font-weight: 400 !important; font-size: 0.875rem !important; margin-top: 0.25rem !important; margin-bottom: 0 !important;">A bill already exists for this month</p>
                    </div>
                </div>
                <div style="background-color: #1e1b18 !important; border: 2px solid #f59e0b !important; padding: 1rem !important; border-radius: 0.75rem !important; margin-bottom: 1.25rem !important; box-shadow: inset 0 2px 4px rgba(0,0,0,0.5) !important;">
                    <p id="billing-dup-dialog-msg" style="color: #ffffff !important; font-weight: 400 !important; font-size: 1rem !important; line-height: 1.5 !important; margin: 0 !important;"></p>
                </div>
                <p style="color: #ffffff !important; font-weight: 400 !important; font-size: 0.95rem !important; margin-bottom: 1.5rem !important;">Do you still want to generate a new bill for the same month?</p>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="hideBillingDupDialog()" style="background-color: #334155 !important; color: #ffffff !important; border: 2px solid #64748b !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 700 !important; font-size: 0.875rem !important; cursor: pointer !important;">
                        Cancel
                    </button>
                    <button type="button" id="billing-dup-dialog-confirm" style="background-color: #fbbf24 !important; color: #0f172a !important; border: none !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 900 !important; font-size: 0.875rem !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important; gap: 0.5rem !important; box-shadow: 0 4px 20px rgba(251, 191, 36, 0.5) !important;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #0f172a !important; stroke: #0f172a !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Submit Anyway
                    </button>
                </div>
            </div>
        </div>
    </dialog>
    {{-- ===== Reading Same/Lower Warning Dialog (Billing) ===== --}}
    <dialog id="billing-reading-warning-dialog" class="fixed inset-0 z-[999999] p-4 m-auto bg-transparent border-none outline-none max-w-lg w-full items-center justify-center backdrop:bg-black/85 backdrop:backdrop-blur-md" style="display:none; color: #ffffff !important;">
        <div class="dup-dialog-panel relative bg-[#0f172a] border-2 border-amber-400 shadow-[0_0_100px_rgba(245,158,11,0.5),0_30px_60px_rgba(0,0,0,0.95)] w-full overflow-hidden rounded-2xl z-10" style="background-color: #0f172a !important; color: #ffffff !important;">
            <div class="h-1.5 w-full bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500"></div>
            <div class="p-6 sm:p-7">
                <div class="flex items-start gap-4 mb-5">
                    <div class="shrink-0 rounded-2xl shadow-lg" style="background-color: rgba(245, 158, 11, 0.25) !important; border: 2px solid rgba(251, 191, 36, 0.5) !important; padding: 0.75rem !important;">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #fbbf24 !important; stroke: #fbbf24 !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="billing-reading-dialog-title" style="color: #ffffff !important; font-weight: 900 !important; font-size: 1.25rem !important; margin: 0 !important; line-height: 1.2 !important;">Reading Verification Warning</h3>
                        <p id="billing-reading-dialog-subtitle" style="color: #fbbf24 !important; font-weight: 400 !important; font-size: 0.875rem !important; margin-top: 0.25rem !important; margin-bottom: 0 !important;">Reading is less than or equal to previous reading</p>
                    </div>
                </div>
                <div style="background-color: #1e1b18 !important; border: 2px solid #f59e0b !important; padding: 1rem !important; border-radius: 0.75rem !important; margin-bottom: 1.25rem !important; box-shadow: inset 0 2px 4px rgba(0,0,0,0.5) !important;">
                    <p id="billing-reading-dialog-msg" style="color: #ffffff !important; font-weight: 400 !important; font-size: 0.95rem !important; line-height: 1.5 !important; margin: 0 !important; white-space: pre-line;"></p>
                </div>
                <p style="color: #fca5a5 !important; font-weight: 500 !important; font-size: 0.95rem !important; margin-bottom: 1.5rem !important;">You cannot proceed. Present reading must be greater than previous reading.</p>
                <div class="flex justify-end">
                    <button type="button" onclick="hideBillingReadingWarningDialog()" style="background-color: #fbbf24 !important; color: #0f172a !important; border: none !important; padding: 0.75rem 1.5rem !important; border-radius: 0.75rem !important; font-weight: 900 !important; font-size: 0.875rem !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important; gap: 0.5rem !important; box-shadow: 0 4px 20px rgba(251, 191, 36, 0.4) !important;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #0f172a !important; stroke: #0f172a !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Go Back & Correct Reading
                    </button>
                </div>
            </div>
        </div>
    </dialog>
</x-layouts::app>

