<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        if (auth()->user()->role === 'consumer') {
            return redirect()->route('dashboard');
        }

        $adminId = auth()->id();
        $barangay = $request->input('barangay');

        $baseQuery = Customer::where('admin_id', $adminId);

        $totalCustomers = (clone $baseQuery)->count();
        // Stats should reflect the total state, not be filtered by the search bar or barangay
        $activeCustomers = (clone $baseQuery)->where('status', 'active')->count();

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
 
        $settings = \App\Models\SystemSetting::pluck('value', 'key')
            ->toArray();
 
        $globalAdditionalCharges = $settings['global_additional_charges'] ?? [];
        if (is_string($globalAdditionalCharges)) {
            $globalAdditionalCharges = json_decode($globalAdditionalCharges, true) ?? [];
        }
        $globalAdditionalChargeTotal = collect($globalAdditionalCharges)->sum('amount');
 
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

        $allIds = Customer::withTrashed()->pluck('customer_id')->map(fn($val) => (int) $val)->toArray();
        $nextId = (count($allIds) > 0 ? max($allIds) : 1000) + 1;

        $customerTypes = \App\Models\CustomerType::all();

        foreach ($customerTypes as $type) {
            $lowerName = strtolower($type->name);
            $settings[$lowerName.'_base_charge'] = $type->base_charge;
            $settings[$lowerName.'_usage_rate'] = $type->usage_rate;
            $settings[$lowerName.'_base_limit'] = $type->base_limit;
        }

        return view('customers.index', [
            'customers' => $customers,
            'Consumers' => $customers,
            'totalCustomers' => $totalCustomers,
            'activeCustomers' => $activeCustomers,
            'customerGrowth' => $customerGrowth,
            'paidCustomersCount' => $paidCustomersCount,
            'unpaidCustomersCount' => $unpaidCustomersCount,
            'barangays' => $barangays,
            'settings' => $settings,
            'globalAdditionalChargeTotal' => $globalAdditionalChargeTotal,
            'nextId' => $nextId,
            'customerTypes' => $customerTypes
        ]);
    }

    public function report(Request $request)
    {
        if (auth()->user()->role === 'consumer') {
            return redirect()->route('dashboard');
        }

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

        return view('customers.report', [
            'customers' => $customers,
            'Consumers' => $customers,
            'monthName' => $monthName
        ]);
    }



    public function store(Request $request)
    {
        if (auth()->user()->role === 'consumer') {
            abort(403);
        }

        $validated = $request->validate([
            'customer_id' => 'nullable|string|unique:customers,customer_id',
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'customer_type_id' => 'required|exists:customer_types,id',
            'meter_post' => 'required|string',
            'phone_number' => 'nullable|string',
            'barangay' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        $customerType = \App\Models\CustomerType::find($validated['customer_type_id']);

        $fullName = trim(strtoupper($validated['last_name']) . ', ' . strtoupper($validated['first_name']) . ' ' . strtoupper($validated['middle_name'] ?? ''));
        $validated['name'] = preg_replace('/\s+/', ' ', $fullName);

        $validated['barangay'] = strtoupper($validated['barangay']);
        $validated['address'] = $validated['barangay'] . ' DOLORES EASTERN SAMAR';

        if ($request->filled('customer_id')) {
            $validated['customer_id'] = $request->customer_id;
        } else {
            // Auto-generate customer ID if not provided. Use max existing customer_id integer (including trashed).
            $allIds = Customer::withTrashed()->pluck('customer_id')->map(fn($val) => (int) $val)->toArray();
            $nextId = (count($allIds) > 0 ? max($allIds) : 1000) + 1;
            $validated['customer_id'] = sprintf('%d', $nextId);
        }

        $customer = Customer::create([
            'admin_id' => auth()->id(),
            'name' => $validated['name'],
            'type' => $customerType->name,
            'customer_type_id' => $validated['customer_type_id'],
            'email' => $validated['customer_id'] . '@system.local',
            'meter_post' => $validated['meter_post'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'address' => $validated['address'],
            'barangay' => $validated['barangay'],
            'customer_id' => $validated['customer_id'],
        ]);

        if ($request->has('create_account') && $request->filled('password')) {
            $username = $request->filled('username') ? trim($request->input('username')) : $customer->customer_id;
            $email = str_contains($username, '@') ? $username : ($customer->customer_id . '@system.local');

            // If username is already taken by another user, fallback to customer_id
            if (\App\Models\User::withTrashed()->where('username', $username)->where(function($q) use ($customer) {
                $q->whereNull('customer_id')->orWhere('customer_id', '!=', $customer->id);
            })->exists()) {
                $username = $customer->customer_id;
            }

            // Check if user already exists
            $existingUser = \App\Models\User::withTrashed()
                ->where('customer_id', $customer->id)
                ->orWhere('email', $email)
                ->orWhere('username', $username)
                ->first();

            if ($existingUser) {
                $existingUser->restore();
                $existingUser->update([
                    'name' => $customer->name,
                    'username' => $username,
                    'email' => $email,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
                    'plain_password' => $request->input('password'),
                    'customer_id' => $customer->id,
                ]);
            } else {
                \App\Models\User::create([
                    'name' => $customer->name,
                    'username' => $username,
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





    public function update(Request $request, Customer $customer)
    {
        if (auth()->user()->role === 'consumer') {
            abort(403);
        }

        $validated = $request->validate([
            'customer_id' => 'required|string|unique:customers,customer_id,' . $customer->id,
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'customer_type_id' => 'required|exists:customer_types,id',
            'meter_post' => 'required|string',
            'phone_number' => 'nullable|string',
            'barangay' => 'required|string',
        ]);

        $customerType = \App\Models\CustomerType::find($validated['customer_type_id']);

        $fullName = trim(strtoupper($validated['last_name']) . ', ' . strtoupper($validated['first_name']) . ' ' . strtoupper($validated['middle_name'] ?? ''));
        $validated['name'] = preg_replace('/\s+/', ' ', $fullName);

        $validated['barangay'] = strtoupper($validated['barangay']);
        $validated['address'] = $validated['barangay'] . ' DOLORES EASTERN SAMAR';

        $newEmail = $validated['customer_id'] . '@system.local';

        $customer->update([
            'customer_id' => $validated['customer_id'],
            'email' => $newEmail,
            'name' => $validated['name'],
            'type' => $customerType->name,
            'customer_type_id' => $validated['customer_type_id'],
            'meter_post' => $validated['meter_post'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'address' => $validated['address'],
            'barangay' => $validated['barangay'],
        ]);

        if ($customer->user) {
            $customer->user->update([
                'name' => $validated['name'],
                'email' => $newEmail,
            ]);
        }

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully');
    }

    public function destroy(Customer $customer)
    {
        if (auth()->user()->role === 'consumer') {
            abort(403);
        }

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

    public function createAccount(Request $request, Customer $customer)
    {
        if (auth()->user()->role === 'consumer') {
            abort(403);
        }

        if ($customer->user) {
            return back()->with('error', 'Account already exists for this consumer.');
        }

        $request->validate([
            'username' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
        ]);

        $password = $request->input('password') ?: \Illuminate\Support\Str::random(8);
        $username = $request->filled('username') ? trim($request->input('username')) : $customer->customer_id;
        $email = str_contains($username, '@') ? $username : ($customer->customer_id . '@system.local');

        // Check for existing username on another user
        $usernameConflict = \App\Models\User::withTrashed()
            ->where(function($q) use ($username, $email) {
                $q->where('username', $username)
                  ->orWhere('email', $email);
            })
            ->where(function($q) use ($customer) {
                $q->whereNull('customer_id')
                  ->orWhere('customer_id', '!=', $customer->id);
            })
            ->exists();

        if ($usernameConflict) {
            return back()->with('error', "The username '{$username}' is already taken by another account. Please choose a different username.");
        }

        // Check for existing user linked to this customer or matching email/username
        $existingUser = \App\Models\User::withTrashed()
            ->where('customer_id', $customer->id)
            ->orWhere('email', $email)
            ->orWhere('username', $username)
            ->first();

        if ($existingUser) {
            $existingUser->restore();
            $existingUser->update([
                'name' => $customer->name,
                'username' => $username,
                'email' => $email,
                'customer_id' => $customer->id,
                'password' => \Illuminate\Support\Facades\Hash::make($password),
                'plain_password' => $password,
                'role' => 'consumer',
            ]);

            return back()->with('success', "Account updated successfully for {$customer->name}! Username: {$username}");
        }

        \App\Models\User::create([
            'name' => $customer->name,
            'username' => $username,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'plain_password' => $password,
            'role' => 'consumer',
            'customer_id' => $customer->id,
            'email_verified_at' => now(),
        ]);

        return back()->with('success', "Account created successfully for {$customer->name}! Username: {$username}");
    }

    public function updatePassword(Request $request, Customer $customer)
    {
        if (auth()->user()->role === 'consumer') {
            abort(403);
        }

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

    public function printBillingHistory(Customer $customer): View
    {
        $user = auth()->user();
        if ($user->role === 'consumer' && (int)$user->customer_id !== (int)$customer->id) {
            abort(403, 'Unauthorized access to customer billing history.');
        }

        $selectedBillIds = request()->input('bill_ids');
        $customer->load(['customerType', 'bills' => function ($q) use ($selectedBillIds) {
            if (!empty($selectedBillIds)) {
                $ids = is_array($selectedBillIds) ? $selectedBillIds : explode(',', $selectedBillIds);
                $q->whereIn('id', $ids);
            }
            $q->orderBy('billing_date', 'asc');
        }]);

        $disconnectionThreshold = (int) \App\Models\SystemSetting::get('disconnection_unpaid_months', 4);
        $totalBilled = (float) $customer->bills->sum('total_amount');
        $totalPaid = (float) $customer->bills->where('status', 'Paid')->sum('total_amount');
        $totalUnpaid = (float) $customer->bills->where('status', '!=', 'Paid')->sum('total_amount');
        $unpaidBills = $customer->bills->where('status', '!=', 'Paid');

        return view('customers.billing-history-print', compact(
            'customer',
            'disconnectionThreshold',
            'totalBilled',
            'totalPaid',
            'totalUnpaid',
            'unpaidBills'
        ));
    }

    public function sendDisconnectionNotice(Request $request, Customer $customer)
    {
        if (auth()->user()->role === 'consumer') {
            abort(403);
        }

        $threshold = (int) \App\Models\SystemSetting::get('disconnection_unpaid_months', 4);
        $unpaidCount = $customer->unpaid_bills_count;
        $unpaidTotal = $customer->unpaid_bills_total;

        if ($customer->user) {
            $noticeText = "URGENT DISCONNECTION NOTICE: Dear {$customer->name}, your account ({$customer->customer_id}) has {$unpaidCount} unpaid bill(s) totaling ₱" . number_format($unpaidTotal, 2) . ". In accordance with our water service policy ({$threshold} months unpaid threshold), your service is subject to disconnection. Please settle your accounts immediately.";

            \App\Models\Message::create([
                'sender_id' => auth()->id(),
                'receiver_id' => $customer->user->id,
                'message' => $noticeText,
            ]);

            try {
                $customer->user->notify(new \App\Notifications\DisconnectionWarningNotification($unpaidCount, $unpaidTotal));
            } catch (\Exception $e) {
                // Ignore web push exception if client isn't actively listening
            }
        }

        return back()->with('success', "Disconnection notice successfully sent to {$customer->name} (Account #{$customer->customer_id}).");
    }
}
