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

        User::create([
            'name' => 'admin1',
            'email' => 'admin@water.system', // Need an email for standard login unless changed
            'password' => bcrypt('09092200129'),
            'role' => 'admin',
        ]);
    }
}
