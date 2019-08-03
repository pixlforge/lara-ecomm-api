<?php

namespace Tests\Feature\Products;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductShowTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_fails_if_a_product_cannot_be_found()
    {
        $response = $this->getJson(route('products.show', 'non-existent-product'));

        $response->assertNotFound();
    }

    /** @test */
    public function it_shows_a_product()
    {
        $product = factory(Product::class)->create();

        $response = $this->getJson(route('products.show', $product));

        $response->assertJsonFragment([
            'id' => $product->id
        ]);
    }
}
