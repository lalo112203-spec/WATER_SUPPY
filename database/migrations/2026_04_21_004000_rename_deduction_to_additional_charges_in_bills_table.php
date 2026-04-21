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
        Schema::table('bills', function (Blueprint $table) {
            $table->renameColumn('deduction_amount', 'additional_charge_amount');
            $table->renameColumn('deduction_note', 'additional_charge_note');
            $table->renameColumn('applied_deductions', 'applied_additional_charges');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->renameColumn('additional_charge_amount', 'deduction_amount');
            $table->renameColumn('additional_charge_note', 'deduction_note');
            $table->renameColumn('applied_additional_charges', 'applied_deductions');
        });
    }
};
