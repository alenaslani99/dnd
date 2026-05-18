<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Order::query()
            ->with(['user', 'items'])
            ->orderBy('created_at', 'desc');

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%'.$search.'%')
                    ->orWhere('guest_email', 'like', '%'.$search.'%')
                    ->orWhere('guest_name', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        $status = $request->input('status');
        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20)->withQueryString();

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders->through(fn (Order $order) => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->user?->name ?? $order->guest_name ?? 'Gost',
                'customer_email' => $order->user?->email ?? $order->guest_email ?? '',
                'total_amount' => $order->total_amount,
                'status' => $order->status->value,
                'created_at' => $order->created_at->format('d.m.Y. H:i'),
                'items_count' => $order->items->sum('quantity'),
            ]),
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
            'statuses' => collect(OrderStatus::cases())->map(fn ($s) => [
                'value' => $s->value,
                'label' => $this->statusLabel($s->value),
            ])->all(),
        ]);
    }

    public function show(Request $request, string $orderNumber): Response
    {
        $order = Order::with(['user', 'items'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return Inertia::render('Admin/Orders/Show', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status->value,
                'total_amount' => $order->total_amount,
                'shipping_cost' => $order->shipping_cost,
                'payment_method' => $order->payment_method,
                'created_at' => $order->created_at->format('d.m.Y. H:i'),
                'customer' => [
                    'name' => $order->user?->name ?? $order->guest_name ?? 'Gost',
                    'email' => $order->user?->email ?? $order->guest_email ?? '',
                    'phone' => $order->user?->phone ?? $order->guest_phone ?? '',
                ],
                'shipping' => [
                    'address' => $order->shipping_address,
                    'house_number' => $order->shipping_house_number,
                    'zip' => $order->shipping_zip,
                    'city' => $order->shipping_city,
                ],
                'items' => $order->items->map(fn ($item) => [
                    'product_name' => $item->product_name_snapshot,
                    'brand_name' => $item->brand_name_snapshot,
                    'size_label' => $item->size_label_snapshot,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->unit_price * $item->quantity,
                ]),
                'next_status' => $this->nextStatus($order->status)?->value,
            ],
        ]);
    }

    public function updateStatus(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $next = $this->nextStatus($order->status);
        if ($next === null) {
            return back()->with('error', 'Status ne može biti promenjen.');
        }

        $order->update(['status' => $next]);

        return back()->with('success', 'Status porudžbine je ažuriran.');
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Na čekanju',
            'processing' => 'U obradi',
            'shipped' => 'Poslato',
            'delivered' => 'Dostavljeno',
            'cancelled' => 'Otkazano',
            'refunded' => 'Refundirano',
            default => $status,
        };
    }

    private function nextStatus(OrderStatus $status): ?OrderStatus
    {
        return match ($status) {
            OrderStatus::Pending => OrderStatus::Processing,
            OrderStatus::Processing => OrderStatus::Shipped,
            OrderStatus::Shipped => OrderStatus::Delivered,
            default => null,
        };
    }
}
