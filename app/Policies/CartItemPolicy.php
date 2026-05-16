<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;

class CartItemPolicy
{
    public function update(?User $user, CartItem $item): bool
    {
        if ($item->cart->user_id) {
            return $user !== null && $item->cart->user_id === $user->id;
        }

        return $item->cart->session_id === session()->getId();
    }

    public function delete(?User $user, CartItem $item): bool
    {
        return $this->update($user, $item);
    }
}
