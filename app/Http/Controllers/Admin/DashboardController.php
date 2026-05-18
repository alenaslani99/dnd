<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\ContactSubmission;
use App\Models\Order;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::pending()->count(),
            'today_revenue' => (float) Order::whereDate('created_at', today())->sum('total_amount'),
            'total_products' => Product::count(),
            'total_brands' => Brand::count(),
            'unread_messages' => ContactSubmission::whereNull('read_at')->count(),
        ];

        $recentOrders = Order::query()
            ->with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn (Order $order) => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->user?->name ?? $order->guest_name ?? 'Gost',
                'total_amount' => $order->total_amount,
                'status' => $order->status->value,
                'created_at' => $order->created_at->format('d.m.Y. H:i'),
                'items_count' => $order->items->sum('quantity'),
            ]);

        $ordersByStatus = [
            ['label' => 'Na čekanju', 'value' => Order::pending()->count(), 'color' => 'yellow'],
            ['label' => 'U obradi', 'value' => Order::processing()->count(), 'color' => 'blue'],
            ['label' => 'Poslato', 'value' => Order::shipped()->count(), 'color' => 'purple'],
            ['label' => 'Dostavljeno', 'value' => Order::delivered()->count(), 'color' => 'green'],
            ['label' => 'Otkazano', 'value' => Order::cancelled()->count(), 'color' => 'gray'],
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'ordersByStatus' => $ordersByStatus,
        ]);
    }
}
