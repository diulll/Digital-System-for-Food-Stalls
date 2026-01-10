<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            // Makanan Berat
            [
                'category_id' => 1,
                'name' => 'Nasi Goreng',
                'description' => 'Nasi goreng spesial dengan telur',
                'price' => 15000,
                'stock' => 50,
            ],
            [
                'category_id' => 1,
                'name' => 'Mie Goreng',
                'description' => 'Mie goreng spesial',
                'price' => 12000,
                'stock' => 40,
            ],
            [
                'category_id' => 1,
                'name' => 'Nasi Ayam Geprek',
                'description' => 'Nasi dengan ayam geprek pedas',
                'price' => 18000,
                'stock' => 30,
            ],
            
            // Minuman
            [
                'category_id' => 2,
                'name' => 'Es Teh Manis',
                'description' => 'Es teh manis segar',
                'price' => 3000,
                'stock' => 100,
            ],
            [
                'category_id' => 2,
                'name' => 'Es Jeruk',
                'description' => 'Es jeruk peras',
                'price' => 5000,
                'stock' => 80,
            ],
            [
                'category_id' => 2,
                'name' => 'Kopi Hitam',
                'description' => 'Kopi hitam panas',
                'price' => 4000,
                'stock' => 70,
            ],
            
            // Snack
            [
                'category_id' => 3,
                'name' => 'Pisang Goreng',
                'description' => 'Pisang goreng crispy',
                'price' => 8000,
                'stock' => 60,
            ],
            [
                'category_id' => 3,
                'name' => 'Tahu Isi',
                'description' => 'Tahu isi sayuran',
                'price' => 7000,
                'stock' => 50,
            ],
            
            // Gorengan
            [
                'category_id' => 4,
                'name' => 'Bakwan',
                'description' => 'Bakwan sayur',
                'price' => 1000,
                'stock' => 100,
            ],
            [
                'category_id' => 4,
                'name' => 'Tempe Goreng',
                'description' => 'Tempe goreng crispy',
                'price' => 1000,
                'stock' => 100,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
