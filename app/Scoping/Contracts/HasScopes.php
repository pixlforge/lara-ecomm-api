<?php

namespace App\Scoping\Contracts;

interface HasScopes
{
    /**
     * The scopes by which a product can be scoped.
     *
     * @return array
     */
    public function scopes();
}