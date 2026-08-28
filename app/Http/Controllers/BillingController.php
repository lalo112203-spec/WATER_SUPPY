<?php
 
namespace App\Http\Controllers;
 
use App\Models\Bill;
use App\Models\Customer;
use App\Models\SystemSetting;
use Illuminate\View\View;
use Illuminate\Http\Request;
 
class BillingController extends Controller
{
    public function index(Request $request): View
    {
        $adminId = auth()->id();
        $search = $request->input('search');
        $myCustomerIds = Customer::where('admin_id', $adminId)->pluck('id')->toArray();

        $pendingQuery = Bill::with(['customer' => function ($query) { $query->withTrashed(); }])
            ->whereIn('customer_id', $myCustomerIds)
            ->where('status', '!=', 'Paid');

        $paidQuery = Bill::with(['customer' => function ($query) { $query->withTrashed(); }])
            ->whereIn('customer_id', $myCustomerIds)
            ->where('status', 'Paid');

        if ($search) {
            $pendingQuery->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%")
                       ->orWhere('customer_id', 'like', "%{$search}%");
                });
            });
            $paidQuery->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%")
                       ->orWhere('customer_id', 'like', "%{$search}%");
                });
            });
        }

        $pendingBills = $pendingQuery->orderBy('billing_date', 'desc')
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        $paidBills = $paidQuery->orderBy('paid_date', 'desc')
            ->paginate(10, ['*'], 'paid_page')
            ->withQueryString();

        $paidCount = Bill::where('status', 'Paid')
            ->whereIn('customer_id', $myCustomerIds)
            ->count();
        $pendingCount = Bill::where('status', 'Pending')
            ->whereIn('customer_id', $myCustomerIds)
            ->count();
 
        $unpaidCustomersCount = Customer::whereIn('id', $myCustomerIds)
            ->whereHas('bills', function ($q) {
                $q->where('status', '!=', 'Paid');
            })->count();

        $paidCustomersCount = Customer::whereIn('id', $myCustomerIds)
            ->whereHas('bills')
            ->whereDoesntHave('bills', function ($q) {
                $q->where('status', '!=', 'Paid');
            })->count();
        $totalBilled = Bill::whereIn('customer_id', $myCustomerIds)
            ->sum('total_amount');
 
        $customerTypes = \App\Models\CustomerType::all();
        $thresholds = [];
        $settings = [];

        foreach ($customerTypes as $type) {
            $thresholds[$type->name] = [
                'green_max' => $type->green_max,
                'orange_max' => $type->orange_max,
            ];

            // Maintain same key pattern dynamically
            $lowerName = strtolower($type->name);
            $settings[$lowerName.'_base_charge'] = $type->base_charge;
            $settings[$lowerName.'_usage_rate'] = $type->usage_rate;
            $settings[$lowerName.'_base_limit'] = $type->base_limit;
        }

        $customers = Customer::where('admin_id', $adminId)->where('status', 'active')->get();

        $globalAdditionalCharges = json_decode(SystemSetting::get('global_additional_charges', '[]'), true);
        $globalAdditionalChargeTotal = collect($globalAdditionalCharges)->sum('amount');
 
        return view('billing.index', [
            'pendingBills' => $pendingBills,
            'paidBills' => $paidBills,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'totalBilled' => $totalBilled,
            'paidCustomersCount' => $paidCustomersCount,
            'unpaidCustomersCount' => $unpaidCustomersCount,
            'thresholds' => $thresholds,
            'customers' => $customers,
            'settings' => $settings,
            'globalAdditionalCharges' => $globalAdditionalCharges,
            'globalAdditionalChargeTotal' => $globalAdditionalChargeTotal
        ]);
    }
 

 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'billing_date' => 'required|date',
            'new_reading' => 'required|numeric|min:0',
            'consumption' => 'required|numeric|min:0',
            'base_charge' => 'required|numeric',
            'usage_charge' => 'required|numeric',
            'additional_charge_amount' => 'nullable|numeric|min:0',
            'additional_charge_note' => 'nullable|string',
            'due_date' => 'required|date|after:billing_date',
        ]);
 
        $globalAdditionalCharges = json_decode(SystemSetting::get('global_additional_charges', '[]'), true);
        $globalAdditionalChargeTotal = collect($globalAdditionalCharges)->sum('amount');

        $customer = Customer::find($validated['customer_id']);
        $previousReading = $customer ? ($customer->meter_reading ?? 0) : 0;
        $newReading = $validated['new_reading'];
        $usage = $newReading - $previousReading;

        $validated['previous_reading'] = $previousReading;
        $validated['new_reading'] = $newReading;
        $validated['usage_units'] = max(0, $usage);
        $validated['consumption'] = max(0, $usage);
        $validated['applied_additional_charges'] = $globalAdditionalCharges;
        $validated['total_amount'] = ($validated['base_charge'] + $validated['usage_charge']) + (($validated['additional_charge_amount'] ?? 0) + $globalAdditionalChargeTotal);
        $validated['status'] = 'Pending';
 
        $bill = Bill::create($validated);
 
        if ($customer) {
            $customer->update([
                'meter_reading' => $newReading
            ]);

            if ($customer->user) {
                \App\Models\Message::create([
                    'sender_id' => auth()->id(),
                    'receiver_id' => $customer->user->id,
                    'message' => 'A new bill for the amount of ' . number_format($validated['total_amount'], 2) . ' has been generated. Due date is ' . \Carbon\Carbon::parse($validated['due_date'])->format('M d, Y') . '.',
                ]);
                
                $customer->user->notify(new \App\Notifications\NewBillPushNotification($validated['total_amount'], \Carbon\Carbon::parse($validated['due_date'])->format('M d, Y')));
            }
        }
 
        return redirect()->back()
            ->with('success', 'Bill created successfully');
    }
 
    public function show(Bill $bill): View
    {
        $customerTypes = \App\Models\CustomerType::all();
        $settings = [];
        
        foreach ($customerTypes as $type) {
            $lowerName = strtolower($type->name);
            $settings[$lowerName.'_base_charge'] = $type->base_charge;
            $settings[$lowerName.'_usage_rate'] = $type->usage_rate;
            $settings[$lowerName.'_base_limit'] = $type->base_limit;
        }

        $globalAdditionalChargeTotal = collect($bill->applied_additional_charges ?? [])->sum('amount');

        return view('billing.show', compact('bill', 'settings', 'globalAdditionalChargeTotal'));
    }
 
    public function receipt(Bill $bill): View
    {
        if (auth()->user()->role === 'consumer' && auth()->user()->customer_id !== $bill->customer_id) {
            abort(403);
        }
 
        return view('billing.receipt', compact('bill'));
    }
 
    public function markAsPaid(Bill $bill)
    {
        $bill->update([
            'status' => 'Paid',
            'paid_date' => now(),
        ]);
        
        // Notify the consumer device/account via in-app message
        if ($bill->customer && $bill->customer->user) {
            \App\Models\Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $bill->customer->user->id,
                'message' => 'Your bill from ' . $bill->billing_date->format('M d, Y') . ' for the amount of ' . number_format($bill->total_amount, 2) . ' has been successfully marked as paid. Thank you!',
            ]);
            
            // Dispatch Web Push Notification
            $bill->customer->user->notify(new \App\Notifications\BillPaidPushNotification($bill->total_amount, $bill->billing_date->format('M d, Y')));
        }
 
        return redirect()->route('billing.index')
            ->with('success', 'Bill marked as paid and notification sent.');
    }
 
    public function destroy(Bill $bill)
    {
        $customerId = $bill->customer_id;
        $bill->delete();

        // Update customer's current meter reading to the latest remaining bill's new reading
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

        return redirect()->back()
            ->with('success', 'Bill deleted successfully and meter reading reverted.');
    }
 
    public function getCustomerReadings(Customer $customer)
    {
        $readings = Bill::where('customer_id', $customer->id)
            ->orderBy('billing_date', 'desc')
            ->get(['billing_date', 'usage_units', 'consumption', 'total_amount', 'status']);
 
        return response()->json([
            'readings' => $readings,
            'customer' => $customer
        ]);
    }

 
    public function update(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'billing_date' => 'required|date',
            'new_reading' => 'required|numeric|min:0',
            'consumption' => 'required|numeric|min:0',
            'base_charge' => 'required|numeric',
            'usage_charge' => 'required|numeric',
            'additional_charge_amount' => 'nullable|numeric|min:0',
            'additional_charge_note' => 'nullable|string',
            'due_date' => 'required|date|after:billing_date',
        ]);
 
        $globalAdditionalChargeTotal = collect($bill->applied_additional_charges ?? [])->sum('amount');
        
        $newReading = $validated['new_reading'];
        $previousReading = $bill->previous_reading ?? 0;
        $usage = max(0, $newReading - $previousReading);

        $validated['new_reading'] = $newReading;
        $validated['usage_units'] = $usage;
        $validated['consumption'] = $usage;
        $validated['total_amount'] = ($validated['base_charge'] + $validated['usage_charge']) + (($validated['additional_charge_amount'] ?? 0) + $globalAdditionalChargeTotal);
 
        $bill->update($validated);

        // If this is the latest bill, update customer's current meter reading
        $latestBill = Bill::where('customer_id', $bill->customer_id)
            ->orderBy('billing_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if ($latestBill && $latestBill->id === $bill->id) {
            $customer = Customer::find($bill->customer_id);
            if ($customer) {
                $customer->update([
                    'meter_reading' => $bill->new_reading
                ]);
            }
        }

        return redirect()->route('billing.show', $bill)
            ->with('success', 'Bill updated successfully and meter reading synchronized.');
    }
}
