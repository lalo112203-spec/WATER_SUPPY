<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Admin2Seeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'admin2',
            'email' => 'admin2@water.system',
            'password' => Hash::make('doloreswatersupplyoffice'),
            'role' => 'admin',
        ]);
    }
}
