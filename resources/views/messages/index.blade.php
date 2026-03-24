<x-layouts::app title="Message & Posting">
    <div class="h-screen max-h-screen pt-4 pb-4 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto flex flex-col font-sans bg-gray-50/50">
        <!-- Header Section -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between mt-2">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 font-sans">Message & Announcements</h1>
                <p class="mt-1 text-sm text-gray-500 font-medium tracking-wide">Stay connected with your consumers and broadcast global updates.</p>
            </div>
            @if(session('success'))
                <div class="mt-4 md:mt-0 flex items-center bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded-lg shadow-sm animate-fade-in-down">
                    <svg class="h-5 w-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mt-4 md:mt-0 flex items-center bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg shadow-sm animate-fade-in-down">
                    <svg class="h-5 w-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Main Chat App Canvas -->
        <div class="bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-gray-100 rounded-2xl flex-1 flex overflow-hidden border border-gray-100 min-h-[500px]">
            
            <!-- Sidebar / Conversations List -->
            <div id="users_list" class="w-full md:w-[340px] border-r border-gray-100 flex flex-col bg-white overflow-hidden transition-all duration-300 md:static absolute inset-0 z-20">
                <!-- Sidebar Header -->
                <div class="p-5 border-b border-gray-100 bg-white shadow-sm z-10">
                    <div class="relative">
                        <input type="text" id="search_users" placeholder="Search conversations..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 shadow-inner text-gray-800 transition-all font-medium">
                        <svg class="w-5 h-5 absolute left-3.5 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- Users List -->
                <ul class="flex-1 overflow-y-auto divide-y divide-gray-50/50 scrollbar-hide">
                    <!-- Global Announcement -->
                    <li data-user-id="post" class="user-list-item p-4 hover:bg-slate-50 cursor-pointer transition-colors duration-200 border-l-4 border-transparent hover:border-blue-500 group" onclick="selectConversation('post', 'Global Announcements', 'Broadcast new updates to all users')">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[15px] font-bold text-gray-900 truncate tracking-tight">Post Announcement</p>
                                <p class="text-xs text-indigo-600 font-medium mt-0.5 truncate">Send news to all users</p>
                            </div>
                        </div>
                    </li>

                    @forelse($users as $u)
                        <li data-user-id="{{ $u->id }}" class="user-list-item p-4 hover:bg-slate-50 cursor-pointer transition-colors duration-200 border-l-4 border-transparent hover:border-gray-300 group" onclick="selectConversation('{{ $u->id }}', '{{ addslashes($u->name) }}', 'Customer ID: {{ $u->customer_id ?? $u->id }}')">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 w-full">
                                    <div class="relative flex-shrink-0">
                                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 border border-gray-200 flex items-center justify-center text-gray-600 font-bold text-lg shadow-sm group-hover:scale-105 transition-transform">
                                            {{ substr($u->name, 0, 1) }}
                                        </div>
                                        @if($u->unread_count > 0)
                                            <span id="unread_indicator_{{ $u->id }}" class="absolute -top-1 -right-1 block h-4 w-4 rounded-full bg-red-500 border-2 border-white shadow-sm"></span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0 pr-2">
                                        <div class="flex justify-between items-baseline mb-0.5">
                                            <p class="text-[15px] truncate tracking-tight {{ $u->unread_count > 0 ? 'text-gray-900 font-bold' : 'text-gray-800 font-semibold' }}">{{ $u->name }}</p>
                                        </div>
                                        <p class="text-xs truncate {{ $u->unread_count > 0 ? 'text-gray-800 font-semibold' : 'text-gray-500 font-medium' }}">ID: {{ $u->customer_id ?? $u->id }}</p>
                                    </div>
                                    @if($u->unread_count > 0)
                                        <div class="flex-shrink-0">
                                            <span id="unread_badge_{{ $u->id }}" class="bg-red-500 text-white text-[11px] font-bold px-2 py-1 rounded-full shadow-sm">{{ $u->unread_count }} new</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <div class="flex flex-col items-center justify-center h-32 px-4 text-center">
                            <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <p class="text-sm text-gray-500 font-medium">No consumers found</p>
                        </div>
                    @endforelse
                </ul>
            </div>

            <!-- Content Area (Chat/Post) -->
            <div id="chat_area" class="flex-1 flex flex-col bg-[#f8fafc] hidden md:flex relative w-full h-full">
                <!-- Top Navbar for Active Chat -->
                <div class="h-[76px] px-6 border-b border-gray-100 bg-white shadow-sm flex items-center justify-between flex-shrink-0 z-10 w-full">
                    <div class="flex items-center space-x-4">
                        <button type="button" class="md:hidden text-gray-400 hover:text-gray-600 transition-colors p-2 -ml-2 rounded-lg hover:bg-gray-50" onclick="showUsersList()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <div>
                            <h2 class="text-[17px] font-bold text-gray-900 tracking-tight" id="chat_with">Global Announcements</h2>
                            <p class="text-[13px] text-gray-500 font-medium mt-0.5" id="chat_with_sub">Broadcast new updates to all users</p>
                        </div>
                    </div>
                </div>
                
                <!-- Chat Messages Scroll Area -->
                <div class="flex-1 overflow-y-auto px-4 py-6 scroll-smooth" id="messages_container" style="display: none;">
                    <div class="space-y-6 max-w-4xl mx-auto flex flex-col justify-end" id="chat_messages_wrapper">
                        @foreach($messages as $msg)
                            <div class="message-item w-full flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}" 
                                 data-partner="{{ $msg->sender_id === auth()->id() ? $msg->receiver_id : $msg->sender_id }}"
                                 style="display: none;">
                                @if($msg->sender_id !== auth()->id())
                                <div class="flex-shrink-0 mr-3 mt-auto mb-1">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-gray-600 text-xs font-bold border border-white shadow-sm">
                                        {{ substr($msg->sender->name ?? 'U', 0, 1) }}
                                    </div>
                                </div>
                                @endif

                                <div class="max-w-[75%] lg:max-w-[60%] flex flex-col {{ $msg->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                                    <div class="px-5 py-3.5 shadow-sm {{ $msg->sender_id === auth()->id() ? 'bg-[#007AFF] text-white rounded-2xl rounded-tr-sm' : 'bg-white border border-gray-100/80 text-gray-800 rounded-2xl rounded-tl-sm shadow-[0_2px_10px_rgb(0,0,0,0.02)]' }}">
                                        <p class="text-[15px] leading-relaxed break-words font-medium">{{ $msg->message }}</p>
                                    </div>
                                    <div class="flex items-center mt-1.5 px-1 space-x-1.5">
                                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">{{ $msg->created_at->format('M d, H:i') }}</p>
                                        @if($msg->sender_id === auth()->id())
                                            @if($msg->read_at)
                                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Chat Input Area -->
                <div class="p-4 bg-white border-t border-gray-100 shadow-[0_-4px_20px_rgb(0,0,0,0.02)] z-10 w-full" id="chat_form_container" style="display: none;">
                    <form action="{{ route('messages.store') }}" method="POST" class="max-w-4xl mx-auto flex items-end space-x-3">
                        @csrf
                        <input type="hidden" name="receiver_id" id="receiver_id" value="">
                        <div class="flex-1 relative bg-gray-50/80 rounded-2xl border border-gray-200 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all shadow-inner">
                            <textarea name="message" id="message_input" rows="1" class="w-full bg-transparent border-0 focus:ring-0 resize-none py-3.5 px-5 text-[15px] font-medium text-gray-800 placeholder-gray-400 max-h-32 scrollbar-hide" placeholder="Type a message..." required oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"></textarea>
                        </div>
                        <button type="submit" class="flex-shrink-0 h-12 w-12 rounded-full bg-[#007AFF] hover:bg-blue-600 hover:scale-105 hover:shadow-lg hover:shadow-blue-500/30 text-white flex items-center justify-center transition-all duration-200 border border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mb-1">
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>

                <!-- Announcements Composer & Feed -->
                <div class="flex-1 overflow-y-auto w-full bg-[#f8fafc]" id="announcements_container">
                    <div class="max-w-4xl mx-auto p-6 md:p-8 space-y-8">
                        <!-- Composer -->
                        <div class="bg-white rounded-3xl p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center tracking-tight">
                                <svg class="w-6 h-6 mr-2.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                Create Announcement
                            </h3>
                            
                            <form action="{{ route('messages.storePost') }}" method="POST">
                                @csrf
                                <div class="space-y-5">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1.5 uppercase tracking-wider text-[11px]">Title</label>
                                        <input type="text" name="title" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[15px] font-medium text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-inner" placeholder="E.g. Scheduled Water Interruption">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1.5 uppercase tracking-wider text-[11px]">Detailed Message</label>
                                        <textarea name="content" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[15px] font-medium text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-inner resize-none" placeholder="Provide full details of your announcement..." required></textarea>
                                    </div>
                                    <div class="pt-2 flex justify-end">
                                        <button type="submit" class="bg-gray-900 hover:bg-black text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:shadow-lg transition-all focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 flex items-center text-[15px]">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                            Publish Post
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Posts Feed -->
                        <div class="pl-2">
                            <h3 class="text-sm font-bold text-gray-400 tracking-widest uppercase mb-6 flex items-center">
                                <span class="mr-3">Previous Broadcasts</span>
                                <div class="h-px bg-gray-200 flex-1"></div>
                            </h3>
                            
                            <div class="space-y-6">
                                @forelse($posts as $post)
                                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow relative overflow-hidden group">
                                        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500 opacity-80"></div>
                                        <div class="flex justify-between items-start mb-3">
                                            @if($post->title)
                                                <h4 class="text-[17px] font-bold text-gray-900 tracking-tight">{{ $post->title }}</h4>
                                            @else
                                                <h4 class="text-[17px] font-bold text-gray-500 italic">No Title</h4>
                                            @endif
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 tracking-wide">
                                                {{ $post->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-[15px] text-gray-700 leading-relaxed font-medium bg-gray-50 rounded-xl p-4">{{ $post->content }}</p>
                                        <p class="text-[11px] font-bold text-gray-400 tracking-wider uppercase mt-4">{{ $post->created_at->format('M d, Y • h:i A') }}</p>
                                    </div>
                                @empty
                                    <div class="bg-white rounded-2xl p-10 shadow-sm border border-gray-100 text-center border-dashed">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <p class="text-[15px] text-gray-500 font-medium">No announcements published yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        #messages_container::-webkit-scrollbar {
            width: 6px;
        }
        #messages_container::-webkit-scrollbar-track {
            background: transparent;
        }
        #messages_container::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 10px;
        }
    </style>

    <script>
        function selectConversation(partnerId, title, sub) {
            // Update active state in list
            document.querySelectorAll('.user-list-item').forEach(el => {
                el.classList.remove('bg-blue-50/60', 'border-blue-500');
                el.classList.add('border-transparent');
            });
            const activeEl = document.querySelector(`li[data-user-id="${partnerId}"]`);
            if (activeEl) {
                activeEl.classList.add('bg-blue-50/60', 'border-blue-500');
                activeEl.classList.remove('border-transparent');
            }

            document.getElementById('chat_with').innerText = title;
            document.getElementById('chat_with_sub').innerText = sub;

            if (partnerId === 'post') {
                document.getElementById('messages_container').style.display = 'none';
                document.getElementById('chat_form_container').style.display = 'none';
                document.getElementById('announcements_container').style.display = 'block';
            } else {
                document.getElementById('announcements_container').style.display = 'none';
                document.getElementById('messages_container').style.display = 'flex';
                document.getElementById('chat_form_container').style.display = 'block';
                document.getElementById('receiver_id').value = partnerId;

                // Show only related messages
                let hasMessages = false;
                document.querySelectorAll('.message-item').forEach(el => {
                    if (el.dataset.partner === partnerId) {
                        el.style.display = 'flex';
                        hasMessages = true;
                    } else {
                        el.style.display = 'none';
                    }
                });

                // Scroll to bottom with slight delay for render
                setTimeout(() => {
                    const container = document.getElementById('messages_container');
                    container.scrollTop = container.scrollHeight;
                    // Focus input
                    const input = document.getElementById('message_input');
                    if(window.innerWidth > 768) {
                        input.focus();
                    }
                }, 10);

                // Mark as read via AJAX
                fetch('{{ route("messages.markRead") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ partner_id: partnerId })
                }).then(res => res.json()).then(data => {
                    if(data.success) {
                        const indicator = document.getElementById('unread_indicator_' + partnerId);
                        if (indicator) indicator.remove();
                        const badge = document.getElementById('unread_badge_' + partnerId);
                        if (badge) badge.remove();
                        // Also make name normal
                        const row = document.querySelector(`li[data-user-id="${partnerId}"]`);
                        if(row) {
                            const nameEl = row.querySelector('.text-gray-900.font-bold');
                            if(nameEl) {
                                nameEl.classList.remove('font-bold', 'text-gray-900');
                                nameEl.classList.add('font-semibold', 'text-gray-800');
                            }
                        }
                    }
                });
            }

            // Mobile layout swap
            if(window.innerWidth < 768) {
                document.getElementById('users_list').classList.add('hidden');
                document.getElementById('users_list').classList.remove('absolute', 'inset-0');
                document.getElementById('chat_area').classList.remove('hidden');
                document.getElementById('chat_area').classList.add('flex', 'absolute', 'inset-0');
            }
        }

        function showUsersList() {
            document.getElementById('users_list').classList.remove('hidden');
            document.getElementById('users_list').classList.add('absolute', 'inset-0');
            document.getElementById('chat_area').classList.add('hidden');
            document.getElementById('chat_area').classList.remove('flex', 'absolute', 'inset-0');
        }

        // Search logic
        document.getElementById('search_users').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            document.querySelectorAll('.user-list-item').forEach(el => {
                const partnerId = el.dataset.userId;
                if (partnerId === 'post') return; // keep announcement mostly visible or hide if search

                const text = el.innerText.toLowerCase();
                if (text.includes(search)) {
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            @if(request()->has('select_user'))
                const userId = '{{ request('select_user') }}';
                const userElement = document.querySelector(`li[data-user-id="${userId}"]`);
                if (userElement) {
                    userElement.click();
                } else {
                    selectConversation('post', 'Global Announcements', 'Broadcast new updates to all users');
                }
            @else
                selectConversation('post', 'Global Announcements', 'Broadcast new updates to all users');
            @endif
        });
    </script>
</x-layouts::app>
