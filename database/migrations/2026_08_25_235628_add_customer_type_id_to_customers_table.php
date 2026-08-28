<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('customer_type_id')->nullable()->constrained('customer_types')->nullOnDelete();
        });

        // Backfill existing data
        $types = DB::table('customer_types')->pluck('id', 'name')->toArray();
        if (isset($types['Regular'])) {
            DB::table('customers')->where('type', 'Regular')->update(['customer_type_id' => $types['Regular']]);
        }
        if (isset($types['Commercial'])) {
            DB::table('customers')->where('type', 'Commercial')->update(['customer_type_id' => $types['Commercial']]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['customer_type_id']);
            $table->dropColumn('customer_type_id');
        });
    }
};
