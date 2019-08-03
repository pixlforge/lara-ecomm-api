<?php

namespace App\Models;

use App\Money\Money;
use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasPrice;

    /**
     * Get the price attribute.
     *
     * @return int
     */
    public function getPriceAttribute($value)
    {
        if (is_null($value)) {
            return $this->product->price;
        }

        return new Money($value);
    }

    /**
     * Checks whether or not the variation price varies
     * from the price of the base product.
     *
     * @return void
     */
    public function priceVaries()
    {
        return $this->price->getAmount() !== $this->product->price->getAmount();
    }

    /**
     * Type relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->hasOne(ProductVariationType::class, 'id', 'product_variation_type_id');
    }

    /**
     * Product relationship.
     *
     * @return void
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Stocks relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
