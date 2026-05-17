<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'brand' => $this->brand?->name ?? '',
            'images' => $this->images->map(fn ($img) => [
                'path' => $img->path,
                'alt' => $img->alt_text,
                'is_primary' => $img->is_primary,
            ]),
            'variants' => $this->activeVariants->map(function ($variant) {
                $promotion = $variant->activePromotion();
                $price = $variant->currentPrice()?->amount;

                return [
                    'id' => $variant->id,
                    'size_label' => $variant->size_label,
                    'sku' => $variant->sku,
                    'price' => $price !== null ? number_format($price, 0, ',', '.').' RSD' : '',
                    'sale_price' => $promotion?->sale_price !== null ? number_format($promotion->sale_price, 0, ',', '.').' RSD' : '',
                    'is_available' => $variant->is_available,
                ];
            }),
        ];
    }
}
