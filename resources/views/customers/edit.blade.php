<x-layouts::app title="Edit Customer">
    <div class="max-w-2xl">
        <flux:heading class="mb-6">Edit Customer</flux:heading>

        <flux:card>
            <form method="POST" action="{{ route('customers.update', $customer) }}">
                @csrf
                @method('PUT')

                <flux:fieldset>
                    <flux:field>
                        <flux:label for="customer_id">Account Number</flux:label>
                        <flux:input
                            id="customer_id"
                            name="customer_id"
                            type="text"
                            required
                            value="{{ old('customer_id', $customer->customer_id) }}"
                        />
                        @error('customer_id')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="name">Full Name</flux:label>
                        <flux:input
                            id="name"
                            name="name"
                            type="text"
                            required
                            value="{{ old('name', $customer->name) }}"
                        />
                        @error('name')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="type">Customer Type</flux:label>
                        <flux:select
                            id="type"
                            name="type"
                            required
                        >
                            <option value="Regular" {{ old('type', $customer->type) === 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="Commercial" {{ old('type', $customer->type) === 'Commercial' ? 'selected' : '' }}>Commercial</option>
                        </flux:select>
                        @error('type')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="phone_number">Phone Number</flux:label>
                        <flux:input
                            id="phone_number"
                            name="phone_number"
                            type="text"
                            placeholder="e.g. 09123456789"
                            value="{{ old('phone_number', $customer->phone_number) }}"
                        />
                        @error('phone_number')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>


                    <flux:field>
                        <flux:label for="street">Street Name (Optional)</flux:label>
                        <flux:input
                            id="street"
                            name="street"
                            type="text"
                            value="{{ old('street', $customer->street) }}"
                            class="uppercase"
                            oninput="updateAddressPreview()"
                        />
                        @error('street')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label for="barangay">Barangay</flux:label>
                        <div class="flex gap-4 items-center">
                            <flux:input
                                id="barangay"
                                name="barangay"
                                type="text"
                                list="barangays_list"
                                required
                                value="{{ old('barangay', $customer->barangay) }}"
                                class="flex-1 uppercase"
                                oninput="updateAddressPreview()"
                            />
                            <datalist id="barangays_list">
                                @foreach($barangays as $brgy)
                                    <option value="{{ $brgy }}">
                                @endforeach
                            </datalist>
                            <div class="px-3 py-1.5 bg-[#1a2432]/30 border border-[#263548] rounded text-sm text-gray-200 font-mono">
                                <span id="street_text" class="text-cyan-400 font-bold">{{ $customer->street ? strtoupper($customer->street) . ', ' : '' }}</span><span id="brgy_text" class="text-cyan-400 font-bold">{{ $customer->barangay ?? '...' }}</span> DOLORES EASTERN SAMAR
                            </div>
                        </div>
                        @error('barangay')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror

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
                        </script>
                    </flux:field>
                </flux:fieldset>

                <div class="flex gap-3 mt-6">
                    <flux:button type="submit" variant="primary">Update Customer</flux:button>
                    <flux:button :href="route('customers.index')" variant="ghost" wire:navigate>Cancel</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts::app>
