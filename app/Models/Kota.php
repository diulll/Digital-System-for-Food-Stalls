<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kota extends Model
{
    protected $fillable = ['propinsi_id', 'nama'];

    public function propinsi(): BelongsTo
    {
        return $this->belongsTo(Propinsi::class);
    }
}
