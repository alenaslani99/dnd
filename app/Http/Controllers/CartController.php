<?php

namespace App\Http\Controllers;

use App\Actions\GetOrCreateCart;
use App\Http\Requests\Cart\StoreRequest;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(Request $request, GetOrCreateCart $cartAction): Response
    {
        $cart = $cartAction->getOrCreate();
        $cart->load([
            'items.productVariant.product.brand',
            'items.productVariant.product.images',
        ]);

        return Inertia::render('Cart/Index', [
            'cart' => [
                'items' => $cart->items->map(function (CartItem $item) {
                    $variant = $item->productVariant;
                    $unitPrice = $item->price_snapshot ?? $variant->activePrice() ?? 0;

                    return [
                        'id' => $item->id,
                        'quantity' => $item->quantity,
                        'variant' => [
                            'id' => $variant->id,
                            'size_label' => $variant->size_label,
                            'sku' => $variant->sku,
                        ],
                        'product' => [
                            'slug' => $variant->product->slug,
                            'name' => $variant->product->name,
                            'brand' => $variant->product->brand->name,
                            'image' => $variant->product->images->first()?->path ?? '/assets/img/pexels-suhashanjar-36779951.webp',
                        ],
                        'unit_price' => $unitPrice,
                        'total_price' => $unitPrice * $item->quantity,
                    ];
                }),
                'total' => $cart->items->sum(function (CartItem $item) {
                    return ($item->price_snapshot ?? $item->productVariant->activePrice() ?? 0) * $item->quantity;
                }),
                'shipping_cost' => 500,
            ],
        ]);
    }

    public function store(StoreRequest $request, GetOrCreateCart $cartAction): RedirectResponse
    {
        $cart = $cartAction->getOrCreate();
        $variant = ProductVariant::with('product')->findOrFail($request->product_variant_id);

        if (! $variant->is_active || ! $variant->product->is_active) {
            return redirect()->back()->with('error', 'Proizvod nije dostupan.');
        }

        if (! $variant->is_available) {
            return redirect()->back()->with('error', 'Proizvod trenutno nije na stanju.');
        }

        $item = $cart->items()->where('product_variant_id', $variant->id)->first();
        $unitPrice = $variant->activePrice() ?? 0;

        if ($item) {
            $newQuantity = $item->quantity + $request->quantity;

            if ($newQuantity > 10) {
                return redirect()->back()->with('error', 'Maksimalna količina po proizvodu je 10.');
            }

            $item->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_variant_id' => $variant->id,
                'quantity' => $request->quantity,
                'price_snapshot' => $unitPrice,
            ]);
        }

        return redirect()->route('cart.index');
    }

    public function update(Request $request, CartItem $item): RedirectResponse
    {
        $this->authorize('update', $item);

        $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:10']]);

        $variant = $item->productVariant;
        if ($variant && ! $variant->is_available) {
            return redirect()->back()->with('error', 'Proizvod trenutno nije na stanju.');
        }

        $item->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index');
    }

    public function destroy(CartItem $item): RedirectResponse
    {
        $this->authorize('delete', $item);

        $item->delete();

        return redirect()->route('cart.index');
    }
}
