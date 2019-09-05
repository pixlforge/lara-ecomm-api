<?php

namespace App\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class ProductVariationCollection extends Collection
{
    /**
     * Format the products variations in the user's cart
     * for syncing when ordering.
     *
     * @return array
     */
    public function forSyncing()
    {
        return $this->keyBy('id')->map(function ($variation) {
            return [
                'quantity' => $variation->pivot->quantity
            ];
        })->toArray();
    }
}