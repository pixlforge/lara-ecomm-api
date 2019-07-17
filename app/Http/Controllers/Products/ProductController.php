<?php

namespace App\Http\Controllers\Products;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\Products\ProductIndexResource;
use App\Http\Resources\Products\ProductResource;

class ProductController extends Controller
{
    /**
     * Returns all products.
     *
     * @return void
     */
    public function index()
    {
        $products = Product::paginate(10);

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
}
