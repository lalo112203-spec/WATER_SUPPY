<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RecoveryController;

Route::redirect('/', '/dashboard')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::post('customers/{customer}/create-account', [CustomerController::class, 'createAccount'])->name('customers.create-account');
    Route::post('customers/{customer}/update-password', [CustomerController::class, 'updatePassword'])->name('customers.update-password');

    // Messaging
    Route::get('messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::post('messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

    // Billing
    Route::get('billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('billing/create', [BillingController::class, 'create'])->name('billing.create');
    Route::post('billing', [BillingController::class, 'store'])->name('billing.store');
    Route::get('billing/{bill}', [BillingController::class, 'show'])->name('billing.show');
    Route::patch('billing/{bill}/mark-paid', [BillingController::class, 'markAsPaid'])->name('billing.mark-paid');
    Route::delete('billing/{bill}', [BillingController::class, 'destroy'])->name('billing.destroy');

    // Recovery
    Route::get('recovery', [RecoveryController::class, 'index'])->name('recovery.index');
    Route::post('recovery/customers/{id}', [RecoveryController::class, 'restoreCustomer'])->name('recovery.restoreCustomer');
    Route::post('recovery/bills/{id}', [RecoveryController::class, 'restoreBill'])->name('recovery.restoreBill');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    // API Routes for AJAX
    Route::get('api/customers/{customer}/readings', [BillingController::class, 'getCustomerReadings']);
});

require __DIR__.'/settings.php';
