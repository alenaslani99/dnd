<?php

namespace App\Http\Controllers;

use App\Actions\GetOrCreateCart;
use App\Enums\OrderStatus;
use App\Http\Requests\Checkout\StoreRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function create(Request $request, GetOrCreateCart $cartAction): Response|RedirectResponse
    {
        $cart = $cartAction->find();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $cart->load([
            'items.productVariant.product.brand',
        ]);

        $items = $cart->items->map(function ($item) {
            $variant = $item->productVariant;
            $unitPrice = $item->price_snapshot ?? $variant->activePrice() ?? 0;

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

    public function store(StoreRequest $request, GetOrCreateCart $cartAction): RedirectResponse
    {
        $cart = $cartAction->find();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $cart->load([
            'items.productVariant.product.brand',
        ]);

        foreach ($cart->items as $item) {
            $variant = $item->productVariant;

            if (! $variant || ! $variant->is_active || ! $variant->is_available || ! $variant->product->is_active) {
                return redirect()->route('cart.index')->with('error', 'Neki proizvodi u korpi više nisu dostupni.');
            }
        }

        $order = DB::transaction(function () use ($request, $cart) {
            $subtotal = 0;
            foreach ($cart->items as $item) {
                $subtotal += ($item->price_snapshot ?? $item->productVariant->activePrice() ?? 0) * $item->quantity;
            }

            $shipping = 500;

            $order = new Order([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-'.Str::upper(Str::random(6)),
                'guest_email' => Auth::guest() ? $request->email : null,
                'guest_phone' => Auth::guest() ? $request->phone : null,
                'guest_name' => Auth::guest() ? $request->name : null,
                'shipping_address' => $request->address,
                'shipping_house_number' => $request->house_number,
                'shipping_zip' => $request->zip,
                'shipping_city' => $request->city,
                'shipping_cost' => $shipping,
                'payment_method' => 'cash_on_delivery',
            ]);
            $order->total_amount = $subtotal + $shipping;
            $order->status = OrderStatus::Pending;
            $order->save();

            foreach ($cart->items as $item) {
                $variant = $item->productVariant;
                $unitPrice = $item->price_snapshot ?? $variant->activePrice() ?? 0;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'product_name_snapshot' => $variant->product->name,
                    'brand_name_snapshot' => $variant->product->brand->name,
                    'size_label_snapshot' => $variant->size_label,
                ]);
            }

            $cart->items()->delete();
            $cart->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order->order_number);
    }
}
