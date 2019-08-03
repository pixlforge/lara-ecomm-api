<?php

namespace Tests\Feature\Products;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductScopingTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_can_scope_by_category()
    {
        $product = factory(Product::class)->create();
        factory(Product::class)->create();

        $product->categories()->save(
            $category = factory(Category::class)->create()
        );

        $response = $this->getJson(route('products.index', ['category' => $category->slug]));

        $response->assertJsonCount(1, 'data');
    }
}
