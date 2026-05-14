<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrackOrderController extends Controller
{
    public function create(Request $request): Response
    {
        $orderNumber = $request->query('order_number');

        $order = null;
        if ($orderNumber) {
            $order = Order::with(['items.productVariant.product.brand'])
                ->where('order_number', $orderNumber)
                ->first();
        }

        return Inertia::render('TrackOrder/Index', [
            'order' => $order ? [
                'order_number' => $order->order_number,
                'status' => $order->status->value,
                'total_amount' => $order->total_amount,
                'shipping_cost' => $order->shipping_cost,
                'created_at' => $order->created_at->format('d.m.Y.'),
                'items' => $order->items->map(function ($item) {
                    $variant = $item->productVariant;

                    return [
                        'product_name' => $variant?->product?->name ?? 'Proizvod',
                        'brand_name' => $variant?->product?->brand?->name ?? '',
                        'size_label' => $variant?->size_label,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->unit_price * $item->quantity,
                    ];
                }),
            ] : null,
            'searched' => $orderNumber !== null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_number' => ['required', 'string', 'max:20'],
        ]);

        $order = Order::where('order_number', $validated['order_number'])->first();

        if (! $order) {
            return redirect()->route('track-order.create', [
                'order_number' => $validated['order_number'],
            ])->with('error', 'Porudžbina sa ovim brojem nije pronađena.');
        }

        return redirect()->route('track-order.create', [
            'order_number' => $order->order_number,
        ])->with('success', 'Porudžbina je pronađena.');
    }
}
