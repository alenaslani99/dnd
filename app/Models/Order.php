<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'guest_email',
        'guest_phone',
        'guest_name',
        'shipping_address',
        'shipping_house_number',
        'shipping_zip',
        'shipping_city',
        'shipping_cost',
        'payment_method',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'total_amount' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::Pending);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', OrderStatus::Processing);
    }

    public function scopeShipped($query)
    {
        return $query->where('status', OrderStatus::Shipped);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', OrderStatus::Delivered);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', OrderStatus::Cancelled);
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', OrderStatus::Refunded);
    }

    public function scopeGuest($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
