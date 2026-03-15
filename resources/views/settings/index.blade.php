<x-layouts::app title="System Settings">
    <div class="px-6 py-4 bg-[#f8f9fa] min-h-screen font-sans text-gray-700">
        <h1 class="text-2xl font-bold mb-8 text-gray-800">System Settings</h1>

        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            
            <div class="bg-[#ebf0f5] p-6 mb-6">
                <h3 class="text-[#337ab7] font-semibold mb-4 text-base">General Settings</h3>
                <div class="mb-4 max-w-md">
                    <label class="block text-gray-600 mb-1 text-sm">Price of Water (₱ per unit)</label>
                    <input type="number" step="0.01" name="usage_rate" value="{{ old('usage_rate', $settings['usage_rate'] ?? 50) }}" class="w-full bg-transparent border-b border-gray-300 py-1 focus:outline-none focus:border-blue-400 text-gray-700">
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-12 bg-[#ebf0f5] p-6">
                <!-- Regular User Config -->
                <div class="flex-1">
                    <h3 class="text-[#337ab7] font-semibold mb-4 text-base">Regular User Config</h3>
                    
                    <div class="mb-4">
                        <label class="block text-gray-600 mb-1 text-sm">Base Charge (₱)</label>
                        <input type="number" step="0.01" name="base_charge" value="{{ old('base_charge', $settings['base_charge'] ?? 100) }}" class="w-full bg-transparent border-b border-gray-300 py-1 focus:outline-none focus:border-blue-400 text-gray-700">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-600 mb-1 text-sm">Green Alert (L)</label>
                        <input type="number" name="regular_green_max" value="{{ old('regular_green_max', $settings['regular_green_max'] ?? 10) }}" class="w-full bg-transparent border-b border-gray-300 py-1 focus:outline-none focus:border-blue-400 text-gray-700">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-600 mb-1 text-sm">Orange Alert (L)</label>
                        <input type="number" name="regular_orange_max" value="{{ old('regular_orange_max', $settings['regular_orange_max'] ?? 11) }}" class="w-full bg-transparent border-b border-gray-300 py-1 focus:outline-none focus:border-blue-400 text-gray-700">
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-600 mb-1 text-sm">Red Alert (L)</label>
                        <input type="number" name="regular_red_max" value="{{ old('regular_red_max', $settings['regular_red_max'] ?? 20) }}" class="w-full bg-transparent border-b border-gray-300 py-1 focus:outline-none focus:border-blue-400 text-gray-700">
                    </div>
                    
                    <button type="submit" class="bg-[#337ab7] hover:bg-[#286090] text-white px-4 py-2 rounded font-medium text-sm shadow-sm">
                        Save Configuration
                    </button>
                </div>

                <!-- Commercial User Config -->
                <div class="flex-1">
                    <h3 class="text-[#337ab7] font-semibold mb-4 text-base">Commercial User Config</h3>
                    
                    <div class="mb-4">
                        <label class="block text-gray-600 mb-1 text-sm">Base Charge (₱)</label>
                        <input type="number" step="0.01" name="commercial_base_charge" value="{{ old('commercial_base_charge', $settings['commercial_base_charge'] ?? 250) }}" class="w-full bg-transparent border-b border-gray-300 py-1 focus:outline-none focus:border-blue-400 text-gray-700">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-600 mb-1 text-sm">Green Alert (L)</label>
                        <input type="number" name="commercial_green_max" value="{{ old('commercial_green_max', $settings['commercial_green_max'] ?? 49) }}" class="w-full bg-transparent border-b border-gray-300 py-1 focus:outline-none focus:border-blue-400 text-gray-700">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-600 mb-1 text-sm">Orange Alert (L)</label>
                        <input type="number" name="commercial_orange_max" value="{{ old('commercial_orange_max', $settings['commercial_orange_max'] ?? 50) }}" class="w-full bg-transparent border-b border-gray-300 py-1 focus:outline-none focus:border-blue-400 text-gray-700">
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-600 mb-1 text-sm">Red Alert (L)</label>
                        <input type="number" name="commercial_red_max" value="{{ old('commercial_red_max', $settings['commercial_red_max'] ?? 100) }}" class="w-full bg-transparent border-b border-gray-300 py-1 focus:outline-none focus:border-blue-400 text-gray-700">
                    </div>
                </div>
            </div>
            
            <!-- Hidden required fields from original form to maintain compatibility -->
            <div class="hidden">
                <input type="email" name="alert_email" value="{{ $settings['alert_email'] ?? 'admin@example.com' }}">
                <input type="number" name="alert_threshold" value="{{ $settings['alert_threshold'] ?? 15 }}">
            </div>
        </form>
    </div>
</x-layouts::app>
