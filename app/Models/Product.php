<?php

namespace App\Models;

use App\Scoping\Scoper;
use App\Scoping\Scopes\CategoryScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
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
    protected function scopes()
    {
        return [
            'category' => new CategoryScope(),
        ];
    }

    /**
     * Model can be scoped by a list of scopes.
     *
     * @param Builder $builder
     * @param array $scopes
     * @return void
     */
    public function scopeWithScopes(Builder $builder)
    {
        return (new Scoper(request()))->apply($builder, self::scopes());
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
