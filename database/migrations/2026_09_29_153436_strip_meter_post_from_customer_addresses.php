<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (\App\Models\Customer::all() as $customer) {
            $barangay = strtoupper($customer->barangay ?? '');
            $customer->update([
                'address' => $barangay . ' DOLORES EASTERN SAMAR',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
