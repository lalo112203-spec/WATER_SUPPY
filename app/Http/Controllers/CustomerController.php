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
        $search = $request->input('search');

        $baseQuery = Customer::where('admin_id', $adminId);

        // Stats should reflect the total state, not be filtered by the search bar
        $totalCustomers = (clone $baseQuery)->count();
        $activeCustomers = (clone $baseQuery)->where('status', 'active')->count();

        $customersQuery = clone $baseQuery;
        if ($search) {
            $customersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('customer_id', 'like', "%{$search}%");
            });
        }

        $customers = $customersQuery->with(['user', 'bills'])->paginate(15)->withQueryString();

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
        $allIds = Customer::pluck('customer_id')->map(fn($val) => (int) $val)->toArray();
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
            'customer_id' => $validated['customer_id'],
        ]);

        if ($request->has('create_account') && $request->filled('password')) {
            $email = $customer->email;

            // Check if user already exists with this email to avoid 500 error
            if (\App\Models\User::where('email', $email)->exists()) {
                // If it exists, update it instead of creating new one to avoid clash
                $existingUser = \App\Models\User::where('email', $email)->first();
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
        // Also delete the associated user account if it exists
        if ($customer->user) {
            $customer->user->delete();
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer and associated account deleted successfully');
    }

    public function createAccount(Customer $customer)
    {
        if ($customer->user) {
            return back()->with('error', 'Account already exists.');
        }

        $password = \Illuminate\Support\Str::random(8);
        $email = $customer->customer_id . '@system.local';

        // Check for existing email to avoid Integrity Constraint Violation
        if (\App\Models\User::where('email', $email)->exists()) {
            $existingUser = \App\Models\User::where('email', $email)->first();

            // Re-sync if it belongs to this customer but somehow the link was lost
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
