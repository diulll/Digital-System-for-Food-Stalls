<?php

namespace Database\Seeders;

use App\Models\Kota;
use App\Models\Propinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kotas = [
            // DKI Jakarta (id: 11)
            ['propinsi_nama' => 'DKI Jakarta', 'nama' => 'Jakarta Pusat'],
            ['propinsi_nama' => 'DKI Jakarta', 'nama' => 'Jakarta Utara'],
            ['propinsi_nama' => 'DKI Jakarta', 'nama' => 'Jakarta Barat'],
            ['propinsi_nama' => 'DKI Jakarta', 'nama' => 'Jakarta Selatan'],
            ['propinsi_nama' => 'DKI Jakarta', 'nama' => 'Jakarta Timur'],
            
            // Jawa Barat (id: 12)
            ['propinsi_nama' => 'Jawa Barat', 'nama' => 'Bandung'],
            ['propinsi_nama' => 'Jawa Barat', 'nama' => 'Bekasi'],
            ['propinsi_nama' => 'Jawa Barat', 'nama' => 'Bogor'],
            ['propinsi_nama' => 'Jawa Barat', 'nama' => 'Depok'],
            ['propinsi_nama' => 'Jawa Barat', 'nama' => 'Cirebon'],
            
            // Jawa Tengah (id: 13)
            ['propinsi_nama' => 'Jawa Tengah', 'nama' => 'Semarang'],
            ['propinsi_nama' => 'Jawa Tengah', 'nama' => 'Solo'],
            ['propinsi_nama' => 'Jawa Tengah', 'nama' => 'Pekalongan'],
            
            // DI Yogyakarta (id: 14)
            ['propinsi_nama' => 'DI Yogyakarta', 'nama' => 'Yogyakarta'],
            ['propinsi_nama' => 'DI Yogyakarta', 'nama' => 'Sleman'],
            ['propinsi_nama' => 'DI Yogyakarta', 'nama' => 'Bantul'],
            
            // Jawa Timur (id: 15)
            ['propinsi_nama' => 'Jawa Timur', 'nama' => 'Surabaya'],
            ['propinsi_nama' => 'Jawa Timur', 'nama' => 'Malang'],
            ['propinsi_nama' => 'Jawa Timur', 'nama' => 'Sidoarjo'],
            
            // Banten (id: 16)
            ['propinsi_nama' => 'Banten', 'nama' => 'Tangerang'],
            ['propinsi_nama' => 'Banten', 'nama' => 'Tangerang Selatan'],
            ['propinsi_nama' => 'Banten', 'nama' => 'Serang'],
        ];

        foreach ($kotas as $kota) {
            $propinsi = Propinsi::where('nama', $kota['propinsi_nama'])->first();
            if ($propinsi) {
                Kota::create([
                    'propinsi_id' => $propinsi->id,
                    'nama' => $kota['nama'],
                ]);
            }
        }
    }
}
