<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Stock;
use Faker\Generator as Faker;
use App\Models\ProductVariation;

$factory->define(Stock::class, function (Faker $faker) {
    return [
        'product_variation_id' => factory(ProductVariation::class),
        'quantity' => array_random(range(1, 100)),
    ];
});
