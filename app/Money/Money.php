<?php

namespace App\Money;

use Money\Currency;
use NumberFormatter;
use Money\Money as BaseMoney;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Parser\DecimalMoneyParser;
use Money\Number;
use Money\Formatter\DecimalMoneyFormatter;

class Money
{
    /**
     * The Money instance property.
     *
     * @var BaseMoney
     */
    protected $money;

    /**
     * Money constructor.
     *
     * @param int $value
     */
    public function __construct($value)
    {
        $this->money = new BaseMoney($value, new Currency('CHF'));
    }

    /**
     * Get the underlying Money instance amount.
     *
     * @return void
     */
    public function getAmount()
    {
        return $this->money->getAmount();
    }

    /**
     * Get the quick formatted price.
     *
     * @return void
     */
    public function formatted()
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter('de_CH', NumberFormatter::CURRENCY),
            new ISOCurrencies()
        );

        return $formatter->format($this->money);
    }

    /**
     * Get the detailed price.
     *
     * @return void
     */
    public function detailed()
    {
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return [
            'amount' => $formatter->format($this->money),
            'currency' => $this->money->getCurrency()
        ];
    }
}