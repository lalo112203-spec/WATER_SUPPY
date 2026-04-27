<x-layouts::app title="{{ __('System Settings') }}">
    <section class="w-full">
        @include('partials.settings-heading')

        <flux:heading class="sr-only">{{ __('System settings') }}</flux:heading>

        @component('pages.settings.layout', [
            'heading' => __('System Configuration'),
            'subheading' => __('Update your system billing charges and alert thresholds.')
        ])
            
            <form method="POST" action="{{ route('settings.update') }}" class="my-6 w-full space-y-6">
                @csrf
                
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

                <h3 class="text-lg font-bold mt-8">Regular User Config</h3>
                <flux:input name="regular_base_charge" :label="__('Base Charge (₱)')" type="number" step="0.01" value="{{ old('regular_base_charge', $settings['regular_base_charge'] ?? 100) }}" />
                <flux:input name="regular_usage_rate" :label="__('Usage Rate (₱ per m³)')" type="number" step="0.01" value="{{ old('regular_usage_rate', $settings['regular_usage_rate'] ?? 15) }}" />
                <flux:input name="regular_green_max" :label="__('Green Alert (m³)')" type="number" value="{{ old('regular_green_max', $settings['regular_green_max'] ?? 10) }}" />
                <flux:input name="regular_orange_max" :label="__('Orange Alert (m³)')" type="number" value="{{ old('regular_orange_max', $settings['regular_orange_max'] ?? 11) }}" />
                <flux:input name="regular_red_max" :label="__('Red Alert (m³)')" type="number" value="{{ old('regular_red_max', $settings['regular_red_max'] ?? 20) }}" />
                <flux:input name="regular_base_limit" :label="__('Base Usage Limit (m³ - usage above this is billed)')" type="number" value="{{ old('regular_base_limit', $settings['regular_base_limit'] ?? 10) }}" />
 
                <h3 class="text-lg font-bold mt-8">Commercial User Config</h3>
                <flux:input name="commercial_base_charge" :label="__('Base Charge (₱)')" type="number" step="0.01" value="{{ old('commercial_base_charge', $settings['commercial_base_charge'] ?? 250) }}" />
                <flux:input name="commercial_usage_rate" :label="__('Usage Rate (₱ per m³)')" type="number" step="0.01" value="{{ old('commercial_usage_rate', $settings['commercial_usage_rate'] ?? 25) }}" />
                <flux:input name="commercial_green_max" :label="__('Green Alert (m³)')" type="number" value="{{ old('commercial_green_max', $settings['commercial_green_max'] ?? 49) }}" />
                <flux:input name="commercial_orange_max" :label="__('Orange Alert (m³)')" type="number" value="{{ old('commercial_orange_max', $settings['commercial_orange_max'] ?? 50) }}" />
                <flux:input name="commercial_red_max" :label="__('Red Alert (m³)')" type="number" value="{{ old('commercial_red_max', $settings['commercial_red_max'] ?? 100) }}" />
                <flux:input name="commercial_base_limit" :label="__('Base Usage Limit (m³ - usage above this is billed)')" type="number" value="{{ old('commercial_base_limit', $settings['commercial_base_limit'] ?? 10) }}" />

                <h3 class="text-lg font-bold mt-8 flex items-center gap-2">
                    Global Additional Charges
                    <span class="text-[10px] bg-emerald-500/10 text-emerald-500 px-2 py-0.5 rounded-full uppercase tracking-widest font-black">Applied to all new bills</span>
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
                        <div class="empty-state text-center py-6 bg-[#1b2636]/10 rounded-2xl border border-dashed border-[#2d4059]/50 text-gray-500 text-sm italic">
                            No additional charges configured. Click below to add one.
                        </div>
                    @endforelse
                </div>
                
                <button type="button" onclick="addAdditionalChargeRow()" class="mt-4 px-4 py-2 bg-blue-600/10 hover:bg-blue-600/20 text-blue-400 rounded-xl text-xs flex items-center gap-2 font-bold uppercase tracking-widest transition-all hover:scale-[1.02] active:scale-95 border border-blue-500/20">
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
                    <div class="flex items-center justify-start">
                        <flux:button variant="primary" type="submit" class="w-full h-12 px-10">
                            {{ __('Save System Configuration') }}
                        </flux:button>
                    </div>
                    
                </div>
            </form>
        @endcomponent
    </section>
</x-layouts::app>
