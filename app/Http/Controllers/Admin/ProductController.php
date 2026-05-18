<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Product::query()
            ->with(['brand', 'variants.latestPrice']);

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhereHas('brand', function ($bq) use ($search) {
                        $bq->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'price_asc' => $query->orderBy(
                Product::selectRaw('MIN(amount)')
                    ->from('prices')
                    ->join('product_variants', 'product_variants.id', '=', 'prices.product_variant_id')
                    ->whereColumn('product_variants.product_id', 'products.id'),
                'asc'
            ),
            'price_desc' => $query->orderBy(
                Product::selectRaw('MIN(amount)')
                    ->from('prices')
                    ->join('product_variants', 'product_variants.id', '=', 'prices.product_variant_id')
                    ->whereColumn('product_variants.product_id', 'products.id'),
                'desc'
            ),
            default => $query->orderBy('created_at', 'desc'),
        };

        $products = $query->paginate(20)->withQueryString();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products->through(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $product->brand?->name ?? '',
                'type' => $product->type,
                'gender' => $product->gender,
                'is_active' => $product->is_active,
                'price' => $product->variants->first()?->latestPrice?->amount
                    ? number_format($product->variants->first()->latestPrice->amount, 0, ',', '.').' RSD'
                    : '-',
                'variants_count' => $product->variants->count(),
            ]),
            'filters' => [
                'search' => $search,
                'sort' => $sort,
            ],
        ]);
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->back()->with('success', 'Proizvod je uspešno obrisan.');
    }
}
