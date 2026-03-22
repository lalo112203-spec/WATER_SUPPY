<x-layouts::app title="Customers">
    <div class="px-6 py-4 bg-[#f8f9fa] min-h-screen font-sans text-gray-700">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Customers</h1>

        <!-- Customer Summary Table -->
        <div class="bg-white rounded shadow-sm overflow-x-auto mb-8 border border-gray-200">
            <table class="w-full text-left border-collapse min-w-max">
                <thead>
                    <tr class="bg-[#42a5f5] text-white">
                        <th class="px-4 py-3 font-medium">No.</th>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Address</th>
                        <th class="px-4 py-3 font-medium">Total Usage</th>
                        <th class="px-4 py-3 font-medium text-right">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50 border-b border-gray-200">
                        <td class="px-4 py-3">{{ $customer->customer_number ?? $customer->id }}</td>
                        <td class="px-4 py-3">{{ $customer->name }}</td>
                        <td class="px-4 py-3">{{ $customer->address }}</td>
                        <td class="px-4 py-3 font-semibold text-[#42a5f5]">{{ number_format($customer->bills->sum('usage_units'), 2) }} L</td>
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
                <div class="bg-white rounded shadow-sm overflow-x-auto border border-gray-200">
                    <table class="w-full text-left border-collapse min-w-max">
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
                            @php
                                $sortedBills = $customer->bills->sortByDesc('billing_date');
                            @endphp
                            @forelse($sortedBills as $bill)
                            @php
                                // Cumulative sum up to this bill
                                $reading = $customer->bills->where('billing_date', '<=', $bill->billing_date)->sum('usage_units');

                                $greenMax = $customer->type === 'Commercial' ? $settings['commercial_green_max'] : $settings['regular_green_max'];
                                $orangeMax = $customer->type === 'Commercial' ? $settings['commercial_orange_max'] : $settings['regular_orange_max'];
                                $colorClass = $bill->usage_units <= $greenMax ? 'bg-[#5cb85c]' : ($bill->usage_units <= $orangeMax ? 'bg-[#f0ad4e]' : 'bg-[#d9534f]');
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">{{ $bill->billing_date->format('F Y') }}</td>
                                <td class="px-4 py-3 text-sm">{{ number_format($reading, 2) }} L</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="text-white px-2 py-0.5 rounded text-xs {{ $colorClass }}">
                                        {{ number_format($bill->usage_units, 2) }} L
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
                            <div class="flex flex-col items-start mb-4 max-w-full">
                                <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded text-sm font-medium">
                                    Account Active
                                </span>
                                <span class="text-sm text-gray-500 mt-1">Login ID: {{ $customer->customer_id ?? $customer->id }}</span>
                                <span class="text-sm text-gray-500 mt-1 break-all">Password: {{ $customer->user->plain_password ?? 'Not Recorded' }}</span>
                            </div>
                            
                            <div class="mb-4">
                                <a href="{{ route('messages.index', ['select_user' => $customer->user->id]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-medium inline-block">
                                    Message Consumer
                                </a>
                            </div>
                            
                            <div class="mt-4 p-4 border border-gray-200 rounded bg-[#fcfcfc] max-w-sm">
                                <h3 class="text-sm font-semibold mb-3 text-gray-700">Change Account Password</h3>
                                <form action="{{ route('customers.update-password', $customer->id) }}" method="POST" class="flex flex-col gap-3">
                                    @csrf
                                    <div class="relative">
                                        <input type="password" name="password" placeholder="New Password" required minlength="8" class="w-full px-3 py-2 border border-gray-300 rounded outline-none focus:border-[#42a5f5] text-sm hidden pr-10" id="change_pwd_input_{{ $customer->id }}">
                                        <button type="button" onclick="togglePassword('change_pwd_input_{{ $customer->id }}')" class="absolute top-2 right-3 text-gray-600 hover:text-gray-800 hidden" id="toggle_change_pwd_input_{{ $customer->id }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required minlength="8" class="w-full px-3 py-2 border border-gray-300 rounded outline-none focus:border-[#42a5f5] text-sm hidden pr-10" id="change_pwd_confirm_{{ $customer->id }}">
                                        <button type="button" onclick="togglePassword('change_pwd_confirm_{{ $customer->id }}')" class="absolute top-2 right-3 text-gray-600 hover:text-gray-800 hidden" id="toggle_change_pwd_confirm_{{ $customer->id }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
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
                        document.getElementById('toggle_change_pwd_input_' + id).classList.remove('hidden');
                        document.getElementById('toggle_change_pwd_confirm_' + id).classList.remove('hidden');
                        document.getElementById('save_pwd_btn_' + id).classList.remove('hidden');
                        document.getElementById('cancel_pwd_btn_' + id).classList.remove('hidden');
                        document.getElementById('change_pwd_btn_' + id).classList.add('hidden');
                    }
                    function cancelPwdForm(id) {
                        document.getElementById('change_pwd_input_' + id).classList.add('hidden');
                        document.getElementById('change_pwd_confirm_' + id).classList.add('hidden');
                        document.getElementById('toggle_change_pwd_input_' + id).classList.add('hidden');
                        document.getElementById('toggle_change_pwd_confirm_' + id).classList.add('hidden');
                        document.getElementById('save_pwd_btn_' + id).classList.add('hidden');
                        document.getElementById('cancel_pwd_btn_' + id).classList.add('hidden');
                        document.getElementById('change_pwd_btn_' + id).classList.remove('hidden');
                        document.getElementById('change_pwd_input_' + id).value = '';
                        document.getElementById('change_pwd_confirm_' + id).value = '';
                    }
                    function togglePassword(id) {
                        const input = document.getElementById(id);
                        const button = document.getElementById('toggle_' + id);
                        if (input.type === 'password') {
                            input.type = 'text';
                            button.innerHTML = `
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            `;
                        } else {
                            input.type = 'password';
                            button.innerHTML = `
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            `;
                        }
                    }
                </script>
            </div>


        </div>
    </div>
</x-layouts::app>
