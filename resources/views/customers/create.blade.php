<x-layouts::app title="Register New Customer">
    <div class="px-6 py-4 bg-transparent min-h-screen font-sans text-gray-300">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-200">Register New Customer</h1>
            <a href="{{ route('customers.index') }}" class="text-[#337ab7] hover:underline">
                &larr; Back to Customers
            </a>
        </div>

        <div
            class="bg-[#121a25]/80 backdrop-blur-md rounded shadow-sm overflow-hidden border border-[#263548] max-w-3xl">
            <form method="POST" action="{{ route('customers.store') }}" class="p-6">
                @csrf

                <h3 class="text-[#337ab7] font-semibold text-base mb-6 border-b border-[#263548] pb-2">Customer Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-400 mb-1 text-sm font-medium">Account Number (Leave blank to
                            auto-generate)</label>
                        <input type="text" name="customer_id" value="{{ old('customer_id') }}"
                            placeholder="Auto-generate"
                            class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                        @error('customer_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-400 mb-1 text-sm font-medium">Full Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-400 mb-1 text-sm font-medium">Customer Type <span
                                class="text-red-500">*</span></label>
                        <select name="type" required
                            class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                            <option value="">Select Type</option>
                            <option value="Regular" {{ old('type') === 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="Commercial" {{ old('type') === 'Commercial' ? 'selected' : '' }}>Commercial
                            </option>
                        </select>
                        @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mb-8">
                    <label class="block text-gray-400 mb-1 text-sm font-medium">Barangay <span
                            class="text-red-500">*</span></label>
                    <div class="flex flex-col md:flex-row gap-4 items-start">
                        <div class="w-full md:w-1/2 text-gray-200 bg-[#0f151e]">
                            <input type="text" name="barangay" id="barangay" list="barangays_list"
                                value="{{ old('barangay') }}" required placeholder="e.g. BRGY 1"
                                oninput="updateAddressPreview()"
                                class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                            <datalist id="barangays_list">
                                @foreach($barangays as $brgy)
                                    <option value="{{ $brgy }}">
                                @endforeach
                            </datalist>
                            @error('barangay') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div
                            class="w-full md:w-1/2 bg-[#1a2432]/30 p-2 border border-[#263548] rounded min-h-[42px] flex items-center">
                            <p id="address_preview" class="text-sm text-gray-500 font-mono">
                                <span id="brgy_text" class="text-cyan-400 font-bold">...</span> DOLORES EASTERN SAMAR
                            </p>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-2 font-bold uppercase tracking-widest italic">Dolores Eastern
                        Samar will be automatically added to the address.</p>
                </div>

                <script>
                    function updateAddressPreview() {
                        const brgy = document.getElementById('barangay');
                        const brgyText = document.getElementById('brgy_text');
                        brgyText.textContent = brgy.value || '...';
                        brgyText.className = brgy.value ? 'text-cyan-400 font-bold' : 'text-gray-500';
                    }
                    // Run once on load
                    document.addEventListener('DOMContentLoaded', updateAddressPreview);
                </script>

                <div class="mb-6 bg-[#1a2432]/50 p-4 border border-[#263548] rounded">
                    <label class="flex items-center space-x-3 text-gray-300 font-medium mb-3 cursor-pointer">
                        <input type="checkbox" name="create_account" id="create_account" value="1"
                            class="rounded text-[#42a5f5] focus:ring-[#42a5f5] focus:ring-offset-[#121a25] bg-[#0f151e] border-[#263548] w-5 h-5 cursor-pointer"
                            {{ old('create_account') ? 'checked' : '' }}>
                        <span>Also Create Login Account for this Customer</span>
                    </label>
                    <div id="password_field" style="{{ old('create_account') ? 'display: block;' : 'display: none;' }}">
                        <label class="block text-gray-400 mb-1 text-sm font-medium">Password <span
                                class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password"
                            class="w-full md:w-1/2 px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                        <p class="text-xs text-gray-400 mt-1">Minimum 8 characters. The customer can log in using their
                            Account Number and this password.</p>
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <script>
                    document.getElementById('create_account').addEventListener('change', function () {
                        const passField = document.getElementById('password_field');
                        const passInput = document.getElementById('password');
                        if (this.checked) {
                            passField.style.display = 'block';
                            passInput.required = true;
                        } else {
                            passField.style.display = 'none';
                            passInput.required = false;
                        }
                    });
                </script>

                <div class="flex justify-end pt-4 border-t border-[#263548]">
                    <div class="text-sm text-gray-400 mr-auto self-center">
                        Note: Leave the Account Number blank to auto-generate it.
                    </div>
                    <button type="submit"
                        class="bg-[#5cb85c] hover:bg-[#4cae4c] text-white px-6 py-2 rounded font-medium shadow-sm flex items-center gap-2">
                        <span>Save Customer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>