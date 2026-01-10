<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for this order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the transaction associated with this order.
     */
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Generate unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', now()->toDateString())->latest()->first();
        $increment = $lastOrder ? (int) substr($lastOrder->order_number, -4) + 1 : 1;
        
        return 'ORD-' . $date . '-' . str_pad($increment, 4, '0', STR_PAD_LEFT);
    }
}
