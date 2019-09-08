<?php

namespace App\Cart;

use App\Models\User;
use App\Money\Money;
use App\Models\ShippingMethod;

class Cart
{
    /**
     * The user property.
     *
     * @var User
     */
    protected $user;

    /**
     * Indicates that a product's quantity in the user's
     * cart has been updated automatically.
     *
     * @var boolean
     */
    protected $changed = false;

    /**
     * The shipping method.
     *
     * @var string
     */
    protected $shippingMethod;

    /**
     * Cart constructor.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the product variations in the user's cart.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function products()
    {
        return $this->user->cart;
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
     * Update a product variation's quantity in the cart.
     *
     * @param int $productId
     * @param int $quantity
     * @return void
     */
    public function update($productId, $quantity)
    {
        $this->user->cart()->updateExistingPivot($productId, [
            'quantity' => $quantity
        ]);
    }

    /**
     * Delete a product from the user's cart.
     *
     * @param int $productId
     * @return void
     */
    public function delete($productId)
    {
        $this->user->cart()->detach($productId);
    }

    /**
     * Empty the user's cart.
     *
     * @return void
     */
    public function empty()
    {
        $this->user->cart()->detach();
    }

    /**
     * Checks whether the cart is empty in terms of quantity.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->user->cart->sum('pivot.quantity') <= 0;
    }

    /**
     * Add a shipping method id to the cart.
     *
     * @param string $shippingMethodId
     * @return $this
     */
    public function withShipping($shippingMethodId)
    {
        $this->shippingMethod = ShippingMethod::find($shippingMethodId);

        return $this;
    }

    /**
     * Get the user's cart subtotal.
     *
     * @return Money
     */
    public function subtotal()
    {
        $subtotal = $this->user->cart->sum(function ($product) {
            return $product->price->getAmount() * $product->pivot->quantity;
        });

        return new Money($subtotal);
    }

    /**
     * Get the user's cart total.
     *
     * @return Money
     */
    public function total()
    {
        if ($this->shippingMethod) {
            return $this->subtotal()->add($this->shippingMethod->price);
        }
        
        return $this->subtotal();
    }

    /**
     * Sync the cart product variation quantities.
     *
     * @return void
     */
    public function sync()
    {
        $this->user->cart->each(function ($product) {
            $quantity = $product->minStock($product->pivot->quantity);

            if ($quantity != $product->pivot->quantity) {
                $this->changed = true;
            }

            if ($this->hasChanged()) {
                $product->pivot->update([
                    'quantity' => $quantity
                ]);
            }
        });
    }

    /**
     * Checks whether a product quantity in the
     * user's cart has been updated or not.
     *
     * @return boolean
     */
    public function hasChanged()
    {
        return $this->changed;
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
