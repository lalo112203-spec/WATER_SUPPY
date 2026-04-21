<?php
 
namespace App\Http\Controllers;
 
use App\Models\Bill;
use App\Models\Customer;
use App\Models\SystemSetting;
use Illuminate\View\View;
use Illuminate\Http\Request;
 
class BillingController extends Controller
{
    public function index(): View
    {
        $adminId = auth()->id();
        $myCustomerIds = Customer::where('admin_id', $adminId)->pluck('id')->toArray();
 
        $pendingBills = Bill::with(['customer' => function ($query) { $query->withTrashed(); }])
            ->whereIn('customer_id', $myCustomerIds)
            ->where('status', '!=', 'Paid')
            ->orderBy('billing_date', 'desc')
            ->paginate(10, ['*'], 'pending_page');
 
        $paidBills = Bill::with(['customer' => function ($query) { $query->withTrashed(); }])
            ->whereIn('customer_id', $myCustomerIds)
            ->where('status', 'Paid')
            ->orderBy('paid_date', 'desc')
            ->paginate(10, ['*'], 'paid_page');
 
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
 
        $thresholds = [
            'Regular' => [
                'green_max' => SystemSetting::get('regular_green_max', 10),
                'orange_max' => SystemSetting::get('regular_orange_max', 14),
            ],
            'Commercial' => [
                'green_max' => SystemSetting::get('commercial_green_max', 49),
                'orange_max' => SystemSetting::get('commercial_orange_max', 50),
            ],
        ];
 
        return view('billing.index', [
            'pendingBills' => $pendingBills,
            'paidBills' => $paidBills,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'totalBilled' => $totalBilled,
            'paidCustomersCount' => $paidCustomersCount,
            'unpaidCustomersCount' => $unpaidCustomersCount,
            'thresholds' => $thresholds,
        ]);
    }
 
    public function create(): View
    {
        $adminId = auth()->id();
        $customers = Customer::where('admin_id', $adminId)->where('status', 'active')->get();
        $thresholds = [
            'Regular' => [
                'green_max' => SystemSetting::get('regular_green_max', 10),
                'orange_max' => SystemSetting::get('regular_orange_max', 14),
            ],
            'Commercial' => [
                'green_max' => SystemSetting::get('commercial_green_max', 49),
                'orange_max' => SystemSetting::get('commercial_orange_max', 50),
            ],
        ];
 
        $globalAdditionalCharges = json_decode(SystemSetting::get('global_additional_charges', '[]'), true);
        $globalAdditionalChargeTotal = collect($globalAdditionalCharges)->sum('amount');
 
        return view('billing.create', compact('customers', 'thresholds', 'globalAdditionalCharges', 'globalAdditionalChargeTotal'));
    }
 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'billing_date' => 'required|date',
            'usage_units' => 'required|numeric',
            'consumption' => 'required|numeric',
            'base_charge' => 'required|numeric',
            'usage_charge' => 'required|numeric',
            'additional_charge_amount' => 'nullable|numeric|min:0',
            'additional_charge_note' => 'nullable|string',
            'due_date' => 'required|date|after:billing_date',
        ]);
 
        $globalAdditionalCharges = json_decode(SystemSetting::get('global_additional_charges', '[]'), true);
        $globalAdditionalChargeTotal = collect($globalAdditionalCharges)->sum('amount');

        $validated['applied_additional_charges'] = $globalAdditionalCharges;
        $validated['total_amount'] = ($validated['base_charge'] + $validated['usage_charge']) + (($validated['additional_charge_amount'] ?? 0) + $globalAdditionalChargeTotal);
        $validated['status'] = 'Pending';
 
        $bill = Bill::create($validated);
 
        $customer = Customer::find($validated['customer_id']);
        if ($customer) {
            $customer->update([
                'meter_reading' => $validated['usage_units']
            ]);
        }
 
        return redirect()->back()
            ->with('success', 'Bill created successfully');
    }
 
    public function show(Bill $bill): View
    {
        return view('billing.show', compact('bill'));
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
 
        return redirect()->route('billing.index')
            ->with('success', 'Bill marked as paid');
    }
 
    public function destroy(Bill $bill)
    {
        $customerId = $bill->customer_id;
        $bill->delete();

        // Update customer's current meter reading to the latest remaining bill's usage units
        $latestBill = Bill::where('customer_id', $customerId)
            ->orderBy('billing_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $customer = Customer::find($customerId);
        if ($customer) {
            $customer->update([
                'meter_reading' => $latestBill ? $latestBill->usage_units : 0
            ]);
        }

        return redirect()->back()
            ->with('success', 'Bill deleted successfully and meter reading reverted.');
    }
 
    public function getCustomerReadings(Customer $customer)
    {
        $readings = Bill::where('customer_id', $customer->id)
            ->orderBy('billing_date', 'desc')
            ->get(['billing_date', 'usage_units', 'total_amount', 'status']);
 
        return response()->json([
            'readings' => $readings,
            'customer' => $customer
        ]);
    }
    public function edit(Bill $bill): View
    {
        $adminId = auth()->id();
        $customers = Customer::where('admin_id', $adminId)->where('status', 'active')->get();
        $thresholds = [
            'Regular' => [
                'green_max' => SystemSetting::get('regular_green_max', 10),
                'orange_max' => SystemSetting::get('regular_orange_max', 14),
            ],
            'Commercial' => [
                'green_max' => SystemSetting::get('commercial_green_max', 49),
                'orange_max' => SystemSetting::get('commercial_orange_max', 50),
            ],
        ];

        $globalAdditionalChargeTotal = collect($bill->applied_additional_charges ?? [])->sum('amount');
 
        return view('billing.edit', compact('bill', 'customers', 'thresholds', 'globalAdditionalChargeTotal'));
    }
 
    public function update(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'billing_date' => 'required|date',
            'usage_units' => 'required|numeric',
            'consumption' => 'required|numeric',
            'base_charge' => 'required|numeric',
            'usage_charge' => 'required|numeric',
            'additional_charge_amount' => 'nullable|numeric|min:0',
            'additional_charge_note' => 'nullable|string',
            'due_date' => 'required|date|after:billing_date',
        ]);
 
        $globalAdditionalChargeTotal = collect($bill->applied_additional_charges ?? [])->sum('amount');
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
                    'meter_reading' => $bill->usage_units
                ]);
            }
        }

        return redirect()->route('billing.show', $bill)
            ->with('success', 'Bill updated successfully and meter reading synchronized.');
    }
}
