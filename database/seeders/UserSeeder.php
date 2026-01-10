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
        $adminRole = Role::where('name', 'Admin')->first();
        $cashierRole = Role::where('name', 'Cashier')->first();

        // Create Admin User
        if ($adminRole) {
            User::firstOrCreate(
                ['email' => 'admin@foodstall.com'],
                [
                    'role_id' => $adminRole->id,
                    'name' => 'Administrator',
                    'password' => Hash::make('admin123'),
                ]
            );
        }

        // Create Cashier Users
        if ($cashierRole) {
            User::firstOrCreate(
                ['email' => 'kasir1@foodstall.com'],
                [
                    'role_id' => $cashierRole->id,
                    'name' => 'Kasir Satu',
                    'password' => Hash::make('kasir123'),
                ]
            );

            User::firstOrCreate(
                ['email' => 'kasir2@foodstall.com'],
                [
                    'role_id' => $cashierRole->id,
                    'name' => 'Kasir Dua',
                    'password' => Hash::make('kasir123'),
                ]
            );

            User::firstOrCreate(
                ['email' => 'kasir3@foodstall.com'],
                [
                    'role_id' => $cashierRole->id,
                    'name' => 'Kasir Tiga',
                    'password' => Hash::make('kasir123'),
                ]
            );
        }
    }
}
