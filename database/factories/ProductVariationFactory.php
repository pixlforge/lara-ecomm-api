<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Product;
use Faker\Generator as Faker;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;

$factory->define(ProductVariation::class, function (Faker $faker) {
    return [
        'product_id' => factory(Product::class),
        'product_variation_type_id' => factory(ProductVariationType::class),
        'name' => $faker->sentence,
        'price' => array_random(range(1000, 15000, 5))
    ];
});
