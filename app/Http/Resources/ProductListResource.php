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

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'brand' => $this->brand?->name ?? '',
            'image' => $this->images->first()?->path ?? '/assets/img/pexels-suhashanjar-36779951.webp',
            'price' => $variant?->currentPrice()?->amount,
            'sale_price' => $promotion?->sale_price,
            'size_label' => $variant?->size_label,
            'badge' => $promotion ? 'Akcija' : null,
        ];
    }
}
