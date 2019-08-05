<?php

namespace App\Models;

use App\Models\Traits\HasPrice;
use App\Scoping\Contracts\HasScopes;
use App\Models\Traits\HasScopesTrait;
use App\Scoping\Scopes\CategoryScope;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements HasScopes
{
    use HasScopesTrait, HasPrice;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Checks whether or not the product has any stock at all.
     *
     * @return boolean
     */
    public function inStock()
    {
        return $this->stockCount() > 0;
    }

    /**
     * Get the stock count from all related product variations.
     *
     * @return integer
     */
    public function stockCount()
    {
        return $this->variations->sum(function ($variation) {
            return $variation->stockCount();
        });
    }

    /**
     * The scopes by which a product can be scoped.
     *
     * @return array
     */
    public function scopes()
    {
        return [
            'category' => new CategoryScope(),
        ];
    }

    /**
     * Categories relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Variations relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variations()
    {
        return $this->hasMany(ProductVariation::class)->orderBy('order', 'asc');
    }
}
