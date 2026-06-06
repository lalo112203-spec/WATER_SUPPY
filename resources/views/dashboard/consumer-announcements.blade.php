<x-layouts::app title="Announcements">
    <div class="flex flex-col py-4 sm:py-8 px-1 sm:px-6 lg:px-8 w-full font-sans min-h-[calc(100vh-4rem)]">
        
        <!-- Header -->
        <div class="mb-6 sm:mb-8 px-2 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-[24px] sm:text-[28px] font-bold text-gray-100 tracking-tight flex items-center">
                    System Announcements
                </h1>
                <p class="mt-1 sm:mt-2 text-[14px] sm:text-[15px] text-gray-200 font-medium">Read the latest news and updates from the administration.</p>
            </div>
        </div>

        @if(isset($posts) && count($posts) > 0)
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl sm:rounded-3xl p-1 mb-8 shadow-[0_8px_30px_rgb(0,0,0,0.06)] transform transition-transform duration-300 mx-0 sm:mx-2 relative overflow-hidden group flex-1">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="bg-[#121a25]/80 backdrop-blur-md/95 backdrop-blur-sm rounded-[22px] p-4 sm:p-6 lg:p-8 h-full">
                <div class="space-y-4 pr-1 sm:pr-3">
                    @foreach($posts as $post)
                        <div class="bg-[#0f1722]/80 hover:bg-[#121a25]/80 backdrop-blur-md border border-[#263548] hover:border-blue-100 hover:shadow-md hover:shadow-blue-500/5 transition-all duration-300 p-5 rounded-2xl">
                            @if($post->title)
                                <h3 class="font-extrabold text-gray-100 text-lg mb-2 tracking-tight">{{ $post->title }}</h3>
                            @endif
                            <p class="text-gray-300 text-[15px] leading-relaxed mb-4">{{ $post->content }}</p>
                            <div class="flex items-center text-gray-200 text-[12px] font-bold uppercase tracking-wider space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ $post->created_at->format('M d, Y • h:i A') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="flex-1 flex flex-col items-center justify-center p-8 mx-0 sm:mx-2 bg-[#121a25]/80 backdrop-blur-md border border-[#263548] rounded-2xl sm:rounded-3xl shadow-sm text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#0f1722] mb-4 border border-[#263548]">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-100 mb-2">No Announcements</h3>
            <p class="text-gray-300 font-medium">There are currently no system announcements to show.</p>
        </div>
        @endif
    </div>
</x-layouts::app>
