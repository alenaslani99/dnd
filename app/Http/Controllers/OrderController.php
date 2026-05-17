<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function show(Request $request, string $orderNumber): Response|RedirectResponse
    {
        $order = Order::with(['items.productVariant.product.brand'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $receiptOrderId = $request->session()->get('receipt_order_id');

        // One-time receipt access after successful checkout.
        if ($receiptOrderId === $order->id) {
            $request->session()->forget('receipt_order_id');

            return Inertia::render('Orders/Show', [
                'order' => $this->orderToArray($order),
                'isReceipt' => true,
            ]);
        }

        // Strict authorization: authenticated users can only view their own orders.
        if ($order->user_id !== null) {
            if (! Auth::check() || $order->user_id !== Auth::id()) {
                abort(403);
            }

            return Inertia::render('Orders/Show', [
                'order' => $this->orderToArray($order),
                'isReceipt' => false,
            ]);
        }

        // Guest orders without a receipt flash require secondary verification.
        return redirect()->route('track-order.create', [
            'order_number' => $order->order_number,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function orderToArray(Order $order): array
    {
        return [
            'order_number' => $order->order_number,
            'status' => $order->status->value,
            'total_amount' => $order->total_amount,
            'shipping_cost' => $order->shipping_cost,
            'created_at' => $order->created_at->format('d.m.Y.'),
            'items' => $order->items->map(function ($item) {
                return [
                    'product_name' => $item->product_name_snapshot ?? $item->productVariant?->product?->name ?? 'Proizvod',
                    'brand_name' => $item->brand_name_snapshot ?? $item->productVariant?->product?->brand?->name ?? '',
                    'size_label' => $item->size_label_snapshot ?? $item->productVariant?->size_label,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->unit_price * $item->quantity,
                ];
            }),
        ];
    }
}
