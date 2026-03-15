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
        if (auth()->user()->role === 'consumer') {
            return redirect()->route('messages.index');
        }

        $totalCustomers = Customer::count();
        $totalRevenue = Bill::where('status', 'Paid')->sum('total_amount');
        $pendingRevenue = Bill::where('status', 'Pending')->sum('total_amount');
        $totalConsumption = WaterUsage::sum('usage');

        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        
        $billMonthExpr = "strftime('%Y-%m', billing_date)";
        $usageMonthExpr = "strftime('%Y-%m', reading_date)";
        
        if ($driver === 'mysql' || $driver === 'mariadb') {
            $billMonthExpr = "DATE_FORMAT(billing_date, '%Y-%m')";
            $usageMonthExpr = "DATE_FORMAT(reading_date, '%Y-%m')";
        } elseif ($driver === 'pgsql') {
            $billMonthExpr = "to_char(billing_date, 'YYYY-MM')";
            $usageMonthExpr = "to_char(reading_date, 'YYYY-MM')";
        }

        // Get monthly revenue data for chart
        $monthlyRevenue = Bill::where('status', 'Paid')
            ->selectRaw("{$billMonthExpr} as month, SUM(total_amount) as total")
            ->groupByRaw($billMonthExpr)
            ->orderByRaw("{$billMonthExpr} asc")
            ->get();

        // Get usage trend data
        $usageTrend = WaterUsage::selectRaw("{$usageMonthExpr} as month, SUM(usage) as total_usage")
            ->groupByRaw($usageMonthExpr)
            ->orderByRaw("{$usageMonthExpr} asc")
            ->get();

        // Customer type distribution
        $customerTypes = Customer::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        // Revenue by customer type
        $revenueByType = Bill::where('bills.status', 'Paid')
            ->join('customers', 'bills.customer_id', '=', 'customers.id')
            ->selectRaw('customers.type, SUM(bills.total_amount) as total')
            ->groupBy('customers.type')
            ->get();

        return view('dashboard.index', [
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'pendingRevenue' => $pendingRevenue,
            'totalConsumption' => $totalConsumption,
            'monthlyRevenue' => $monthlyRevenue,
            'usageTrend' => $usageTrend,
            'customerTypes' => $customerTypes,
            'revenueByType' => $revenueByType,
        ]);
    }
}
