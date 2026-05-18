<?php

namespace App\Http\Middleware;

use App\Actions\GetOrCreateCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $data = [];

        if ($request->hasSession()) {
            $data = parent::share($request);
        }

        $cacheKey = 'cart_count.'.($request->user()?->id ?? $request->session()->getId());

        $cartCount = Cache::remember($cacheKey, 300, function () {
            $cart = app(GetOrCreateCart::class)->find();

            return $cart?->items()->count() ?? 0;
        });

        return [
            ...$data,
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => $request->hasSession() ? [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ] : [],
            'cartCount' => $cartCount,
        ];
    }
}
