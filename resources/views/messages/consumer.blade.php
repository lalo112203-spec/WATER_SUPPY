<x-layouts::app title="Message & Posting">
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 min-h-[calc(100vh-4rem)]">
    <div class="h-full flex flex-col py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto font-sans">
        
        <!-- Header -->
        <div class="mb-8 px-2">
            <h1 class="text-[28px] font-bold text-gray-900 tracking-tight flex items-center justify-between">
                <div>
                    Welcome back, <span class="text-blue-600">{{ auth()->user()->name }}</span>
                </div>
                <!-- Optional Header Action -->
            </h1>
            <p class="mt-2 text-[15px] text-gray-500 font-medium">View your billing history, latest announcements, and message the admin.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 mx-2 animate-fade-in-down flex items-center bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl shadow-sm">
                <svg class="h-6 w-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[15px] font-semibold">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 mx-2 animate-fade-in-down flex items-center bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl shadow-sm">
                <svg class="h-6 w-6 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[15px] font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Announcements Section -->
        @if(isset($posts) && count($posts) > 0)
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-1 mb-10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] transform transition-transform duration-300 mx-2 relative overflow-hidden group">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="bg-white/95 backdrop-blur-sm rounded-[22px] p-6 lg:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center tracking-tight">
                    <span class="bg-blue-100 text-blue-600 p-2 rounded-xl mr-4 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </span>
                    System Announcements
                </h2>
                <div class="space-y-4 max-h-72 overflow-y-auto pr-3 custom-scrollbar">
                    @foreach($posts as $post)
                        <div class="bg-gray-50/80 hover:bg-white border border-gray-100 hover:border-blue-100 hover:shadow-md hover:shadow-blue-500/5 transition-all duration-300 p-5 rounded-2xl">
                            @if($post->title)
                                <h3 class="font-extrabold text-gray-900 text-lg mb-2 tracking-tight">{{ $post->title }}</h3>
                            @endif
                            <p class="text-gray-700 text-[15px] leading-relaxed mb-4">{{ $post->content }}</p>
                            <div class="flex items-center text-gray-400 text-[12px] font-bold uppercase tracking-wider space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ $post->created_at->format('M d, Y • h:i A') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] xl:grid-cols-[1fr_480px] gap-8 flex-1 mb-8 mx-2 items-start">
            
            <!-- Payment History Section -->
            <div class="bg-white shadow-[0_4px_20px_rgb(0,0,0,0.03)] rounded-3xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Billing Overview
                    </h2>
                </div>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-full">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-6 py-4 text-[12px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">Date</th>
                                <th class="px-6 py-4 text-[12px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">Usage</th>
                                <th class="px-6 py-4 text-[12px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">Total</th>
                                <th class="px-6 py-4 text-[12px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100">Status</th>
                                <th class="px-6 py-4 text-[12px] font-bold text-gray-500 uppercase tracking-widest border-b border-gray-100 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @if(isset($customer) && $customer->bills->count() > 0)
                                @foreach($customer->bills as $bill)
                                    <tr class="hover:bg-blue-50/30 transition-colors">
                                        <td class="px-6 py-5 whitespace-nowrap text-[15px] font-semibold text-gray-800 tracking-tight">{{ \Carbon\Carbon::parse($bill->billing_date)->format('M d, Y') }}</td>
                                        <td class="px-6 py-5 whitespace-nowrap text-[15px] font-medium text-gray-500">{{ $bill->usage_units }} <span class="text-xs">L</span></td>
                                        <td class="px-6 py-5 whitespace-nowrap text-[15px] font-bold text-gray-900">₱{{ number_format($bill->total_amount, 2) }}</td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            @if(strtolower($bill->status) === 'paid')
                                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-green-100 text-green-700 shadow-sm border border-green-200 uppercase tracking-wide">Paid</span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full bg-red-100 text-red-700 shadow-sm border border-red-200 uppercase tracking-wide">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-right">
                                            @if(strtolower($bill->status) !== 'paid')
                                                <span class="text-red-500 font-bold text-xs uppercase tracking-wide border-2 border-red-100 bg-red-50 rounded-lg px-3 py-1.5 inline-block text-center shadow-sm">Not Paid</span>
                                            @else
                                                <a href="{{ route('billing.receipt', $bill->id) }}" class="text-green-700 font-bold text-xs uppercase tracking-wide border-2 border-green-200 bg-green-50 hover:bg-green-100 hover:border-green-300 rounded-lg px-3 py-1.5 inline-flex items-center text-center shadow-sm transition-all ml-auto w-max">
                                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Receipt
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3 border border-gray-100">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-[15px] font-medium text-gray-500">No billing records found.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modern Messaging Interface -->
            <div class="bg-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl flex flex-col overflow-hidden border border-gray-100 h-[600px] relative mt-8 lg:mt-0">
                <!-- Chat Header -->
                <div class="px-6 py-5 border-b border-gray-100 bg-white/95 backdrop-blur-md flex-shrink-0 flex items-center shadow-sm z-10 relative">
                    <div class="relative flex-shrink-0">
                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-[#007AFF] to-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-500/30">
                            A
                        </div>
                        <span class="absolute -bottom-1 -right-1 block h-4 w-4 rounded-full bg-green-500 border-2 border-white shadow-sm"></span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-bold text-gray-900 tracking-tight leading-tight">Admin Support</h2>
                        <p class="text-[13px] font-semibold text-green-600 tracking-wide">Online</p>
                    </div>
                </div>
                
                <!-- Chat View Area -->
                <div class="flex-1 p-5 overflow-y-auto bg-[#f8fafc] scroll-smooth" id="messages_container">
                    <div class="space-y-5 max-w-full flex-col justify-end min-h-full pb-2">
                        @forelse($messages as $msg)
                            <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="relative group max-w-[85%] lg:max-w-[75%] flex flex-col {{ $msg->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                                    <div class="px-5 py-3.5 shadow-sm {{ $msg->sender_id === auth()->id() ? 'bg-[#007AFF] text-white rounded-3xl rounded-br-sm' : 'bg-white border border-gray-100/80 text-gray-800 rounded-3xl rounded-bl-sm shadow-[0_2px_10px_rgb(0,0,0,0.02)]' }}">
                                        <p class="text-[15px] leading-relaxed break-words font-medium">{{ $msg->message }}</p>
                                    </div>
                                    <div class="flex items-center mt-1.5 px-2 space-x-1">
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">{{ $msg->created_at->format('H:i') }}</p>
                                        @if($msg->sender_id === auth()->id())
                                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full opacity-50 space-y-4">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <div class="text-center font-medium text-blue-900">
                                    <p class="text-[15px]">Need help?</p>
                                    <p class="text-sm opacity-80 mt-1">Send a message to Support.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Chat Composer -->
                <div class="p-4 bg-white border-t border-gray-100 shadow-[0_-10px_30px_rgb(0,0,0,0.02)] z-10 w-full relative">
                    <form action="{{ route('messages.store') }}" method="POST" class="flex items-end space-x-2">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $admin->id ?? '' }}">
                        <div class="flex-1 relative bg-gray-50 rounded-2xl border border-gray-200 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all shadow-inner">
                            <textarea name="message" id="message_input" rows="1" class="w-full bg-transparent border-0 focus:ring-0 resize-none py-3 px-4 text-[15px] font-medium text-gray-800 placeholder-gray-400 max-h-24 scrollbar-hide" placeholder="Type a message..." required oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"></textarea>
                        </div>
                        <button type="submit" class="flex-shrink-0 h-[46px] w-[46px] rounded-full bg-[#007AFF] hover:bg-blue-600 hover:scale-105 hover:shadow-lg hover:shadow-blue-500/30 text-white flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 border border-transparent focus:ring-blue-500 mb-[2px]">
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.4);
        border-radius: 10px;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById('messages_container');
        container.scrollTop = container.scrollHeight;
    });
</script>
</x-layouts::app>
