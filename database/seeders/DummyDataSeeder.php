<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\WaterUsage;
use App\Models\User;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            echo "No admin found.\n";
            return;
        }

        $types = ['Residential', 'Commercial', 'Industrial', 'Institutional'];

        for ($i = 1; $i <= 10; $i++) {
            $customer = Customer::create([
                'customer_id' => 'CUST' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => 'Dummy Customer ' . $i,
                'type' => $types[array_rand($types)],
                'email' => 'customer' . $i . '@example.com',
                'address' => '123 Dummy St, City',
                'meter_reading' => rand(100, 1000),
                'total_consumption' => 0,
                'status' => 'Active',
                'admin_id' => $admin->id,
                'barangay' => 'Barangay ' . rand(1, 5)
            ]);

            $totalConsumption = 0;
            $currentReading = rand(100, 500);

            // 6 months of data
            for ($m = 6; $m >= 0; $m--) {
                $date = Carbon::now()->subMonths($m)->startOfMonth();
                $usage = rand(10, 50);
                $previousReading = $currentReading;
                $currentReading += $usage;
                $totalConsumption += $usage;

                WaterUsage::create([
                    'customer_id' => $customer->id,
                    'reading_date' => $date->copy()->addDays(20),
                    'usage' => $usage,
                    'previous_reading' => $previousReading,
                    'current_reading' => $currentReading,
                ]);

                Bill::create([
                    'customer_id' => $customer->id,
                    'billing_date' => $date->copy()->endOfMonth(),
                    'usage_units' => $usage,
                    'base_charge' => 100,
                    'usage_charge' => $usage * 15,
                    'total_amount' => 100 + ($usage * 15),
                    'status' => $m == 0 ? 'Pending' : 'Paid',
                    'due_date' => $date->copy()->addMonths(1)->startOfMonth()->addDays(15),
                    'paid_date' => $m == 0 ? null : $date->copy()->addMonths(1)->startOfMonth()->addDays(rand(1, 14)),
                    'consumption' => $usage,
                    'additional_charge_amount' => 0,
                    'applied_additional_charges' => []
                ]);
            }
            
            $customer->update([
                'meter_reading' => $currentReading,
                'total_consumption' => $totalConsumption
            ]);
        }
        
        echo "Successfully seeded dummy customers, bills, and usages.\n";
    }
}
