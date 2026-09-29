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

        $customerTypes = \App\Models\CustomerType::all();
        $settings = [];
        
        foreach ($customerTypes as $type) {
            $lowerName = strtolower($type->name);
            $settings[$lowerName.'_base_charge'] = $type->base_charge;
            $settings[$lowerName.'_usage_rate'] = $type->usage_rate;
            $settings[$lowerName.'_base_limit'] = $type->base_limit;
        }

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
        } elseif ($usage === 0) {
            return redirect()->back()->withErrors(['reading' => "No water used. Bill cannot be generated for zero consumption."]);
        }

        // Check for existing bill in the same month
        $today = now();
        $existingBill = Bill::where('customer_id', $customer->id)
            ->whereYear('billing_date', $today->year)
            ->whereMonth('billing_date', $today->month)
            ->first();

        if ($existingBill && !$request->boolean('force_billing')) {
            return redirect()->back()
                ->with('billing_warning', [
                    'customer_id'   => $customer->id,
                    'customer_name' => $customer->name,
                    'month'         => $existingBill->billing_date->format('F Y'),
                    'amount'        => number_format($existingBill->total_amount, 2),
                    'bill_id'       => $existingBill->id,
                    'prefill_reading' => $currentReading,
                ]);
        }

        $customerType = $customer->customerType;
        if (!$customerType) {
            $baseLimit = 10;
            $rate = 15;
            $baseCharge = 100;
        } else {
            $baseLimit = (float) $customerType->base_limit;
            $rate = (float) $customerType->usage_rate;
            $baseCharge = (float) $customerType->base_charge;
        }

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
            'previous_reading' => $previousReading,
            'new_reading' => $currentReading,
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

        if ($customer && $customer->user) {
            \App\Models\Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $customer->user->id,
                'message' => 'A new bill for the amount of ' . number_format($totalAmount, 2) . ' has been generated. Due date is ' . \Carbon\Carbon::parse($dueDate)->format('M d, Y') . '.',
            ]);
            
            $customer->user->notify(new \App\Notifications\NewBillPushNotification($totalAmount, \Carbon\Carbon::parse($dueDate)->format('M d, Y')));
        }

        return redirect()->route('reader.dashboard')
            ->with('success', 'Reading successfully submitted and bill generated for ' . $customer->name);
    }

    public function getBillHistory(Customer $customer)
    {
        $bills = Bill::where('customer_id', $customer->id)
            ->orderBy('billing_date', 'desc')
            ->orderBy('id', 'desc')
            ->get(['id', 'billing_date', 'previous_reading', 'new_reading', 'usage_units', 'consumption', 'total_amount', 'status', 'due_date', 'paid_date']);

        return response()->json([
            'customer' => [
                'id'          => $customer->id,
                'name'        => $customer->name,
                'customer_id' => $customer->customer_id,
                'type'        => $customer->type,
            ],
            'bills' => $bills,
        ]);
    }

    public function viewReceipt(Bill $bill)
    {
        return view('billing.receipt', compact('bill'));
    }

    public function deleteBill(Bill $bill)
    {
        $customerId = $bill->customer_id;
        $bill->delete();

        // Revert customer meter reading to the latest remaining bill
        $latestBill = Bill::where('customer_id', $customerId)
            ->orderBy('billing_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $customer = Customer::find($customerId);
        if ($customer) {
            $customer->update([
                'meter_reading' => $latestBill ? $latestBill->new_reading : 0
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Bill deleted successfully.']);
    }
}
