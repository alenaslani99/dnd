<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BrandController extends Controller
{
    public function index(Request $request): Response
    {
        $brands = Brand::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Brands/Index', [
            'brands' => $brands->map(fn (Brand $brand) => [
                'id' => $brand->id,
                'name' => $brand->name,
                'slug' => $brand->slug,
                'logo' => $brand->logo,
                'products_count' => $brand->products_count,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
            'logo' => ['required', 'file', 'mimes:svg', 'max:2048'],
        ]);

        $logo = $request->file('logo');
        $filename = time().'_'.Str::slug($validated['name']).'.svg';
        $validated['logo'] = $logo->storeAs('brands', $filename, 'public');

        Brand::create($validated);

        Cache::forget('product_listing.brands');
        Cache::forget('homepage.brands');
        Cache::forget('brands_listing.brands');

        return redirect()->back()->with('success', 'Brend je uspešno dodat.');
    }
}
