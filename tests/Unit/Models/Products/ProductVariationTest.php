<?php

namespace Tests\Unit\Models\Products;

use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;

class ProductVariationTest extends TestCase
{
    /** @test */
    public function it_has_one_variation_type()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(ProductVariationType::class, $variation->type);
    }

    /** @test */
    public function it_belongs_to_a_product()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(Product::class, $variation->product);
    }
}
