<?php

namespace App\Models;

use Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'stock_quantity',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'stock_quantity' => 'integer',
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
        return $this->hasMany(Price::class)->orderBy('effective_date', 'desc');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    public function currentPrice(): ?Price
    {
        if ($this->relationLoaded('prices')) {
            return $this->prices->first();
        }

        return $this->prices()->first();
    }

    public function activePromotion(): ?Promotion
    {
        if ($this->relationLoaded('promotions')) {
            return $this->promotions
                ->where('starts_at', '<=', now())
                ->where('ends_at', '>=', now())
                ->first();
        }

        return $this->promotions()
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->first();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
