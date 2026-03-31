<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Bill;

class RecoveryController extends Controller
{
    public function index()
    {
        $deletedCustomers = Customer::onlyTrashed()->get();
        $deletedBills = Bill::onlyTrashed()->with('customer')->get();

        return view('recovery.index', compact('deletedCustomers', 'deletedBills'));
    }

    public function restoreCustomer($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->restore();

        return redirect()->route('recovery.index')->with('success', 'Customer restored successfully.');
    }

    public function forceDeleteCustomer($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        
        // Also delete associated user if exists
        if ($customer->user) {
            $customer->user->forceDelete();
        }
        
        $customer->forceDelete();

        return redirect()->route('recovery.index')->with('success', 'Customer permanently deleted.');
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
