<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumer Directory Backup - {{ $monthName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { 
                background-color: #ffffff !important; 
                color: #000000 !important; 
                padding: 0 !important; 
                margin: 0 !important;
                font-size: 11px !important;
            }
            .print-container { 
                width: 100% !important; 
                max-width: 100% !important; 
                margin: 0 !important; 
                padding: 10px !important; 
                box-shadow: none !important; 
                border: none !important; 
                background: #ffffff !important;
            }
            .print-title {
                color: #000000 !important;
            }
            .print-subtitle {
                color: #333333 !important;
            }
            table { 
                border-collapse: collapse !important; 
                width: 100% !important; 
            }
            th { 
                background-color: #f1f5f9 !important;
                color: #000000 !important;
                border: 1px solid #475569 !important; 
                padding: 6px 8px !important; 
                font-weight: 700 !important;
                text-transform: uppercase !important;
                font-size: 10px !important;
            }
            td { 
                border: 1px solid #475569 !important; 
                padding: 5px 8px !important; 
                color: #000000 !important;
                font-size: 10.5px !important;
            }
            tr.print-excluded { 
                display: none !important; 
            }
            .badge-print {
                border: 1px solid #333 !important;
                color: #000 !important;
                background: transparent !important;
                padding: 1px 4px !important;
            }
            .page-break { page-break-after: always; }
        }
        body { 
            background-color: #f1f5f9; 
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            color: #0f172a; 
        }
        .consumer-row {
            transition: background-color 0.15s ease, opacity 0.15s ease;
        }
    </style>
</head>
<body class="p-4 sm:p-8">
    <div class="max-w-6xl mx-auto bg-white border border-slate-200 p-6 sm:p-10 shadow-xl rounded-2xl print-container">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b-2 border-slate-200 pb-6 mb-6">
            <div>
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-blue-50 border border-blue-200 rounded-xl text-blue-600 no-print">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight uppercase print-title">Consumer Directory Backup</h1>
                        <p class="text-sm sm:text-base font-semibold text-slate-600 print-subtitle">As of end of {{ $monthName }}</p>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-2 text-xs sm:text-sm text-slate-600">
                    <div>
                        <span class="font-bold text-slate-900">Total Records:</span> 
                        <span id="total-count">{{ ($Consumers ?? $customers)->count() }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-slate-900">Selected to Print:</span>
                        <span id="selected-badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300">
                            <span id="selected-count">{{ ($Consumers ?? $customers)->count() }}</span> / {{ ($Consumers ?? $customers)->count() }}
                        </span>
                    </div>
                    <div>
                        <span class="font-bold text-slate-900">Generated:</span> 
                        <span class="text-slate-800 font-medium">{{ now()->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons (Hidden on Print) -->
            <div class="no-print flex flex-wrap items-center gap-3 w-full md:w-auto justify-end">
                <a href="javascript:history.back()" class="bg-slate-800 hover:bg-slate-900 text-white border border-slate-700 px-5 py-2.5 rounded-xl font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
                <button type="button" id="print-btn" onclick="handlePrint()" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:shadow-blue-500/30 transition-all flex items-center gap-2 text-sm cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Selected Report
                </button>
            </div>
        </div>

        <!-- Filter & Selection Bar (Hidden on Print) -->
        <div class="no-print bg-slate-50 border border-slate-200 rounded-xl p-3.5 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Select:</span>
                <button type="button" onclick="selectAll()" class="px-3 py-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-300 rounded-lg transition-colors">
                    Select All
                </button>
                <button type="button" onclick="deselectAll()" class="px-3 py-1.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-300 rounded-lg transition-colors">
                    Deselect All
                </button>
            </div>

            <!-- Quick Search Filter -->
            <div class="relative w-full sm:w-72">
                <input type="text" id="filter-input" onkeyup="filterTable()" placeholder="Filter by name, account #, barangay..." 
                    class="w-full pl-9 pr-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto w-full border border-slate-200 rounded-xl shadow-sm">
            <table class="w-full text-sm" id="report-table">
                <thead>
                    <tr class="bg-slate-100 text-slate-800">
                        <th class="py-3 px-3 border border-slate-300 w-10 text-center no-print">
                            <input type="checkbox" id="select-all-checkbox" checked onchange="toggleSelectAll(this)" title="Toggle Select All"
                                class="rounded border-slate-400 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4">
                        </th>
                        <th class="py-3 px-3.5 border border-slate-300 font-bold text-slate-800 text-left text-xs uppercase tracking-wider">Account Number</th>
                        <th class="py-3 px-3.5 border border-slate-300 font-bold text-slate-800 text-left text-xs uppercase tracking-wider">Consumer Name</th>
                        <th class="py-3 px-3 border border-slate-300 font-bold text-slate-800 text-center text-xs uppercase tracking-wider">Type</th>
                        <th class="py-3 px-3.5 border border-slate-300 font-bold text-slate-800 text-left text-xs uppercase tracking-wider">Barangay</th>
                        <th class="py-3 px-3 border border-slate-300 font-bold text-slate-800 text-center text-xs uppercase tracking-wider">Present Reading (m³)</th>
                        <th class="py-3 px-3 border border-slate-300 font-bold text-slate-800 text-center text-xs uppercase tracking-wider">Status</th>
                        <th class="py-3 px-3.5 border border-slate-300 font-bold text-slate-800 text-left text-xs uppercase tracking-wider">Registry Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse(($Consumers ?? $customers) as $customer)
                        <tr class="consumer-row hover:bg-slate-50/80 cursor-pointer" onclick="handleRowClick(event, this)">
                            <td class="px-3 py-3 border border-slate-200 text-center no-print" onclick="event.stopPropagation()">
                                <input type="checkbox" checked onchange="updateSelection()" 
                                    class="consumer-checkbox rounded border-slate-400 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4">
                            </td>
                            <td class="px-3.5 py-3 border border-slate-200 font-mono font-bold text-slate-900 col-account">
                                {{ $customer->customer_id }}
                            </td>
                            <td class="px-3.5 py-3 border border-slate-200 font-bold text-slate-900 uppercase col-name">
                                {{ $customer->name }}
                            </td>
                            <td class="px-3 py-3 border border-slate-200 text-center col-type">
                                <span class="badge-print px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider {{ strtolower($customer->type) === 'commercial' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-blue-100 text-blue-800 border border-blue-300' }}">
                                    {{ $customer->type }}
                                </span>
                            </td>
                            <td class="px-3.5 py-3 border border-slate-200 font-medium text-slate-700 col-barangay">
                                {{ $customer->barangay }}
                            </td>
                            <td class="px-3 py-3 border border-slate-200 text-center font-bold text-blue-700">
                                {{ number_format($customer->meter_reading ?? 0, 0) }}
                            </td>
                            <td class="px-3 py-3 border border-slate-200 text-center">
                                <span class="badge-print font-bold text-xs px-2 py-0.5 rounded {{ strtolower($customer->status) === 'active' ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' : 'text-rose-700 bg-rose-50 border border-rose-200' }}">
                                    {{ strtoupper($customer->status) }}
                                </span>
                            </td>
                            <td class="px-3.5 py-3 border border-slate-200 text-slate-700 font-medium text-xs">
                                {{ $customer->created_at ? $customer->created_at->format('M d, Y') : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-slate-500 font-medium italic">
                                No customer records found for this period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-slate-200 text-xs text-center text-slate-500 font-medium">
            <p>This document serves as an official physical record of the consumer database. Keep in a secure location.</p>
            <p class="mt-1 font-semibold text-slate-600">© {{ date('Y') }} Dolores Water System Solutions</p>
        </div>
    </div>

    <!-- Script for Selection and Print Filtering -->
    <script>
        function updateSelection() {
            const checkboxes = document.querySelectorAll('.consumer-checkbox');
            const checked = document.querySelectorAll('.consumer-checkbox:checked');
            const selectedCountEl = document.getElementById('selected-count');
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            const printBtn = document.getElementById('print-btn');

            if (selectedCountEl) {
                selectedCountEl.textContent = checked.length;
            }

            // Sync master checkbox
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checked.length === checkboxes.length && checkboxes.length > 0;
                selectAllCheckbox.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
            }

            // Update row visual feedback and print exclusion class
            checkboxes.forEach(cb => {
                const row = cb.closest('tr');
                if (row) {
                    if (cb.checked) {
                        row.classList.remove('print-excluded', 'opacity-35', 'bg-slate-100');
                    } else {
                        row.classList.add('print-excluded', 'opacity-35', 'bg-slate-100');
                    }
                }
            });

            if (printBtn) {
                if (checked.length === 0) {
                    printBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    printBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        function toggleSelectAll(masterCheckbox) {
            const checkboxes = document.querySelectorAll('.consumer-checkbox');
            checkboxes.forEach(cb => {
                // If filter is active, only toggle visible rows
                const row = cb.closest('tr');
                if (!row || row.style.display !== 'none') {
                    cb.checked = masterCheckbox.checked;
                }
            });
            updateSelection();
        }

        function selectAll() {
            document.querySelectorAll('.consumer-checkbox').forEach(cb => {
                const row = cb.closest('tr');
                if (!row || row.style.display !== 'none') {
                    cb.checked = true;
                }
            });
            updateSelection();
        }

        function deselectAll() {
            document.querySelectorAll('.consumer-checkbox').forEach(cb => {
                const row = cb.closest('tr');
                if (!row || row.style.display !== 'none') {
                    cb.checked = false;
                }
            });
            updateSelection();
        }

        function handleRowClick(event, row) {
            // Avoid toggling if user clicked directly on checkbox or link/button
            if (event.target.tagName.toLowerCase() === 'input' || event.target.tagName.toLowerCase() === 'a') {
                return;
            }
            const cb = row.querySelector('.consumer-checkbox');
            if (cb) {
                cb.checked = !cb.checked;
                updateSelection();
            }
        }

        function filterTable() {
            const query = (document.getElementById('filter-input')?.value || '').toLowerCase().trim();
            const rows = document.querySelectorAll('#report-table tbody tr.consumer-row');

            rows.forEach(row => {
                const account = row.querySelector('.col-account')?.textContent.toLowerCase() || '';
                const name = row.querySelector('.col-name')?.textContent.toLowerCase() || '';
                const barangay = row.querySelector('.col-barangay')?.textContent.toLowerCase() || '';
                const type = row.querySelector('.col-type')?.textContent.toLowerCase() || '';

                if (!query || account.includes(query) || name.includes(query) || barangay.includes(query) || type.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function handlePrint() {
            const checked = document.querySelectorAll('.consumer-checkbox:checked');
            if (checked.length === 0) {
                alert('Please select at least one consumer to print.');
                return;
            }
            window.print();
        }

        // Initialize state on page load
        document.addEventListener('DOMContentLoaded', updateSelection);
    </script>
</body>
</html>
