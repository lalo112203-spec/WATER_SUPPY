<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Bill;

class RecoveryController extends Controller
{
    public function index()
    {
        // Get all trashed customers
        $deletedCustomers = Customer::onlyTrashed()->get();
        // Get all trashed bills with their (possibly trashed) customers
        $deletedBills = Bill::onlyTrashed()->with(['customer' => function($q) {
            $q->withTrashed();
        }])->get();

        return view('recovery.index', compact('deletedCustomers', 'deletedBills'));
    }

    public function restoreCustomer($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->restore();

        // Also restore associated user if it was soft deleted
        $user = \App\Models\User::onlyTrashed()->where('customer_id', $customer->id)->first();
        if ($user) {
            $user->restore();
        }

        // Also restore all associated bills and water usages
        Bill::onlyTrashed()->where('customer_id', $customer->id)->restore();
        \App\Models\WaterUsage::onlyTrashed()->where('customer_id', $customer->id)->restore();

        return redirect()->route('recovery.index')->with('success', 'Customer and associated accounts/data restored successfully.');
    }

    public function forceDeleteCustomer($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        
        // Permanently delete associated user
        $user = \App\Models\User::withTrashed()->where('customer_id', $customer->id)->first();
        if ($user) {
            $user->forceDelete();
        }

        // Permanently delete associated bills and water usages
        Bill::withTrashed()->where('customer_id', $customer->id)->forceDelete();
        \App\Models\WaterUsage::withTrashed()->where('customer_id', $customer->id)->forceDelete();
        
        $customer->forceDelete();

        return redirect()->route('recovery.index')->with('success', 'Customer and all associated data permanently deleted.');
    }

    public function restoreBill($id)
    {
        $bill = Bill::onlyTrashed()->findOrFail($id);
        $bill->restore();

        return redirect()->route('recovery.index')->with('success', 'Bill restored successfully.');
    }

    public function forceDeleteBill($id)
    {
        $bill = Bill::onlyTrashed()->findOrFail($id);
        $bill->forceDelete();

        return redirect()->route('recovery.index')->with('success', 'Bill permanently deleted.');
    }
}
