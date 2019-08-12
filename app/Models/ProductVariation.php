<?php

namespace App\Models;

use App\Money\Money;
use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pivots\ProductVariationStockPivot;

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
     * Undocumented function
     *
     * @return void
     */
    public function inStock()
    {
        return $this->stockCount() > 0;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function stockCount()
    {
        return $this->stock->sum('pivot.stock');
    }

    /**
     * Returns the minimal quantity between stock count and items in cart.
     *
     * @param int $quantity
     * @return int
     */
    public function minStock($quantity)
    {
        return min($this->stockCount(), $quantity);
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

    /**
     * Undocumented function
     *
     * @return void
     */
    public function stock()
    {
        return $this->belongsToMany(ProductVariation::class, 'product_variation_stock_view')
            ->using(ProductVariationStockPivot::class)
            ->withPivot([
                'stock', 'in_stock'
            ]);
    }
}
