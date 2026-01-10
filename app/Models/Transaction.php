<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_number',
        'amount',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the order that owns the transaction.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Generate unique transaction number.
     */
    public static function generateTransactionNumber(): string
    {
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', now()->toDateString())->latest()->first();
        $increment = $lastTransaction ? (int) substr($lastTransaction->transaction_number, -4) + 1 : 1;
        
        return 'TRX-' . $date . '-' . str_pad($increment, 4, '0', STR_PAD_LEFT);
    }
}
