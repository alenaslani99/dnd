<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackOrder\StoreRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TrackOrderController extends Controller
{
    public function create(Request $request): Response
    {
        $orderNumber = $request->query('order_number');
        $verifiedEmail = $request->query('verified_email');

        $order = null;
        if ($orderNumber && $this->canViewOrder($orderNumber, $verifiedEmail)) {
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
                    return [
                        'product_name' => $item->product_name_snapshot ?? $item->productVariant?->product?->name ?? 'Proizvod',
                        'brand_name' => $item->brand_name_snapshot ?? $item->productVariant?->product?->brand?->name ?? '',
                        'size_label' => $item->size_label_snapshot ?? $item->productVariant?->size_label,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->unit_price * $item->quantity,
                    ];
                }),
            ] : null,
            'searched' => $orderNumber !== null,
            'verified_email' => $verifiedEmail,
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $order = Order::where('order_number', $request->order_number)->first();

        if (! $order) {
            return redirect()->route('track-order.create', [
                'order_number' => $request->order_number,
            ])->with('error', 'Porudžbina sa ovim brojem nije pronađena.');
        }

        // For guest orders, verify email matches the order's guest email.
        if ($order->user_id === null) {
            if (strtolower((string) $order->guest_email) !== strtolower($request->email)) {
                return redirect()->route('track-order.create', [
                    'order_number' => $request->order_number,
                ])->with('error', 'Email adresa se ne poklapa sa porudžbinom.');
            }
        }

        // For authenticated orders, verify the logged-in user owns it.
        if ($order->user_id !== null && (! Auth::check() || $order->user_id !== Auth::id())) {
            return redirect()->route('track-order.create', [
                'order_number' => $request->order_number,
            ])->with('error', 'Nemate dozvolu da pristupite ovoj porudžbini.');
        }

        return redirect()->route('track-order.create', [
            'order_number' => $order->order_number,
            'verified_email' => $request->email,
        ])->with('success', 'Porudžbina je pronađena.');
    }

    private function canViewOrder(string $orderNumber, ?string $verifiedEmail): bool
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (! $order) {
            return false;
        }

        // Authenticated users can view their own orders directly.
        if ($order->user_id !== null) {
            return Auth::check() && $order->user_id === Auth::id();
        }

        // Guest orders require verified email.
        if ($verifiedEmail === null) {
            return false;
        }

        return strtolower((string) $order->guest_email) === strtolower($verifiedEmail);
    }
}
