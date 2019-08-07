<?php

namespace App\Cart;

use App\Models\User;

class Cart
{
    /**
     * The user property.
     *
     * @var User
     */
    protected $user;

    /**
     * Cart constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Add product variations to the user's cart.
     *
     * @param array $products
     * @return $this
     */
    public function add($products)
    {
        $this->user->cart()->syncWithoutDetaching($this->formatPayload($products));

        return $this;
    }

    /**
     * Format the product variations to add to the cart.
     *
     * @param array $products
     * @return array
     */
    protected function formatPayload($products)
    {
        return collect($products)->keyBy('id')->map(function ($product) {
            return [
                'quantity' => $product['quantity'] + $this->getCurrentQuantity($product['id'])
            ];
        })->toArray();
    }

    /**
     * Get the current quantity for a product variation
     *
     * @param int $productId
     * @return int
     */
    protected function getCurrentQuantity($productId)
    {
        if ($product = $this->user->cart->where('id', $productId)->first()) {
            return $product->pivot->quantity;
        }

        return 0;
    }
}