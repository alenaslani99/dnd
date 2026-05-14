<?php

namespace App\Actions;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class GetOrCreateCart
{
    public function getOrCreate(): Cart
    {
        $cart = $this->findCart();

        if (! $cart) {
            $cart = Cart::create(
                Auth::check()
                    ? ['user_id' => Auth::id(), 'session_id' => null, 'expires_at' => now()->addDays(7)]
                    : ['user_id' => null, 'session_id' => session()->getId(), 'expires_at' => now()->addDays(7)]
            );
        }

        return $cart;
    }

    public function find(): ?Cart
    {
        return $this->findCart();
    }

    private function findCart(): ?Cart
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())
                ->where('expires_at', '>', now())
                ->latest()
                ->first();
        }

        return Cart::where('session_id', session()->getId())
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }
}
