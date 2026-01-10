<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Propinsi extends Model
{
    protected $fillable = ['nama'];

    public function kotas(): HasMany
    {
        return $this->hasMany(Kota::class);
    }
}