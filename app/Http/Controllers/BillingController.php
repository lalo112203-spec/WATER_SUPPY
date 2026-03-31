<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Customer;
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
        $totalBilled = Bill::whereIn('customer_id', $myCustomerIds)
            ->sum('total_amount');

        $thresholds = [
            'Regular' => [
                'green_max' => \App\Models\SystemSetting::get('regular_green_max', 10),
                'orange_max' => \App\Models\SystemSetting::get('regular_orange_max', 14),
            ],
            'Commercial' => [
                'green_max' => \App\Models\SystemSetting::get('commercial_green_max', 49),
                'orange_max' => \App\Models\SystemSetting::get('commercial_orange_max', 50),
            ],
        ];

        return view('billing.index', [
            'pendingBills' => $pendingBills,
            'paidBills' => $paidBills,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'totalBilled' => $totalBilled,
            'thresholds' => $thresholds,
        ]);
    }

    public function create(): View
    {
        $adminId = auth()->id();
        $customers = Customer::where('admin_id', $adminId)->where('status', 'active')->get();
        // load thresholds from settings so the form can colorize usage
        $thresholds = [
            'Regular' => [
                'green_max' => \App\Models\SystemSetting::get('regular_green_max', 10),
                'orange_max' => \App\Models\SystemSetting::get('regular_orange_max', 14),
            ],
            'Commercial' => [
                'green_max' => \App\Models\SystemSetting::get('commercial_green_max', 49),
                'orange_max' => \App\Models\SystemSetting::get('commercial_orange_max', 50),
            ],
        ];

        return view('billing.create', compact('customers', 'thresholds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'billing_date' => 'required|date',
            'usage_units' => 'required|numeric',
            'base_charge' => 'required|numeric',
            'usage_charge' => 'required|numeric',
            'due_date' => 'required|date|after:billing_date',
        ]);

        $validated['total_amount'] = $validated['base_charge'] + $validated['usage_charge'];
        $validated['status'] = 'Pending';

        Bill::create($validated);

        return redirect()->route('billing.index')
            ->with('success', 'Bill created successfully');
    }

    public function show(Bill $bill): View
    {
        return view('billing.show', compact('bill'));
    }

    public function receipt(Bill $bill): View
    {
        // Require the bill to be paid to view receipt
        if (strtolower($bill->status) !== 'paid') {
            abort(403, 'Receipt is only available for paid bills.');
        }
        
        // Ensure user is authorized if it's a consumer
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
        $bill->delete();

        return redirect()->route('billing.index')
            ->with('success', 'Bill deleted successfully');
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
}
