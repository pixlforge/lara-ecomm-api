<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\PaymentMethod;
use Faker\Generator as Faker;

$factory->define(PaymentMethod::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'card_type' => $faker->creditCardType,
        'last_four' => $faker->randomNumber(4),
        'provider_id' => $faker->md5,
        'default' => true
    ];
});
