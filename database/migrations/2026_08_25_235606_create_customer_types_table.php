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
        Schema::create('customer_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('base_charge', 10, 2)->default(0);
            $table->decimal('usage_rate', 10, 2)->default(0);
            $table->integer('green_max')->default(0);
            $table->integer('orange_max')->default(0);
            $table->integer('red_max')->default(0);
            $table->integer('base_limit')->default(0);
            $table->timestamps();
        });

        // Insert initial data based on old settings
        DB::table('customer_types')->insert([
            [
                'name' => 'Regular',
                'base_charge' => \App\Models\SystemSetting::get('regular_base_charge', 100),
                'usage_rate' => \App\Models\SystemSetting::get('regular_usage_rate', 15),
                'green_max' => \App\Models\SystemSetting::get('regular_green_max', 10),
                'orange_max' => \App\Models\SystemSetting::get('regular_orange_max', 14),
                'red_max' => \App\Models\SystemSetting::get('regular_red_max', 20),
                'base_limit' => \App\Models\SystemSetting::get('regular_base_limit', 10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Commercial',
                'base_charge' => \App\Models\SystemSetting::get('commercial_base_charge', 250),
                'usage_rate' => \App\Models\SystemSetting::get('commercial_usage_rate', 25),
                'green_max' => \App\Models\SystemSetting::get('commercial_green_max', 49),
                'orange_max' => \App\Models\SystemSetting::get('commercial_orange_max', 50),
                'red_max' => \App\Models\SystemSetting::get('commercial_red_max', 100),
                'base_limit' => \App\Models\SystemSetting::get('commercial_base_limit', 10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_types');
    }
};
