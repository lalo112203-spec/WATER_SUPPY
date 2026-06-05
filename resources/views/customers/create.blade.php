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
                        <label class="block text-gray-200 mb-1 text-sm font-medium">Account Number (Leave blank to
                            auto-generate)</label>
                        <input type="text" name="customer_id" value="{{ old('customer_id') }}"
                            placeholder="Auto-generate"
                            class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                        @error('customer_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-200 mb-1 text-sm font-medium">Full Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-200 mb-1 text-sm font-medium">Customer Type <span
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

                    <div>
                        <label class="block text-gray-200 mb-1 text-sm font-medium">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                            placeholder="e.g. 09123456789"
                            class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm">
                        @error('phone_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mb-8">
                    <label class="block text-gray-200 mb-1 text-sm font-medium">Address <span
                            class="text-red-500">*</span></label>
                    <div class="flex flex-col md:flex-row gap-4 items-start">
                        <div class="w-full md:w-1/3 text-gray-200 bg-[#0f151e]">
                            <input type="text" name="street" id="street"
                                value="{{ old('street') }}" placeholder="Street Name (Optional)"
                                oninput="updateAddressPreview()"
                                class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm uppercase">
                            @error('street') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="w-full md:w-1/3 text-gray-200 bg-[#0f151e]">
                            <input type="text" name="barangay" id="barangay" list="barangays_list"
                                value="{{ old('barangay') }}" required placeholder="Barangay e.g. BRGY 1"
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
                            class="w-full md:w-1/3 bg-[#1a2432]/30 p-2 border border-[#263548] rounded min-h-[42px] flex items-center">
                            <p id="address_preview" class="text-sm text-gray-200 font-mono">
                                <span id="street_text" class="text-cyan-400 font-bold"></span><span id="brgy_text" class="text-cyan-400 font-bold">...</span> DOLORES EASTERN SAMAR
                            </p>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-200 mt-2 font-bold uppercase tracking-widest italic">Dolores Eastern
                        Samar will be automatically added to the address.</p>
                </div>

                <script>
                    function updateAddressPreview() {
                        const street = document.getElementById('street');
                        const brgy = document.getElementById('barangay');
                        const streetText = document.getElementById('street_text');
                        const brgyText = document.getElementById('brgy_text');
                        
                        let streetVal = street.value.trim().toUpperCase();
                        streetText.textContent = streetVal ? streetVal + ', ' : '';
                        
                        brgyText.textContent = brgy.value ? brgy.value.toUpperCase() + ' ' : '... ';
                        brgyText.className = brgy.value ? 'text-cyan-400 font-bold' : 'text-gray-200';
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
                        <label class="block text-gray-200 mb-1 text-sm font-medium">Password <span
                                class="text-red-500">*</span></label>
                        <div class="relative w-full md:w-1/2">
                            <input type="password" name="password" id="password"
                                class="w-full px-3 py-2 border border-[#263548] rounded focus:outline-none focus:border-[#42a5f5] text-gray-200 bg-[#0f151e] shadow-sm pr-10">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200 focus:outline-none">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-200 mt-1">Minimum 8 characters. The customer can log in using their
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

                    document.getElementById('togglePassword').addEventListener('click', function (e) {
                        const passwordInput = document.getElementById('password');
                        const eyeIcon = document.getElementById('eyeIcon');
                        const eyeSlashIcon = document.getElementById('eyeSlashIcon');
                        
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            eyeIcon.classList.add('hidden');
                            eyeSlashIcon.classList.remove('hidden');
                        } else {
                            passwordInput.type = 'password';
                            eyeIcon.classList.remove('hidden');
                            eyeSlashIcon.classList.add('hidden');
                        }
                    });
                </script>

                <div class="flex justify-end pt-4 border-t border-[#263548]">
                    <div class="text-sm text-gray-200 mr-auto self-center">
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
