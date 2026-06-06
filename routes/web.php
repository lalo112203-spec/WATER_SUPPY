<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\RegistrationCodeController;

Route::redirect('/', '/dashboard')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reader
    Route::get('reader/dashboard', [\App\Http\Controllers\ReaderController::class, 'index'])->name('reader.dashboard');
    Route::post('reader/reading', [\App\Http\Controllers\ReaderController::class, 'storeReading'])->name('reader.storeReading');

    // Customers
    Route::get('customers/report', [CustomerController::class, 'report'])->name('customers.report');
    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::post('customers/{customer}/create-account', [CustomerController::class, 'createAccount'])->name('customers.create-account');
    Route::post('customers/{customer}/update-password', [CustomerController::class, 'updatePassword'])->name('customers.update-password');

    // Messaging
    Route::get('messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::post('messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    Route::post('messages/post', [\App\Http\Controllers\MessageController::class, 'storePost'])->name('messages.storePost');
    Route::post('messages/mark-read', [\App\Http\Controllers\MessageController::class, 'markRead'])->name('messages.markRead');
    Route::put('messages/{message}', [\App\Http\Controllers\MessageController::class, 'update'])->name('messages.update');
    Route::delete('messages/{message}', [\App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');

    // Billing
    Route::get('billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('billing/create', [BillingController::class, 'create'])->name('billing.create');
    Route::post('billing', [BillingController::class, 'store'])->name('billing.store');
    Route::get('billing/{bill}', [BillingController::class, 'show'])->name('billing.show');
    Route::get('billing/{bill}/edit', [BillingController::class, 'edit'])->name('billing.edit');
    Route::put('billing/{bill}', [BillingController::class, 'update'])->name('billing.update');
    Route::get('billing/{bill}/receipt', [BillingController::class, 'receipt'])->name('billing.receipt');
    Route::patch('billing/{bill}/mark-paid', [BillingController::class, 'markAsPaid'])->name('billing.mark-paid');
    Route::delete('billing/{bill}', [BillingController::class, 'destroy'])->name('billing.destroy');

    // Recovery
    Route::get('recovery', [RecoveryController::class, 'index'])->name('recovery.index');
    Route::post('recovery/customers/{id}', [RecoveryController::class, 'restoreCustomer'])->name('recovery.restoreCustomer');
    Route::delete('recovery/customers/{id}', [RecoveryController::class, 'forceDeleteCustomer'])->name('recovery.forceDeleteCustomer');
    Route::post('recovery/bills/{id}', [RecoveryController::class, 'restoreBill'])->name('recovery.restoreBill');
    Route::delete('recovery/bills/{id}', [RecoveryController::class, 'forceDeleteBill'])->name('recovery.forceDeleteBill');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/authorize', [SettingsController::class, 'authorize'])->name('settings.authorize');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Registration Codes (Admin Only)
    Route::get('registration-codes', [RegistrationCodeController::class, 'index'])->name('registration-codes.index');
    Route::post('registration-codes', [RegistrationCodeController::class, 'store'])->name('registration-codes.store');
    Route::delete('registration-codes/{registrationCode}', [RegistrationCodeController::class, 'destroy'])->name('registration-codes.destroy');

    // API Routes for AJAX
    Route::get('api/customers/{customer}/readings', [BillingController::class, 'getCustomerReadings']);
    Route::post('push-subscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'store'])->name('push.subscribe');
});

require __DIR__.'/settings.php';
