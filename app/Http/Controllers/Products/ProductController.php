<?php

namespace App\Http\Controllers\Products;

use App\Models\Product;
use App\Http\Controllers\Controller;
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
        $products = Product::paginate(10);

        return ProductIndexResource::collection($products);
    }
}
