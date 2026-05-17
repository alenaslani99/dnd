<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $variant = $this->activeVariants->first();
        $promotion = $variant?->activePromotion();

        $price = $variant?->currentPrice()?->amount;

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'brand' => $this->brand?->name ?? '',
            'image' => $this->primaryImage->path,
            'price' => $price !== null ? number_format($price, 0, ',', '.').' RSD' : '',
            'sale_price' => $promotion?->sale_price !== null ? number_format($promotion->sale_price, 0, ',', '.').' RSD' : '',
            'size_label' => $variant?->size_label,
            'badge' => $promotion ? 'Akcija' : null,
        ];
    }
}
