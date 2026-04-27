<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Report - {{ $monthName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; color: black !important; }
            .print-container { width: 100% !important; margin: 0 !important; padding: 0 !important; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #000 !important; padding: 8px; text-align: left; }
            .page-break { page-break-after: always; }
        }
        body { background-color: #f8fafc; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="p-8">
    <div class="max-w-5xl mx-auto bg-white p-10 shadow-lg rounded-lg print-container">
        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-gray-100 pb-8 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 uppercase">Customer Directory Backup</h1>
                <p class="text-gray-500 font-medium">As of end of {{ $monthName }}</p>
                <div class="mt-4 flex gap-6 text-sm text-gray-600">
                    <div><span class="font-bold text-gray-800">Total Records:</span> {{ $customers->count() }}</div>
                    <div><span class="font-bold text-gray-800">Generated:</span> {{ now()->format('M d, Y h:i A') }}</div>
                </div>
            </div>
            <div class="no-print">
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Report
                </button>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="py-4 px-3 border border-gray-200 font-bold text-gray-700">Account Number</th>
                    <th class="py-4 px-3 border border-gray-200 font-bold text-gray-700">Customer Name</th>
                    <th class="py-4 px-3 border border-gray-200 font-bold text-gray-700">Type</th>
                    <th class="py-4 px-3 border border-gray-200 font-bold text-gray-700">Barangay</th>
                    <th class="py-4 px-3 border border-gray-200 font-bold text-gray-700 text-center">Present Reading (m³)</th>
                    <th class="py-4 px-3 border border-gray-200 font-bold text-gray-700">Status</th>
                    <th class="py-4 px-3 border border-gray-200 font-bold text-gray-700">Registry Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr class="odd:bg-white even:bg-gray-50/50">
                        <td class="px-3 py-3 border border-gray-200 font-mono font-bold">{{ $customer->customer_id }}</td>
                        <td class="px-3 py-3 border border-gray-200 uppercase font-medium">{{ $customer->name }}</td>
                        <td class="px-3 py-3 border border-gray-200 text-center">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $customer->type === 'Commercial' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $customer->type }}
                            </span>
                        </td>
                        <td class="px-3 py-3 border border-gray-200">{{ $customer->barangay }}</td>
                        <td class="px-3 py-3 border border-gray-200 text-center font-bold text-blue-600">
                            {{ number_format($customer->meter_reading ?? 0, 1) }}
                        </td>
                        <td class="px-3 py-3 border border-gray-200 text-center">
                             <span class="font-bold text-[11px] {{ $customer->status === 'active' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ strtoupper($customer->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-3 border border-gray-200 text-gray-500">{{ $customer->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-10 text-center text-gray-400 italic">No customer records found for this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer -->
        <div class="mt-12 pt-8 border-t border-gray-100 text-xs text-center text-gray-400 italic">
            This document serves as a physical record of the customer database. Keep in a secure location.
            <br>
            © {{ date('Y') }} Dolores Water System Solutions
        </div>
    </div>
</body>
</html>
