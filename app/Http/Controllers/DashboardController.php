<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Bill;
use App\Models\WaterUsage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|\Illuminate\Http\RedirectResponse
    {
        try {
            $user = auth()->user();
            if ($user->role === 'consumer') {
                $posts = \App\Models\Post::with('admin')->orderBy('created_at', 'desc')->get();
                $customer = \App\Models\Customer::with([
                    'bills' => function ($q) {
                        $q->orderBy('billing_date', 'desc');
                    },
                    'waterUsages'
                ])->find($user->customer_id);

                return view('dashboard.consumer', compact('customer', 'posts'));
            }

            $adminId = auth()->id();

            // Pre-fetch customer IDs specifically for this admin to speed up other queries
            $myCustomerIds = Customer::where('admin_id', $adminId)->pluck('id')->toArray();

            $totalCustomers = count($myCustomerIds);

            $totalRevenue = Bill::where('status', 'Paid')
                ->whereIn('customer_id', $myCustomerIds)
                ->sum('total_amount');

            $pendingRevenue = Bill::where('status', 'Pending')
                ->whereIn('customer_id', $myCustomerIds)
                ->sum('total_amount');

            $totalConsumption = Bill::whereIn('customer_id', $myCustomerIds)
                ->sum('consumption');

            $unpaidCustomersCount = Customer::whereIn('id', $myCustomerIds)
                ->whereHas('bills', function ($q) {
                    $q->where('status', '!=', 'Paid');
                })->count();

            $paidCustomersCount = Customer::whereIn('id', $myCustomerIds)
                ->whereHas('bills')
                ->whereDoesntHave('bills', function ($q) {
                    $q->where('status', '!=', 'Paid');
                })->count();

            $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();

            $billMonthExpr = "strftime('%Y-%m', billing_date)";

            if ($driver === 'mysql' || $driver === 'mariadb') {
                $billMonthExpr = "DATE_FORMAT(billing_date, '%Y-%m')";
            } elseif ($driver === 'pgsql') {
                $billMonthExpr = "to_char(billing_date, 'YYYY-MM')";
            }

            // Get monthly revenue data for chart
            $monthlyRevenue = Bill::where('status', 'Paid')
                ->whereIn('customer_id', $myCustomerIds)
                ->selectRaw("{$billMonthExpr} as month, SUM(total_amount) as total")
                ->groupByRaw($billMonthExpr)
                ->orderByRaw("{$billMonthExpr} asc")
                ->get();

            // Get usage trend data
            $usageTrend = Bill::whereIn('customer_id', $myCustomerIds)
                ->selectRaw("{$billMonthExpr} as month, SUM(consumption) as total_usage")
                ->groupByRaw($billMonthExpr)
                ->orderByRaw("{$billMonthExpr} asc")
                ->get();

            // Customer type distribution
            $customerTypes = Customer::where('admin_id', $adminId)
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get();

            // Revenue by customer type
            $revenueByType = Bill::where('bills.status', 'Paid')
                ->whereIn('bills.customer_id', $myCustomerIds)
                ->join('customers', 'bills.customer_id', '=', 'customers.id')
                ->selectRaw('customers.type, SUM(bills.total_amount) as total')
                ->groupBy('customers.type')
                ->get();

            return view('dashboard.index', [
                'totalCustomers' => $totalCustomers,
                'totalRevenue' => $totalRevenue,
                'pendingRevenue' => $pendingRevenue,
                'totalConsumption' => $totalConsumption,
                'paidCustomersCount' => $paidCustomersCount,
                'unpaidCustomersCount' => $unpaidCustomersCount,
                'monthlyRevenue' => $monthlyRevenue,
                'usageTrend' => $usageTrend,
                'customerTypes' => $customerTypes,
                'revenueByType' => $revenueByType,
            ]);
        } catch (\Throwable $e) {
            die("Dashboard Error, Driver " . \Illuminate\Support\Facades\DB::connection()->getDriverName() . " : " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile());
        }
    }
}
