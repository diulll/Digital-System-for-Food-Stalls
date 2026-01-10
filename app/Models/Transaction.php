<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'order_id',
        'user_id',
        'payment_method',
        'amount_paid',
        'change',
        'status',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'change' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateTransactionNumber(): string
    {
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', today())->latest()->first();
        $sequence = $lastTransaction ? intval(substr($lastTransaction->transaction_number, -4)) + 1 : 1;
        return 'TRX-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount_paid, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'success' => '<span class="badge bg-success">Success</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
            'refunded' => '<span class="badge bg-warning text-dark">Refunded</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}
