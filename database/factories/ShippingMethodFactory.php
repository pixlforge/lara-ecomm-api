<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ShippingMethod;
use Faker\Generator as Faker;

$factory->define(ShippingMethod::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'price' => array_random(range(1000, 20000, 5))
    ];
});
