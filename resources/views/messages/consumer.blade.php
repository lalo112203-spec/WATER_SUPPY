<x-layouts::app title="Message & Posting">
    <div class="flex flex-col py-8 px-4 sm:px-6 lg:px-8 w-full font-sans min-h-[calc(100vh-4rem)]">
        
        <!-- Header -->
        <div class="mb-8 px-2">
            <h1 class="text-[28px] font-bold text-gray-100 tracking-tight flex items-center justify-between">
                <div>
                    Message Support
                </div>
            </h1>
            <p class="mt-2 text-[15px] text-gray-200 font-medium">Send a message to the admin support team.</p>
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

        <div class="flex-1 mb-8 mx-2">
            <!-- Modern Messaging Interface -->
            <div class="bg-[#121a25]/80 backdrop-blur-md shadow-[0_8px_30px_rgb(0,0,0,0.06)] rounded-3xl flex flex-col overflow-hidden border border-[#263548] h-[700px] relative mt-2">
                <!-- Chat Header -->
                <div class="px-6 py-5 border-b border-[#263548] bg-[#121a25]/80 backdrop-blur-md/95 backdrop-blur-md flex-shrink-0 flex items-center shadow-sm z-10 relative">
                    <div class="relative flex-shrink-0">
                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-[#007AFF] to-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-500/30">
                            A
                        </div>
                        <span class="absolute -bottom-1 -right-1 block h-4 w-4 rounded-full bg-green-500 border-2 border-white shadow-sm"></span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-bold text-gray-100 tracking-tight leading-tight">Admin Support</h2>
                        <p class="text-[13px] font-semibold text-green-600 tracking-wide">Online</p>
                    </div>
                </div>
                
                <!-- Chat View Area -->
                <div class="flex-1 p-5 overflow-y-auto bg-[#f8fafc] scroll-smooth relative" id="messages_container" style="@if(auth()->user()->messenger_background) background-image: url('{{ asset('storage/' . auth()->user()->messenger_background) }}'); background-size: cover; background-position: center; @endif">
                    @if(auth()->user()->messenger_background)
                        <div class="absolute inset-0 bg-black/40 pointer-events-none z-0"></div>
                    @endif
                    <div class="space-y-5 flex flex-col justify-end min-h-full pb-2 relative z-10">
                        @forelse($messages as $msg)
                            <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="relative group max-w-[85%] lg:max-w-[75%] flex flex-col {{ $msg->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                                    <div class="px-5 py-3.5 shadow-sm {{ $msg->sender_id === auth()->id() ? 'bg-[#007AFF] text-white rounded-3xl rounded-br-sm' : 'bg-[#121a25]/80 backdrop-blur-md border border-[#263548]/80 text-gray-200 rounded-3xl rounded-bl-sm shadow-[0_2px_10px_rgb(0,0,0,0.02)]' }}">
                                        <p class="text-[15px] leading-relaxed break-words font-medium">{{ $msg->message }}</p>
                                    </div>
                                    <div class="flex items-center mt-1.5 px-2 space-x-1">
                                        <p class="text-[11px] font-bold text-gray-200 uppercase tracking-widest">{{ $msg->created_at->format('H:i') }}</p>
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
                <div class="p-4 bg-[#121a25]/80 backdrop-blur-md border-t border-[#263548] shadow-[0_-10px_30px_rgb(0,0,0,0.02)] z-10 w-full relative">
                    <form action="{{ route('messages.store') }}" method="POST" class="flex items-end space-x-2">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $admin->id ?? '' }}">
                        <div class="flex-1 relative bg-[#0f1722] rounded-2xl border border-[#263548] focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all shadow-inner">
                            <textarea name="message" id="message_input" rows="1" class="w-full bg-transparent border-0 focus:ring-0 resize-none py-3 px-4 text-[15px] font-medium text-gray-200 placeholder-gray-400 max-h-24 scrollbar-hide" placeholder="Type a message..." required oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"></textarea>
                        </div>
                        <button type="submit" class="flex-shrink-0 h-[46px] w-[46px] rounded-full bg-[#007AFF] hover:bg-blue-600 hover:scale-105 hover:shadow-lg hover:shadow-blue-500/30 text-white flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 border border-transparent focus:ring-blue-500 mb-[2px]">
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

