<x-layouts.app title="{{ __('System Settings') }}">
    <section class="w-full">
        @include('partials.settings-heading')

        <flux:heading class="sr-only">{{ __('System settings') }}</flux:heading>

        <x-pages::settings.layout :heading="__('System Configuration')" :subheading="__('Update your system billing charges and alert thresholds.')">
            
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
                
                <h3 class="text-lg font-bold">General Settings</h3>
                <flux:input name="usage_rate" :label="__('Price of Water (₱ per unit)')" type="number" step="0.01" value="{{ old('usage_rate', $settings['usage_rate'] ?? 50) }}" />

                <h3 class="text-lg font-bold mt-8">Regular User Config</h3>
                <flux:input name="base_charge" :label="__('Base Charge (₱)')" type="number" step="0.01" value="{{ old('base_charge', $settings['base_charge'] ?? 100) }}" />
                <flux:input name="regular_green_max" :label="__('Green Alert (L)')" type="number" value="{{ old('regular_green_max', $settings['regular_green_max'] ?? 10) }}" />
                <flux:input name="regular_orange_max" :label="__('Orange Alert (L)')" type="number" value="{{ old('regular_orange_max', $settings['regular_orange_max'] ?? 11) }}" />
                <flux:input name="regular_red_max" :label="__('Red Alert (L)')" type="number" value="{{ old('regular_red_max', $settings['regular_red_max'] ?? 20) }}" />

                <h3 class="text-lg font-bold mt-8">Commercial User Config</h3>
                <flux:input name="commercial_base_charge" :label="__('Base Charge (₱)')" type="number" step="0.01" value="{{ old('commercial_base_charge', $settings['commercial_base_charge'] ?? 250) }}" />
                <flux:input name="commercial_green_max" :label="__('Green Alert (L)')" type="number" value="{{ old('commercial_green_max', $settings['commercial_green_max'] ?? 49) }}" />
                <flux:input name="commercial_orange_max" :label="__('Orange Alert (L)')" type="number" value="{{ old('commercial_orange_max', $settings['commercial_orange_max'] ?? 50) }}" />
                <flux:input name="commercial_red_max" :label="__('Red Alert (L)')" type="number" value="{{ old('commercial_red_max', $settings['commercial_red_max'] ?? 100) }}" />

                <div class="hidden">
                    <input type="email" name="alert_email" value="{{ $settings['alert_email'] ?? 'admin@example.com' }}">
                    <input type="number" name="alert_threshold" value="{{ $settings['alert_threshold'] ?? 15 }}">
                </div>

                <div class="flex items-center gap-4 mt-8">
                    <div class="flex items-center justify-start">
                        <flux:button variant="primary" type="submit" class="w-full">
                            {{ __('Save Configuration') }}
                        </flux:button>
                    </div>
                </div>
            </form>
        </x-pages::settings.layout>
    </section>
</x-layouts.app>
