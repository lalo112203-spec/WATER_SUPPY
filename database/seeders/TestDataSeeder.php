<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\WaterUsage;
use App\Models\Bill;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create system settings
        SystemSetting::set('base_charge', 500, 'number');
        SystemSetting::set('usage_rate', 50, 'number');
        SystemSetting::set('alert_threshold', 1000, 'number');
        SystemSetting::set('alert_email', 'admin@watersystem.local', 'email');

        // Create sample customers
        $regularCustomers = [
            [
                'customer_id' => 'CUST-001',
                'name' => 'Juan Dela Cruz',
                'type' => 'Regular',
                'email' => 'juan@example.com',
                'address' => '123 Main Street, Manila',
                'meter_reading' => 100,
                'total_consumption' => 450,
                'status' => 'active',
            ],
            [
                'customer_id' => 'CUST-002',
                'name' => 'Maria Santos',
                'type' => 'Regular',
                'email' => 'maria@example.com',
                'address' => '456 Oak Avenue, Quezon City',
                'meter_reading' => 150,
                'total_consumption' => 520,
                'status' => 'active',
            ],
            [
                'customer_id' => 'CUST-003',
                'name' => 'Pedro Reyes',
                'type' => 'Regular',
                'email' => 'pedro@example.com',
                'address' => '789 Elm Street, Makati',
                'meter_reading' => 80,
                'total_consumption' => 380,
                'status' => 'active',
            ],
        ];

        $commercialCustomers = [
            [
                'customer_id' => 'COM-001',
                'name' => 'ABC Manufacturing Co.',
                'type' => 'Commercial',
                'email' => 'admin@abcmfg.com',
                'address' => 'Industrial Complex, Laguna',
                'meter_reading' => 5000,
                'total_consumption' => 12500,
                'status' => 'active',
            ],
            [
                'customer_id' => 'COM-002',
                'name' => 'XYZ Hotel & Resort',
                'type' => 'Commercial',
                'email' => 'accounts@xyzhotel.com',
                'address' => 'Boracay, Aklan',
                'meter_reading' => 8000,
                'total_consumption' => 18500,
                'status' => 'active',
            ],
        ];

        $customers = array_merge($regularCustomers, $commercialCustomers);
        
        foreach ($customers as $customerData) {
            $customer = Customer::create($customerData);

            // Create water usage records
            for ($i = 1; $i <= 6; $i++) {
                $previousReading = $customer->meter_reading - ($i * 50);
                $currentReading = $previousReading + 50;
                
                WaterUsage::create([
                    'customer_id' => $customer->id,
                    'reading_date' => now()->subMonths($i),
                    'usage' => 50,
                    'previous_reading' => $previousReading,
                    'current_reading' => $currentReading,
                ]);

                // Create corresponding bills
                $baseCharge = 500;
                $usageCharge = 50 * 50;
                $totalAmount = $baseCharge + $usageCharge;

                $bill = Bill::create([
                    'customer_id' => $customer->id,
                    'billing_date' => now()->subMonths($i),
                    'usage_units' => 50,
                    'base_charge' => $baseCharge,
                    'usage_charge' => $usageCharge,
                    'total_amount' => $totalAmount,
                    'status' => rand(0, 1) ? 'Paid' : 'Pending',
                    'due_date' => now()->subMonths($i)->addDays(30),
                    'paid_date' => rand(0, 1) ? now()->subMonths($i)->addDays(15) : null,
                ]);
            }
        }
    }
}
