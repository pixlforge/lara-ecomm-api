<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use Faker\Generator as Faker;
use App\Models\ShippingMethod;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'address_id' => factory(Address::class),
        'shipping_method_id' => factory(ShippingMethod::class)
    ];
});
