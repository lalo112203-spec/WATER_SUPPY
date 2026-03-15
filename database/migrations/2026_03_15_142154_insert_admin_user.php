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
        \App\Models\User::firstOrCreate(
            ['name' => 'admin1'],
            [
                'email' => 'admin@water.system',
                'password' => bcrypt('09092200129'),
                'role' => 'admin',
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\User::where('name', 'admin1')->delete();
    }
};
