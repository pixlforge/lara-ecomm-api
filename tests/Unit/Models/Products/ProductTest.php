<?php

namespace Tests\Unit\Models\Products;

use Tests\TestCase;
use App\Money\Money;
use App\Models\Stock;
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

    /** @test */
    public function it_returns_a_money_instance_for_the_price()
    {
        $product = factory(Product::class)->create();

        $this->assertInstanceOf(Money::class, $product->price);
    }

    /** @test */
    public function it_returns_a_formatted_price()
    {
        $product = factory(Product::class)->create([
            'price' => 1990
        ]);

        $this->assertEquals(
            (new Money($product->price->getAmount()))->formatted(),
            $product->formattedPrice
        );
    }

    /** @test */
    public function it_returns_a_detailed_price_amount()
    {
        $product = factory(Product::class)->create([
            'price' => '2790'
        ]);
    
        $this->assertEquals('27.90', $product->detailedPrice['amount']);
    }

    /** @test */
    public function it_returns_a_detailed_price_currency()
    {
        $product = factory(Product::class)->create();
    
        $this->assertEquals('CHF', $product->detailedPrice['currency']);
    }

    /** @test */
    public function it_checks_if_stock_is_available()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            $variation = factory(ProductVariation::class)->make()
        );

        $variation->stocks()->save(
            factory(Stock::class)->make()
        );

        $this->assertTrue($product->inStock());
    }

    /** @test */
    public function it_can_get_the_stock_count()
    {
        $product = factory(Product::class)->create();

        $product->variations()->save(
            $variation = factory(ProductVariation::class)->make()
        );

        $variation->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => $quantity = 29
            ])
        );

        $this->assertEquals($quantity, $product->stockCount());
    }
}
