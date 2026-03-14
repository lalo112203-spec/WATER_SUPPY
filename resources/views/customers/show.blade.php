<x-layouts::app title="Customers">
    <div class="px-6 py-4 bg-[#f8f9fa] min-h-screen font-sans text-gray-700">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Customers</h1>

        <!-- Customer Summary Table -->
        <div class="bg-white rounded shadow-sm overflow-hidden mb-8 border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#42a5f5] text-white">
                        <th class="px-4 py-3 font-medium">No.</th>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Address</th>
                        <th class="px-4 py-3 font-medium">Last Reading</th>
                        <th class="px-4 py-3 font-medium text-right">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50 border-b border-gray-200">
                        <td class="px-4 py-3">{{ $customer->customer_number ?? $customer->id }}</td>
                        <td class="px-4 py-3">{{ $customer->name }}</td>
                        <td class="px-4 py-3">{{ $customer->address }}</td>
                        <td class="px-4 py-3">{{ $customer->waterUsages->last()->current_reading ?? 0 }} L</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('billing.create', ['customer_id' => $customer->id]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded text-sm font-medium inline-flex items-center">
                                Billing <span class="ml-1 text-red-500 font-bold">&#10060;</span>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Reading History -->
            <div class="flex-1">
                <h2 class="text-lg font-semibold mb-3">Reading History for [{{ $customer->customer_number ?? $customer->id }}]</h2>
                <div class="bg-white rounded shadow-sm overflow-hidden border border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#42a5f5] text-white">
                                <th class="px-4 py-3 font-medium">Period</th>
                                <th class="px-4 py-3 font-medium">Reading</th>
                                <th class="px-4 py-3 font-medium">Usage</th>
                                <th class="px-4 py-3 font-medium">Bill</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($customer->bills as $bill)
                            @php
                                $usage = $customer->waterUsages->where('billing_id', $bill->id)->first() ?? $customer->waterUsages->where('reading_date', '<=', $bill->billing_date)->last();
                                $reading = $usage ? $usage->current_reading : 0;
                                $greenMax = $customer->type === 'Commercial' ? $settings['commercial_green_max'] : $settings['regular_green_max'];
                                $orangeMax = $customer->type === 'Commercial' ? $settings['commercial_orange_max'] : $settings['regular_orange_max'];
                                $colorClass = $bill->usage_units <= $greenMax ? 'bg-[#5cb85c]' : ($bill->usage_units <= $orangeMax ? 'bg-[#f0ad4e]' : 'bg-[#d9534f]');
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">{{ $bill->billing_date->format('F Y') }}</td>
                                <td class="px-4 py-3 text-sm">{{ $reading }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="text-white px-2 py-0.5 rounded text-xs {{ $colorClass }}">
                                        {{ $bill->usage_units }} L
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">₱{{ number_format($bill->total_amount, 0) }}</td>
                                <td class="px-4 py-3 text-sm flex items-center justify-between">
                                    <span class="text-gray-600">{{ $bill->status }}</span>
                                    <form action="{{ route('billing.destroy', $bill) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 px-2 py-1 rounded border border-gray-300 text-gray-500 font-bold ml-2">
                                            ×
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">No reading history</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-3">Actions</h2>
                    <div class="space-x-2">
                        <a href="{{ route('customers.create') }}" class="bg-[#5bc0de] hover:bg-[#46b8da] text-white px-4 py-2 rounded text-sm font-medium inline-block">
                            Create New Customer &rarr;
                        </a>

                        @if(!$customer->user)
                            <form action="{{ route('customers.create-account', $customer->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium">
                                    Create Login Account
                                </button>
                            </form>
                        @else
                            <div class="inline-flex flex-col mb-4">
                                <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded text-sm font-medium">
                                    Account Active
                                </span>
                                <span class="text-sm text-gray-500 mt-1">Login Email: {{ $customer->user->email }}</span>
                            </div>
                            
                            <div class="mt-4 p-4 border border-gray-200 rounded bg-[#fcfcfc] max-w-sm">
                                <h3 class="text-sm font-semibold mb-3 text-gray-700">Change Account Password</h3>
                                <form action="{{ route('customers.update-password', $customer->id) }}" method="POST" class="flex flex-col gap-3">
                                    @csrf
                                    <div>
                                        <input type="password" name="password" placeholder="New Password" required minlength="8" class="w-full px-3 py-2 border border-gray-300 rounded outline-none focus:border-[#42a5f5] text-sm hidden" id="change_pwd_input_{{ $customer->id }}">
                                    </div>
                                    <div>
                                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required minlength="8" class="w-full px-3 py-2 border border-gray-300 rounded outline-none focus:border-[#42a5f5] text-sm hidden" id="change_pwd_confirm_{{ $customer->id }}">
                                    </div>
                                    
                                    <div>
                                        <button type="button" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded text-sm font-medium shadow-sm transition-colors" onclick="togglePwdForm({{ $customer->id }})" id="change_pwd_btn_{{ $customer->id }}">
                                            Change Password
                                        </button>
                                        <button type="submit" class="bg-[#5cb85c] hover:bg-[#4cae4c] text-white px-3 py-1.5 rounded text-sm font-medium shadow-sm transition-colors hidden" id="save_pwd_btn_{{ $customer->id }}">
                                            Save Password
                                        </button>
                                        <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded text-sm font-medium shadow-sm transition-colors hidden ml-2" onclick="cancelPwdForm({{ $customer->id }})" id="cancel_pwd_btn_{{ $customer->id }}">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <script>
                    function togglePwdForm(id) {
                        document.getElementById('change_pwd_input_' + id).classList.remove('hidden');
                        document.getElementById('change_pwd_confirm_' + id).classList.remove('hidden');
                        document.getElementById('save_pwd_btn_' + id).classList.remove('hidden');
                        document.getElementById('cancel_pwd_btn_' + id).classList.remove('hidden');
                        document.getElementById('change_pwd_btn_' + id).classList.add('hidden');
                    }
                    function cancelPwdForm(id) {
                        document.getElementById('change_pwd_input_' + id).classList.add('hidden');
                        document.getElementById('change_pwd_confirm_' + id).classList.add('hidden');
                        document.getElementById('save_pwd_btn_' + id).classList.add('hidden');
                        document.getElementById('cancel_pwd_btn_' + id).classList.add('hidden');
                        document.getElementById('change_pwd_btn_' + id).classList.remove('hidden');
                        document.getElementById('change_pwd_input_' + id).value = '';
                        document.getElementById('change_pwd_confirm_' + id).value = '';
                    }
                </script>
            </div>

            <!-- New Entry Form -->
            <div class="w-full md:w-80">
                <h2 class="text-lg font-semibold mb-3">New Entry for {{ date('F Y') }}</h2>
                <div class="bg-[#f8f9fa] p-4">
                    <form action="{{ route('billing.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        
                        <div class="mb-3">
                            <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded bg-white focus:outline-none focus:border-blue-400">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <input type="text" name="year" value="{{ date('Y') }}" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-400" placeholder="Year">
                        </div>
                        
                        <div class="mb-4">
                            <input type="number" name="current_reading" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-400 text-gray-400" placeholder="New Reading">
                        </div>
                        
                        <button type="submit" class="w-full bg-[#5cb85c] hover:bg-[#4cae4c] text-white px-4 py-2 rounded font-medium">
                            Generate Bill
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
