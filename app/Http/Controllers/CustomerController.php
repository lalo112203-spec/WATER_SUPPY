<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $adminId = auth()->id();
        $barangay = $request->input('barangay');

        $baseQuery = Customer::where('admin_id', $adminId);

        $totalCustomers = (clone $baseQuery)->count();
        // Stats should reflect the total state, not be filtered by the search bar or barangay
        $activeCustomers = (clone $baseQuery)->where('status', 'active')->count();

        // Self-healing: Ensure meter_reading for all customers matches their latest bill
        // This fixes any data inconsistency from previous deletions or manual edits
        foreach (Customer::where('admin_id', $adminId)->get() as $c) {
            $latest = $c->bills()->orderBy('billing_date', 'desc')->orderBy('id', 'desc')->first();
            $correctReading = $latest ? $latest->usage_units : 0;
            if ($c->meter_reading != $correctReading) {
                $c->update(['meter_reading' => $correctReading]);
            }
        }

        $unpaidCustomersCount = (clone $baseQuery)->whereHas('bills', function ($q) {
            $q->where('status', '!=', 'Paid');
        })->count();

        $paidCustomersCount = (clone $baseQuery)->whereHas('bills')
            ->whereDoesntHave('bills', function ($q) {
                $q->where('status', '!=', 'Paid');
            })->count();

        // Get list of barangays for the filter/grouping if needed
        $barangays = (clone $baseQuery)->whereNotNull('barangay')->distinct()->pluck('barangay')->sort();

        $search = $request->input('search');
        $customersQuery = clone $baseQuery;
        if ($search) {
            $customersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('customer_id', 'like', "%{$search}%");
            });
        }

        if ($barangay) {
            $customersQuery->where('barangay', $barangay);
        }

        $customers = $customersQuery->with(['user', 'bills'])
            ->orderBy('barangay', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        $monthExpr = "strftime('%Y-%m', created_at)";
        if ($driver === 'mysql' || $driver === 'mariadb') {
            $monthExpr = "DATE_FORMAT(created_at, '%Y-%m')";
        } elseif ($driver === 'pgsql') {
            $monthExpr = "to_char(created_at, 'YYYY-MM')";
        }

        // Get cumulative customer growth data for the last 6 months
        $startOfRange = now()->subMonths(5)->startOfMonth();
        $initialCount = (clone $baseQuery)->where('created_at', '<', $startOfRange)->count();
        
        $customerGrowth = collect();
        $runningTotal = $initialCount;
        
        for ($i = 5; $i >= 0; $i--) {
            $monthObj = now()->subMonths($i);
            $monthStr = $monthObj->format('Y-m');
            $newInMonth = (clone $baseQuery)->whereRaw("{$monthExpr} = ?", [$monthStr])->count();
            $runningTotal += $newInMonth;
            
            $customerGrowth->push([
                'month' => $monthObj->format('M Y'),
                'count' => $runningTotal
            ]);
        }

        return view('customers.index', [
            'customers' => $customers,
            'totalCustomers' => $totalCustomers,
            'activeCustomers' => $activeCustomers,
            'customerGrowth' => $customerGrowth,
            'paidCustomersCount' => $paidCustomersCount,
            'unpaidCustomersCount' => $unpaidCustomersCount,
            'barangays' => $barangays
        ]);
    }

    public function report(Request $request)
    {
        $adminId = auth()->id();
        // Default to current month if no month is specified
        $date = $request->filled('month') ? \Carbon\Carbon::parse($request->month) : now();
        $endOfPeriod = $date->isFuture() ? now() : $date->endOfMonth();

        // Get all customers who were registered up to the selected period
        $customers = Customer::where('admin_id', $adminId)
            ->where('created_at', '<=', $endOfPeriod)
            ->orderBy('barangay', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $monthName = $endOfPeriod->format('F Y');

        return view('customers.report', compact('customers', 'monthName'));
    }

    public function create(): View
    {
        $allIds = Customer::pluck('customer_id')->map(fn($val) => (int) $val)->toArray();
        $nextId = (count($allIds) > 0 ? max($allIds) : 1000) + 1;
        
        $barangays = Customer::where('admin_id', auth()->id())->whereNotNull('barangay')->distinct()->pluck('barangay')->sort();
        
        return view('customers.create', compact('nextId', 'barangays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|string|unique:customers,customer_id',
            'name' => 'required|string',
            'type' => 'required|in:Regular,Commercial',
            'barangay' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        $validated['barangay'] = strtoupper($validated['barangay']);
        $validated['address'] = $validated['barangay'] . ' DOLORES EASTERN SAMAR';

        if ($request->filled('customer_id')) {
            $validated['customer_id'] = $request->customer_id;
        } else {
            // Auto-generate customer ID if not provided. Use max existing customer_id integer.
            $allIds = Customer::pluck('customer_id')->map(fn($val) => (int) $val)->toArray();
            $nextId = (count($allIds) > 0 ? max($allIds) : 1000) + 1;
            $validated['customer_id'] = sprintf('%d', $nextId);
        }

        $customer = Customer::create([
            'admin_id' => auth()->id(),
            'name' => $validated['name'],
            'type' => $validated['type'],
            'email' => $validated['customer_id'] . '@system.local',
            'address' => $validated['address'],
            'barangay' => $validated['barangay'],
            'customer_id' => $validated['customer_id'],
        ]);

        if ($request->has('create_account') && $request->filled('password')) {
            $email = $customer->email;

            // Check if user already exists with this email to avoid 500 error
            if (\App\Models\User::withTrashed()->where('email', $email)->exists()) {
                // If it exists, update it instead of creating new one to avoid clash
                $existingUser = \App\Models\User::withTrashed()->where('email', $email)->first();
                $existingUser->restore();
                $existingUser->update([
                    'name' => $customer->name,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
                    'plain_password' => $request->input('password'),
                    'customer_id' => $customer->id,
                ]);
            } else {
                \App\Models\User::create([
                    'name' => $customer->name,
                    'email' => $email,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
                    'plain_password' => $request->input('password'),
                    'role' => 'consumer',
                    'customer_id' => $customer->id,
                    'email_verified_at' => now(),
                ]);
            }
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully');
    }



    public function edit(Customer $customer): View
    {
        $barangays = Customer::where('admin_id', auth()->id())->whereNotNull('barangay')->distinct()->pluck('barangay')->sort();
        
        return view('customers.edit', compact('customer', 'barangays'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|unique:customers,customer_id,' . $customer->id,
            'name' => 'required|string',
            'type' => 'required|in:Regular,Commercial',
            'barangay' => 'required|string',
        ]);

        $validated['barangay'] = strtoupper($validated['barangay']);
        $validated['address'] = $validated['barangay'] . ' DOLORES EASTERN SAMAR';

        // Keep existing email, just update others
        $customer->update([
            'customer_id' => $validated['customer_id'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'address' => $validated['address'],
            'barangay' => $validated['barangay'],
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully');
    }

    public function destroy(Customer $customer)
    {
        // Also delete the associated user account if it exists
        if ($customer->user) {
            $customer->user->delete();
        }

        // Also soft-delete associated bills and water usages
        $customer->bills()->delete();
        $customer->waterUsages()->delete();

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer and all associated data deleted successfully');
    }

    public function createAccount(Customer $customer)
    {
        if ($customer->user) {
            return back()->with('error', 'Account already exists.');
        }

        $password = \Illuminate\Support\Str::random(8);
        $email = $customer->customer_id . '@system.local';

        // Check for existing email to avoid Integrity Constraint Violation
        if (\App\Models\User::withTrashed()->where('email', $email)->exists()) {
            $existingUser = \App\Models\User::withTrashed()->where('email', $email)->first();

            // Re-sync if it belongs to this customer but somehow the link was lost or it was soft-deleted
            $existingUser->restore();
            $existingUser->update([
                'customer_id' => $customer->id,
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'plain_password' => $password,
            ]);

            return back()->with('success', 'Account updated successfully. Password reset to: ' . $password);
        }

        \App\Models\User::create([
            'name' => $customer->name,
            'email' => $email,
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
