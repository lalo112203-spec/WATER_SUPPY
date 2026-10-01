<?php
 
namespace App\Http\Controllers;
 
use App\Models\Bill;
use App\Models\Customer;
use App\Models\SystemSetting;
use Illuminate\View\View;
use Illuminate\Http\Request;
 
class BillingController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        if (auth()->user()->role === 'consumer') {
            return redirect()->route('dashboard');
        }

        $adminId = auth()->id();
        $search = $request->input('search');
        $rawMonth = $request->input('month');
        $selectedYear = $request->input('year');
        $selectedBarangay = $request->input('barangay');
        if ($selectedBarangay === 'all' || empty($selectedBarangay)) {
            $selectedBarangay = null;
        }
        $selectedMeterPost = $request->input('meter_post');
        if ($selectedMeterPost === 'all' || empty($selectedMeterPost)) {
            $selectedMeterPost = null;
        }
        $sort = $request->input('sort', 'latest');
        $myCustomerIds = Customer::where('admin_id', $adminId)->pluck('id')->toArray();

        $availableBarangays = Customer::whereIn('id', $myCustomerIds)
            ->whereNotNull('barangay')
            ->where('barangay', '!=', '')
            ->distinct()
            ->orderBy('barangay', 'asc')
            ->pluck('barangay');

        $availableMeterPosts = Customer::whereIn('id', $myCustomerIds)
            ->whereNotNull('meter_post')
            ->where('meter_post', '!=', '')
            ->distinct()
            ->orderBy('meter_post', 'asc')
            ->pluck('meter_post');

        $selectedMonth = null;
        if ($rawMonth && $rawMonth !== 'all') {
            if (str_contains($rawMonth, '-')) {
                $parts = explode('-', $rawMonth);
                if (count($parts) === 2) {
                    $selectedYear = $selectedYear ?: $parts[0];
                    $selectedMonth = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                }
            } else {
                $selectedMonth = str_pad($rawMonth, 2, '0', STR_PAD_LEFT);
            }
        }

        if ($selectedYear === 'all' || empty($selectedYear)) {
            $selectedYear = null;
        }

        $allMonths = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        // Get list of all available dates from actual bills
        $allBillsDates = Bill::whereIn('customer_id', $myCustomerIds)
            ->whereNotNull('billing_date')
            ->select('billing_date')
            ->orderBy('billing_date', 'desc')
            ->get();

        $billYears = $allBillsDates->map(function ($b) {
            return $b->billing_date ? (int) $b->billing_date->format('Y') : null;
        })->filter()->unique()->values()->toArray();

        $currentYear = (int) now()->year;
        $maxYear = 2100;
        $quickYears = range($currentYear + 4, $currentYear - 6);
        $extraYears = array_filter([$selectedYear ? (int) $selectedYear : null]);
        $combinedYears = array_unique(array_merge($billYears, $quickYears, $extraYears));
        rsort($combinedYears);
        $availableYears = collect($combinedYears)->values();

        $availableMonths = $allBillsDates->groupBy(function ($b) {
            return $b->billing_date->format('Y-m');
        })->map(function ($group, $key) {
            return [
                'key' => $key,
                'label' => $group->first()->billing_date->format('F Y'),
                'count' => $group->count(),
            ];
        })->values();

        $pendingQuery = Bill::with(['customer' => function ($query) { $query->withTrashed(); }])
            ->whereIn('bills.customer_id', $myCustomerIds)
            ->where('bills.status', '!=', 'Paid');

        $paidQuery = Bill::with(['customer' => function ($query) { $query->withTrashed(); }])
            ->whereIn('bills.customer_id', $myCustomerIds)
            ->where('bills.status', 'Paid');

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

        if ($selectedYear) {
            $pendingQuery->whereYear('bills.billing_date', $selectedYear);
            $paidQuery->whereYear('bills.billing_date', $selectedYear);
        }

        if ($selectedMonth) {
            $pendingQuery->whereMonth('bills.billing_date', (int)$selectedMonth);
            $paidQuery->whereMonth('bills.billing_date', (int)$selectedMonth);
        }

        if ($selectedBarangay) {
            $pendingQuery->whereHas('customer', function ($q) use ($selectedBarangay) {
                $q->where('barangay', $selectedBarangay);
            });
            $paidQuery->whereHas('customer', function ($q) use ($selectedBarangay) {
                $q->where('barangay', $selectedBarangay);
            });
        }

        if ($selectedMeterPost) {
            $pendingQuery->whereHas('customer', function ($q) use ($selectedMeterPost) {
                $q->where('meter_post', $selectedMeterPost);
            });
            $paidQuery->whereHas('customer', function ($q) use ($selectedMeterPost) {
                $q->where('meter_post', $selectedMeterPost);
            });
        }

        if ($sort === 'oldest') {
            $pendingQuery->orderBy('bills.billing_date', 'asc');
            $paidQuery->orderBy('bills.billing_date', 'asc');
        } elseif ($sort === 'consumer_asc') {
            $pendingQuery->join('customers as cp', 'bills.customer_id', '=', 'cp.id')->orderBy('cp.name', 'asc')->select('bills.*');
            $paidQuery->join('customers as cpd', 'bills.customer_id', '=', 'cpd.id')->orderBy('cpd.name', 'asc')->select('bills.*');
        } else {
            $pendingQuery->orderBy('bills.billing_date', 'desc');
            $paidQuery->orderBy('bills.paid_date', 'desc');
        }

        $pendingBills = $pendingQuery->paginate(10, ['*'], 'pending_page')->withQueryString();
        $paidBills = $paidQuery->paginate(10, ['*'], 'paid_page')->withQueryString();

        // Query for monthly consumers list (Item 5)
        $monthlyQuery = Bill::with(['customer' => function ($query) { $query->withTrashed(); }])
            ->whereIn('bills.customer_id', $myCustomerIds);

        if ($search) {
            $monthlyQuery->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%")
                       ->orWhere('customer_id', 'like', "%{$search}%");
                });
            });
        }

        if ($selectedYear) {
            $monthlyQuery->whereYear('bills.billing_date', $selectedYear);
        }

        if ($selectedMonth) {
            $monthlyQuery->whereMonth('bills.billing_date', (int)$selectedMonth);
        }

        if ($selectedBarangay) {
            $monthlyQuery->whereHas('customer', function ($q) use ($selectedBarangay) {
                $q->where('barangay', $selectedBarangay);
            });
        }

        if ($selectedMeterPost) {
            $monthlyQuery->whereHas('customer', function ($q) use ($selectedMeterPost) {
                $q->where('meter_post', $selectedMeterPost);
            });
        }

        $statusFilter = $request->input('status', 'all');
        if ($statusFilter && $statusFilter !== 'all') {
            if (in_array(strtolower($statusFilter), ['unpaid', 'pending'])) {
                $monthlyQuery->whereNotIn('bills.status', ['Paid', 'paid']);
            } else {
                $monthlyQuery->where('bills.status', ucfirst($statusFilter));
            }
        }

        if ($sort === 'oldest') {
            $monthlyQuery->orderBy('bills.billing_date', 'asc');
        } elseif ($sort === 'consumer_asc') {
            $monthlyQuery->join('customers as mc', 'bills.customer_id', '=', 'mc.id')
                ->orderBy('mc.name', 'asc')
                ->select('bills.*');
        } else {
            $monthlyQuery->orderBy('bills.billing_date', 'desc');
        }

        $monthlyBills = $monthlyQuery->get();
        $monthlyBillingRecords = $monthlyBills->groupBy(function ($b) {
            return $b->billing_date->format('F Y');
        });

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

        $customers = Customer::where('admin_id', $adminId)->where('status', 'active')->withCount('bills')->get();

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
            'globalAdditionalChargeTotal' => $globalAdditionalChargeTotal,
            'availableMonths' => $availableMonths,
            'allMonths' => $allMonths,
            'availableYears' => $availableYears,
            'maxYear' => $maxYear,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'selectedBarangay' => $selectedBarangay,
            'selectedMeterPost' => $selectedMeterPost,
            'availableBarangays' => $availableBarangays,
            'availableMeterPosts' => $availableMeterPosts,
            'sort' => $sort,
            'monthlyBillingRecords' => $monthlyBillingRecords,
        ]);
    }
 

 
    public function store(Request $request)
    {
        if (auth()->user()->role === 'consumer') {
            abort(403);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'billing_date' => 'nullable|date',
            'new_reading' => 'required|numeric|min:0',
            'consumption' => 'nullable|numeric|min:0',
            'base_charge' => 'nullable|numeric',
            'usage_charge' => 'nullable|numeric',
            'additional_charge_amount' => 'nullable|numeric|min:0',
            'additional_charge_note' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $validated['billing_date'] = $validated['billing_date'] ?? now()->format('Y-m-d');
        $validated['due_date'] = $validated['due_date'] ?? \Carbon\Carbon::parse($validated['billing_date'])->addDays(30)->format('Y-m-d');

        // Check for an existing bill in the same month
        $billingDate = \Carbon\Carbon::parse($validated['billing_date']);
        $existingBill = Bill::where('customer_id', $validated['customer_id'])
            ->whereYear('billing_date', $billingDate->year)
            ->whereMonth('billing_date', $billingDate->month)
            ->first();

        if ($existingBill && !$request->boolean('force_billing')) {
            $customer = Customer::find($validated['customer_id']);
            return redirect()->back()
                ->withInput()
                ->with('billing_warning', [
                    'customer_id'     => $validated['customer_id'],
                    'customer_name'   => $customer?->name ?? 'This customer',
                    'new_reading'     => $validated['new_reading'],
                    'consumption'     => $validated['consumption'] ?? null,
                    'base_charge'     => $validated['base_charge'] ?? null,
                    'usage_charge'    => $validated['usage_charge'] ?? null,
                    'additional_charge_amount' => $validated['additional_charge_amount'] ?? null,
                    'month'           => \Carbon\Carbon::parse($existingBill->billing_date)->format('F Y'),
                    'amount'          => number_format($existingBill->total_amount, 2),
                    'bill_id'         => $existingBill->id,
                ]);
        }

        $globalAdditionalCharges = json_decode(SystemSetting::get('global_additional_charges', '[]'), true);
        $globalAdditionalChargeTotal = collect($globalAdditionalCharges)->sum('amount');

        $customer = Customer::find($validated['customer_id']);
        $previousReading = $customer ? ($customer->meter_reading ?? 0) : 0;
        $newReading = (float) $validated['new_reading'];
        $usage = max(0, $newReading - $previousReading);

        // Fetch customer type rates dynamically if charges are not passed
        $customerType = $customer ? $customer->customerType : null;
        if (!$customerType && $customer) {
            $customerType = \App\Models\CustomerType::where('name', $customer->type)->first();
        }

        $baseRate = $customerType ? $customerType->base_charge : 150;
        $usageRate = $customerType ? $customerType->usage_rate : 15;
        $baseLimit = $customerType ? $customerType->base_limit : 10;

        $baseCharge = (isset($validated['base_charge']) && is_numeric($validated['base_charge'])) ? (float)$validated['base_charge'] : $baseRate;
        
        $isFirstReading = !Bill::where('customer_id', $validated['customer_id'])->exists();

        if (!$isFirstReading && $newReading <= $previousReading) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['new_reading' => 'Present reading must be greater than previous reading (' . $previousReading . ' m³).']);
        }

        if ($isFirstReading) {
            $usageCharge = 0.0;
        } elseif (isset($validated['usage_charge']) && is_numeric($validated['usage_charge'])) {
            $usageCharge = (float)$validated['usage_charge'];
        } else {
            $billableUsage = max(0, $usage - $baseLimit);
            $usageCharge = $billableUsage * $usageRate;
        }

        $validated['previous_reading'] = $previousReading;
        $validated['new_reading'] = $newReading;
        $validated['usage_units'] = $usage;
        $validated['consumption'] = $usage;
        $validated['base_charge'] = $baseCharge;
        $validated['usage_charge'] = $usageCharge;
        $validated['applied_additional_charges'] = $globalAdditionalCharges;
        $validated['total_amount'] = ($baseCharge + $usageCharge) + (($validated['additional_charge_amount'] ?? 0) + $globalAdditionalChargeTotal);
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
                
                try {
                    $customer->user->notify(new \App\Notifications\NewBillPushNotification($validated['total_amount'], \Carbon\Carbon::parse($validated['due_date'])->format('M d, Y')));
                } catch (\Throwable $e) {
                    // Ignore push notification errors to avoid breaking bill generation
                }
            }
        }

        return redirect()->back()
            ->with('success', 'Bill created successfully');
    }
 
    public function show(Bill $bill): View
    {
        if (auth()->user()->role === 'consumer' && auth()->user()->customer_id !== $bill->customer_id) {
            abort(403);
        }

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

    public function printBatch(Request $request): View
    {
        $request->validate([
            'bill_ids' => 'required|array',
            'bill_ids.*' => 'exists:bills,id'
        ]);

        $bills = Bill::whereIn('id', $request->bill_ids)->get();

        if (auth()->user()->role === 'consumer') {
            foreach ($bills as $bill) {
                if (auth()->user()->customer_id !== $bill->customer_id) {
                    abort(403);
                }
            }
        }

        return view('billing.receipt-batch', compact('bills'));
    }
 
    public function markAsPaid(Request $request, Bill $bill)
    {
        if (auth()->user()->role === 'consumer') {
            abort(403);
        }

        // Additional payment verification process (Item 4)
        if ($request->has('payment_amount')) {
            $request->validate([
                'payment_amount' => 'required|numeric',
                'or_number' => 'nullable|string|max:50',
            ]);

            $enteredAmount = (float) $request->input('payment_amount');
            if (abs($enteredAmount - (float) $bill->total_amount) > 0.01) {
                return redirect()->back()
                    ->with('error', 'Payment amount is incorrect. Expected ₱' . number_format($bill->total_amount, 2) . ', but entered ₱' . number_format($enteredAmount, 2) . '.');
            }
        }

        $orNumber = $request->input('or_number') ?: ($bill->or_number ?: 'OR-' . str_pad($bill->id, 6, '0', STR_PAD_LEFT));

        $bill->update([
            'status' => 'Paid',
            'paid_date' => now(),
            'or_number' => $orNumber,
        ]);
        
        // Notify the consumer device/account via in-app message
        if ($bill->customer && $bill->customer->user) {
            \App\Models\Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $bill->customer->user->id,
                'message' => 'Your bill from ' . $bill->billing_date->format('M d, Y') . ' for the amount of ' . number_format($bill->total_amount, 2) . ' has been successfully marked as paid with OR #' . $orNumber . '. Thank you!',
            ]);
            
            // Dispatch Web Push Notification
            try {
                $bill->customer->user->notify(new \App\Notifications\BillPaidPushNotification($bill->total_amount, $bill->billing_date->format('M d, Y')));
            } catch (\Throwable $e) {
                // Ignore push notification errors
            }
        }
 
        return redirect()->route('billing.index')
            ->with('success', 'Bill for ' . ($bill->customer?->name ?? 'Consumer') . ' marked as paid with OR #' . $orNumber . '.');
    }
 
    public function destroy(Bill $bill)
    {
        if (auth()->user()->role === 'consumer') {
            abort(403);
        }

        $customerId = $bill->customer_id;
        $previousReading = $bill->previous_reading;
        $bill->delete();

        // Update customer's current meter reading to the latest remaining bill's new reading,
        // or revert to the deleted bill's previous reading if no other bills remain
        $latestBill = Bill::where('customer_id', $customerId)
            ->orderBy('billing_date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $customer = Customer::find($customerId);
        if ($customer) {
            $customer->update([
                'meter_reading' => $latestBill ? $latestBill->new_reading : ($previousReading ?? 0)
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
        if (auth()->user()->role === 'consumer') {
            abort(403);
        }

        $validated = $request->validate([
            'new_reading' => 'required|numeric|min:0',
            'billing_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'additional_charge_amount' => 'nullable|numeric|min:0',
            'additional_charge_note' => 'nullable|string',
        ]);
 
        $newReading = (float) $validated['new_reading'];
        $previousReading = (float) ($bill->previous_reading ?? 0);
        $isFirstBill = $bill->isFirstBill();

        if (!$isFirstBill && $newReading <= $previousReading) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['new_reading' => 'Present reading must be greater than previous reading (' . $previousReading . ' m³).']);
        }

        $usage = max(0, $newReading - $previousReading);

        $customer = Customer::find($bill->customer_id);
        $customerType = $customer ? $customer->customerType : null;
        if (!$customerType && $customer) {
            $customerType = \App\Models\CustomerType::where('name', $customer->type)->first();
        }
        $baseRate = $customerType ? (float)$customerType->base_charge : 150.0;
        $usageRate = $customerType ? (float)$customerType->usage_rate : 15.0;
        $baseLimit = $customerType ? (float)$customerType->base_limit : 10.0;

        $baseCharge = $baseRate;
        $usageCharge = $isFirstBill ? 0.0 : (max(0, $usage - $baseLimit) * $usageRate);

        $globalAdditionalChargeTotal = collect($bill->applied_additional_charges ?? [])->sum('amount');
        $additionalAmount = isset($validated['additional_charge_amount']) ? (float)$validated['additional_charge_amount'] : (float)($bill->additional_charge_amount ?? 0);
        $totalAmount = $baseCharge + $usageCharge + $additionalAmount + $globalAdditionalChargeTotal;

        $bill->update([
            'new_reading' => $newReading,
            'usage_units' => $usage,
            'consumption' => $usage,
            'base_charge' => $baseCharge,
            'usage_charge' => $usageCharge,
            'additional_charge_amount' => $additionalAmount,
            'additional_charge_note' => $validated['additional_charge_note'] ?? $bill->additional_charge_note,
            'billing_date' => $validated['billing_date'] ?? $bill->billing_date,
            'due_date' => $validated['due_date'] ?? $bill->due_date,
            'total_amount' => $totalAmount,
        ]);

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
