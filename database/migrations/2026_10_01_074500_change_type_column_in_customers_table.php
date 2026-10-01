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
            $table->string('type', 100)->change();
        });

        try {
            DB::statement("ALTER TABLE water_db.customers MODIFY COLUMN type VARCHAR(100) NOT NULL");
        } catch (\Throwable $e) {
            // Silently ignore if water_db is not accessible
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('type', ['Regular', 'Commercial'])->change();
        });

        try {
            DB::statement("ALTER TABLE water_db.customers MODIFY COLUMN type ENUM('Regular', 'Commercial') NOT NULL");
        } catch (\Throwable $e) {
            // Silently ignore if water_db is not accessible
        }
    }
};
