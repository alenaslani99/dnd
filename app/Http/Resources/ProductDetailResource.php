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

                return [
                    'id' => $variant->id,
                    'size_label' => $variant->size_label,
                    'sku' => $variant->sku,
                    'price' => $variant->currentPrice()?->amount,
                    'sale_price' => $promotion?->sale_price,
                    'is_available' => $variant->is_available,
                ];
            }),
        ];
    }
}
