<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'price' => array_random(range(1000, 20000, 5)),
        'description' => $faker->paragraphs(2, true),
    ];
});
