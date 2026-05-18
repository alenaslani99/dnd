<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductListResource;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Product::query()
            ->with(['brand', 'primaryImage', 'activeVariants.latestPrice', 'activeVariants.currentPromotion'])
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

        // Multi-gender filter
        $selectedGenders = $request->collect('genders')->filter()->all();
        if (! empty($selectedGenders)) {
            $query->whereIn('gender', $selectedGenders);
        }

        // Sort
        $sort = $request->input('sort');
        $priceSub = Product::select('amount')
            ->from('prices')
            ->join('product_variants', 'product_variants.id', '=', 'prices.product_variant_id')
            ->whereColumn('product_variants.product_id', 'products.id')
            ->orderBy('prices.created_at', 'desc')
            ->limit(1);

        match ($sort) {
            'price_asc' => $query->orderBy($priceSub, 'asc'),
            'price_desc' => $query->orderBy($priceSub, 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $products = $query->paginate(24)->withQueryString();

        $sizes = DB::table('product_variants')
            ->select('size_label')
            ->whereNotNull('size_label')
            ->distinct()
            ->orderBy('size_label')
            ->pluck('size_label');

        return Inertia::render('Products/Index', [
            'products' => $products->through(fn (Product $product) => ProductListResource::make($product)->toArray($request)),
            'total_count' => (string) $products->total(),
            'filters' => [
                'brands' => $selectedBrands,
                'sizes' => $selectedSizes,
                'genders' => $selectedGenders,
                'sort' => $sort,
            ],
            'brands' => Brand::orderBy('name')->get(['id', 'name', 'slug']),
            'sizes' => $sizes,
            'genders' => [
                ['value' => 'male', 'label' => 'Muški'],
                ['value' => 'female', 'label' => 'Ženski'],
                ['value' => 'unisex', 'label' => 'Uniseks'],
            ],
        ]);
    }

    public function show(Request $request, string $slug): Response
    {
        $product = Product::query()
            ->with(['brand', 'images', 'primaryImage', 'activeVariants.latestPrice', 'activeVariants.currentPromotion'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return Inertia::render('Products/Show', [
            'product' => ProductDetailResource::make($product)->toArray($request),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 3) {
            return response()->json([]);
        }

        $products = Product::query()
            ->with(['brand', 'primaryImage', 'activeVariants.latestPrice'])
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%'.$query.'%')
                    ->orWhereHas('brand', function ($bq) use ($query) {
                        $bq->where('name', 'like', '%'.$query.'%');
                    });
            })
            ->limit(5)
            ->get();

        return response()->json(
            $products->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'brand' => $product->brand?->name ?? '',
                'image' => $product->primaryImage?->path,
                'price' => $product->activeVariants->first()?->latestPrice?->amount
                    ? number_format($product->activeVariants->first()->latestPrice->amount, 0, ',', '.').' RSD'
                    : '',
            ])
        );
    }
}
