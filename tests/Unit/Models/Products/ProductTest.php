<?php

namespace Tests\Unit\Models\Products;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariation;

class ProductTest extends TestCase
{
    /** @test */
    public function it_uses_the_slug_for_the_route_key_name()
    {
        $product = new Product();

        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }

    /** @test */
    public function it_belongs_to_many_categories()
    {
        $product = factory(Product::class)->create();

        $product->categories()->save(
            factory(Category::class)->create()
        );

        $this->assertInstanceOf(Category::class, $product->categories->first());
    }

    /** @test */
    public function it_has_many_variations()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            factory(ProductVariation::class)->make()
        );

        $this->assertInstanceOf(ProductVariation::class, $product->variations->first());
    }
}
