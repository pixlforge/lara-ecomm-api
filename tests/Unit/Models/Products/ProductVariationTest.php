<?php

namespace Tests\Unit\Models\Products;

use Tests\TestCase;
use App\Money\Money;
use App\Models\Stock;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductVariationTest extends TestCase
{
    use RefreshDatabase;
    
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

    /** @test */
    public function it_has_many_stock_blocks()
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make()
        );

        $this->assertInstanceOf(Stock::class, $variation->stocks->first());
    }

    /** @test */
    public function it_has_stock_information()
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make()
        );

        $this->assertInstanceOf(ProductVariation::class, $variation->stock->first());
    }

    /** @test */
    public function it_has_stock_count_data_on_pivot()
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => $quantity = 5
            ])
        );

        $this->assertEquals($quantity, $variation->stock->first()->pivot->stock);
    }

    /** @test */
    public function it_has_in_stock_data_on_pivot()
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make()
        );

        $this->assertTrue($variation->stock->first()->pivot->in_stock);
    }

    /** @test */
    public function it_can_check_if_it_is_in_stock()
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make()
        );

        $this->assertTrue($variation->inStock());
    }

    /** @test */
    public function it_can_check_the_stock_count()
    {
        $variation = factory(ProductVariation::class)->create();

        $variation->stocks()->save(
            factory(Stock::class)->make([
                'quantity' => $quantity = 100
            ])
        );

        $this->assertEquals($quantity, $variation->stockCount());
    }
}
