<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasChildren
{
    /**
     * Get only the models which are top level.
     *
     * @param Builder $builder
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeParents(Builder $builder)
    {
        return $builder->whereNull('parent_id');
    }
}