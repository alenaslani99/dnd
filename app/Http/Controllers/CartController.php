<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    private function getOrCreateCart(): Cart
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            if (! $cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'session_id' => null,
                    'expires_at' => now()->addDays(7),
                ]);
            }

            return $cart;
        }

        $sessionId = session()->getId();

        $cart = Cart::where('session_id', $sessionId)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $cart) {
            $cart = Cart::create([
                'user_id' => null,
                'session_id' => $sessionId,
                'expires_at' => now()->addDays(7),
            ]);
        }

        return $cart;
    }

    public function index(Request $request): Response
    {
        $cart = $this->getOrCreateCart();
        $cart->load([
            'items.productVariant.product.brand',
            'items.productVariant.product.images',
            'items.productVariant.prices',
            'items.productVariant.promotions',
        ]);

        return Inertia::render('Cart/Index', [
            'cart' => [
                'items' => $cart->items->map(function (CartItem $item) {
                    $variant = $item->productVariant;
                    $price = $variant->prices->first();
                    $promotion = $variant->promotions
                        ->where('starts_at', '<=', now())
                        ->where('ends_at', '>=', now())
                        ->first();

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
                        'unit_price' => $promotion?->sale_price ?? $price?->amount,
                        'total_price' => ($promotion?->sale_price ?? $price?->amount) * $item->quantity,
                    ];
                }),
                'total' => $cart->items->sum(function (CartItem $item) {
                    $variant = $item->productVariant;
                    $price = $variant->prices->first();
                    $promotion = $variant->promotions
                        ->where('starts_at', '<=', now())
                        ->where('ends_at', '>=', now())
                        ->first();

                    return ($promotion?->sale_price ?? $price?->amount) * $item->quantity;
                }),
                'shipping_cost' => 500,
            ],
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $cart = $this->getOrCreateCart();
        $variant = ProductVariant::with('product')->findOrFail($request->product_variant_id);

        if (! $variant->is_active || ! $variant->product->is_active) {
            return redirect()->back()->with('error', 'Proizvod nije dostupan.');
        }

        if (! $variant->is_available) {
            return redirect()->back()->with('error', 'Proizvod trenutno nije na stanju.');
        }

        $item = $cart->items()->where('product_variant_id', $variant->id)->first();

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
