<?php

namespace App\Models;

use App\Scoping\Contracts\HasScopes;
use App\Models\Traits\HasScopesTrait;
use App\Scoping\Scopes\CategoryScope;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements HasScopes
{
    use HasScopesTrait;

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
