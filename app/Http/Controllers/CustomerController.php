<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(): View
    {
        $adminId = auth()->id();
        $customersQuery = Customer::where('admin_id', $adminId);
        
        $totalCustomers = (clone $customersQuery)->count();
        $activeCustomers = (clone $customersQuery)->where('status', 'active')->count();
        $customers = $customersQuery->paginate(15);
        
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        $monthExpr = "strftime('%Y-%m', created_at)";
        if ($driver === 'mysql' || $driver === 'mariadb') {
            $monthExpr = "DATE_FORMAT(created_at, '%Y-%m')";
        } elseif ($driver === 'pgsql') {
            $monthExpr = "to_char(created_at, 'YYYY-MM')";
        }

        // Get customer growth data for the chart, specific to this admin
        $customerGrowth = (clone $customersQuery)->selectRaw("{$monthExpr} as month, COUNT(*) as count")
            ->groupByRaw($monthExpr)
            ->orderByRaw("{$monthExpr} asc")
            ->get();

        return view('customers.index', compact('customers', 'totalCustomers', 'activeCustomers', 'customerGrowth'));
    }

    public function create(): View
    {
        $allIds = Customer::pluck('customer_id')->map(fn($val) => (int)$val)->toArray();
        $nextId = (count($allIds) > 0 ? max($allIds) : 1000) + 1;
        return view('customers.create', compact('nextId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|string|unique:customers,customer_id',
            'name' => 'required|string',
            'type' => 'required|in:Regular,Commercial',
            'address' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->filled('customer_id')) {
            $validated['customer_id'] = $request->customer_id;
        } else {
            // Auto-generate customer ID if not provided. Use max existing customer_id integer.
            $allIds = Customer::pluck('customer_id')->map(fn($val) => (int)$val)->toArray();
            $nextId = (count($allIds) > 0 ? max($allIds) : 1000) + 1;
            $validated['customer_id'] = sprintf('%d', $nextId);
        }

        $customer = Customer::create([
            'admin_id' => auth()->id(),
            'name' => $validated['name'],
            'type' => $validated['type'],
            'email' => $validated['customer_id'] . '@system.local',
            'address' => $validated['address'],
            'customer_id' => $validated['customer_id'],
        ]);

        // automatically create initial bill
        // base charge depends on customer type
        $baseCharge = $customer->type === 'Commercial' ? 250 : 100;
        // initial usage 0, so usage charge = max(0 - 10,0) * rate = 0
        $rate = $customer->type === 'Commercial' ? 25 : 15;
        $usageCharge = 0;
        $totalAmount = $baseCharge + $usageCharge;

        
        
        \App\Models\Bill::create([
            'customer_id' => $customer->id,
            'billing_date' => now(),
            'usage_units' => 0,
            'base_charge' => $baseCharge,
            'usage_charge' => $usageCharge,
            'total_amount' => $totalAmount,
            'status' => 'Pending',
            'due_date' => now()->addDays(30),
        ]);

        if ($request->has('create_account') && $request->filled('password')) {
            \App\Models\User::create([
                'name' => $customer->name,
                'email' => $customer->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
                'plain_password' => $request->input('password'),
                'role' => 'consumer',
                'customer_id' => $customer->id,
                'email_verified_at' => now(),
            ]);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully');
    }

    public function show(Customer $customer): View
    {
        $customer->load(['waterUsages', 'bills']);
        $settings = [
            'regular_green_max' => \App\Models\SystemSetting::get('regular_green_max', 10),
            'commercial_green_max' => \App\Models\SystemSetting::get('commercial_green_max', 49),
            'regular_orange_max' => \App\Models\SystemSetting::get('regular_orange_max', 14),
            'commercial_orange_max' => \App\Models\SystemSetting::get('commercial_orange_max', 50),
        ];
        return view('customers.show', compact('customer', 'settings'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|unique:customers,customer_id,' . $customer->id,
            'name' => 'required|string',
            'type' => 'required|in:Regular,Commercial',
            'address' => 'required|string',
        ]);

        // Keep existing email, just update others
        $customer->update([
            'customer_id' => $validated['customer_id'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully');
    }

    public function createAccount(Customer $customer)
    {
        if ($customer->user) {
            return back()->with('error', 'Account already exists.');
        }

        $password = \Illuminate\Support\Str::random(8);

        \App\Models\User::create([
            'name' => $customer->name,
            'email' => $customer->customer_id . '@system.local',
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'plain_password' => $password,
            'role' => 'consumer',
            'customer_id' => $customer->id,
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'Account created successfully. Default password is: ' . $password);
    }

    public function updatePassword(Request $request, Customer $customer)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!$customer->user) {
            return back()->with('error', 'Customer does not have an account.');
        }

        $customer->user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'plain_password' => $request->password,
        ]);

        return back()->with('success', 'Customer account password updated successfully.');
    }
}
