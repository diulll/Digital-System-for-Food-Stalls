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
     * Data sesuai dengan gambar tabel kota
     */
    public function run(): void
    {
        // Buat propinsi yang dibutuhkan terlebih dahulu
        $propinsis = [
            ['id' => 1, 'nama' => 'DI Yogyakarta'],
            ['id' => 2, 'nama' => 'Jawa Tengah'],
            ['id' => 3, 'nama' => 'Jawa Barat'],
            ['id' => 4, 'nama' => 'DKI Jakarta'],
            ['id' => 5, 'nama' => 'Jawa Timur'],
        ];

        foreach ($propinsis as $propinsi) {
            Propinsi::updateOrCreate(
                ['id' => $propinsi['id']],
                ['nama' => $propinsi['nama']]
            );
        }

        // Data kota sesuai gambar
        // Format: [id, propinsi_id, nama_kota]
        $kotas = [
            ['id' => 1, 'propinsi_id' => 1, 'nama' => 'Kodya Yogyakarta'],  // propinsi_id 1 = DI Yogyakarta
            ['id' => 2, 'propinsi_id' => 1, 'nama' => 'Bantul'],
            ['id' => 3, 'propinsi_id' => 1, 'nama' => 'Sleman'],
            ['id' => 4, 'propinsi_id' => 1, 'nama' => 'Kulon Progo'],
            ['id' => 5, 'propinsi_id' => 2, 'nama' => 'Klaten'],            // propinsi_id 2 = Jawa Tengah
            ['id' => 6, 'propinsi_id' => 2, 'nama' => 'Magelang'],
            ['id' => 7, 'propinsi_id' => 5, 'nama' => 'Malang'],            // propinsi_id 5 = Jawa Timur
            ['id' => 8, 'propinsi_id' => 5, 'nama' => 'Mojokerto'],
            ['id' => 9, 'propinsi_id' => 3, 'nama' => 'Cirebon'],           // propinsi_id 3 = Jawa Barat
        ];

        foreach ($kotas as $kota) {
            Kota::updateOrCreate(
                ['id' => $kota['id']],
                [
                    'propinsi_id' => $kota['propinsi_id'],
                    'nama' => $kota['nama']
                ]
            );
        }

        $this->command->info('Kota seeded successfully! Total: ' . count($kotas) . ' kota');
    }
}
