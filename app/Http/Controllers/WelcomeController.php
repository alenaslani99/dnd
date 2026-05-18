<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductListResource;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $featuredProducts = Product::query()
            ->with(['brand', 'primaryImage', 'activeVariants.latestPrice', 'activeVariants.currentPromotion'])
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return Inertia::render('Welcome', [
            'featuredProducts' => $featuredProducts->map(fn (Product $product) => ProductListResource::make($product)->toArray($request)),
            'brands' => Inertia::defer(fn () => Cache::remember('homepage.brands', 86400, fn () => Brand::orderBy('name')->get(['name', 'slug', 'logo'])
                ->map(fn ($b) => [
                    'name' => $b->name,
                    'logo' => $b->logo,
                ])->toArray(),
            )),
        ]);
    }
}
