<?php

namespace App\Models\Traits;

use App\Money\Money;

trait HasPrice
{
    /**
     * Get the price attribute.
     *
     * @return int
     */
    public function getPriceAttribute($value)
    {
        return new Money($value);
    }

    /**
     * Get the formattedPrice attribute.
     *
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        return $this->price->formatted();
    }

    /**
     * Get the detailedPrice attribute.
     *
     * @return void
     */
    public function getDetailedPriceAttribute()
    {
        return $this->price->detailed();
    }
}