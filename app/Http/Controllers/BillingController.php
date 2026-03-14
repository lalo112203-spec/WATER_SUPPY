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
        $bills = Bill::with('customer')
            ->paginate(15);

        $paidCount = Bill::where('status', 'Paid')->count();
        $pendingCount = Bill::where('status', 'Pending')->count();
        $totalBilled = Bill::sum('total_amount');

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
            'bills' => $bills,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'totalBilled' => $totalBilled,
            'thresholds' => $thresholds,
        ]);
    }

    public function create(): View
    {
        $customers = Customer::where('status', 'active')->get();
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
