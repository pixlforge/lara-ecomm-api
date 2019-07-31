<?php

namespace App\Scoping;

use Illuminate\Http\Request;
use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class Scoper
{
    /**
     * The request.
     *
     * @var Request
     */
    protected $request;
    
    /**
     * Scoper constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the provided scopes to the query builder.
     *
     * @param Builder $builder
     * @param array $scopes
     * @return void
     */
    public function apply(Builder $builder, array $scopes)
    {
        foreach ($scopes as $key => $scope) {
            if (!$scope instanceof Scope) {
                continue;
            }
            
            $scope->apply($builder, $this->request->get($key));
        }

        return $builder;
    }
}