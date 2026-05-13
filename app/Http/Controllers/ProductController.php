<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Product::query()
            ->with(['brand', 'images', 'variants.prices', 'variants.promotions'])
            ->where('is_active', true);

        // Multi-brand filter
        $selectedBrands = $request->collect('brands')->filter()->all();
        if (! empty($selectedBrands)) {
            $query->whereHas('brand', function ($q) use ($selectedBrands) {
                $q->whereIn('slug', $selectedBrands);
            });
        }

        // Multi-size filter
        $selectedSizes = $request->collect('sizes')->filter()->all();
        if (! empty($selectedSizes)) {
            $query->whereHas('variants', function ($q) use ($selectedSizes) {
                $q->whereIn('size_label', $selectedSizes);
            });
        }

        // Sort
        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderBy(
                Product::selectRaw('MIN(amount)')
                    ->from('prices')
                    ->join('product_variants', 'product_variants.id', '=', 'prices.product_variant_id')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->where('prices.effective_date', '<=', now())
                    ->orderBy('effective_date', 'desc')
                    ->limit(1),
                'asc'
            ),
            'price_desc' => $query->orderBy(
                Product::selectRaw('MIN(amount)')
                    ->from('prices')
                    ->join('product_variants', 'product_variants.id', '=', 'prices.product_variant_id')
                    ->whereColumn('product_variants.product_id', 'products.id')
                    ->where('prices.effective_date', '<=', now())
                    ->orderBy('effective_date', 'desc')
                    ->limit(1),
                'desc'
            ),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $products = $query->get();

        $sizes = DB::table('product_variants')
            ->select('size_label')
            ->whereNotNull('size_label')
            ->distinct()
            ->orderBy('size_label')
            ->pluck('size_label');

        return Inertia::render('Products/Index', [
            'products' => $products->map(function (Product $product) {
                $variant = $product->variants->first();
                $price = $variant?->prices->first();
                $promotion = $variant?->promotions
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>=', now())
                    ->first();

                return [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'name' => $product->name,
                    'brand' => $product->brand->name,
                    'image' => $product->images->first()?->path ?? '/assets/img/pexels-suhashanjar-36779951.webp',
                    'price' => $price?->amount,
                    'sale_price' => $promotion?->sale_price,
                    'size_label' => $variant?->size_label,
                ];
            }),
            'filters' => [
                'brands' => $selectedBrands,
                'sizes' => $selectedSizes,
                'sort' => $sort,
            ],
            'brands' => Brand::orderBy('name')->get(['id', 'name', 'slug']),
            'sizes' => $sizes,
        ]);
    }

    public function show(Request $request, string $slug): Response
    {
        $product = Product::query()
            ->with(['brand', 'images', 'variants.prices', 'variants.promotions'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return Inertia::render('Products/Show', [
            'product' => [
                'id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'description' => $product->description,
                'brand' => $product->brand->name,
                'images' => $product->images->map(fn ($img) => [
                    'path' => $img->path,
                    'alt' => $img->alt_text,
                    'is_primary' => $img->is_primary,
                ]),
                'variants' => $product->variants->map(function ($variant) {
                    $price = $variant->prices->first();
                    $promotion = $variant->promotions
                        ->where('starts_at', '<=', now())
                        ->where('ends_at', '>=', now())
                        ->first();

                    return [
                        'id' => $variant->id,
                        'size_label' => $variant->size_label,
                        'sku' => $variant->sku,
                        'price' => $price?->amount,
                        'sale_price' => $promotion?->sale_price,
                    ];
                }),
            ],
        ]);
    }
}
