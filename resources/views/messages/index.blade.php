<x-layouts::app title="Message & Posting">
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 h-[calc(100vh-4rem)]">
    <div class="h-full flex flex-col pt-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Message & Posting (Admin)</h1>
            <p class="mt-1 text-sm text-gray-500">Communicate with consumers and post global announcements.</p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg flex-1 mb-8 overflow-hidden flex flex-col md:flex-row relative">
            <!-- Navigation / Users List -->
            <div id="users_list" class="w-full md:w-1/3 border-r border-gray-200 overflow-y-auto block absolute md:static inset-0 z-10 bg-white md:bg-transparent">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-900">Conversations & Posts</h2>
                </div>
                <ul class="divide-y divide-gray-200">
                    <!-- Global Announcement Item -->
                    <li data-user-id="post" class="p-4 hover:bg-gray-100 cursor-pointer bg-blue-50" onclick="document.getElementById('chat_with').innerText = 'Global Announcement (Post)'; filterMessages('post')">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 relative">
                                <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-blue-900">Post Announcement</p>
                                <p class="text-xs text-blue-700">Send news to all users</p>
                            </div>
                        </div>
                    </li>

                    @forelse($users as $u)
                        <li data-user-id="{{ $u->id }}" class="p-4 hover:bg-gray-50 cursor-pointer" onclick="document.getElementById('receiver_id').value = '{{ $u->id }}'; document.getElementById('chat_with').innerText = '{{ addslashes($u->name) }}'; filterMessages('{{ $u->id }}')">
                            <div class="flex items-center space-x-3 justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 relative">
                                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold">
                                            {{ substr($u->name, 0, 1) }}
                                        </div>
                                        @if($u->unread_count > 0)
                                            <span id="unread_indicator_{{ $u->id }}" class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-green-500 ring-2 ring-white"></span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium {{ $u->unread_count > 0 ? 'text-gray-900 font-bold' : 'text-gray-900' }}">{{ $u->name }}</p>
                                        <p class="text-xs {{ $u->unread_count > 0 ? 'text-gray-800 font-semibold' : 'text-gray-500' }}">ID: {{ $u->customer_id ?? $u->id }}</p>
                                    </div>
                                </div>
                                @if($u->unread_count > 0)
                                    <div>
                                        <span id="unread_badge_{{ $u->id }}" class="bg-green-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $u->unread_count }}</span>
                                    </div>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="p-4 text-sm text-gray-500">No consumers found.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Content Area -->
            <div id="chat_area" class="w-full md:w-2/3 flex-col h-full bg-gray-50 hidden flex">
                <div class="p-4 border-b border-gray-200 bg-white shadow-sm flex-shrink-0 flex items-center">
                    <button type="button" class="md:hidden mr-3 text-gray-500 hover:text-gray-700" onclick="showUsersList()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <h2 class="text-lg font-medium text-gray-900" id="chat_with">Post Announcement</h2>
                </div>
                
                <!-- Messages Container -->
                <div class="flex-1 p-4 overflow-y-auto" id="messages_container" style="display: none;">
                    @foreach($messages as $msg)
                        <div class="message-item mb-4 flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}" 
                             data-partner="{{ $msg->sender_id === auth()->id() ? $msg->receiver_id : $msg->sender_id }}"
                             style="display: none;">
                            <div class="max-w-xs px-4 py-2 rounded-lg {{ $msg->sender_id === auth()->id() ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white border border-gray-200 text-gray-900 rounded-bl-none' }}">
                                <p class="text-sm">{{ $msg->message }}</p>
                                <p class="text-[10px] mt-1 {{ $msg->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-400' }}">{{ $msg->created_at->format('M d, H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Chat Input Form -->
                <div class="p-4 bg-white border-t border-gray-200 flex-shrink-0" id="chat_form_container" style="display: none;">
                    <form action="{{ route('messages.store') }}" method="POST" class="flex space-x-2">
                        @csrf
                        <input type="hidden" name="receiver_id" id="receiver_id" value="">
                        <input type="text" name="message" class="flex-1 focus:ring-blue-500 focus:border-blue-500 block w-full rounded-md sm:text-sm border-gray-300 px-4 py-2 border" placeholder="Type a message..." required>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Send
                        </button>
                    </form>
                </div>

                <!-- Announcements Panel -->
                <div class="flex-1 p-6 overflow-y-auto bg-white" id="announcements_container">
                    <div class="mb-8 p-6 bg-blue-50 rounded-lg border border-blue-100">
                        <h3 class="text-lg font-bold text-blue-900 mb-4">Create New Announcement</h3>
                        <form action="{{ route('messages.storePost') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title (Optional)</label>
                                <input type="text" name="title" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border p-2">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                                <textarea name="content" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border p-2" required></textarea>
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Publish Post</button>
                        </form>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Previous Posts</h3>
                    <div class="space-y-4">
                        @forelse($posts as $post)
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                @if($post->title)
                                    <h4 class="font-bold text-gray-900">{{ $post->title }}</h4>
                                @endif
                                <p class="text-sm text-gray-800 mt-2">{{ $post->content }}</p>
                                <p class="text-xs text-gray-500 mt-2">Posted on {{ $post->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No posts have been made yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<script>
    function filterMessages(partnerId) {
        if (partnerId === 'post') {
            document.getElementById('messages_container').style.display = 'none';
            document.getElementById('chat_form_container').style.display = 'none';
            document.getElementById('announcements_container').style.display = 'block';
        } else {
            document.getElementById('announcements_container').style.display = 'none';
            document.getElementById('messages_container').style.display = 'block';
            document.getElementById('chat_form_container').style.display = 'block';

            document.querySelectorAll('.message-item').forEach(el => {
                if (el.dataset.partner === partnerId) {
                    el.style.display = 'flex';
                } else {
                    el.style.display = 'none';
                }
            });
            const container = document.getElementById('messages_container');
            container.scrollTop = container.scrollHeight;

            // Mark as read
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
                }
            });
        }

        // Mobile layout swap
        if(window.innerWidth < 768) {
            document.getElementById('users_list').classList.add('hidden');
            document.getElementById('users_list').classList.remove('block');
            document.getElementById('chat_area').classList.remove('hidden');
            document.getElementById('chat_area').classList.add('flex');
        }
    }

    function showUsersList() {
        document.getElementById('users_list').classList.remove('hidden');
        document.getElementById('users_list').classList.add('block');
        document.getElementById('chat_area').classList.add('hidden');
        document.getElementById('chat_area').classList.remove('flex');
    }

    document.addEventListener("DOMContentLoaded", function() {
        @if(request()->has('select_user'))
            const userId = '{{ request('select_user') }}';
            const userElement = document.querySelector(`li[data-user-id="${userId}"]`);
            if (userElement) {
                userElement.click();
            }
        @else
            // Open post by default
            filterMessages('post');
        @endif
    });
</script>
</x-layouts::app>
