<?php

namespace Database\Seeders;

use App\Models\Role;
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
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $ownerRole = Role::where('name', 'owner')->first();
        $cashierRole = Role::where('name', 'cashier')->first();
        $customerRole = Role::where('name', 'customer')->orWhere('name', 'Customer')->first();

        // Create Admin User
        if ($adminRole) {
            User::firstOrCreate(
                ['email' => 'admin@foodstall.com'],
                [
                    'role_id' => $adminRole->id,
                    'name' => 'Admin User',
                    'password' => Hash::make('password'),
                ]
            );
        }

        // Create Owner User
        if ($ownerRole) {
            User::firstOrCreate(
                ['email' => 'owner@foodstall.com'],
                [
                    'role_id' => $ownerRole->id,
                    'name' => 'Owner User',
                    'password' => Hash::make('password'),
                ]
            );

            // Create additional owner for testing
            User::firstOrCreate(
                ['email' => 'budi@foodstall.com'],
                [
                    'role_id' => $ownerRole->id,
                    'name' => 'Warung Pak Budi',
                    'password' => Hash::make('password'),
                ]
            );
        }

        // Create Cashier User
        if ($cashierRole) {
            User::firstOrCreate(
                ['email' => 'cashier@foodstall.com'],
                [
                    'role_id' => $cashierRole->id,
                    'name' => 'Kasir 1',
                    'password' => Hash::make('password'),
                ]
            );

            // Create additional cashier
            User::firstOrCreate(
                ['email' => 'cashier2@foodstall.com'],
                [
                    'role_id' => $cashierRole->id,
                    'name' => 'Kasir 2',
                    'password' => Hash::make('password'),
                ]
            );
        }

        // Create Customer User for testing
        if ($customerRole) {
            User::firstOrCreate(
                ['email' => 'customer@foodstall.com'],
                [
                    'role_id' => $customerRole->id,
                    'name' => 'Pelanggan Test',
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
