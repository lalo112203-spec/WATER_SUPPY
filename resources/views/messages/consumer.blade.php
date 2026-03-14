<x-layouts::app title="Messages">
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 h-[calc(100vh-4rem)]">
    <div class="h-full flex flex-col pt-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Welcome, {{ auth()->user()->name }}. View your billing history and contact admin.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 flex-1 mb-8 max-h-[calc(100vh-12rem)]">
            <!-- Payment History Section -->
            <div class="bg-white shadow rounded-lg flex flex-col overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-white shadow-sm flex-shrink-0">
                    <h2 class="text-lg font-medium text-gray-900">Payment History</h2>
                </div>
                <div class="flex-1 overflow-y-auto w-full">
                    <table class="w-full text-left border-collapse min-w-full">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Billing Date</th>
                                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @if(isset($customer) && $customer->bills->count() > 0)
                                @foreach($customer->bills as $bill)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($bill->billing_date)->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $bill->usage_units }} L</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 font-medium">₱{{ number_format($bill->total_amount, 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if(strtolower($bill->status) === 'paid')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            @if(strtolower($bill->status) !== 'paid')
                                                <form action="{{ route('billing.mark-paid', $bill->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900 font-medium text-xs border border-blue-600 rounded px-2 py-1">Pay Now</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-xs">Settled</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 text-sm">No billing records found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Messaging Section -->
            <div class="bg-white shadow rounded-lg flex flex-col overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-white shadow-sm flex-shrink-0 flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                        A
                    </div>
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Message Admin</h2>
                    </div>
                </div>
                
                <div class="flex-1 p-4 overflow-y-auto bg-gray-50" id="messages_container">
                    @forelse($messages as $msg)
                        <div class="mb-4 flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs px-4 py-2 rounded-lg {{ $msg->sender_id === auth()->id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white border border-gray-200 text-gray-900 rounded-bl-none' }}">
                                <p class="text-sm shadow-sm">{{ $msg->message }}</p>
                                <p class="text-[10px] mt-1 {{ $msg->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-400' }}">{{ $msg->created_at->format('M d, H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 text-sm py-4">No messages yet. Say hi!</div>
                    @endforelse
                </div>

                <div class="p-4 bg-white border-t border-gray-200 flex-shrink-0">
                    <form action="{{ route('messages.store') }}" method="POST" class="flex space-x-2">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $admin->id ?? '' }}">
                        <input type="text" name="message" class="flex-1 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full rounded-md sm:text-sm border-gray-300 px-4 py-2 border" placeholder="Type a message..." required>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById('messages_container');
        container.scrollTop = container.scrollHeight;
    });
</script>
</x-layouts::app>
