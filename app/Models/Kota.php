<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kota extends Model
{
    protected $fillable = ['propinsi_id', 'nama'];

    public function propinsi(): BelongsTo
    {
        return $this->belongsTo(Propinsi::class);
    }

    /**
     * Ongkir dari kota ini sebagai kota asal
     */
    public function ongkirDari(): HasMany
    {
        return $this->hasMany(Ongkir::class, 'kota_asal_id');
    }

    /**
     * Ongkir ke kota ini sebagai kota tujuan
     */
    public function ongkirKe(): HasMany
    {
        return $this->hasMany(Ongkir::class, 'kota_tujuan_id');
    }
}
