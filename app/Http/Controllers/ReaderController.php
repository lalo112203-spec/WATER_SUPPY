<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Bill;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReaderController extends Controller
{
    public function index(): View
    {
        // For a meter reader, we can fetch all active customers or customers assigned to specific admin
        // Assuming they can see all active customers for now.
        $groupedCustomers = Customer::whereIn('status', ['active', 'Active', 'ACTIVE'])
            ->orderBy('barangay', 'asc')
            ->orderBy('name', 'asc')
            ->get()
            ->groupBy(function($customer) {
                return $customer->barangay ?: 'Unassigned Barangay';
            });

        $settings = [
            'regular_base_charge' => SystemSetting::get('regular_base_charge', 100),
            'commercial_base_charge' => SystemSetting::get('commercial_base_charge', 250),
            'regular_usage_rate' => SystemSetting::get('regular_usage_rate', 15),
            'commercial_usage_rate' => SystemSetting::get('commercial_usage_rate', 25),
            'regular_base_limit' => SystemSetting::get('regular_base_limit', 10),
            'commercial_base_limit' => SystemSetting::get('commercial_base_limit', 10),
        ];

        $globalAdditionalChargeTotal = collect(json_decode(SystemSetting::get('global_additional_charges', '[]'), true))->sum('amount');

        return view('reader.dashboard', compact('groupedCustomers', 'settings', 'globalAdditionalChargeTotal'));
    }

    public function storeReading(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'reading' => 'required|numeric|min:0',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);
        
        $currentReading = $validated['reading'];
        $previousReading = $customer->meter_reading ?? 0;
        
        $usage = $currentReading - $previousReading;

        if ($usage < 0) {
            return redirect()->back()->withErrors(['reading' => "New reading ({$currentReading}) cannot be lower than the previous reading ({$previousReading})."]);
        }

        $typePrefix = $customer->type === 'Commercial' ? 'commercial' : 'regular';
        
        $baseLimit = (float) SystemSetting::get("{$typePrefix}_base_limit", 10);
        $rate = (float) SystemSetting::get("{$typePrefix}_usage_rate", $customer->type === 'Commercial' ? 25 : 15);
        $baseCharge = (float) SystemSetting::get("{$typePrefix}_base_charge", $customer->type === 'Commercial' ? 250 : 100);

        $billableUsage = max($usage - $baseLimit, 0);
        $usageCharge = $billableUsage * $rate;

        $globalAdditionalCharges = json_decode(SystemSetting::get('global_additional_charges', '[]'), true);
        $globalAdditionalChargeTotal = collect($globalAdditionalCharges)->sum('amount');

        $totalAmount = $baseCharge + $usageCharge + $globalAdditionalChargeTotal;

        // Determine billing and due date
        $billingDate = now()->format('Y-m-d');
        $dueDate = now()->addDays(30)->format('Y-m-d'); // Default due date logic

        $bill = Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => $billingDate,
            'usage_units' => $usage,
            'consumption' => $usage, // same as usage_units based on existing system logic
            'base_charge' => $baseCharge,
            'usage_charge' => $usageCharge,
            'additional_charge_amount' => 0, // specific individual extra charges
            'applied_additional_charges' => $globalAdditionalCharges,
            'total_amount' => $totalAmount,
            'due_date' => $dueDate,
            'status' => 'Pending',
        ]);

        $customer->update([
            'meter_reading' => $currentReading
        ]);

        return redirect()->route('reader.dashboard')
            ->with('success', 'Reading successfully submitted and bill generated for ' . $customer->name);
    }
}
