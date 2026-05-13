<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\Checkout\StoreRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function create(Request $request): Response
    {
        $cart = $this->getCart();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $cart->load(['items.productVariant.product.brand', 'items.productVariant.prices', 'items.productVariant.promotions']);

        $items = $cart->items->map(function ($item) {
            $variant = $item->productVariant;
            $price = $variant->prices->first();
            $promotion = $variant->promotions
                ->where('starts_at', '<=', now())
                ->where('ends_at', '>=', now())
                ->first();
            $unitPrice = $promotion?->sale_price ?? $price?->amount ?? 0;

            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'product_name' => $variant->product->name,
                'brand_name' => $variant->product->brand->name,
                'size_label' => $variant->size_label,
                'unit_price' => $unitPrice,
                'total_price' => $unitPrice * $item->quantity,
            ];
        });

        $subtotal = $items->sum('total_price');
        $shipping = 500;

        return Inertia::render('Checkout/Index', [
            'items' => $items,
            'summary' => [
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $subtotal + $shipping,
            ],
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $cart = $this->getCart();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $cart->load(['items.productVariant.prices', 'items.productVariant.promotions']);

        $order = DB::transaction(function () use ($request, $cart) {
            $subtotal = 0;
            foreach ($cart->items as $item) {
                $variant = $item->productVariant;
                $price = $variant->prices->first();
                $promotion = $variant->promotions
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>=', now())
                    ->first();
                $subtotal += ($promotion?->sale_price ?? $price?->amount ?? 0) * $item->quantity;
            }

            $shipping = 500;

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'DND-'.now()->format('Ymd').'-'.Str::upper(Str::random(3)),
                'status' => OrderStatus::Pending,
                'guest_email' => Auth::guest() ? $request->email : null,
                'guest_phone' => Auth::guest() ? $request->phone : null,
                'guest_name' => Auth::guest() ? $request->name : null,
                'shipping_address' => $request->address,
                'shipping_house_number' => $request->house_number,
                'shipping_zip' => $request->zip,
                'shipping_city' => $request->city,
                'total_amount' => $subtotal + $shipping,
                'shipping_cost' => $shipping,
                'payment_method' => 'cash_on_delivery',
            ]);

            foreach ($cart->items as $item) {
                $variant = $item->productVariant;
                $price = $variant->prices->first();
                $promotion = $variant->promotions
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>=', now())
                    ->first();
                $unitPrice = $promotion?->sale_price ?? $price?->amount ?? 0;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                ]);
            }

            $cart->items()->delete();
            $cart->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order->order_number);
    }

    private function getCart(): ?Cart
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())
                ->where('expires_at', '>', now())
                ->latest()
                ->first();
        }

        $sessionId = Cookie::get('cart_session');

        if (! $sessionId) {
            return null;
        }

        return Cart::where('session_id', $sessionId)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }
}
