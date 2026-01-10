<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator with full access to manage users and view all reports',
            ],
            [
                'name' => 'owner',
                'description' => 'Food stall owner who can manage menu, categories, orders and view sales reports',
            ],
            [
                'name' => 'cashier',
                'description' => 'Cashier who can create orders and process transactions',
            ],
            [
                'name' => 'customer',
                'description' => 'Customer who can place orders and view their order history',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}
