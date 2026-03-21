<x-layouts::app title="Messages">
<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 h-[calc(100vh-4rem)]">
    <div class="h-full flex flex-col pt-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Messages (Admin)</h1>
            <p class="mt-1 text-sm text-gray-500">Communicate with consumers</p>
        </div>

        <div class="bg-white shadow rounded-lg flex-1 mb-8 overflow-hidden flex flex-col md:flex-row relative">
            <!-- Consumers List -->
            <div id="users_list" class="w-full md:w-1/3 border-r border-gray-200 overflow-y-auto block absolute md:static inset-0 z-10 bg-white md:bg-transparent">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Consumers</h2>
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($users as $u)
                        <li data-user-id="{{ $u->id }}" class="p-4 hover:bg-gray-50 cursor-pointer" onclick="document.getElementById('receiver_id').value = '{{ $u->id }}'; document.getElementById('chat_with').innerText = '{{ addslashes($u->name) }}'; filterMessages('{{ $u->id }}')">
                            <div class="flex items-center space-x-3 justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 relative">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
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

            <!-- Chat Area -->
            <div id="chat_area" class="w-full md:w-2/3 flex-col h-full bg-gray-50 hidden md:flex">
                <div class="p-4 border-b border-gray-200 bg-white shadow-sm flex-shrink-0 flex items-center">
                    <button type="button" class="md:hidden mr-3 text-gray-500 hover:text-gray-700" onclick="showUsersList()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <h2 class="text-lg font-medium text-gray-900" id="chat_with">Select a consumer</h2>
                </div>
                
                <div class="flex-1 p-4 overflow-y-auto" id="messages_container">
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

                <div class="p-4 bg-white border-t border-gray-200 flex-shrink-0">
                    <form action="{{ route('messages.store') }}" method="POST" class="flex space-x-2">
                        @csrf
                        <input type="hidden" name="receiver_id" id="receiver_id" value="">
                        <input type="text" name="message" class="flex-1 focus:ring-blue-500 focus:border-blue-500 block w-full rounded-md sm:text-sm border-gray-300 px-4 py-2 border" placeholder="Type a message..." required>
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
    function filterMessages(partnerId) {
        document.querySelectorAll('.message-item').forEach(el => {
            if (el.dataset.partner === partnerId) {
                el.style.display = 'flex';
            } else {
                el.style.display = 'none';
            }
        });
        const container = document.getElementById('messages_container');
        container.scrollTop = container.scrollHeight;

        // Mobile layout swap
        if(window.innerWidth < 768) {
            document.getElementById('users_list').classList.add('hidden');
            document.getElementById('users_list').classList.remove('block');
            document.getElementById('chat_area').classList.remove('hidden');
            document.getElementById('chat_area').classList.add('flex');
        }

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
        @endif
    });
</script>
</x-layouts::app>
