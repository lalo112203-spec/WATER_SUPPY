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
        if (\Illuminate\Support\Facades\DB::table('users')->where('name', 'admin1')->doesntExist()) {
            \Illuminate\Support\Facades\DB::table('users')->insert([
                'name' => 'admin1',
                'email' => 'admin@water.system',
                'password' => \Illuminate\Support\Facades\Hash::make('09092200129'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('users')->where('name', 'admin1')->delete();
    }
}
