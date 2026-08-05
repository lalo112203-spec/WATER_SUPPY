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
        if (\Illuminate\Support\Facades\DB::table('users')->where('email', 'reader')->doesntExist()) {
            \Illuminate\Support\Facades\DB::table('users')->insert([
                'name' => 'Meter Reader',
                'email' => 'reader',
                'password' => \Illuminate\Support\Facades\Hash::make('09092200129'),
                'role' => 'reader',
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
        \Illuminate\Support\Facades\DB::table('users')->where('email', 'reader')->delete();
    }
};
