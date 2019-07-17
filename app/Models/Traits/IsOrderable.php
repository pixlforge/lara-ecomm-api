<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait isOrderable
{
    /**
     * Order models by order.
     *
     * @param Builder $builder
     * @param string $direction
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered(Builder $builder, $direction = 'asc')
    {
        return $builder->orderBy('order', $direction);
    }
}