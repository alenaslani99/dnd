<?php

namespace App\Models;

use Database\Factories\PromotionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    /** @use HasFactory<PromotionFactory> */
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'sale_price',
        'starts_at',
        'ends_at',
    ];

    protected static function booted(): void
    {
        static::saving(function (Promotion $promotion) {
            $maxDays = 30;
            if ($promotion->starts_at && $promotion->ends_at) {
                if ($promotion->ends_at->diffInDays($promotion->starts_at, false) > $maxDays) {
                    throw new \InvalidArgumentException('Promocija ne može trajati duže od 30 dana.');
                }

                if ($promotion->ends_at->lessThanOrEqualTo($promotion->starts_at)) {
                    throw new \InvalidArgumentException('Datum završetka mora biti posle datuma početka.');
                }
            }
        });
    }

    protected function casts(): array
    {
        return [
            'sale_price' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function scopeActive($query)
    {
        return $query
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('ends_at', '<', now());
    }
}
