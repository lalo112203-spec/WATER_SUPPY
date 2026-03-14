<x-layouts::app title="Register New Customer">
    <div class="px-6 py-4 bg-[#f8f9fa] min-h-screen font-sans text-gray-700">
        
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Register New Customer</h1>
            <a href="{{ route('customers.index') }}" class="text-[#337ab7] hover:underline">
                &larr; Back to Customers
            </a>
        </div>

        <div class="bg-white rounded shadow-sm overflow-hidden border border-gray-200 max-w-3xl">
            <form method="POST" action="{{ route('customers.store') }}" class="p-6">
                @csrf
                
                <h3 class="text-[#337ab7] font-semibold text-base mb-6 border-b border-gray-100 pb-2">Customer Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-600 mb-1 text-sm font-medium">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-[#42a5f5] text-gray-700 bg-[#fefefe] shadow-sm">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-600 mb-1 text-sm font-medium">Customer Type <span class="text-red-500">*</span></label>
                        <select name="type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-[#42a5f5] text-gray-700 bg-[#fefefe] shadow-sm">
                            <option value="">Select Type</option>
                            <option value="Regular" {{ old('type') === 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="Commercial" {{ old('type') === 'Commercial' ? 'selected' : '' }}>Commercial</option>
                        </select>
                        @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-600 mb-1 text-sm font-medium">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-[#42a5f5] text-gray-700 bg-[#fefefe] shadow-sm">
                        @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-600 mb-1 text-sm font-medium">Home/Business Address <span class="text-red-500">*</span></label>
                    <textarea name="address" rows="3" required
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-[#42a5f5] text-gray-700 bg-[#fefefe] shadow-sm">{{ old('address') }}</textarea>
                    @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6 bg-blue-50/50 p-4 border border-blue-100 rounded">
                    <label class="flex items-center space-x-2 text-gray-700 font-medium mb-3 cursor-pointer">
                        <input type="checkbox" name="create_account" id="create_account" value="1" class="rounded text-[#42a5f5] focus:border-[#42a5f5] w-5 h-5" {{ old('create_account') ? 'checked' : '' }}>
                        <span>Also Create Login Account for this Customer</span>
                    </label>
                    <div id="password_field" style="{{ old('create_account') ? 'display: block;' : 'display: none;' }}">
                        <label class="block text-gray-600 mb-1 text-sm font-medium">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password"
                            class="w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-[#42a5f5] text-gray-700 bg-[#fefefe] shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters. The customer can log in using their ID/Email and this password.</p>
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <script>
                    document.getElementById('create_account').addEventListener('change', function() {
                        const passField = document.getElementById('password_field');
                        const passInput = document.getElementById('password');
                        if(this.checked) {
                            passField.style.display = 'block';
                            passInput.required = true;
                        } else {
                            passField.style.display = 'none';
                            passInput.required = false;
                        }
                    });
                </script>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <div class="text-sm text-gray-400 mr-auto self-center">
                        Note: The Customer No. (ID) will be automatically generated.
                    </div>
                    <button type="submit" class="bg-[#5cb85c] hover:bg-[#4cae4c] text-white px-6 py-2 rounded font-medium shadow-sm flex items-center gap-2">
                        <span>Save Customer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
