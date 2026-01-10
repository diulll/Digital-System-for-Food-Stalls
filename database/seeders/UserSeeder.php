<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'role_id' => 1, // admin
            'name' => 'Admin User',
            'email' => 'admin@foodstall.com',
            'password' => Hash::make('password'),
        ]);

        // Create Owner User
        User::create([
            'role_id' => 2, // owner
            'name' => 'Owner User',
            'email' => 'owner@foodstall.com',
            'password' => Hash::make('password'),
        ]);

        // Create additional owner for testing
        User::create([
            'role_id' => 2, // owner
            'name' => 'Warung Pak Budi',
            'email' => 'budi@foodstall.com',
            'password' => Hash::make('password'),
        ]);
    }
}
