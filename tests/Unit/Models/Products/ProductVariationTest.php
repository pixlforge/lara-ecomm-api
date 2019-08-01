<?php

namespace Tests\Unit\Models\Products;

use Tests\TestCase;
use App\Money\Money;
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

    /** @test */
    public function it_returns_a_money_instance_for_the_price()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(Money::class, $variation->price);
    }

    /** @test */
    public function it_returns_a_formatted_price()
    {
        $variation = factory(ProductVariation::class)->create([
            'price' => 1990
        ]);

        $this->assertEquals(
            (new Money($variation->price->getAmount()))->formatted(),
            $variation->formattedPrice
        );
    }

    /** @test */
    public function it_returns_a_detailed_price_amount()
    {
        $variation = factory(ProductVariation::class)->create([
            'price' => 1760
        ]);

        $this->assertEquals('17.60', $variation->detailedPrice['amount']);
    }

    /** @test */
    public function it_returns_a_detailed_price_currency()
    {
        $variation = factory(ProductVariation::class)->create();
        
        $this->assertEquals('CHF', $variation->detailedPrice['currency']);
    }

    /** @test */
    public function it_returns_the_base_product_price_if_price_is_null()
    {
        $product = factory(Product::class)->create([
            'price' => 1990
        ]);

        $variation = factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'price' => null
        ]);

        $this->assertEquals(
            $product->price->getAmount(),
            $variation->price->getAmount()
        );
    }

    /** @test */
    public function it_checks_if_the_base_product_and_variation_price_differ()
    {
        $product = factory(Product::class)->create([
            'price' => 1990
        ]);

        $variation = factory(ProductVariation::class)->create([
            'product_id' => $product->id,
            'price' => 2990
        ]);

        $this->assertTrue($variation->priceVaries());
    }
}
