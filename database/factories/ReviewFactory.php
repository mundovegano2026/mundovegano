<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Review::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomDigitNot(0),
        'product_id' => $faker->randomDigitNot(0),
        'score' => $faker->randomDigitNot(0)
    ];
});
