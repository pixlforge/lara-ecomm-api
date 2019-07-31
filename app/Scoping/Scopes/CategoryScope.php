<?php

namespace App\Scoping\Scopes;

use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class CategoryScope implements Scope
{
    /**
     * Apply scopes.
     *
     * @param Builder $builder
     * @param string $value
     * @return Builder
     */
    public function apply(Builder $builder, $value)
    {
        return $builder->whereHas('categories', function ($builder) use ($value) {
            $builder->where('slug', $value);
        });
    }
}