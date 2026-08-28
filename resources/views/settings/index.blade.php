<x-layouts::app title="{{ __('System Settings') }}">
    <section class="w-full">
        @include('partials.settings-heading')

        <flux:heading class="sr-only">{{ __('System settings') }}</flux:heading>

        @component('pages.settings.layout', [
            'heading' => __('System Configuration'),
            'subheading' => __('Update your system billing charges and alert thresholds.'),
            'maxWidth' => 'max-w-5xl'
        ])
            
            <form method="POST" action="{{ route('settings.update') }}" class="my-6 w-full space-y-6">
                @csrf
                
                <style>
                    /* Force all flux labels on this page to be white */
                    [data-flux-label], label, h3 {
                        color: white !important;
                    }
                    [data-flux-label], label {
                        margin-bottom: 0.35rem !important;
                        display: inline-block;
                    }
                </style>
                
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="hidden">
                    <flux:input name="usage_rate" :label="__('Price of Water (₱ per unit)')" type="number" step="0.01" value="{{ old('usage_rate', $settings['regular_usage_rate'] ?? 15) }}" />
                </div>

                @php
                    $defaultTab = $customerTypes->first()->id ?? '';
                    if ($errors->any()) {
                        foreach($customerTypes as $type) {
                            $hasError = false;
                            foreach(['base_charge', 'usage_rate', 'green_max', 'orange_max', 'red_max', 'base_limit'] as $field) {
                                if ($errors->has('types.'.$type->id.'.'.$field)) {
                                    $hasError = true;
                                    break;
                                }
                            }
                            if ($hasError) {
                                $defaultTab = $type->id;
                                break;
                            }
                        }
                    }
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                    <!-- Left Column -->
                    <div>
                        <div x-data="{ selectedType: '{{ $defaultTab }}' }">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Select Customer Type to Configure</label>
                        <select x-model="selectedType" class="w-full bg-[#1b2636]/40 border border-[#2d4059]/50 focus:border-blue-500/50 text-gray-200 text-sm rounded-xl py-2.5 px-4 outline-none transition-all placeholder:text-gray-600 focus:ring-1 focus:ring-blue-500/20">
                            @foreach($customerTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @foreach($customerTypes as $type)
                    <div x-show="selectedType == '{{ $type->id }}'" class="mb-10 pb-6 border-b border-gray-700/50">
                        <div class="flex items-center justify-between mt-8 mb-4">
                            <h3 class="text-lg font-bold flex items-center gap-2">
                                {{ $type->name }} User Config
                                @if(auth()->user()->role === 'admin')
                                <button type="button" class="text-[10px] bg-rose-600/80 hover:bg-rose-600 text-white px-2 py-0.5 rounded-full uppercase tracking-widest font-black shadow-sm transition-all ml-2" onclick="openDeleteModal('{{ route('settings.customer-type.destroy', $type) }}')">Delete Type</button>
                                @endif
                            </h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 items-end">
                            <flux:input name="types[{{ $type->id }}][base_charge]" :label="__('Base Charge (₱)')" type="number" step="0.01" value="{{ old('types.'.$type->id.'.base_charge', $type->base_charge) }}" />
                            <flux:input name="types[{{ $type->id }}][usage_rate]" :label="__('Usage Rate (₱ per m³)')" type="number" step="0.01" value="{{ old('types.'.$type->id.'.usage_rate', $type->usage_rate) }}" />
                            <flux:input name="types[{{ $type->id }}][base_limit]" :label="__('Base Usage Limit (m³)')" type="number" value="{{ old('types.'.$type->id.'.base_limit', $type->base_limit) }}" />
                        </div>
                        
                        <h4 class="text-sm font-semibold text-gray-300 mb-3 border-b border-gray-700/50 pb-2">Usage Alerts</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                            <flux:input name="types[{{ $type->id }}][green_max]" :label="__('Green Alert (m³)')" type="number" value="{{ old('types.'.$type->id.'.green_max', $type->green_max) }}" />
                            <flux:input name="types[{{ $type->id }}][orange_max]" :label="__('Orange Alert (m³)')" type="number" value="{{ old('types.'.$type->id.'.orange_max', $type->orange_max) }}" />
                            <flux:input name="types[{{ $type->id }}][red_max]" :label="__('Red Alert (m³)')" type="number" value="{{ old('types.'.$type->id.'.red_max', $type->red_max) }}" />
                        </div>
                    </div>
                    @endforeach
                </div>

                        @if(auth()->user()->role === 'admin')
                        <div class="mt-4 mb-8">
                            <button type="button" onclick="document.getElementById('addTypeModal').classList.remove('hidden')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs flex items-center gap-2 font-bold uppercase tracking-widest transition-all shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add New Customer Type
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div>
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            Global Additional Charges
                            <span class="text-[10px] bg-emerald-600 text-white px-2 py-0.5 rounded-full uppercase tracking-widest font-black shadow-sm">Applied to all new bills</span>
                        </h3>
                        
                        <div id="additional-charges-container" class="space-y-4 mt-4">
                    @forelse($settings['global_additional_charges'] ?? [] as $index => $additionalCharge)
                        <div class="flex items-center gap-3 group animate-in fade-in slide-in-from-left-2 duration-300">
                            <div class="flex-1">
                                <input name="additional_charge_names[]" type="text" placeholder="Charge Name (e.g. Environmental Fee)" value="{{ $additionalCharge['name'] }}" 
                                    class="w-full bg-[#1b2636]/40 border border-[#2d4059]/50 focus:border-blue-500/50 text-gray-200 text-sm rounded-xl py-2.5 px-4 outline-none transition-all placeholder:text-gray-600 focus:ring-1 focus:ring-blue-500/20">
                            </div>
                            <div class="w-32">
                                <input name="additional_charge_amounts[]" type="number" step="0.01" placeholder="Amount" value="{{ $additionalCharge['amount'] }}" 
                                    class="w-full bg-[#1b2636]/40 border border-[#2d4059]/50 focus:border-blue-500/50 text-gray-200 text-sm rounded-xl py-2.5 px-4 outline-none transition-all text-right placeholder:text-gray-600 focus:ring-1 focus:ring-blue-500/20">
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-rose-500/50 hover:text-rose-500 hover:bg-rose-500/10 rounded-xl transition-all group-hover:scale-105 active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @empty
                        <div class="empty-state text-center py-6 bg-[#0f172a]/80 backdrop-blur-sm rounded-2xl border border-dashed border-gray-400 text-white font-medium text-sm italic shadow-sm">
                            No additional charges configured. Click below to add one.
                        </div>
                    @endforelse
                </div>
                
                <button type="button" onclick="addAdditionalChargeRow()" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs flex items-center gap-2 font-bold uppercase tracking-widest transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Charge Row
                </button>

                <script>
                    function addAdditionalChargeRow() {
                        const container = document.getElementById('additional-charges-container');
                        const emptyState = container.querySelector('.empty-state');
                        if (emptyState) {
                            emptyState.remove();
                        }
                        
                        const div = document.createElement('div');
                        div.className = 'flex items-center gap-3 animate-in fade-in zoom-in-95 duration-300 group';
                        div.innerHTML = `
                            <div class="flex-1">
                                <input name="additional_charge_names[]" type="text" placeholder="Charge Name (e.g. Environmental Fee)" 
                                    class="w-full bg-[#1b2636]/40 border border-[#2d4059]/50 focus:border-blue-500/50 text-gray-200 text-sm rounded-xl py-2.5 px-4 outline-none transition-all placeholder:text-gray-600 focus:ring-1 focus:ring-blue-500/20">
                            </div>
                            <div class="w-32">
                                <input name="additional_charge_amounts[]" type="number" step="0.01" placeholder="Amount" 
                                    class="w-full bg-[#1b2636]/40 border border-[#2d4059]/50 focus:border-blue-500/50 text-gray-200 text-sm rounded-xl py-2.5 px-4 outline-none transition-all text-right placeholder:text-gray-600 focus:ring-1 focus:ring-blue-500/20">
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-rose-500/50 hover:text-rose-500 hover:bg-rose-500/10 rounded-xl transition-all group-hover:scale-105 active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        `;
                        container.appendChild(div);
                    }
                </script>

                        <div class="hidden">
                            <input type="email" name="alert_email" value="{{ $settings['alert_email'] ?? 'admin@example.com' }}">
                            <input type="number" name="alert_threshold" value="{{ $settings['alert_threshold'] ?? 15 }}">
                        </div>

                        <div class="p-6 bg-rose-500/5 border border-rose-500/20 rounded-2xl mt-12 mb-4">
                            <p class="text-sm font-bold text-rose-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Security Verification
                    </p>
                    
                    
                            <flux:input name="admin_password_verification" :label="__('Confirm Admin Password')" type="password" required placeholder="••••••••" />
                        </div>

                        <div class="flex items-center gap-4 mt-4">
                            <div class="flex items-center justify-start w-full">
                                <flux:button variant="primary" type="submit" class="w-full h-12 px-10">
                                    {{ __('Save System Configuration') }}
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endcomponent
    </section>



    @if(auth()->user()->role === 'admin')
    <!-- Add Type Modal -->
    <div id="addTypeModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center animate-in fade-in duration-200">
        <div class="bg-[#1e293b] border border-gray-700/50 p-6 rounded-2xl w-full max-w-md shadow-2xl relative">
            <button onclick="document.getElementById('addTypeModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-white mb-6">Add Customer Type</h3>
            <form action="{{ route('settings.customer-type.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Type Name</label>
                    <input type="text" name="name" required class="w-full bg-[#0f172a] border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500" placeholder="e.g. Senior Citizen">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Confirm Admin Password</label>
                    <input type="password" name="admin_password_verification" required class="w-full bg-[#0f172a] border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500" placeholder="••••••••">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('addTypeModal').classList.add('hidden')" class="px-4 py-2 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-colors">Add Type</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Type Modal -->
    <div id="deleteTypeModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center animate-in fade-in duration-200">
        <div class="bg-[#1e293b] border border-gray-700/50 p-6 rounded-2xl w-full max-w-md shadow-2xl relative">
            <button type="button" onclick="document.getElementById('deleteTypeModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-white mb-6">Delete Customer Type</h3>
            <form id="deleteTypeForm" method="POST" class="space-y-4">
                @csrf
                @method('DELETE')
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Confirm Admin Password</label>
                    <input type="password" name="admin_password_verification" required class="w-full bg-[#0f172a] border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-rose-500" placeholder="••••••••">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('deleteTypeModal').classList.add('hidden')" class="px-4 py-2 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-lg font-medium transition-colors">Delete Type</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openDeleteModal(url) {
            document.getElementById('deleteTypeForm').action = url;
            document.getElementById('deleteTypeModal').classList.remove('hidden');
        }
    </script>
    @endif
</x-layouts::app>
