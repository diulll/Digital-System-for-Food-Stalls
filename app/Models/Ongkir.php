<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ongkir extends Model
{
    protected $fillable = [
        'kota_asal_id',
        'kota_tujuan_id',
        'tarif_per_kg'
    ];

    protected $casts = [
        'tarif_per_kg' => 'decimal:2',
    ];

    /**
     * Relasi ke kota asal
     */
    public function kotaAsal(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'kota_asal_id');
    }

    /**
     * Relasi ke kota tujuan
     */
    public function kotaTujuan(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'kota_tujuan_id');
    }

    /**
     * Hitung total ongkos kirim berdasarkan berat
     */
    public function hitungOngkir(float $beratKg): float
    {
        return $this->tarif_per_kg * $beratKg;
    }

    /**
     * Cari tarif berdasarkan kota asal dan tujuan
     */
    public static function getTarif(int $kotaAsalId, int $kotaTujuanId): ?self
    {
        return self::where('kota_asal_id', $kotaAsalId)
            ->where('kota_tujuan_id', $kotaTujuanId)
            ->first();
    }
}
