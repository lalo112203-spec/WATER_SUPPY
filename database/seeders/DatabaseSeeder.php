<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'admin@water.system'], // Search by email
            [
                'name' => 'admin1',
                'password' => bcrypt('09092200129'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'reader'],
            [
                'name' => 'Meter Reader',
                'password' => bcrypt('09092200129'),
                'role' => 'reader',
            ]
        );
    }
}
