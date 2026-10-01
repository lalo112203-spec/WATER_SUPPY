<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->unique()->after('name');
            });

            // Backfill existing users
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                $username = null;
                if ($user->role === 'consumer' && !empty($user->customer_id)) {
                    $customer = DB::table('customers')->where('id', $user->customer_id)->first();
                    $username = $customer ? $customer->customer_id : null;
                }
                
                if (empty($username)) {
                    $username = !empty($user->name) && !str_contains($user->name, ' ') ? $user->name : Str::before($user->email, '@');
                }

                if (!empty($username)) {
                    // Ensure unique
                    $counter = 1;
                    $orig = $username;
                    while (DB::table('users')->where('username', $username)->where('id', '!=', $user->id)->exists()) {
                        $username = $orig . '_' . $counter++;
                    }

                    DB::table('users')->where('id', $user->id)->update(['username' => $username]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('username');
            });
        }
    }
};
