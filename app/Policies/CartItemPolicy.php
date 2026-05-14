<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartItemPolicy
{
    public function update(?User $user, CartItem $item): bool
    {
        if ($item->cart->user_id) {
            return Auth::check() && $item->cart->user_id === Auth::id();
        }

        return $item->cart->session_id === session()->getId();
    }

    public function delete(?User $user, CartItem $item): bool
    {
        return $this->update($user, $item);
    }
}
