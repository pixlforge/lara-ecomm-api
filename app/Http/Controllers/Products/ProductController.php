<?php

namespace App\Http\Controllers\Products;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Scoping\Scopes\CategoryScope;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Products\ProductIndexResource;

class ProductController extends Controller
{
    /**
     * Returns all products.
     *
     * @return void
     */
    public function index()
    {
        $products = Product::withScopes($this->scopes())->paginate(10);

        return ProductIndexResource::collection($products);
    }

    /**
     * Show a product.
     *
     * @param Product $product
     * @return void
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
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
}
