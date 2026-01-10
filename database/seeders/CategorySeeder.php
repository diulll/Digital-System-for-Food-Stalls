<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Makanan Berat',
                'description' => 'Nasi dan lauk pauk',
            ],
            [
                'name' => 'Minuman',
                'description' => 'Minuman dingin dan hangat',
            ],
            [
                'name' => 'Snack',
                'description' => 'Cemilan dan makanan ringan',
            ],
            [
                'name' => 'Gorengan',
                'description' => 'Gorengan tradisional',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
