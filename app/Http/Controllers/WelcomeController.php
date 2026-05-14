<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductListResource;
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
            ->with(['brand', 'images', 'activeVariants.latestPrice', 'activeVariants.currentPromotion'])
            ->where('is_active', true)
            ->limit(4)
            ->get();

        $brands = Brand::orderBy('name')->get(['name', 'slug', 'logo']);

        return Inertia::render('Welcome', [
            'featuredProducts' => $featuredProducts->map(fn (Product $product) => ProductListResource::make($product)->toArray($request)),
            'brands' => $brands->map(fn ($b) => [
                'name' => $b->name,
                'logo' => $b->logo,
            ]),
        ]);
    }
}
