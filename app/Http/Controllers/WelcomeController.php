<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $featuredProducts = Product::query()
            ->with(['brand', 'images', 'variants.prices', 'variants.promotions'])
            ->where('is_active', true)
            ->limit(4)
            ->get();

        $brands = Brand::orderBy('name')->get(['name', 'slug', 'logo']);

        return Inertia::render('Welcome', [
            'featuredProducts' => $featuredProducts->map(function (Product $product) {
                $variant = $product->variants->first();
                $price = $variant?->prices->first();
                $promotion = $variant?->promotions
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>=', now())
                    ->first();

                return [
                    'slug' => $product->slug,
                    'name' => $product->name,
                    'brand' => $product->brand->name,
                    'image' => $product->images->first()?->path ?? '/assets/img/pexels-suhashanjar-36779951.webp',
                    'price' => $price?->amount,
                    'sale_price' => $promotion?->sale_price,
                    'size_label' => $variant?->size_label,
                    'badge' => $promotion ? 'Akcija' : null,
                ];
            }),
            'brands' => $brands->map(fn ($b) => [
                'name' => $b->name,
                'logo' => $b->logo,
            ]),
        ]);
    }
}
