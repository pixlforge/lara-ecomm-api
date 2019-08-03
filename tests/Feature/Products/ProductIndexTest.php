<?php

namespace Tests\Feature\Products;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_shows_a_collection_of_products()
    {
        $products = factory(Product::class, 2)->create();
        
        $response = $this->getJson(route('products.index'));

        $products->each(function ($product) use ($response) {
            $response->assertJsonFragment([
                'id' => $product->id
            ]);
        });
    }

    /** @test */
    public function it_has_paginated_data()
    {
        factory(Product::class)->create();
        
        $response = $this->getJson(route('products.index'));

        $response->assertJsonStructure([
            'links', 'meta'
        ]);
    }
}
