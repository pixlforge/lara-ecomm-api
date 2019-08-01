<?php

namespace App\Models\Traits;

use App\Scoping\Scoper;
use Illuminate\Database\Eloquent\Builder;

trait HasScopesTrait
{
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
}