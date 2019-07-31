<?php

namespace App\Scoping\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Scope
{
    /**
     * Apply scopes.
     *
     * @param Builder $builder
     * @param string $value
     * @return Builder
     */
    public function apply(Builder $buider, $value);
}