<?php

namespace Database\Seeders;

use App\Models\Ongkir;
use Illuminate\Database\Seeder;

class OngkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data sesuai dengan gambar tabel ongkir
     * 
     * Kota:
     * 1 = Kodya Yogyakarta
     * 2 = Bantul
     * 3 = Sleman
     * 4 = Kulon Progo
     * 5 = Klaten
     * 6 = Magelang
     * 7 = Malang
     * 8 = Mojokerto
     * 9 = Cirebon
     */
    public function run(): void
    {

        $ongkirs = [
            // Tarif satu arah (sesuai gambar soal)
            ['id' => 1, 'kota_asal_id' => 1, 'kota_tujuan_id' => 2, 'tarif_per_kg' => 10000],  // Kodya Yogyakarta -> Bantul
            ['id' => 2, 'kota_asal_id' => 1, 'kota_tujuan_id' => 3, 'tarif_per_kg' => 10000],  // Kodya Yogyakarta -> Sleman
            ['id' => 3, 'kota_asal_id' => 2, 'kota_tujuan_id' => 4, 'tarif_per_kg' => 10000],  // Bantul -> Kulon Progo
            ['id' => 4, 'kota_asal_id' => 2, 'kota_tujuan_id' => 6, 'tarif_per_kg' => 15000],  // Bantul -> Magelang
            ['id' => 5, 'kota_asal_id' => 3, 'kota_tujuan_id' => 8, 'tarif_per_kg' => 20000],  // Sleman -> Mojokerto
            ['id' => 6, 'kota_asal_id' => 3, 'kota_tujuan_id' => 9, 'tarif_per_kg' => 19000],  // Sleman -> Cirebon
            
            // Tarif kebalikan (opsional - jika ingin support dua arah)
            ['id' => 7, 'kota_asal_id' => 2, 'kota_tujuan_id' => 1, 'tarif_per_kg' => 10000],  // Bantul -> Kodya Yogyakarta
            ['id' => 8, 'kota_asal_id' => 3, 'kota_tujuan_id' => 1, 'tarif_per_kg' => 10000],  // Sleman -> Kodya Yogyakarta
            ['id' => 9, 'kota_asal_id' => 4, 'kota_tujuan_id' => 2, 'tarif_per_kg' => 10000],  // Kulon Progo -> Bantul
            ['id' => 10, 'kota_asal_id' => 6, 'kota_tujuan_id' => 2, 'tarif_per_kg' => 15000], // Magelang -> Bantul
            ['id' => 11, 'kota_asal_id' => 8, 'kota_tujuan_id' => 3, 'tarif_per_kg' => 20000], // Mojokerto -> Sleman
            ['id' => 12, 'kota_asal_id' => 9, 'kota_tujuan_id' => 3, 'tarif_per_kg' => 19000], // Cirebon -> Sleman
        ];

        foreach ($ongkirs as $ongkir) {
            Ongkir::updateOrCreate(
                ['id' => $ongkir['id']],
                [
                    'kota_asal_id' => $ongkir['kota_asal_id'],
                    'kota_tujuan_id' => $ongkir['kota_tujuan_id'],
                    'tarif_per_kg' => $ongkir['tarif_per_kg']
                ]
            );
        }

        $this->command->info('Ongkir seeded successfully! Total: ' . count($ongkirs) . ' tarif');
    }
}
