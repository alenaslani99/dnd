<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class BrandController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Brands/Index', [
            'brands' => Cache::remember('brands_listing.brands', 86400, fn () => Brand::query()
                ->withCount('products')
                ->orderBy('name')
                ->get()
                ->toArray(),
            ),
        ]);
    }
}
