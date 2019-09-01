<?php

namespace Tests\Unit\Money;

use Tests\TestCase;
use App\Money\Money;
use Money\Money as BaseMoney;

class MoneyTest extends TestCase
{
    /** @test */
    public function it_can_get_the_raw_amount()
    {
        $money = new Money(1000);

        $this->assertEquals(1000, $money->getAmount());
    }

    /** @test */
    public function it_can_get_the_formatted_amount()
    {
        $money = new Money(1000);

        $this->assertEquals(
            (new Money(1000))->formatted(),
            $money->formatted()
        );
    }

    /** @test */
    public function it_can_get_the_detailed_amount()
    {
        $money = new Money(1000);

        $this->assertEquals(
            '10.00',
            $money->detailed()['amount']
        );
    }

    /** @test */
    public function it_can_get_the_detailed_currency()
    {
        $money = new Money(1000);

        $this->assertEquals(
            'CHF',
            $money->detailed()['currency']
        );
    }

    /** @test */
    public function it_can_add_up()
    {
        $money = new Money(1000);

        $money = $money->add(new Money(1000));

        $this->assertEquals(2000, $money->getAmount());
    }

    /** @test */
    public function it_can_get_the_underlying_instance()
    {
        $money = new Money(1000);

        $this->assertInstanceOf(BaseMoney::class, $money->instance());
    }
}
