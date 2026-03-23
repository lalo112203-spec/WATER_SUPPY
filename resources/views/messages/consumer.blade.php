<x-layouts::app title="Message & Posting">
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 h-[calc(100vh-4rem)]">
    <div class="h-full flex flex-col pt-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Message & Posting</h1>
            <p class="mt-1 text-sm text-gray-500">Welcome, {{ auth()->user()->name }}. View your billing history, announcements, and contact admin. <a href="{{ route('user-password.edit') }}" class="text-blue-600 hover:text-blue-700 ml-2">Change Password</a></p>
        </div>

        <!-- Announcements Section -->
        @if(isset($posts) && count($posts) > 0)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-8 shadow-sm">
            <h2 class="text-xl font-bold text-blue-900 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                System Announcements
            </h2>
            <div class="space-y-4 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                @foreach($posts as $post)
                    <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        @if($post->title)
                            <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $post->title }}</h3>
                        @endif
                        <p class="text-gray-700 text-sm leading-relaxed mb-3">{{ $post->content }}</p>
                        <p class="text-[11px] font-semibold text-gray-400 tracking-wide uppercase">Posted on {{ $post->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 flex-1 mb-8 lg:max-h-[calc(100vh-12rem)]">
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
                                                <span class="text-red-500 font-medium text-xs border border-red-500 rounded px-2 py-1 inline-block text-center bg-red-50">Not Paid</span>
                                            @else
                                                <a href="{{ route('billing.receipt', $bill->id) }}" class="text-green-600 hover:text-green-900 font-medium text-xs border border-green-600 hover:bg-green-50 rounded px-2 py-1 inline-block text-center flex items-center gap-1 w-max">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Receipt
                                                </a>
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
