<?php

namespace Database\Seeders;

use App\Models\Propinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $propinsis = [
            ['nama' => 'Aceh'],
            ['nama' => 'Sumatera Utara'],
            ['nama' => 'Sumatera Barat'],
            ['nama' => 'Riau'],
            ['nama' => 'Jambi'],
            ['nama' => 'Sumatera Selatan'],
            ['nama' => 'Bengkulu'],
            ['nama' => 'Lampung'],
            ['nama' => 'Kepulauan Bangka Belitung'],
            ['nama' => 'Kepulauan Riau'],
            ['nama' => 'DKI Jakarta'],
            ['nama' => 'Jawa Barat'],
            ['nama' => 'Jawa Tengah'],
            ['nama' => 'DI Yogyakarta'],
            ['nama' => 'Jawa Timur'],
            ['nama' => 'Banten'],
            ['nama' => 'Bali'],
            ['nama' => 'Nusa Tenggara Barat'],
            ['nama' => 'Nusa Tenggara Timur'],
            ['nama' => 'Kalimantan Barat'],
            ['nama' => 'Kalimantan Tengah'],
            ['nama' => 'Kalimantan Selatan'],
            ['nama' => 'Kalimantan Timur'],
            ['nama' => 'Kalimantan Utara'],
            ['nama' => 'Sulawesi Utara'],
            ['nama' => 'Sulawesi Tengah'],
            ['nama' => 'Sulawesi Selatan'],
            ['nama' => 'Sulawesi Tenggara'],
            ['nama' => 'Gorontalo'],
            ['nama' => 'Sulawesi Barat'],
            ['nama' => 'Maluku'],
            ['nama' => 'Maluku Utara'],
            ['nama' => 'Papua'],
            ['nama' => 'Papua Barat'],
        ];

        foreach ($propinsis as $propinsi) {
            Propinsi::create($propinsi);
        }
    }
}
