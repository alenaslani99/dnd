<?php

namespace App\Models;

use Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    /** @use HasFactory<ProductVariantFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'size_label',
        'sku',
        'is_active',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_available' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (ProductVariant $variant) {
            if (! $variant->isForceDeleting()) {
                $variant->prices()->delete();
                $variant->promotions()->delete();
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class)->orderBy('created_at', 'desc');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    public function latestPrice(): HasOne
    {
        return $this->hasOne(Price::class)->latestOfMany();
    }

    public function currentPromotion(): HasOne
    {
        return $this->hasOne(Promotion::class)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function currentPrice(): ?Price
    {
        if ($this->relationLoaded('latestPrice')) {
            return $this->latestPrice;
        }

        return $this->latestPrice()->first();
    }

    public function activePromotion(): ?Promotion
    {
        if ($this->relationLoaded('currentPromotion')) {
            return $this->currentPromotion;
        }

        return $this->currentPromotion()->first();
    }

    public function activePrice(): ?float
    {
        $promotion = $this->activePromotion();

        if ($promotion) {
            return (float) $promotion->sale_price;
        }

        return $this->currentPrice()?->amount;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
